<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Transaction;
use App\Notifications\TransactionRequested;
use App\Notifications\TransactionStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// FR-06/08 pengajuan, FR-09 approve/reject, FR-10 status,
// FR-11 konfirmasi 2 pihak, FR-12 riwayat, FR-13/14 impact
class TransactionController extends Controller
{
    // FR-12: riwayat transaksi milik user (Supply atau Demand)
    public function index(Request $request)
    {
        $request->validate([
            "date_from" => ["nullable", "date"],
            "date_to" => ["nullable", "date", "after_or_equal:date_from"],
        ]);

        $user = auth()->user();

        $query = $user->isSupply()
            ? $user->transactionsAsSupply()
            : $user->transactionsAsDemand();

        $query = $query->with(["material.unit", "supply", "demand"]);

        // Filter status - reassign eksplisit supaya tidak ada mutasi query yang "hilang"
        if ($request->filled("status")) {
            $query = $query->where("status", $request->string("status"));
        }

        // Filter tanggal
        if ($request->date_filter === "today") {
            $query = $query->whereDate("created_at", today());
        } else {
            if ($request->filled("date_from")) {
                $query = $query->whereDate("created_at", ">=", $request->date("date_from"));
            }
            if ($request->filled("date_to")) {
                $query = $query->whereDate("created_at", "<=", $request->date("date_to"));
            }
        }

        $transactions = $query->with(["material", "supply", "demand"])
            //     ->when($request->status, fn ($q) => $q->where("status", $request->status))
            //     ->latest()
            ->paginate(10)
            ->withQueryString();

        return view("transactions.index", compact("transactions"));
        
        }

    public function show(Transaction $transaction)
    {
        $this->authorizeParty($transaction);
        $transaction->load(["material.category", "material.unit", "supply", "demand"]);

        return view("transactions.show", compact("transaction"));
    }

    // FR-06/08: Demand mengajukan permintaan atas material yang tersedia
    public function store(Request $request, Material $material)
    {
        abort_if(auth()->user()->isSupply(), 403, "Hanya akun Demand yang dapat mengajukan transaksi.");
        abort_if($material->status !== Material::STATUS_AVAILABLE, 422, "Material sedang tidak tersedia.");

        $data = $request->validate([
            "requested_quantity" => ["required", "numeric", "min:0.01", "max:" . $material->quantity],
            "note" => ["nullable", "string", "max:1000"],
        ]);

        $transaction = Transaction::create([
            "material_id" => $material->id,
            "supply_id" => $material->supply_id,
            "demand_id" => auth()->id(),
            "requested_quantity" => $data["requested_quantity"],
            "note" => $data["note"] ?? null,
            "status" => Transaction::STATUS_PENDING,
        ]);

        // FR-07: notifikasi ke Supply
        $material->supply->notify(new TransactionRequested($transaction));

        return redirect()->route("transactions.show", $transaction)
            ->with("success", "Permintaan transaksi berhasil diajukan, menunggu persetujuan Supply.");
    }

    // FR-09: Supply menyetujui permintaan
    public function approve(Transaction $transaction)
    {
        abort_unless(auth()->id() === $transaction->supply_id, 403);
        abort_unless($transaction->status === Transaction::STATUS_PENDING, 422, "Transaksi sudah diproses sebelumnya.");
        abort_if($transaction->requested_quantity > $transaction->material->quantity, 422, "Stok material tidak mencukupi lagi.");

        $transaction->update([
            "status" => Transaction::STATUS_APPROVED,
            "approved_at" => now(),
        ]);

        $transaction->demand->notify(new TransactionStatusChanged($transaction));

        return back()->with("success", "Transaksi disetujui.");
    }

    // FR-09: Supply menolak permintaan
    public function reject(Transaction $transaction)
    {
        abort_unless(auth()->id() === $transaction->supply_id, 403);
        abort_unless($transaction->status === Transaction::STATUS_PENDING, 422, "Transaksi sudah diproses sebelumnya.");

        $transaction->update([
            "status" => Transaction::STATUS_REJECTED,
            "rejected_at" => now(),
        ]);

        $transaction->demand->notify(new TransactionStatusChanged($transaction));

        return back()->with("success", "Transaksi ditolak.");
    }

    // Supply/Demand membatalkan transaksi selama masih pending/approved
    public function cancel(Transaction $transaction)
    {
        $this->authorizeParty($transaction);
        abort_unless(
            in_array($transaction->status, [Transaction::STATUS_PENDING, Transaction::STATUS_APPROVED]),
            422,
            "Transaksi tidak dapat dibatalkan pada status ini."
        );

        $transaction->update([
            "status" => Transaction::STATUS_CANCELLED,
            "cancelled_at" => now(),
        ]);

        $other = auth()->id() === $transaction->supply_id ? $transaction->demand : $transaction->supply;
        $other->notify(new TransactionStatusChanged($transaction));

        return back()->with("success", "Transaksi dibatalkan.");
    }

    // Wajib upload foto bukti fisik penerimaan material, bukan sekadar klik tombol.
    // Persetujuan Supply sudah dianggap komitmen menyerahkan, jadi cukup 1 konfirmasi
    // dari Demand (disertai foto) untuk menutup transaksi.
    public function confirm(Request $request, Transaction $transaction)
    {
        abort_unless(auth()->id() === $transaction->demand_id, 403, "Hanya Demand yang dapat konfirmasi penerimaan material.");
        abort_unless($transaction->status === Transaction::STATUS_APPROVED, 422, "Transaksi belum disetujui atau sudah selesai.");

        $data = $request->validate([
            "proof_photo" => ["required", "image", "max:4096"],
        ], [
            "proof_photo.required" => "Foto bukti penerimaan wajib diunggah untuk menyelesaikan transaksi.",
        ]);

        $proofPath = $request->file("proof_photo")->store("transaction-proofs", "public");

        DB::transaction(function () use ($transaction, $proofPath) {
            $transaction->confirmed_by_demand_at = now();
            $transaction->proof_photo_path = $proofPath;
            $transaction->status = Transaction::STATUS_COMPLETED;
            $transaction->completed_at = now();
            $transaction->save();

            // FR-13: kurangi stok material sesuai jumlah yang berhasil disalurkan
            $material = $transaction->material()->lockForUpdate()->first();
            $material->decrement("quantity", $transaction->requested_quantity);
            if ($material->quantity <= 0) {
                $material->update(["status" => Material::STATUS_UNAVAILABLE]);
            }
        });

        $transaction->supply->notify(new TransactionStatusChanged($transaction->fresh()));

        return back()->with("success", "Konfirmasi penerimaan & foto bukti berhasil disimpan, transaksi selesai.");
    }


    private function authorizeParty(Transaction $transaction): void
    {
        abort_unless(
            in_array(auth()->id(), [$transaction->supply_id, $transaction->demand_id]),
            403
        );
    }
}
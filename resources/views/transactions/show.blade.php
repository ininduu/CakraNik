@extends("layouts.app")
@section("title", "Detail Transaksi")
@section("content")
<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-900">{{ $transaction->material->name }}</h1>
        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100">{{ $transaction->statusLabel() }}</span>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
        <div>
            <p class="text-gray-400 text-xs">Supply</p>
            <p class="font-medium">{{ $transaction->supply->business_name ?? $transaction->supply->name }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">Demand</p>
            <p class="font-medium">{{ $transaction->demand->name }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">Jumlah Diminta</p>
            <p class="font-medium">{{ $transaction->requested_quantity }} {{ $transaction->material->unit->symbol }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">Diajukan</p>
            <p class="font-medium">{{ $transaction->created_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    @if($transaction->note)
    <div class="bg-gray-50 rounded-xl p-3 text-sm mb-4">"{{ $transaction->note }}"</div>
    @endif

    @if($transaction->proof_photo_path)
    <div class="border-t pt-4 mb-4">
        <p class="text-xs font-medium text-gray-500 mb-2">📷 Foto Bukti Penerimaan</p>
        <a href="{{ asset('storage/' . $transaction->proof_photo_path) }}" target="_blank">
            <img src="{{ asset('storage/' . $transaction->proof_photo_path) }}" alt="Bukti penerimaan" class="rounded-xl border border-gray-200 max-h-64 object-cover">
        </a>
    </div>
    @endif

    <div class="border-t pt-4 space-y-2 text-sm text-gray-500">
        <p>✅ Diajukan: {{ $transaction->created_at->format("d M Y H:i") }}</p>
        @if($transaction->approved_at)<p>✅ Disetujui Supply: {{ $transaction->approved_at->format("d M Y H:i") }}</p>@endif
        @if($transaction->rejected_at)<p>❌ Ditolak: {{ $transaction->rejected_at->format("d M Y H:i") }}</p>@endif
        @if($transaction->confirmed_by_demand_at)<p>✅ Dikonfirmasi diterima oleh Demand (dengan foto bukti): {{ $transaction->confirmed_by_demand_at->format("d M Y H:i") }}</p>@endif
        @if($transaction->completed_at)<p>🏁 Selesai: {{ $transaction->completed_at->format("d M Y H:i") }}</p>@endif
        @if($transaction->cancelled_at)<p>🚫 Dibatalkan: {{ $transaction->cancelled_at->format("d M Y H:i") }}</p>@endif
    </div>

    <div class="border-t mt-4 pt-4">
        @if(auth()->id() === $transaction->supply_id && $transaction->status == "pending")
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('transactions.approve', $transaction) }}">
                @csrf
                <button class="bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Setujui</button>
            </form>
            <form method="POST" action="{{ route('transactions.reject', $transaction) }}">
                @csrf
                <button class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-semibold">Tolak</button>
            </form>
        </div>
        @endif

        @if($transaction->status == "approved" && auth()->id() === $transaction->demand_id)
        <form method="POST" action="{{ route('transactions.confirm', $transaction) }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Upload Foto Bukti Penerimaan <span class="text-red-500">*</span></label>
                <input type="file" name="proof_photo" accept="image/*" required class="w-full text-sm">
                <p class="text-xs text-gray-400 mt-1">Wajib diisi. Foto sebagai bukti material benar-benar diterima.</p>
            </div>
            <button class="bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">Konfirmasi Material Diterima</button>
        </form>
        @endif

        @if($transaction->status == "approved" && auth()->id() === $transaction->supply_id)
            <p class="text-sm text-gray-400">Menunggu Demand mengonfirmasi penerimaan material (disertai foto bukti).</p>
        @endif

        @if(in_array($transaction->status, ["pending","approved"]))
        <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" class="mt-2">
            @csrf
            <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold">Batalkan</button>
        </form>
        @endif
    </div>
</div>
@endsection
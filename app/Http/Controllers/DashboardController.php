<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Transaction;

// FR-13: jumlah material yang berhasil disalurkan (Supply)
// FR-14: circular impact (total per satuan, dipisah karena kg/liter/karung tidak bisa dijumlah)
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSupply()) {
            $completed = Transaction::where("supply_id", $user->id)
                ->where("status", Transaction::STATUS_COMPLETED);

            $stats = [
                "total_materials" => $user->materials()->count(),
                "active_materials" => $user->materials()->available()->count(),
                "pending_requests" => Transaction::where("supply_id", $user->id)
                    ->where("status", Transaction::STATUS_PENDING)->count(),
                "completed_transactions" => (clone $completed)->count(),
            ];

            // FR-14: circular impact dipecah per satuan (kg, liter, karung, dst)
            $impactByUnit = Transaction::where("transactions.supply_id", $user->id)
                ->where("transactions.status", Transaction::STATUS_COMPLETED)
                ->join("materials", "materials.id", "=", "transactions.material_id")
                ->join("units", "units.id", "=", "materials.unit_id")
                ->selectRaw("units.name as unit_name, units.symbol as unit_symbol, SUM(transactions.requested_quantity) as total")
                ->groupBy("units.id", "units.name", "units.symbol")
                ->orderByDesc("total")
                ->get();

            $recentTransactions = Transaction::where("supply_id", $user->id)
                ->with(["material.unit", "demand"])->latest()->take(5)->get();

            return view("dashboard.supply", compact("stats", "impactByUnit", "recentTransactions"));
        }

        $completed = Transaction::where("demand_id", $user->id)
            ->where("status", Transaction::STATUS_COMPLETED);

        $stats = [
            "completed_transactions" => (clone $completed)->count(),
            "pending_requests" => Transaction::where("demand_id", $user->id)
                ->where("status", Transaction::STATUS_PENDING)->count(),
        ];

        // FR-14: circular impact dipecah per satuan (kg, liter, karung, dst)
        $impactByUnit = Transaction::where("transactions.demand_id", $user->id)
            ->where("transactions.status", Transaction::STATUS_COMPLETED)
            ->join("materials", "materials.id", "=", "transactions.material_id")
            ->join("units", "units.id", "=", "materials.unit_id")
            ->selectRaw("units.name as unit_name, units.symbol as unit_symbol, SUM(transactions.requested_quantity) as total")
            ->groupBy("units.id", "units.name", "units.symbol")
            ->orderByDesc("total")
            ->get();

        $recommended = Material::with(["supply", "category", "unit"])
            ->recommendedFor($user)->take(4)->get();

        $recentTransactions = Transaction::where("demand_id", $user->id)
            ->with(["material.unit", "supply"])->latest()->take(5)->get();

        return view("dashboard.demand", compact("stats", "impactByUnit", "recommended", "recentTransactions"));
    }
}
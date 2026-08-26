<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Index tambahan supaya query filter tanggal + riwayat per user tetap cepat
    // walau jumlah transaksi sudah sangat banyak (mis. berjalan bertahun-tahun).
    public function up(): void
    {
        Schema::table("transactions", function (Blueprint $table) {
            $table->index(["supply_id", "created_at"], "transactions_supply_created_idx");
            $table->index(["demand_id", "created_at"], "transactions_demand_created_idx");
        });
    }

    public function down(): void
    {
        Schema::table("transactions", function (Blueprint $table) {
            $table->dropIndex("transactions_supply_created_idx");
            $table->dropIndex("transactions_demand_created_idx");
        });
    }
};
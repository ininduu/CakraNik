<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FR-11: bukti fisik penerimaan material - Demand wajib upload foto saat konfirmasi
    public function up(): void
    {
        Schema::table("transactions", function (Blueprint $table) {
            $table->string("proof_photo_path")->nullable()->after("confirmed_by_demand_at");
        });
    }

    public function down(): void
    {
        Schema::table("transactions", function (Blueprint $table) {
            $table->dropColumn("proof_photo_path");
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("transactions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("material_id")->constrained("materials");
            $table->foreignId("supply_id")->constrained("users");
            $table->foreignId("demand_id")->constrained("users");
            $table->decimal("requested_quantity", 10, 2);
            $table->text("note")->nullable();

            $table->enum("status", [
                "pending", "approved", "rejected", "completed", "cancelled",
            ])->default("pending");

            $table->timestamp("approved_at")->nullable();
            $table->timestamp("rejected_at")->nullable();
            $table->timestamp("confirmed_by_supply_at")->nullable();
            $table->timestamp("confirmed_by_demand_at")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->timestamp("cancelled_at")->nullable();

            $table->timestamps();

            $table->index(["status"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("transactions");
    }
};
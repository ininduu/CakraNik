<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("materials", function (Blueprint $table) {
            $table->id();
            $table->foreignId("supply_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("category_id")->constrained("categories");
            $table->foreignId("unit_id")->constrained("units");
            $table->string("name");
            $table->text("description")->nullable();
            $table->decimal("quantity", 10, 2);
            $table->string("location");
            $table->date("available_from")->nullable();
            $table->date("available_until")->nullable();
            $table->string("photo_path")->nullable();
            $table->enum("status", ["available", "unavailable"])->default("available");
            $table->timestamps();

            $table->index(["status", "category_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("materials");
    }
};
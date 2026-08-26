<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori material organik - data master, tambah/ubah lewat sini atau nanti lewat panel admin
        $categories = [
            "Sisa Makanan Rumah Makan/Hotel",
            "Sisa Sayur & Buah",
            "Sisa Roti & Karbohidrat",
            "Minyak Jelantah",
            "Ampas (Kopi, Tahu, Kedelai)",
            "Sisa Ikan & Daging",
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(["slug" => Str::slug($name)], ["name" => $name]);
        }

        // Satuan
        $units = [
            ["name" => "Kilogram", "symbol" => "kg"],
            ["name" => "Liter", "symbol" => "l"],
            ["name" => "Karung", "symbol" => "karung"],
        ];
        foreach ($units as $unit) {
            Unit::firstOrCreate(["symbol" => $unit["symbol"]], $unit);
        }

        // Akun demo
        User::firstOrCreate(
            ["email" => "supply@cakranik.com"],
            [
                "name" => "RM Sumber Rejeki",
                "role" => "supply",
                "business_name" => "Rumah Makan Sumber Rejeki",
                "phone" => "081234567890",
                "address" => "Jl. Pandanaran No. 10",
                "city" => "Semarang",
                "password" => Hash::make("password"),
            ]
        );

        User::firstOrCreate(
            ["email" => "demand@cakranik.com"],
            [
                "name" => "Peternak Maggot BSF Jaya",
                "role" => "demand",
                "business_name" => "Budidaya Maggot BSF Jaya",
                "phone" => "081298765432",
                "address" => "Jl. Sukun No. 5",
                "city" => "Semarang",
                "password" => Hash::make("password"),
            ]
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_SUPPLY = "supply";
    const ROLE_DEMAND = "demand";

    protected $fillable = [
        "name", "email", "password", "role",
        "business_name", "phone", "address", "city",
    ];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function isSupply(): bool
    {
        return $this->role === self::ROLE_SUPPLY;
    }

    public function isDemand(): bool
    {
        return $this->role === self::ROLE_DEMAND;
    }

    // FR-03: Supply mengelola daftar material
    public function materials()
    {
        return $this->hasMany(Material::class, "supply_id");
    }

    // Transaksi dimana user ini berperan sebagai Supply
    public function transactionsAsSupply()
    {
        return $this->hasMany(Transaction::class, "supply_id");
    }

    // Transaksi dimana user ini berperan sebagai Demand
    public function transactionsAsDemand()
    {
        return $this->hasMany(Transaction::class, "demand_id");
    }
}
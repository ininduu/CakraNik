<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Satuan (kg, liter, karung, dll) - data master di DB.
class Unit extends Model
{
    protected $fillable = ["name", "symbol"];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
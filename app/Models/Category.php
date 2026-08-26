<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Kategori material organik - data master di DB, bukan hardcode di kode/view.
class Category extends Model
{
    protected $fillable = ["name", "slug", "is_active"];

    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
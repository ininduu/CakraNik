<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// FR-03/FR-04: listing material organik oleh Supply, dicari oleh Demand
class Material extends Model
{
    const STATUS_AVAILABLE = "available";
    const STATUS_UNAVAILABLE = "unavailable";

    protected $fillable = [
        "supply_id", "category_id", "unit_id", "name", "description",
        "quantity", "location", "available_from", "available_until",
        "photo_path", "status",
    ];

    protected function casts(): array
    {
        return [
            "quantity" => "decimal:2",
            "available_from" => "date",
            "available_until" => "date",
        ];
    }

    public function supply()
    {
        return $this->belongsTo(User::class, "supply_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where("status", self::STATUS_AVAILABLE)
            ->where("quantity", ">", 0);
    }

    // NFR-09: rekomendasi sederhana berbasis kesesuaian kategori & lokasi
    public function scopeRecommendedFor($query, User $demand)
{
    $pastTransactions = Transaction::where("demand_id", $demand->id)
        ->join("materials", "materials.id", "=", "transactions.material_id")
        ->select("materials.category_id", "transactions.requested_quantity")
        ->get();

    $pastCategoryIds = $pastTransactions->pluck("category_id")->unique();
    $avgQuantity = $pastTransactions->avg("requested_quantity");

    return $query->available()
        // 1) Jenis: prioritaskan kategori yang pernah diminta Demand ini
        ->when($pastCategoryIds->isNotEmpty(), function ($q) use ($pastCategoryIds) {
            $q->orderByRaw("CASE WHEN category_id IN (" . $pastCategoryIds->implode(",") . ") THEN 1 ELSE 0 END DESC");
        })
        // 2) Lokasi: prioritaskan material yang lokasinya sekota dengan Demand
        ->when($demand->city, function ($q) use ($demand) {
            $q->orderByRaw("CASE WHEN location LIKE ? THEN 1 ELSE 0 END DESC", ["%{$demand->city}%"]);
        })
        // 3) Jumlah: prioritaskan material yang stoknya mendekati rata-rata kebutuhan Demand sebelumnya
        ->when($avgQuantity, function ($q) use ($avgQuantity) {
            $q->orderByRaw("ABS(quantity - ?) ASC", [$avgQuantity]);
        })
        ->latest();
        
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// FR-06..FR-14: alur pengajuan -> persetujuan -> konfirmasi 2 pihak -> selesai
class Transaction extends Model
{
    const STATUS_PENDING = "pending";
    const STATUS_APPROVED = "approved";
    const STATUS_REJECTED = "rejected";
    const STATUS_COMPLETED = "completed";
    const STATUS_CANCELLED = "cancelled";

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => "Menunggu Persetujuan",
            self::STATUS_APPROVED => "Disetujui - Menunggu Konfirmasi Diterima",
            self::STATUS_REJECTED => "Ditolak",
            self::STATUS_COMPLETED => "Selesai",
            self::STATUS_CANCELLED => "Dibatalkan",
        ];
    }

    protected $fillable = [
        "material_id", "supply_id", "demand_id", "requested_quantity",
        "note", "status", "approved_at", "rejected_at",
        "confirmed_by_demand_at", "proof_photo_path", "completed_at", "cancelled_at",
    ];

    protected function casts(): array
    {
        return [
            "requested_quantity" => "decimal:2",
            "approved_at" => "datetime",
            "rejected_at" => "datetime",
            "confirmed_by_supply_at" => "datetime",
            "confirmed_by_demand_at" => "datetime",
            "completed_at" => "datetime",
            "cancelled_at" => "datetime",
        ];
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function supply()
    {
        return $this->belongsTo(User::class, "supply_id");
    }

    public function demand()
    {
        return $this->belongsTo(User::class, "demand_id");
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    // public function isFullyConfirmed(): bool
    // {
    //     return $this->confirmed_by_supply_at && $this->confirmed_by_demand_at;
    // }
}
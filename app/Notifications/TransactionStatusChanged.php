<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// FR-10: notifikasi perubahan status transaksi ke Supply maupun Demand
class TransactionStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via(object $notifiable): array
    {
        return ["database"];
    }

    public function toArray(object $notifiable): array
    {
        return [
            "title" => "Status transaksi diperbarui",
            "message" => "Transaksi untuk material \"{$this->transaction->material->name}\" kini berstatus: {$this->transaction->statusLabel()}.",
            "transaction_id" => $this->transaction->id,
        ];
    }
}
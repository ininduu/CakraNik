<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

// FR-07: Supply menerima notifikasi saat Demand mengajukan permintaan
class TransactionRequested extends Notification
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
            "title" => "Permintaan transaksi baru",
            "message" => "{$this->transaction->demand->name} mengajukan permintaan {$this->transaction->requested_quantity} untuk material \"{$this->transaction->material->name}\".",
            "transaction_id" => $this->transaction->id,
        ];
    }
}
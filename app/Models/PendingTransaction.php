<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingTransaction extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'status',
        'data_scheduled',
    ];

    public function sender()
    {
        return $this->belongsTo(BankAccount::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(BankAccount::class, 'receiver_id');
    }

    public static function getModelLabel()
    {
        return 'Pending Transaction ';
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    const STATUS_INITIAL = 'INITIAL';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    const STATUS_FINISHED = 'FINISHED';

    protected $table = 'orders';

    public function pigeon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_pigeon_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_customer_id');
    }
}

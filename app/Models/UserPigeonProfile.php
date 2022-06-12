<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPigeonProfile extends Model
{
    use HasFactory;

    const STATUS_OFFLINE = 'OFFLINE';
    const STATUS_IN_TRANSITS = 'IN_TRANSITS';
    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_RESTING=  'RESTING';


    protected $table = 'user_pigeon_profile';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

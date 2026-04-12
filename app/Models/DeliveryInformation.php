<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeliveryInformation extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'street', 'post_code', 'city', 'province', 'country',
        'house', 'first_name', 'last_name', 'phone_number',
        'session_id', 'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'delivery_information');
    }
}

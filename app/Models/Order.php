<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUuids;

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'code', 'user_id', 'session_id', 'delivery_method_id', 'delivery_information',
        'status', 'total_amount', 'cart_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryMethod(): BelongsTo
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function deliveryInformation(): BelongsTo
    {
        return $this->belongsTo(DeliveryInformation::class, 'delivery_information');
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}

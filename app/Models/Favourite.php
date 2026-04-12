<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Favourite extends Pivot
{
    protected $table = 'favourites';

    public $timestamps = false;

    protected $fillable = ['user_id', 'product_id', 'added_at'];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

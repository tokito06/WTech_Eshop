<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['price', 'product_id', 'symbol', 'inventory'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

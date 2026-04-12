<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['name', 'path', 'position'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_images');
    }
}

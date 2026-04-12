<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['name', 'description', 'sort_order', 'image_id'];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}

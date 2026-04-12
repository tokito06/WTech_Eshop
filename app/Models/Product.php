<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'brand_id', 'category_id', 'sex', 'status'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_images')->orderBy('position');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function favouritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourites', 'product_id', 'user_id')
                    ->withPivot('added_at');
    }

    public function isFavouritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favouritedBy()->where('user_id', $user->id)->exists();
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        $image = $this->images->first();
        return $image ? $image->url : null;
    }

    public function getMinPriceAttribute(): ?float
    {
        return $this->variants->min('price');
    }
}

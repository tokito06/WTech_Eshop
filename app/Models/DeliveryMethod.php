<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryMethod extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['name', 'brief', 'expected_time', 'price'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_method_id');
    }
}

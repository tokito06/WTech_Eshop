<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\DeliveryInformation;
use App\Models\User;

class CartMerger
{
    public function merge(string $guestSessionId, User $user): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();

        if ($guestCart) {
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
            $existingVariants = $userCart->items()->pluck('variant_id')->all();

            foreach ($guestCart->items as $item) {
                if (in_array($item->variant_id, $existingVariants, true)) {
                    continue;
                }
                $item->cart_id = $userCart->id;
                $item->save();
            }

            $guestCart->delete();
        }

        DeliveryInformation::where('session_id', $guestSessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id, 'session_id' => null]);
    }
}

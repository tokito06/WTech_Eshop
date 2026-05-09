<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $cart = $this->findCart($request);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        return redirect()->route('delivery');
    }

    private function findCart(Request $request): ?Cart
    {
        if ($request->user()) {
            return Cart::with('items')
                ->where('user_id', $request->user()->id)
                ->first();
        }

        return Cart::with('items')
            ->where('session_id', $request->session()->getId())
            ->whereNull('user_id')
            ->first();
    }
}

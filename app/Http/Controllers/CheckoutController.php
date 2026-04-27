<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): RedirectResponse|View
    {
        $cart = $this->findCart($request);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        return Auth::check()
            ? redirect()->route('delivery')
            : view('checkout-choice');
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

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DeliveryMethod;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $deliveryInfoId = $request->session()->get('checkout.delivery_information_id');
        $deliveryMethodId = $request->session()->get('checkout.delivery_method_id');

        abort_if(!$deliveryInfoId || !$deliveryMethodId, 400, 'Missing checkout data.');

        $cart = $this->resolveCart($request);

        abort_if(!$cart || $cart->items->isEmpty(), 400, 'Cart is empty.');

        $order = DB::transaction(function () use ($request, $cart, $deliveryInfoId, $deliveryMethodId) {
            return Order::create([
                'code'                 => (string) Str::uuid(),
                'user_id'              => $request->user()?->id,
                'delivery_method_id'   => $deliveryMethodId,
                'delivery_information' => $deliveryInfoId,
                'status'               => 'pending',
                'total_amount'         => $cart->total,
                'cart_id'              => $cart->id,
            ]);
        });

        $deliveryMethod = DeliveryMethod::find($deliveryMethodId);
        $deliveryPrice = $deliveryMethod?->price ?? 0;

        $request->session()->forget(['checkout.delivery_information_id', 'checkout.delivery_method_id']);
        $request->session()->flash('order.code', $order->code);
        $request->session()->flash('order.items_total', $cart->total);
        $request->session()->flash('order.delivery_price', $deliveryPrice);
        $request->session()->flash('order.grand_total', $cart->total + $deliveryPrice);

        return redirect()->route('order.success');
    }

    private function resolveCart(Request $request): ?Cart
    {
        if ($request->user()) {
            return Cart::with('items')->where('user_id', $request->user()->id)->first();
        }

        return Cart::with('items')
            ->where('session_id', $request->session()->getId())
            ->whereNull('user_id')
            ->first();
    }
}

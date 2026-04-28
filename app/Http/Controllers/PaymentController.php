<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DeliveryMethod;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $deliveryInfoId = $request->session()->get('checkout.delivery_information_id');
        $deliveryMethodId = $request->session()->get('checkout.delivery_method_id');

        if (!$deliveryInfoId || !$deliveryMethodId) {
            return redirect()->route('delivery');
        }

        $cart = $this->resolveCart($request);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        $itemsTotal = $cart->total;
        $deliveryMethod = DeliveryMethod::find($deliveryMethodId);
        $deliveryPrice = $deliveryMethod?->price ?? 0;
        $grandTotal = $itemsTotal + $deliveryPrice;

        return view('payment', compact('itemsTotal', 'deliveryPrice', 'grandTotal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $deliveryInfoId = $request->session()->get('checkout.delivery_information_id');
        $deliveryMethodId = $request->session()->get('checkout.delivery_method_id');

        abort_if(!$deliveryInfoId || !$deliveryMethodId, 400, 'Missing checkout data.');

        $cart = $this->resolveCart($request);

        abort_if(!$cart || $cart->items->isEmpty(), 400, 'Cart is empty.');

        $sessionId = $request->user() ? null : $request->session()->getId();

        $itemsTotal = $cart->total;

        $order = DB::transaction(function () use ($request, $cart, $deliveryInfoId, $deliveryMethodId, $sessionId) {
            $order = Order::create([
                'code'                 => (string) Str::uuid(),
                'user_id'              => $request->user()?->id,
                'session_id'           => $sessionId,
                'delivery_method_id'   => $deliveryMethodId,
                'delivery_information' => $deliveryInfoId,
                'status'               => 'pending',
                'total_amount'         => $cart->total,
                'cart_id'              => $cart->id,
            ]);

            $cart->items()->delete();

            return $order;
        });

        $deliveryMethod = DeliveryMethod::find($deliveryMethodId);
        $deliveryPrice = $deliveryMethod?->price ?? 0;

        $request->session()->forget(['checkout.delivery_information_id', 'checkout.delivery_method_id']);
        $request->session()->flash('order.code', $order->code);
        $request->session()->flash('order.items_total', $itemsTotal);
        $request->session()->flash('order.delivery_price', $deliveryPrice);
        $request->session()->flash('order.grand_total', $itemsTotal + $deliveryPrice);

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

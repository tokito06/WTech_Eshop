<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryStoreRequest;
use App\Models\Cart;
use App\Models\DeliveryInformation;
use App\Models\DeliveryMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cart = $this->findCart($request);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        $deliveryMethods = DeliveryMethod::all();
        $itemsTotal = $cart->total;
        $user = $request->user();

        $deliveryInformation = $user
            ? DeliveryInformation::where('user_id', $user->id)->first()
            : DeliveryInformation::where('session_id', $request->session()->getId())
                ->whereNull('user_id')
                ->first();

        $prefill = [
            'first_name' => $deliveryInformation?->first_name ?? ($user?->name ?? ''),
            'last_name' => $deliveryInformation?->last_name ?? ($user?->surname ?? ''),
            'phone_number' => $deliveryInformation?->phone_number ?? ($user?->phone ?? ''),
            'street' => $deliveryInformation?->street ?? '',
            'city' => $deliveryInformation?->city ?? '',
            'post_code' => $deliveryInformation?->post_code ?? '',
            'country' => $deliveryInformation?->country ?? '',
            'province' => $deliveryInformation?->province ?? '',
            'house' => $deliveryInformation?->house ?? '',
        ];

        $selectedDeliveryMethodId = $request->session()->get('checkout.delivery_method_id');
        $selectedDeliveryMethod = $selectedDeliveryMethodId
            ? $deliveryMethods->firstWhere('id', $selectedDeliveryMethodId)
            : null;

        $deliveryPrice = $selectedDeliveryMethod?->price;
        $minDeliveryPrice = $deliveryMethods->min('price');
        $grandTotal = $itemsTotal + ($deliveryPrice ?? 0);

        return view('delivery', compact(
            'deliveryMethods',
            'prefill',
            'selectedDeliveryMethodId',
            'itemsTotal',
            'deliveryPrice',
            'minDeliveryPrice',
            'grandTotal'
        ));
    }

    public function store(DeliveryStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $userId = $request->user()?->id;
        $sessionId = $userId ? null : $request->session()->getId();

        $lookup = $userId
            ? ['user_id' => $userId]
            : ['session_id' => $sessionId, 'user_id' => null];

        $delivery = DeliveryInformation::updateOrCreate($lookup, [
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'phone_number' => $data['phone_number'],
            'street'       => $data['street'],
            'city'         => $data['city'],
            'post_code'    => $data['post_code'],
            'country'      => $data['country'],
            'province'     => $data['province'] ?? null,
            'house'        => $data['house'] ?? null,
            'user_id'      => $userId,
            'session_id'   => $sessionId,
        ]);

        $request->session()->put('checkout.delivery_information_id', $delivery->id);
        $request->session()->put('checkout.delivery_method_id', $data['delivery_method_id']);

        return redirect()->route('payment');
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

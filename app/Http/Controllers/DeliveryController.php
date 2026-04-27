<?php

namespace App\Http\Controllers;

use App\Models\DeliveryInformation;
use App\Models\DeliveryMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function create(Request $request): View
    {
        $deliveryMethods = DeliveryMethod::all();

        return view('delivery', compact('deliveryMethods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'delivery_method_id' => ['required', 'uuid', 'exists:delivery_methods,id'],
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['required', 'string', 'max:50'],
            'phone_number'       => ['required', 'string', 'max:20'],
            'street'             => ['required', 'string', 'max:255'],
            'city'               => ['required', 'string', 'max:100'],
            'post_code'          => ['required', 'string', 'max:50'],
            'country'            => ['required', 'string', 'max:100'],
            'province'           => ['nullable', 'string', 'max:100'],
            'house'              => ['nullable', 'string', 'max:50'],
        ]);

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
        ]);

        $request->session()->put('checkout.delivery_information_id', $delivery->id);
        $request->session()->put('checkout.delivery_method_id', $data['delivery_method_id']);

        return redirect()->route('payment');
    }
}

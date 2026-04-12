<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $request->user()->forceFill([
            'phone' => $validated['phone'],
        ])->save();

        return redirect()->route('payment');
    }
}


<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cart = $this->resolveCart((int) $request->user()->id);

        $cart->load([
            'items.variant.product.images',
        ]);

        return response()->json([
            'success' => true,
            'items' => $cart->items,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'uuid', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->resolveCart((int) $request->user()->id);
        $variant = ProductVariant::query()->findOrFail($data['variant_id']);

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'variant_id' => $variant->id,
        ]);

        $item->quantity = ($item->exists ? $item->quantity : 0) + $data['quantity'];
        $item->amount = (float) $variant->price;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
        ]);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        $this->ensureOwnership($request, $item);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $item->update([
            'quantity' => $data['quantity'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item updated',
        ]);
    }

    public function destroy(Request $request, CartItem $item): JsonResponse
    {
        $this->ensureOwnership($request, $item);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed',
        ]);
    }

    private function resolveCart(int $userId): Cart
    {
        return Cart::query()->firstOrCreate(
            ['user_id' => $userId],
            ['session_id' => session()->getId()]
        );
    }

    private function ensureOwnership(Request $request, CartItem $item): void
    {
        abort_unless($item->cart && (int) $item->cart->user_id === (int) $request->user()->id, 403);
    }
}


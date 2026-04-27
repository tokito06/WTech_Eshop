<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $cart = $this->resolveCart($request);
        $cart->load([
            'items.variant.product.images',
        ]);

        return view('cart', [
            'cart' => $cart,
            'items' => $cart->items,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $cart = $this->resolveCart($request);

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

        $cart = $this->resolveCart($request);
        $variant = ProductVariant::query()->findOrFail($data['variant_id']);

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'variant_id' => $variant->id,
        ]);

        $currentQuantity = $item->exists ? (int) $item->quantity : 0;
        $requestedQuantity = (int) $data['quantity'];
        $maxAdditional = max(0, (int) $variant->inventory - $currentQuantity);
        $addedQuantity = min($requestedQuantity, $maxAdditional);

        if ($addedQuantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No more items are available for this size.',
                'added_quantity' => 0,
                'quantity' => $currentQuantity,
                'capped' => true,
            ], 422);
        }

        $item->quantity = $currentQuantity + $addedQuantity;
        $item->amount = (float) $variant->price;
        $item->save();

        $capped = $addedQuantity < $requestedQuantity;

        return response()->json([
            'success' => true,
            'message' => $capped
                ? "Only {$addedQuantity} item(s) were added to cart due to stock limit."
                : 'Item added to cart',
            'added_quantity' => $addedQuantity,
            'quantity' => (int) $item->quantity,
            'capped' => $capped,
        ]);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        $this->ensureOwnership($request, $item);
        $item->loadMissing('variant');

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $requestedQuantity = (int) $data['quantity'];
        $maxAvailable = (int) ($item->variant->inventory ?? 0);

        if ($maxAvailable < 1) {
            return response()->json([
                'success' => false,
                'message' => 'No more items are available for this size.',
                'quantity' => (int) $item->quantity,
                'capped' => true,
            ], 422);
        }

        $appliedQuantity = min($requestedQuantity, $maxAvailable);

        $item->update([
            'quantity' => $appliedQuantity,
        ]);

        $capped = $appliedQuantity < $requestedQuantity;

        return response()->json([
            'success' => true,
            'message' => $capped
                ? "Only {$appliedQuantity} item(s) are available for this size."
                : 'Item updated',
            'quantity' => $appliedQuantity,
            'capped' => $capped,
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

    private function resolveCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        return Cart::firstOrCreate([
            'session_id' => $request->session()->getId(),
            'user_id' => null,
        ]);
    }

    private function ensureOwnership(Request $request, CartItem $item): void
    {
        if ($request->user()) {
            abort_unless(
                $item->cart && (int) $item->cart->user_id === (int) $request->user()->id,
                403
            );
        } else {
            abort_unless(
                $item->cart
                && $item->cart->user_id === null
                && $item->cart->session_id === $request->session()->getId(),
                403
            );
        }
    }
}


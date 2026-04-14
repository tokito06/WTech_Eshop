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


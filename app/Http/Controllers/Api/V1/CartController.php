<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Services\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService): JsonResponse
    {
        $cart = $cartService->getCart($request->user());
        $items = $cart->items()->with(['product.shop', 'product.primaryImage', 'variant'])->get();

        $selectedSubtotal = $items->where('is_selected', true)->sum(function ($item) {
            $price = $item->product_variant_id && $item->variant
                ? (float) ($item->product->price + $item->variant->price_adjustment)
                : (float) $item->product->final_price;

            return $price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Isi keranjang belanja berhasil dimuat.',
            'data' => [
                'items' => CartItemResource::collection($items),
                'total_items' => $items->count(),
                'selected_subtotal' => $selectedSubtotal,
            ],
        ]);
    }

    public function add(Request $request, CartService $cartService): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $item = $cartService->addItem(
                $request->user(),
                (int) $request->input('product_id'),
                $request->input('product_variant_id') ? (int) $request->input('product_variant_id') : null,
                (int) $request->input('quantity', 1)
            );

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang belanja.',
                'data' => new CartItemResource($item->load(['product.shop', 'variant'])),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, int $id, CartService $cartService): JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $item = $cartService->updateQuantity($request->user(), $id, (int) $request->input('quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Jumlah barang di keranjang berhasil diperbarui.',
                'data' => new CartItemResource($item->load(['product.shop', 'variant'])),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(Request $request, int $id, CartService $cartService): JsonResponse
    {
        try {
            $cartService->removeItem($request->user(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang belanja.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}

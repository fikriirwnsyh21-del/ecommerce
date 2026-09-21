<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        public CartService $cartService
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->cartService->getCart($request->user());
        $cart->load([
            'items.product.shop',
            'items.product.primaryImage',
            'items.product.activeFlashSale',
            'items.variant',
        ]);

        // Group items by shop
        $itemsByShop = $cart->items->groupBy(function ($item) {
            return $item->product->shop->name;
        });

        return view('cart.index', compact('cart', 'itemsByShop'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->addItem(
                $request->user(),
                (int) $request->input('product_id'),
                $request->input('variant_id') ? (int) $request->input('variant_id') : null,
                (int) $request->input('quantity', 1)
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang!',
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function buyNow(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            // Deselect all items first
            $this->cartService->toggleSelectAll($request->user(), false);

            // Add item and mark selected
            $item = $this->cartService->addItem(
                $request->user(),
                (int) $request->input('product_id'),
                $request->input('variant_id') ? (int) $request->input('variant_id') : null,
                (int) $request->input('quantity', 1)
            );

            $item->update(['is_selected' => true]);

            return redirect()->route('checkout.index');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $item = $this->cartService->updateQuantity(
                $request->user(),
                $id,
                (int) $request->input('quantity')
            );

            $cart = $this->cartService->getCart($request->user());
            $cart->load('items.product', 'items.variant');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'itemSubtotal' => $item->subtotal,
                    'cartSubtotal' => $cart->subtotal,
                    'totalCount' => $cart->total_count,
                ]);
            }

            return back()->with('success', 'Kuantitas keranjang diperbarui.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function toggle(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $isSelected = $request->boolean('is_selected');
        $this->cartService->toggleItemSelection($request->user(), $id, $isSelected);

        $cart = $this->cartService->getCart($request->user());
        $cart->load('items.product', 'items.variant');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cartSubtotal' => $cart->subtotal,
            ]);
        }

        return back();
    }

    public function toggleAll(Request $request): JsonResponse|RedirectResponse
    {
        $isSelected = $request->boolean('is_selected');
        $this->cartService->toggleSelectAll($request->user(), $isSelected);

        $cart = $this->cartService->getCart($request->user());
        $cart->load('items.product', 'items.variant');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cartSubtotal' => $cart->subtotal,
            ]);
        }

        return back();
    }

    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $this->cartService->removeItem($request->user(), $id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk dihapus dari keranjang.']);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang belanja.');
    }
}

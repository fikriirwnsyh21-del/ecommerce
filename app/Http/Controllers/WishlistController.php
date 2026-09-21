<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Services\CartService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        public CartService $cartService
    ) {}

    public function index(Request $request): View
    {
        $wishlists = $request->user()->wishlists()
            ->with(['product.shop', 'product.primaryImage', 'product.images', 'product.activeFlashSale'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $productId = (int) $request->input('product_id');
        $user = $request->user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke wishlist!');
    }

    public function moveToCart(Request $request, int $productId): RedirectResponse
    {
        $user = $request->user();

        try {
            $this->cartService->addItem($user, $productId, null, 1);

            // Remove from wishlist
            Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();

            return redirect()->route('cart.index')->with('success', 'Produk berhasil dipindahkan ke keranjang!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $request->user()->wishlists()->where('id', $id)->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}

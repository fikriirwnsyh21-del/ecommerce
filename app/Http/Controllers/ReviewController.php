<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $user = $request->user();
        $orderItem = OrderItem::with(['order', 'product'])->where('id', $request->input('order_item_id'))->firstOrFail();

        // 1. Must be the buyer
        if ($orderItem->order->user_id !== $user->id) {
            abort(403, 'Hanya pembeli yang dapat memberikan ulasan untuk pesanan ini.');
        }

        // 2. Order must be completed
        if ($orderItem->order->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan jika pesanan sudah selesai.');
        }

        // 3. Cannot review if already reviewed
        if ($orderItem->is_reviewed || Review::where('order_item_id', $orderItem->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk pada pesanan ini.');
        }

        // 4. Seller cannot review their own product
        if ($user->shop && $orderItem->shop_id === $user->shop->id) {
            return back()->with('error', 'Penjual tidak diperbolehkan mengulas produk dari tokonya sendiri.');
        }

        DB::transaction(function () use ($request, $user, $orderItem) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('reviews', 'public');
            }

            Review::create([
                'order_item_id' => $orderItem->id,
                'user_id' => $user->id,
                'product_id' => $orderItem->product_id,
                'rating' => (int) $request->input('rating'),
                'comment' => $request->input('comment'),
                'photo_path' => $photoPath,
            ]);

            $orderItem->update(['is_reviewed' => true]);

            // Re-calculate product's average rating and review count
            $product = Product::find($orderItem->product_id);
            if ($product) {
                $avg = Review::where('product_id', $product->id)->avg('rating');
                $count = Review::where('product_id', $product->id)->count();

                $product->update([
                    'rating_avg' => round($avg, 2),
                    'reviews_count' => $count,
                ]);
            }
        });

        return back()->with('success', 'Ulasan Anda berhasil dikirim! Terima kasih atas feedback Anda.');
    }
}

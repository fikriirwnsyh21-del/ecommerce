<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Exception;

class CartService
{
    public function getCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, int $productId, ?int $variantId = null, int $quantity = 1): CartItem
    {
        $cart = $this->getCart($user);
        $product = Product::where('id', $productId)->where('is_active', true)->firstOrFail();

        // Check variant if supplied
        $availableStock = $product->stock;
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)->where('product_id', $productId)->firstOrFail();
            $availableStock = $variant->stock;
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        $newQty = $existingItem ? ($existingItem->quantity + $quantity) : $quantity;

        if ($newQty > $availableStock) {
            throw new Exception("Jumlah melebihi stok yang tersedia ({$availableStock} unit).");
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $newQty]);

            return $existingItem;
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'is_selected' => true,
        ]);
    }

    public function updateQuantity(User $user, int $cartItemId, int $quantity): CartItem
    {
        $cart = $this->getCart($user);
        $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->firstOrFail();

        $maxStock = $item->variant ? $item->variant->stock : $item->product->stock;

        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        if ($quantity > $maxStock) {
            throw new Exception("Stok maksimal hanya {$maxStock} unit.");
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function toggleItemSelection(User $user, int $cartItemId, bool $isSelected): CartItem
    {
        $cart = $this->getCart($user);
        $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->firstOrFail();
        $item->update(['is_selected' => $isSelected]);

        return $item;
    }

    public function toggleSelectAll(User $user, bool $isSelected): void
    {
        $cart = $this->getCart($user);
        $cart->items()->update(['is_selected' => $isSelected]);
    }

    public function removeItem(User $user, int $cartItemId): bool
    {
        $cart = $this->getCart($user);

        return (bool) CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->delete();
    }
}

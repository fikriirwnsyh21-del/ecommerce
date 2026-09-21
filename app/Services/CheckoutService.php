<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\VoucherUsage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        public VoucherService $voucherService
    ) {}

    /**
     * @param array{
     *   address_id: int,
     *   shipping_courier: string,
     *   payment_method: string,
     *   payment_channel?: string|null,
     *   voucher_code?: string|null,
     *   notes?: string|null
     * } $data
     */
    public function processCheckout(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = Cart::where('user_id', $user->id)->first();
            if (! $cart) {
                throw new Exception('Keranjang belanja Anda kosong.');
            }

            // Get selected cart items
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->where('is_selected', true)
                ->get();

            if ($cartItems->isEmpty()) {
                throw new Exception('Tidak ada produk yang dipilih untuk checkout.');
            }

            // Validate Address
            $address = Address::where('user_id', $user->id)->where('id', $data['address_id'])->first();
            if (! $address) {
                throw new Exception('Alamat pengiriman yang dipilih tidak valid.');
            }

            // Validate & Lock Stock using pessimistic lock (lockForUpdate)
            $subtotal = 0.0;
            $itemsToProcess = [];

            foreach ($cartItems as $item) {
                // Lock Product row
                $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();

                if (! $product->is_active) {
                    throw new Exception("Produk '{$product->name}' sedang tidak aktif.");
                }

                $variant = null;
                $availableStock = $product->stock;
                $unitPrice = $product->final_price;

                if ($item->product_variant_id) {
                    // Lock Variant row
                    $variant = ProductVariant::where('id', $item->product_variant_id)
                        ->where('product_id', $product->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $availableStock = $variant->stock;
                    $unitPrice += (float) $variant->price_adjustment;
                }

                if ($availableStock < $item->quantity) {
                    throw new Exception("Stok produk '{$product->name}' tidak mencukupi (Tersedia: {$availableStock}, Diminta: {$item->quantity}).");
                }

                $itemSubtotal = $unitPrice * $item->quantity;
                $subtotal += $itemSubtotal;

                $itemsToProcess[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calculate Shipping Cost based on courier
            $shippingCost = match ($data['shipping_courier']) {
                'Express (JNE YES - 1 Hari)' => 25000.0,
                'Hemat (SiCepat HALU - 3-5 Hari)' => 10000.0,
                default => 15000.0, // Reguler
            };

            // Calculate Voucher Discount
            $discountAmount = 0.0;
            $appliedVoucher = null;

            if (! empty($data['voucher_code'])) {
                $voucherResult = $this->voucherService->validateAndApply(
                    $data['voucher_code'],
                    $subtotal,
                    $user->id
                );
                $discountAmount = $voucherResult['discount_amount'];
                $appliedVoucher = $voucherResult['voucher'];
            }

            $grandTotal = max(0, $subtotal + $shippingCost - $discountAmount);

            // Generate Order Number: ORD-YYYYMMDD-XXXXXX
            $orderNumber = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'shipping_address' => [
                    'recipient_name' => $address->recipient_name,
                    'phone' => $address->phone,
                    'label' => $address->label,
                    'address_line' => $address->address_line,
                    'city' => $address->city,
                    'district' => $address->district,
                    'province' => $address->province,
                    'postal_code' => $address->postal_code,
                ],
                'shipping_courier' => $data['shipping_courier'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Create OrderItems, Decrement Stock atomically, and Increment Sales Count
            foreach ($itemsToProcess as $proc) {
                /** @var Product $prod */
                $prod = $proc['product'];
                /** @var ProductVariant|null $var */
                $var = $proc['variant'];
                $qty = $proc['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'shop_id' => $prod->shop_id,
                    'product_id' => $prod->id,
                    'product_variant_id' => $var?->id,
                    'product_name' => $prod->name,
                    'variant_name' => $var ? "{$var->name}: {$var->value}" : null,
                    'price' => $proc['unit_price'],
                    'quantity' => $qty,
                    'subtotal' => $proc['subtotal'],
                    'is_reviewed' => false,
                ]);

                // Atomically decrement stock
                $prod->decrement('stock', $qty);
                $prod->increment('sales_count', $qty);

                if ($var) {
                    $var->decrement('stock', $qty);
                }
            }

            // Record Voucher Usage
            if ($appliedVoucher) {
                VoucherUsage::create([
                    'voucher_id' => $appliedVoucher->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_applied' => $discountAmount,
                ]);

                $appliedVoucher->increment('used_count');
            }

            // Create Payment Record (Simulated Gateway)
            $paymentNumber = 'PAY-'.date('Ymd').'-'.strtoupper(Str::random(6));

            $pm = strtolower($data['payment_method'].' '.($data['payment_channel'] ?? ''));
            if (str_contains($pm, 'bca')) {
                $trxId = '8808'.sprintf('%010d', 1029384700 + $order->id);
            } elseif (str_contains($pm, 'mandiri')) {
                $trxId = '8902'.sprintf('%010d', 2039485700 + $order->id);
            } elseif (str_contains($pm, 'bri')) {
                $trxId = '7777'.sprintf('%010d', 3049586700 + $order->id);
            } elseif (str_contains($pm, 'bni')) {
                $trxId = '9880'.sprintf('%010d', 4059687700 + $order->id);
            } elseif (str_contains($pm, 'qris')) {
                $trxId = 'QRIS-'.date('Ymd').'-'.sprintf('%06d', $order->id);
            } elseif (str_contains($pm, 'cod')) {
                $trxId = 'COD-'.date('Ymd').'-'.sprintf('%06d', $order->id);
            } elseif (str_contains($pm, 'gopay') || str_contains($pm, 'dana')) {
                $trxId = 'EWL-'.date('Ymd').'-'.strtoupper(Str::random(8));
            } else {
                $trxId = 'TRX-'.Str::random(12);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_number' => $paymentNumber,
                'payment_method' => $data['payment_method'],
                'payment_channel' => $data['payment_channel'] ?? $data['payment_method'],
                'amount' => $grandTotal,
                'status' => 'pending',
                'transaction_id' => $trxId,
            ]);

            // Clear checked-out items from Cart
            CartItem::where('cart_id', $cart->id)
                ->where('is_selected', true)
                ->delete();

            return $order;
        });
    }
}

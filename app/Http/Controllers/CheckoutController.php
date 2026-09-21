<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\VoucherService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        public CartService $cartService,
        public CheckoutService $checkoutService,
        public VoucherService $voucherService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $cart = $this->cartService->getCart($user);
        $cart->load([
            'items.product.shop',
            'items.product.primaryImage',
            'items.product.activeFlashSale',
            'items.variant',
        ]);

        $selectedItems = $cart->items->where('is_selected', true);

        if ($selectedItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal satu produk untuk checkout.');
        }

        $addresses = $user->addresses()->orderByDesc('is_primary')->get();
        $primaryAddress = $addresses->where('is_primary', true)->first() ?? $addresses->first();

        $couriers = [
            [
                'name' => 'Reguler (J&T Express - 2-3 Hari)',
                'service' => 'Reguler',
                'etd' => '2-3 Hari',
                'cost' => 15000,
            ],
            [
                'name' => 'Hemat (SiCepat HALU - 3-5 Hari)',
                'service' => 'Hemat',
                'etd' => '3-5 Hari',
                'cost' => 10000,
            ],
            [
                'name' => 'Express (JNE YES - 1 Hari)',
                'service' => 'Express',
                'etd' => '1 Hari (Besok Sampai)',
                'cost' => 25000,
            ],
        ];

        $paymentMethods = [
            [
                'category' => 'QRIS & E-Wallet (Scan & Bayar Instan)',
                'badge' => 'Paling Populer',
                'methods' => [
                    [
                        'id' => 'qris',
                        'name' => 'QRIS (Semua Pembayaran)',
                        'code' => 'QRIS',
                        'type' => 'qris',
                        'fee' => 0,
                        'desc' => 'Scan otomatis via GoPay, OVO, DANA, ShopeePay, BCA Mobile, Livin, BRImo, dll.',
                        'badge' => 'Rekomendasi',
                        'icon' => 'QRIS',
                    ],
                    [
                        'id' => 'gopay',
                        'name' => 'GoPay / Gojek',
                        'code' => 'GOPAY',
                        'type' => 'ewallet',
                        'fee' => 0,
                        'desc' => 'Pembayaran praktis langsung dari saldo GoPay Anda.',
                        'badge' => 'Instan',
                        'icon' => 'GoPay',
                    ],
                    [
                        'id' => 'dana',
                        'name' => 'DANA Wallet',
                        'code' => 'DANA',
                        'type' => 'ewallet',
                        'fee' => 0,
                        'desc' => 'Pembayaran aman dengan saldo DANA terintegrasi.',
                        'badge' => 'Instan',
                        'icon' => 'DANA',
                    ],
                ],
            ],
            [
                'category' => 'Virtual Account (Verifikasi Otomatis 24 Jam)',
                'badge' => 'Bebas Admin',
                'methods' => [
                    [
                        'id' => 'bca_va',
                        'name' => 'BCA Virtual Account',
                        'code' => 'BCA_VA',
                        'type' => 'va',
                        'fee' => 0,
                        'desc' => 'Bayar via m-BCA, KlikBCA, atau ATM BCA dengan verifikasi otomatis 24 jam.',
                        'badge' => 'Otomatis',
                        'icon' => 'BCA',
                    ],
                    [
                        'id' => 'mandiri_va',
                        'name' => 'Mandiri Virtual Account',
                        'code' => 'MANDIRI_VA',
                        'type' => 'va',
                        'fee' => 0,
                        'desc' => 'Bayar via Livin by Mandiri atau ATM Mandiri tanpa konfirmasi.',
                        'badge' => 'Otomatis',
                        'icon' => 'Mandiri',
                    ],
                    [
                        'id' => 'bri_va',
                        'name' => 'BRI Virtual Account (BRIVA)',
                        'code' => 'BRI_VA',
                        'type' => 'va',
                        'fee' => 0,
                        'desc' => 'Bayar via BRImo atau ATM BRI kapan saja.',
                        'badge' => 'Otomatis',
                        'icon' => 'BRI',
                    ],
                    [
                        'id' => 'bni_va',
                        'name' => 'BNI Virtual Account',
                        'code' => 'BNI_VA',
                        'type' => 'va',
                        'fee' => 0,
                        'desc' => 'Bayar via BNI Mobile Banking atau ATM BNI.',
                        'badge' => 'Otomatis',
                        'icon' => 'BNI',
                    ],
                ],
            ],
            [
                'category' => 'Bayar di Tempat (COD)',
                'badge' => 'Bayar Tunai',
                'methods' => [
                    [
                        'id' => 'cod',
                        'name' => 'Bayar di Tempat (COD)',
                        'code' => 'COD',
                        'type' => 'cod',
                        'fee' => 0,
                        'desc' => 'Bayar tunai kepada kurir saat paket sampai dengan aman di rumah Anda.',
                        'badge' => 'Tunai',
                        'icon' => 'COD',
                    ],
                ],
            ],
        ];

        return view('checkout.index', compact(
            'cart',
            'selectedItems',
            'addresses',
            'primaryAddress',
            'couriers',
            'paymentMethods'
        ));
    }

    public function checkVoucher(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $res = $this->voucherService->validateAndApply(
                $request->string('code'),
                (float) $request->input('subtotal'),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => "Voucher '{$res['voucher']->name}' berhasil diterapkan!",
                'discount' => $res['discount_amount'],
                'voucherCode' => $res['voucher']->code,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function process(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->checkoutService->processCheckout($request->user(), $request->validated());

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}

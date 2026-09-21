<?php

namespace App\Services;

use App\Models\Voucher;
use Exception;

class VoucherService
{
    /**
     * @return array{voucher: Voucher, discount_amount: float}
     */
    public function validateAndApply(string $code, float $subtotal, int $userId): array
    {
        $voucher = Voucher::where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();

        if (! $voucher) {
            throw new Exception('Kode voucher tidak ditemukan atau sudah tidak aktif.');
        }

        if (now()->lt($voucher->start_date) || now()->gt($voucher->end_date)) {
            throw new Exception('Masa berlaku voucher belum dimulai atau telah berakhir.');
        }

        if ($voucher->used_count >= $voucher->quota) {
            throw new Exception('Kuota penukaran voucher ini telah habis.');
        }

        if ($subtotal < $voucher->min_purchase) {
            $minRp = number_format($voucher->min_purchase, 0, ',', '.');
            throw new Exception("Minimum pembelian untuk voucher ini adalah Rp {$minRp}.");
        }

        $alreadyUsed = $voucher->usages()->where('user_id', $userId)->exists();
        if ($alreadyUsed) {
            throw new Exception('Anda telah menggunakan voucher ini sebelumnya.');
        }

        $discountAmount = $voucher->calculateDiscount($subtotal);

        return [
            'voucher' => $voucher,
            'discount_amount' => $discountAmount,
        ];
    }
}

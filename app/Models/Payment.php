<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_number',
        'payment_method',
        'payment_channel',
        'amount',
        'status',
        'transaction_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getIsQrisAttribute(): bool
    {
        return str_contains(strtolower($this->payment_method), 'qris')
            || str_contains(strtolower($this->payment_channel ?? ''), 'qris');
    }

    public function getIsVirtualAccountAttribute(): bool
    {
        return str_contains(strtolower($this->payment_method), 'virtual account')
            || str_contains(strtolower($this->payment_method), 'va')
            || str_contains(strtolower($this->payment_channel ?? ''), 'va');
    }

    public function getIsCodAttribute(): bool
    {
        return str_contains(strtolower($this->payment_method), 'cod')
            || str_contains(strtolower($this->payment_method), 'tempat');
    }

    public function getIsEwalletAttribute(): bool
    {
        $pm = strtolower($this->payment_method.' '.($this->payment_channel ?? ''));

        return str_contains($pm, 'gopay') || str_contains($pm, 'dana') || str_contains($pm, 'ovo') || str_contains($pm, 'shopeepay');
    }

    public function getBankNameAttribute(): string
    {
        $text = strtoupper($this->payment_method.' '.($this->payment_channel ?? ''));
        if (str_contains($text, 'BCA')) {
            return 'BCA';
        }
        if (str_contains($text, 'MANDIRI')) {
            return 'Mandiri';
        }
        if (str_contains($text, 'BRI')) {
            return 'BRI';
        }
        if (str_contains($text, 'BNI')) {
            return 'BNI';
        }

        return 'Bank';
    }

    public function getVaNumberAttribute(): string
    {
        if (! empty($this->transaction_id) && is_numeric($this->transaction_id)) {
            return $this->transaction_id;
        }

        $orderId = $this->order_id ?? 1;
        $bank = $this->bank_name;
        $prefix = match ($bank) {
            'BCA' => '8808',
            'Mandiri' => '8902',
            'BRI' => '7777',
            'BNI' => '9880',
            default => '8888',
        };

        return $prefix.sprintf('%010d', 1029384700 + $orderId);
    }

    public function getFormattedVaNumberAttribute(): string
    {
        return wordwrap($this->va_number, 4, ' ', true);
    }

    public function getQrisQrUrlAttribute(): string
    {
        $payload = sprintf(
            '00020101021226580016ID.CO.KEBUTUHANGAMINGGENZ.WWW0118936000140000000000520454115303360540%.2f5802ID5924KEBUTUHAN GAMING GEN Z6007JAKARTA61051234062%d6304ABCD',
            $this->amount,
            $this->order_id ?? 1
        );

        return 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=10&data='.urlencode($payload);
    }
}

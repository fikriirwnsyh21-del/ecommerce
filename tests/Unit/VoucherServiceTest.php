<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use App\Models\Voucher;
use App\Services\VoucherService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VoucherService $voucherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voucherService = new VoucherService;

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    }

    public function test_voucher_percentage_discount_is_calculated_correctly(): void
    {
        $user = User::factory()->customer()->create();

        $voucher = Voucher::factory()->create([
            'code' => 'DISKON20',
            'type' => 'percentage',
            'amount' => 20,
            'min_purchase' => 100000,
            'max_discount' => 50000,
            'quota' => 10,
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $result = $this->voucherService->validateAndApply('DISKON20', 200000, $user->id);

        $this->assertEquals(40000, $result['discount_amount']);
    }

    public function test_voucher_exceeding_max_discount_is_capped(): void
    {
        $user = User::factory()->customer()->create();

        Voucher::factory()->create([
            'code' => 'DISKONMAX',
            'type' => 'percentage',
            'amount' => 50,
            'min_purchase' => 100000,
            'max_discount' => 30000,
            'quota' => 10,
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $result = $this->voucherService->validateAndApply('DISKONMAX', 200000, $user->id);

        $this->assertEquals(30000, $result['discount_amount']);
    }

    public function test_voucher_fails_if_subtotal_below_minimum_purchase(): void
    {
        $user = User::factory()->customer()->create();

        Voucher::factory()->create([
            'code' => 'MIN100K',
            'type' => 'fixed',
            'amount' => 10000,
            'min_purchase' => 100000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $this->expectException(Exception::class);
        $this->voucherService->validateAndApply('MIN100K', 50000, $user->id);
    }
}

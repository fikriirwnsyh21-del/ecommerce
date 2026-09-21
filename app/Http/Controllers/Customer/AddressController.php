<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreAddressRequest;
use App\Http\Requests\Customer\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        $addresses = $request->user()->addresses()->orderByDesc('is_primary')->latest()->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $user = $request->user();
        $isPrimary = $request->boolean('is_primary') || $user->addresses()->count() === 0;

        if ($isPrimary) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $user->addresses()->create([
            'recipient_name' => trim((string) $request->input('recipient_name')),
            'phone' => trim((string) $request->input('phone')),
            'label' => trim((string) $request->input('label')),
            'province' => trim((string) $request->input('province')),
            'city' => trim((string) $request->input('city')),
            'district' => trim((string) $request->input('district')),
            'postal_code' => trim((string) $request->input('postal_code')),
            'address_line' => trim((string) $request->input('address_line')),
            'is_primary' => $isPrimary,
        ]);

        if ($request->input('redirect') === 'checkout' || str_contains((string) $request->header('referer'), '/checkout')) {
            return redirect()->route('checkout.index')->with('success', 'Alamat pengiriman berhasil ditambahkan!');
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    public function update(UpdateAddressRequest $request, Address $address): RedirectResponse
    {
        $user = $request->user();
        $isPrimary = $request->boolean('is_primary');

        if ($isPrimary && ! $address->is_primary) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address->update([
            'recipient_name' => trim((string) $request->input('recipient_name')),
            'phone' => trim((string) $request->input('phone')),
            'label' => trim((string) $request->input('label')),
            'province' => trim((string) $request->input('province')),
            'city' => trim((string) $request->input('city')),
            'district' => trim((string) $request->input('district')),
            'postal_code' => trim((string) $request->input('postal_code')),
            'address_line' => trim((string) $request->input('address_line')),
            'is_primary' => $isPrimary || $address->is_primary,
        ]);

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat berhasil diperbarui!');
    }

    public function setPrimary(Request $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->user()->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat utama berhasil diubah!');
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $wasPrimary = $address->is_primary;
        $address->delete();

        if ($wasPrimary) {
            $firstRemaining = $request->user()->addresses()->first();
            $firstRemaining?->update(['is_primary' => true]);
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }
}

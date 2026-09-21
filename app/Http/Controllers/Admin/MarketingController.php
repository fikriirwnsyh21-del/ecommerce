<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\StoreVoucherRequest;
use App\Models\Banner;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MarketingController extends Controller
{
    /**
     * Banners Management
     */
    public function banners(): View
    {
        $banners = Banner::orderBy('sort_order')->latest()->paginate(15);

        return view('admin.marketing.banners', compact('banners'));
    }

    public function storeBanner(StoreBannerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $validated['title'],
            'image_path' => $path,
            'target_url' => $validated['target_url'] ?? '/',
            'position' => $validated['position'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner promosi baru berhasil ditambahkan.');
    }

    public function toggleBanner(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('success', 'Status banner berhasil diubah.');
    }

    public function destroyBanner(Banner $banner): RedirectResponse
    {
        if ($banner->image_path && ! str_starts_with($banner->image_path, 'http')) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return back()->with('success', 'Banner promosi berhasil dihapus.');
    }

    /**
     * Vouchers Management
     */
    public function vouchers(): View
    {
        $vouchers = Voucher::withCount('usages')->latest()->paginate(15);

        return view('admin.marketing.vouchers', compact('vouchers'));
    }

    public function storeVoucher(StoreVoucherRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = true;

        Voucher::create($validated);

        return back()->with('success', 'Voucher diskon platform berhasil dibuat.');
    }

    public function toggleVoucher(Voucher $voucher): RedirectResponse
    {
        $voucher->update(['is_active' => ! $voucher->is_active]);

        return back()->with('success', 'Status voucher berhasil diperbarui.');
    }

    public function destroyVoucher(Voucher $voucher): RedirectResponse
    {
        $voucher->delete();

        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}

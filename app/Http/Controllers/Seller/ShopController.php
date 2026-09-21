<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateShopRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function edit(Request $request): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        return view('seller.shop.edit', compact('shop'));
    }

    public function update(UpdateShopRequest $request): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            $validated['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop->update($validated);

        return back()->with('success', 'Profil dan informasi toko Anda berhasil diperbarui!');
    }
}

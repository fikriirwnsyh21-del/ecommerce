<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreProductRequest;
use App\Http\Requests\Seller\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $query = Product::where('shop_id', $shop->id)
            ->with(['category', 'primaryImage', 'variants'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->input('status') === 'low_stock') {
                $query->where('stock', '<=', 5);
            }
        }

        $products = $query->paginate(12)->withQueryString();

        return view('seller.products.index', compact('products'));
    }

    public function create(Request $request): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $categories = Category::orderBy('name')->get();

        return view('seller.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $validated = $request->validated();

        DB::transaction(function () use ($shop, $request, $validated) {
            // Generate unique slug
            $slug = Str::slug($validated['name']);
            $count = Product::where('slug', 'like', "{$slug}%")->count();
            if ($count > 0) {
                $slug = "{$slug}-".($count + 1);
            }

            $sku = 'PRD-'.strtoupper(Str::random(6));

            $product = Product::create([
                'shop_id' => $shop->id,
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'sku' => $sku,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'stock' => $validated['stock'],
                'weight' => $validated['weight'],
                'condition' => $validated['condition'],
                'is_active' => true,
            ]);

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // Handle variants
            if (! empty($validated['variants'])) {
                foreach ($validated['variants'] as $variant) {
                    if (! empty($variant['name'])) {
                        $priceAdj = isset($variant['price']) ? max(0, $variant['price'] - $product->price) : 0;
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => 'Varian',
                            'value' => $variant['name'],
                            'sku' => $product->sku.'-'.strtoupper(Str::slug($variant['name'])),
                            'price_adjustment' => $priceAdj,
                            'stock' => $variant['stock'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan ke katalog toko!');
    }

    public function edit(Request $request, Product $product): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop || $product->shop_id !== $shop->id, 403);

        $product->load(['images', 'variants']);
        $categories = Category::orderBy('name')->get();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop || $product->shop_id !== $shop->id, 403);

        $validated = $request->validated();

        DB::transaction(function () use ($product, $request, $validated) {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'stock' => $validated['stock'],
                'weight' => $validated['weight'],
                'condition' => $validated['condition'],
                'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : $product->is_active,
            ]);

            // Handle new images upload
            if ($request->hasFile('images')) {
                $currentMaxSort = $product->images()->max('sort_order') ?? -1;
                $hasPrimary = $product->images()->where('is_primary', true)->exists();

                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => ! $hasPrimary && $index === 0,
                        'sort_order' => $currentMaxSort + 1 + $index,
                    ]);
                }
            }

            // Sync variants
            if (isset($validated['variants'])) {
                $existingIds = [];
                foreach ($validated['variants'] as $vData) {
                    $priceAdj = isset($vData['price']) ? max(0, $vData['price'] - $product->price) : 0;
                    if (! empty($vData['id'])) {
                        $variant = ProductVariant::where('product_id', $product->id)->find($vData['id']);
                        if ($variant) {
                            $variant->update([
                                'value' => $vData['name'],
                                'price_adjustment' => $priceAdj,
                                'stock' => $vData['stock'],
                            ]);
                            $existingIds[] = $variant->id;
                        }
                    } elseif (! empty($vData['name'])) {
                        $newVariant = ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => 'Varian',
                            'value' => $vData['name'],
                            'sku' => $product->sku.'-'.strtoupper(Str::slug($vData['name'])),
                            'price_adjustment' => $priceAdj,
                            'stock' => $vData['stock'],
                        ]);
                        $existingIds[] = $newVariant->id;
                    }
                }

                // Remove deleted variants
                ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }
        });

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop || $product->shop_id !== $shop->id, 403);

        DB::transaction(function () use ($product) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $product->delete();
        });

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus dari toko.');
    }

    public function toggleActive(Request $request, Product $product): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop || $product->shop_id !== $shop->id, 403);

        $product->update(['is_active' => ! $product->is_active]);

        $statusText = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status produk berhasil {$statusText}.");
    }
}

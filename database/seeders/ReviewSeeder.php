<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerRole = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);

        // Create a pool of realistic Indonesian customers
        $customersData = [
            ['name' => 'Rizky Ramadhan', 'email' => 'rizky.ramadhan@example.com', 'phone' => '081298765001'],
            ['name' => 'Dimas Setiawan', 'email' => 'dimas.setiawan@example.com', 'phone' => '081298765002'],
            ['name' => 'Andi Wijaya', 'email' => 'andi.wijaya@example.com', 'phone' => '081298765003'],
            ['name' => 'Kevin Pratama', 'email' => 'kevin.pratama@example.com', 'phone' => '081298765004'],
            ['name' => 'Farhan Alamsyah', 'email' => 'farhan.alamsyah@example.com', 'phone' => '081298765005'],
            ['name' => 'Gerry Anugrah', 'email' => 'gerry.anugrah@example.com', 'phone' => '081298765006'],
            ['name' => 'Bayu Wicaksono', 'email' => 'bayu.wicaksono@example.com', 'phone' => '081298765007'],
        ];

        $users = [];
        foreach ($customersData as $c) {
            $users[] = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'role_id' => $customerRole->id,
                    'name' => $c['name'],
                    'phone' => $c['phone'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        // Specific high-value products to populate with reviews
        $productReviews = [
            // PC Gaming Budget 10 Juta (Product 64 or by slug)
            'pc-rakitan-budget-king-gen-z-ryzen-5-rtx-4060' => [
                [
                    'user_idx' => 0,
                    'rating' => 5,
                    'comment' => 'Build PC-nya rapi banget! Cable management bersih, suhu adem banget pake AIO cooler. Buat main Valorant dapet 350+ FPS stabil, Black Myth Wukong di 1080p High juga lancar jaya berkat DLSS. Rekomendasi banget buat gamer!',
                ],
                [
                    'user_idx' => 1,
                    'rating' => 5,
                    'comment' => 'Seller fast response, perakitan cepat dan dikirim pakai packing kayu super aman sampai luar pulau. Sudah diinstal Windows dan driver tinggal colok langsung pakai.',
                ],
                [
                    'user_idx' => 2,
                    'rating' => 5,
                    'comment' => 'Harga terbaik untuk spek Ryzen 5 + RTX 4060. Casing panoramic tempered glass-nya cakep pol ada lampu ARGB yang bisa diatur lewat software. Puas banget belanja di sini!',
                ],
            ],
            // PlayStation 5 Slim
            'sony-playstation-5-slim-1tb-garansi-resmi' => [
                [
                    'user_idx' => 3,
                    'rating' => 5,
                    'comment' => 'Konsol PS5 Slim original 100% bergaransi resmi Sony Indonesia. Ukurannya lebih ringkas dibanding varian fat lama. Haptic feedback DualSense sensasinya juara!',
                ],
                [
                    'user_idx' => 4,
                    'rating' => 5,
                    'comment' => 'Pengiriman kilat SiCepat Best 1 hari sampai, packing bubble tebal berlapis. Box mulus tanpa penyok, segel resmi utuh. Mantul!',
                ],
                [
                    'user_idx' => 5,
                    'rating' => 5,
                    'comment' => 'Game Astro Bot dan Spiderman 2 jalan mulus 60 FPS HDR tajam di TV 4K. Kualitas pelayanan toko bintang lima.',
                ],
            ],
            // Razer Huntsman V3 Pro
            'razer-huntsman-v3-pro-tkl-analog-optical' => [
                [
                    'user_idx' => 0,
                    'rating' => 5,
                    'comment' => 'Fitur Rapid Trigger di keyboard ini beneran game changer buat game tactical shooter kayak Valorant & CS2! Counter-strafing jadi instan tanpa delay. Build aluminiumnya juga kokoh mewah.',
                ],
                [
                    'user_idx' => 6,
                    'rating' => 5,
                    'comment' => 'Switch analog opticalnya sangat responsif, wrist rest magnetik empuk bikin betah main seharian. Software Razer Synapse gampang disetting.',
                ],
            ],
            // Logitech G Pro X Superlight 2
            'logitech-g-pro-x-superlight-2-wireless' => [
                [
                    'user_idx' => 1,
                    'rating' => 5,
                    'comment' => 'Mouse teringan dan ternyaman yang pernah saya pakai! Sensor HERO 2 presisi tanpa jitter, baterai awet tahan 2 minggu buat kerja dan gaming.',
                ],
                [
                    'user_idx' => 2,
                    'rating' => 5,
                    'comment' => 'Switch optik hybrid barunya kliknya renyah dan anti double click. Warna Ghost White-nya estetik parah di desk setup.',
                ],
            ],
            // Valve Steam Deck OLED
            'valve-steam-deck-oled-512gb-1tb' => [
                [
                    'user_idx' => 3,
                    'rating' => 5,
                    'comment' => 'Layar OLED 90Hz-nya bener-bener bening dan warna hitamnya pekat luar biasa. Baterai jauh lebih awet daripada versi LCD. Main Cyberpunk & Elden Ring di ranjang jadi rutinitas tiap malam!',
                ],
                [
                    'user_idx' => 4,
                    'rating' => 5,
                    'comment' => 'Packing super aman, dapet case bawaan premium. Performa emulasi dan game Steam library lancar jaya.',
                ],
            ],
            // AMD Ryzen 7 7800X3D
            'amd-ryzen-7-7800x3d-processor-gaming' => [
                [
                    'user_idx' => 5,
                    'rating' => 5,
                    'comment' => 'Prosesor gaming terbaik saat ini tanpa tanding! 3D V-Cache bikin 1% low FPS sangat stabil di game berat. Suhu juga relatif dingin dipairing dengan AIO 360mm.',
                ],
                [
                    'user_idx' => 6,
                    'rating' => 5,
                    'comment' => 'Original box segel garansi resmi distributor 3 tahun. Pengiriman aman pakai asuransi.',
                ],
            ],
        ];

        // Also find product 64 specifically (or latest budget PC product) if its slug differs
        $product64 = Product::find(64);
        if ($product64 && ! isset($productReviews[$product64->slug])) {
            $productReviews[$product64->slug] = [
                [
                    'user_idx' => 0,
                    'rating' => 5,
                    'comment' => 'PC Gaming mantap banget, spek sesuai pesanan dan cable management super rapi! Main game lancar jaya tanpa kendala. Recommended seller!',
                ],
                [
                    'user_idx' => 1,
                    'rating' => 5,
                    'comment' => 'Packing kayu tebal dan bubble wrap aman sampai tujuan. Tinggal colok langsung siap gaming!',
                ],
                [
                    'user_idx' => 2,
                    'rating' => 5,
                    'comment' => 'Performa kencang, rendering video cepat, suhu stabil dan tidak berisik. Terima kasih banyak seller!',
                ],
            ];
        }

        foreach ($productReviews as $slug => $reviews) {
            $product = Product::where('slug', $slug)->first();
            if (! $product) {
                continue;
            }

            foreach ($reviews as $revData) {
                $user = $users[$revData['user_idx']];

                // Create a completed order for this user and product
                $orderNumber = 'ORD-REV-'.strtoupper(Str::random(8));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'subtotal' => $product->final_price,
                    'shipping_cost' => 25000,
                    'discount_amount' => 0,
                    'grand_total' => $product->final_price + 25000,
                    'status' => 'completed',
                    'shipping_address' => [
                        'recipient_name' => $user->name,
                        'phone' => $user->phone,
                        'address_line' => 'Jl. Kebon Jeruk No. 88',
                        'city' => 'Jakarta Barat',
                        'province' => 'DKI Jakarta',
                        'postal_code' => '11530',
                    ],
                    'shipping_courier' => 'Reguler (J&T Express)',
                    'tracking_number' => 'JT'.rand(1000000000, 9999999999),
                    'notes' => 'Tolong dicek sebelum kirim ya gan.',
                ]);

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'shop_id' => $product->shop_id,
                    'product_name' => $product->name,
                    'variant_name' => $product->variants->first()?->value,
                    'price' => $product->final_price,
                    'quantity' => 1,
                    'subtotal' => $product->final_price,
                    'is_reviewed' => true,
                ]);

                Review::create([
                    'order_item_id' => $orderItem->id,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => $revData['rating'],
                    'comment' => $revData['comment'],
                    'photo_path' => null,
                ]);
            }

            // Update product's real average rating and count
            $avg = Review::where('product_id', $product->id)->avg('rating');
            $count = Review::where('product_id', $product->id)->count();

            $product->update([
                'rating_avg' => round($avg, 2),
                'reviews_count' => $count,
            ]);
        }
    }
}

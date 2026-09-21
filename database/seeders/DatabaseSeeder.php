<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with an expansive Electronics & Gaming Gear ecosystem (60+ items).
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $sellerRole = Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        $customerRole = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);

        // 2. Default Accounts
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Platform Administrator',
                'phone' => '081200000001',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $sellerUser1 = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'role_id' => $sellerRole->id,
                'name' => 'ASUS ROG Official Partner',
                'phone' => '081200000002',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'role_id' => $customerRole->id,
                'name' => 'Budi Santoso',
                'phone' => '081200000003',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4 additional tech & gaming sellers
        $sellerNames = [
            'Razer Flagship Store',
            'GeForce & AMD PC Master',
            'SteelSeries & Corsair Hub',
            'PlayStation & Console Station',
        ];
        $sellerEmails = ['seller2@example.com', 'seller3@example.com', 'seller4@example.com', 'seller5@example.com'];
        $otherSellers = [];
        for ($i = 0; $i < 4; $i++) {
            $otherSellers[] = User::firstOrCreate(
                ['email' => $sellerEmails[$i]],
                [
                    'role_id' => $sellerRole->id,
                    'name' => $sellerNames[$i],
                    'phone' => '08120000001'.($i + 4),
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        // 3. Customer Address
        $customerAddress = Address::firstOrCreate(
            ['user_id' => $customerUser->id, 'recipient_name' => 'Budi Santoso'],
            [
                'phone' => '081200000003',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Senopati Raya No. 45 RT 02/RW 03',
                'is_primary' => true,
            ]
        );

        Address::firstOrCreate(
            ['user_id' => $customerUser->id, 'recipient_name' => 'Budi Santoso (Kantor)'],
            [
                'phone' => '081200000003',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Pusat',
                'district' => 'Sudirman Central Business District',
                'postal_code' => '12190',
                'address_line' => 'Gedung Bursa Efek Indonesia Tower 2 Lt. 15',
                'is_primary' => false,
            ]
        );

        // 4. Tech & Gaming Shops
        $shopData = [
            [
                'user_id' => $sellerUser1->id,
                'name' => 'Kebutuhan Gaming Gen Z (Official Store)',
                'slug' => 'kebutuhan-gaming-gen-z',
                'city' => 'Jakarta Pusat',
                'description' => 'Flagship Store resmi Kebutuhan Gaming Gen Z. Pusat terlengkap laptop gaming flagship, komponen PC rakitan enthusiast, monitor 240Hz, keyboard mechanical kustom, dan konsol original bergaransi resmi Indonesia.',
                'rating' => 4.98,
                'followers_count' => 54200,
                'is_active' => true,
            ],
            [
                'user_id' => $otherSellers[0]->id,
                'name' => 'Razer Flagship Store',
                'slug' => 'razer-flagship-store',
                'city' => 'Jakarta Barat',
                'description' => 'Toko resmi Razer Indonesia. For Gamers. By Gamers. Peripheral gaming kompetitif, optical mechanical keyboard, mouse ultralight Chroma RGB, headset THX Spatial, dan streaming gear.',
                'rating' => 4.94,
                'followers_count' => 28900,
                'is_active' => true,
            ],
            [
                'user_id' => $otherSellers[1]->id,
                'name' => 'GeForce & AMD PC Master',
                'slug' => 'geforce-amd-pc-master',
                'city' => 'Bandung',
                'description' => 'Distributor spesialis hardware PC rakitan enthusiast. NVIDIA GeForce RTX 40 & 50 series, AMD Ryzen 7000/9000 series, liquid cooler AIO, RAM DDR5, dan casing airflow premium.',
                'rating' => 4.92,
                'followers_count' => 19400,
                'is_active' => true,
            ],
            [
                'user_id' => $otherSellers[2]->id,
                'name' => 'SteelSeries & Corsair Hub',
                'slug' => 'steelseries-corsair-hub',
                'city' => 'Surabaya',
                'description' => 'Pusat perlengkapan esports pro player. Mechanical keyboard kustom gasket mount, wireless lightweight gaming mouse, audiophile headset, elgato capture card, dan desk setup.',
                'rating' => 4.90,
                'followers_count' => 16500,
                'is_active' => true,
            ],
            [
                'user_id' => $otherSellers[3]->id,
                'name' => 'PlayStation & Console Station',
                'slug' => 'playstation-console-station',
                'city' => 'Jakarta Selatan',
                'description' => 'Pusat konsol resmi PlayStation 5, PlayStation 5 Pro, Nintendo Switch OLED, Steam Deck OLED, controller DualSense, game fisik original, dan aksesoris bergaransi resmi Sony Indonesia.',
                'rating' => 4.96,
                'followers_count' => 42100,
                'is_active' => true,
            ],
        ];

        $shops = [];
        foreach ($shopData as $sd) {
            $shops[] = Shop::firstOrCreate(['slug' => $sd['slug']], $sd);
        }

        // 5. 15 Dedicated Gaming & Tech Categories
        $categoriesData = [
            ['name' => 'Laptop Gaming', 'slug' => 'laptop-gaming', 'icon' => 'laptop', 'sort_order' => 1],
            ['name' => 'PC Rakitan', 'slug' => 'pc-rakitan', 'icon' => 'computer-desktop', 'sort_order' => 2],
            ['name' => 'PC & Komponen', 'slug' => 'pc-komponen', 'icon' => 'cpu-chip', 'sort_order' => 3],
            ['name' => 'Monitor Gaming', 'slug' => 'monitor-gaming', 'icon' => 'tv', 'sort_order' => 4],
            ['name' => 'Keyboard Mechanical', 'slug' => 'keyboard-mechanical', 'icon' => 'keyboard', 'sort_order' => 5],
            ['name' => 'Mouse Gaming', 'slug' => 'mouse-gaming', 'icon' => 'cursor-arrow-rays', 'sort_order' => 6],
            ['name' => 'Audio & Headset', 'slug' => 'audio-headset', 'icon' => 'speaker-wave', 'sort_order' => 7],
            ['name' => 'Konsol & Handheld', 'slug' => 'konsol-handheld', 'icon' => 'puzzle-piece', 'sort_order' => 8],
            ['name' => 'Smartphone Gaming', 'slug' => 'smartphone-gaming', 'icon' => 'device-phone-mobile', 'sort_order' => 9],
            ['name' => 'Streaming Gear', 'slug' => 'streaming-gear', 'icon' => 'video-camera', 'sort_order' => 10],
            ['name' => 'Kursi & Setup Meja', 'slug' => 'kursi-setup-meja', 'icon' => 'sparkles', 'sort_order' => 11],
            ['name' => 'Storage RAM & SSD', 'slug' => 'ram-ssd-storage', 'icon' => 'bolt', 'sort_order' => 12],
            ['name' => 'Cooling & Power Supply', 'slug' => 'cooling-power-supply', 'icon' => 'fan', 'sort_order' => 13],
            ['name' => 'Racing Sim & VR', 'slug' => 'racing-sim-vr', 'icon' => 'steering-wheel', 'sort_order' => 14],
            ['name' => 'Aksesoris & Modding', 'slug' => 'aksesoris-gaming', 'icon' => 'wrench-screwdriver', 'sort_order' => 15],
        ];

        $categories = [];
        foreach ($categoriesData as $cd) {
            $categories[$cd['slug']] = Category::firstOrCreate(['slug' => $cd['slug']], $cd);
        }

        // 6. 60 Expansive High-End Electronics & Gaming Products
        $productsData = [
            // [0] Laptop Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'ASUS ROG Zephyrus G16 OLED Intel Core Ultra 9 32GB 1TB RTX 4080 240Hz',
                'slug' => 'asus-rog-zephyrus-g16-oled-ultra-9-rtx-4080',
                'sku' => 'ROG-ZEP-G16',
                'price' => 45999000,
                'discount_price' => 42499000,
                'stock' => 15,
                'rating_avg' => 4.98,
                'reviews_count' => 84,
                'sales_count' => 165,
                'description' => 'ASUS ROG Zephyrus G16 menghadirkan era baru gaming laptop ultra-tipis dengan chassis aluminium CNC presisi. Ditenagai prosesor Intel Core Ultra 9 185H dengan NPU AI terintegrasi, GPU NVIDIA GeForce RTX 4080 12GB GDDR6X TGP 115W, dan layar memukau 16-inch 2.5K OLED ROG Nebula Display 240Hz 0.2ms 100% DCI-P3 dengan dukungan G-Sync.',
                'images' => [
                    'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Spesifikasi', 'value' => '32GB RAM / 1TB SSD / RTX 4080', 'price_adjustment' => 0, 'stock' => 10],
                    ['name' => 'Spesifikasi', 'value' => '64GB RAM / 2TB SSD / RTX 4090', 'price_adjustment' => 8000000, 'stock' => 5],
                ],
            ],
            // [1] Laptop Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'ASUS TUF Gaming A15 AMD Ryzen 7 7735HS 16GB 512GB RTX 4060 144Hz FHD',
                'slug' => 'asus-tuf-gaming-a15-ryzen-7-rtx-4060',
                'sku' => 'TUF-A15-R7',
                'price' => 18499000,
                'discount_price' => 16999000,
                'stock' => 35,
                'rating_avg' => 4.91,
                'reviews_count' => 128,
                'sales_count' => 410,
                'description' => 'Laptop gaming bertenaga dengan durabilitas militer MIL-STD-810H. Dilengkapi prosesor AMD Ryzen 7 7735HS 8-core 16-thread, GPU NVIDIA GeForce RTX 4060 8GB GDDR6 (140W max TGP dengan MUX Switch), RAM 16GB DDR5 4800MHz dual channel, dan layar 15.6 inch IPS 144Hz.',
                'images' => [
                    'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Kapasitas', 'value' => 'RAM 16GB / 512GB NVMe SSD', 'price_adjustment' => 0, 'stock' => 20],
                    ['name' => 'Kapasitas', 'value' => 'RAM 32GB / 1TB NVMe SSD', 'price_adjustment' => 1800000, 'stock' => 15],
                ],
            ],
            // [2] Laptop Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'Lenovo Legion Pro 7i Gen 9 Intel Core i9 14900HX 32GB 2TB RTX 4090 240Hz',
                'slug' => 'lenovo-legion-pro-7i-i9-14900hx-rtx-4090',
                'sku' => 'LEG-PRO-7I',
                'price' => 52999000,
                'discount_price' => 48999000,
                'stock' => 10,
                'rating_avg' => 4.99,
                'reviews_count' => 42,
                'sales_count' => 95,
                'description' => 'Flagship teratas Lenovo Legion dengan prosesor monster Intel Core i9-14900HX (24 core 32 thread), GPU NVIDIA GeForce RTX 4090 16GB GDDR6 (175W Full TGP), layar 16 inci WQXGA 240Hz 100% DCI-P3 dengan sertifikasi X-Rite Pantone, dan sistem pendingin Legion Coldfront 5.0 vapor chamber.',
                'images' => [
                    'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],
            // [3] Laptop Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'Acer Predator Helios 16 Intel Core i7 14700HX 16GB 1TB RTX 4070 240Hz WQXGA',
                'slug' => 'acer-predator-helios-16-i7-rtx-4070',
                'sku' => 'ACR-HELIOS-16',
                'price' => 28999000,
                'discount_price' => 26499000,
                'stock' => 22,
                'rating_avg' => 4.93,
                'reviews_count' => 64,
                'sales_count' => 180,
                'description' => 'Predator Helios 16 menggabungkan performa dahsyat Core i7 generasi ke-14, RTX 4070 8GB GDDR6 140W MUX Switch, teknologi pendingin 5th Gen AeroBlade 3D Fan liquid metal, dan keyboard RGB per-key berteknologi MagKey 3.0.',
                'images' => [
                    'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],
            // [4] Laptop Gaming
            [
                'shop_idx' => 1,
                'cat_slug' => 'laptop-gaming',
                'name' => 'Razer Blade 14 QHD+ 240Hz AMD Ryzen 9 8945HS 32GB 1TB RTX 4070 Mercury',
                'slug' => 'razer-blade-14-ryzen-9-rtx-4070',
                'sku' => 'RZR-BLADE-14',
                'price' => 41999000,
                'discount_price' => 38999000,
                'stock' => 14,
                'rating_avg' => 4.97,
                'reviews_count' => 39,
                'sales_count' => 85,
                'description' => 'Laptop gaming ultra-portabel teringkas di kelasnya dengan ketebalan hanya 17.99mm. Mengusung AMD Ryzen 9 8945HS dengan Ryzen AI NPU 39 TOPS, GPU RTX 4070 140W TGP, layar 14 inci QHD+ 240Hz 16:10 Calman Verified, dan sasis aluminium unibody anodized.',
                'images' => [
                    'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Chasis', 'value' => 'Classic Matte Black', 'price_adjustment' => 0, 'stock' => 8],
                    ['name' => 'Warna Chasis', 'value' => 'Mercury White Edition', 'price_adjustment' => 1000000, 'stock' => 6],
                ],
            ],
            // [5] Laptop Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'MSI Stealth 16 AI Studio OLED Intel Core Ultra 7 32GB 1TB RTX 4070',
                'slug' => 'msi-stealth-16-ai-studio-oled-rtx-4070',
                'sku' => 'MSI-STEALTH-16',
                'price' => 36499000,
                'discount_price' => 33999000,
                'stock' => 16,
                'rating_avg' => 4.90,
                'reviews_count' => 31,
                'sales_count' => 74,
                'description' => 'Kombinasi sempurna antara gaming dan konten kreasi profesional. Bodi paduan magnesium-aluminium super ringan hanya 1.99kg, baterai raksasa 99.9Whr, audio 6 speaker Dynaudio, dan layar 16 inci UHD+ OLED 120Hz 100% DCI-P3.',
                'images' => [
                    'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [6] PC Rakitan
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Rakitan Gaming Beast Ultra Intel Core Ultra 9 + RTX 4090 24GB + 64GB DDR5 Liquid Cooled',
                'slug' => 'pc-rakitan-gaming-beast-ultra-core-ultra-9-rtx-4090',
                'sku' => 'PC-BEAST-ULTRA',
                'price' => 78999000,
                'discount_price' => 74499000,
                'stock' => 8,
                'rating_avg' => 5.0,
                'reviews_count' => 24,
                'sales_count' => 45,
                'description' => 'Monster PC rakitan tanpa kompromi untuk resolusi 4K Ray Tracing maksimal dan render 3D berat. Spesifikasi: Intel Core Ultra 9 / i9-14900K, ASUS ROG Strix GeForce RTX 4090 24GB OC, Motherboard Z790 Dark Hero, RAM Corsair Dominator Titanium 64GB DDR5 6600MHz, SSD Samsung 990 Pro 4TB NVMe Gen4, AIO Cooler NZXT Kraken Elite 360 LCD, PSU Seasonic 1200W Titanium, Casing Lian Li O11 Dynamic EVO RGB. Sudah terinstal Windows 11 Pro Original berlisensi & stress test 24 jam.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Kapasitas Storage', 'value' => '64GB DDR5 + 2TB NVMe SSD', 'price_adjustment' => 0, 'stock' => 5],
                    ['name' => 'Kapasitas Storage', 'value' => '128GB DDR5 + 4TB NVMe SSD', 'price_adjustment' => 7500000, 'stock' => 3],
                ],
            ],

            // [7] Keyboard Mechanical (Must match index 7 in sample cart)
            [
                'shop_idx' => 1,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Razer Huntsman V3 Pro Tenkeyless Analog Optical Esports Keyboard Gen-2',
                'slug' => 'razer-huntsman-v3-pro-tkl-analog-optical',
                'sku' => 'KB-RZR-HV3P-TKL',
                'price' => 3899000,
                'discount_price' => 3499000,
                'stock' => 45,
                'rating_avg' => 4.97,
                'reviews_count' => 112,
                'sales_count' => 380,
                'description' => 'Keyboard esports kelas turnamen dengan Switch Analog Optical Gen-2 berteknologi Rapid Trigger. Aktuasi yang dapat disetel presisi dari 0.1 mm hingga 4.0 mm, pelat atas aluminium 5052 disikat elegan, keycaps Doubleshot PBT bertekstur, wrist rest kulit magnetik, dan kenop putar digital serbaguna.',
                'images' => [
                    'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [8] Keyboard Mechanical
            [
                'shop_idx' => 0,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Wooting 60HE+ Rapid Trigger Analog Magnetic Switch Custom Gaming Keyboard',
                'slug' => 'wooting-60he-plus-rapid-trigger-keyboard',
                'sku' => 'KB-WOOT-60HE',
                'price' => 3999000,
                'discount_price' => 3699000,
                'stock' => 30,
                'rating_avg' => 4.99,
                'reviews_count' => 150,
                'sales_count' => 490,
                'description' => 'Raja keyboard esports FPS kompetitif Valorant & CS2. Menggunakan Lekker Magnetic Hall Effect switches dengan input analog nyata 0.1mm hingga 4.0mm, fitur Rapid Trigger tak tertandingi tanpa jeda reset, tachyon mode sub-1ms latency, per-key RGB, dan kompatibel modding sasis aluminium standar 60%.',
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [9] Mouse Gaming (Must match index 9 in sample cart)
            [
                'shop_idx' => 1,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Razer DeathAdder V3 Pro Wireless Ergonomic 63g Focus Pro 30K Optical',
                'slug' => 'razer-deathadder-v3-pro-wireless-mouse',
                'sku' => 'MS-RZR-DAV3-PRO',
                'price' => 2499000,
                'discount_price' => 2199000,
                'stock' => 50,
                'rating_avg' => 4.95,
                'reviews_count' => 189,
                'sales_count' => 620,
                'description' => 'Ikon ergonomis esports yang disempurnakan bersama atlet pro dunia. Berat ultra-ringan hanya 63 gram tanpa lubang honeycomb, sensor optik Focus Pro 30K DPI dengan akurasi resolusi 99.8%, optical switch Gen-3 tanpa double-click, daya tahan baterai hingga 90 jam, dan dukungan 8000Hz polling rate dengan dongle HyperPolling.',
                'images' => [
                    'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Pilihan Warna', 'value' => 'Pro Black Edition', 'price_adjustment' => 0, 'stock' => 30],
                    ['name' => 'Pilihan Warna', 'value' => 'Pure White Edition', 'price_adjustment' => 50000, 'stock' => 20],
                ],
            ],

            // [10] Mouse Gaming
            [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Logitech G Pro X Superlight 2 Wireless Gaming Mouse Hero 2 60g 4K Polling',
                'slug' => 'logitech-g-pro-x-superlight-2-wireless',
                'sku' => 'MS-LOG-GPX2',
                'price' => 2399000,
                'discount_price' => 2149000,
                'stock' => 55,
                'rating_avg' => 4.98,
                'reviews_count' => 230,
                'sales_count' => 810,
                'description' => 'Mouse gaming terpopuler di turnamen esports profesional dunia. Dilengkapi Switch Hibrida LIGHTFORCE optik-mekanikal generasi terbaru, sensor HERO 2 dengan tracking lebih dari 500 IPS dan 32.000 DPI, koneksi nirkabel LIGHTSPEED 4000Hz polling rate, pengisian daya USB-C, dan bobot ringan presisi 60 gram.',
                'images' => [
                    'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Mouse', 'value' => 'Matte Black', 'price_adjustment' => 0, 'stock' => 25],
                    ['name' => 'Warna Mouse', 'value' => 'Ghost White', 'price_adjustment' => 0, 'stock' => 20],
                    ['name' => 'Warna Mouse', 'value' => 'Magenta Pink Special Edition', 'price_adjustment' => 100000, 'stock' => 10],
                ],
            ],

            // [11] Mouse Gaming
            [
                'shop_idx' => 1,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Razer Viper V3 Pro Ultra-Lightweight 54g 8000Hz HyperPolling Wireless',
                'slug' => 'razer-viper-v3-pro-ultra-lightweight-mouse',
                'sku' => 'MS-RZR-VIPER-V3P',
                'price' => 2699000,
                'discount_price' => 2399000,
                'stock' => 35,
                'rating_avg' => 4.96,
                'reviews_count' => 94,
                'sales_count' => 340,
                'description' => 'Mouse esports simetris teringan Razer dengan berat hanya 54 gram. Dilengkapi langsung dengan Razer HyperPolling Wireless Dongle native 8000Hz, sensor optik Focus Pro Gen-2 35K DPI dengan akurasi pelacakan 1-DPI step, dan daya tahan baterai hingga 95 jam.',
                'images' => [
                    'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [12] Mouse Gaming
            [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Pulsar X2V2 Wireless Gaming Mouse 52g PixArt PAW3395 Optical Switch',
                'slug' => 'pulsar-x2v2-wireless-gaming-mouse',
                'sku' => 'MS-PLS-X2V2',
                'price' => 1499000,
                'discount_price' => 1349000,
                'stock' => 40,
                'rating_avg' => 4.92,
                'reviews_count' => 76,
                'sales_count' => 290,
                'description' => 'Mouse gaming wireless favorit komunitas enthusiast dengan bentuk simetris low-profile. Berat hanya 52 gram tanpa lubang, Optical Switch anti-debounce delay, sensor PixArt PAW3395 26.000 DPI 650 IPS, dan kompatibel 4K polling rate dongle.',
                'images' => [
                    'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [13] Konsol & Handheld (Must match index 13 for PS5 Slim)
            [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Sony PlayStation 5 Slim Digital & Disc Edition 1TB Garansi Resmi Sony Indonesia',
                'slug' => 'sony-playstation-5-slim-1tb-garansi-resmi',
                'sku' => 'CNS-SONY-PS5-SLIM',
                'price' => 9699000,
                'discount_price' => 8999000,
                'stock' => 35,
                'rating_avg' => 4.98,
                'reviews_count' => 260,
                'sales_count' => 740,
                'description' => 'Konsol gaming generasi terbaru dari Sony dalam desain yang lebih ramping 30% dan ringan. Dilengkapi penyimpanan internal ultra-fast SSD 1TB, dukungan grafis 4K 120Hz dengan Ray Tracing berbasis arsitektur AMD RDNA 2, Tempest 3D AudioTech, dan kontroler DualSense dengan Haptic Feedback & Adaptive Triggers. 100% Original bergaransi resmi Sony Indonesia 1+1 tahun.',
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Tipe Edisi', 'value' => 'Standar (1 DualSense Controller)', 'price_adjustment' => 0, 'stock' => 20],
                    ['name' => 'Tipe Edisi', 'value' => 'Disc Edition + Extra DualSense Controller', 'price_adjustment' => 1400000, 'stock' => 15],
                ],
            ],

            // [14] Konsol & Handheld
            [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Sony PlayStation 5 Pro 2TB PSSR Advanced Ray Tracing 4K 120FPS Garansi Resmi',
                'slug' => 'sony-playstation-5-pro-2tb-pssr-ray-tracing',
                'sku' => 'CNS-SONY-PS5-PRO',
                'price' => 14999000,
                'discount_price' => 13999000,
                'stock' => 15,
                'rating_avg' => 4.99,
                'reviews_count' => 54,
                'sales_count' => 110,
                'description' => 'Konsol paling bertenaga yang pernah diciptakan PlayStation. Menghadirkan PlayStation Spectral Super Resolution (PSSR) bertenaga AI machine learning, performa rendering GPU 45% lebih cepat, advanced ray tracing 2-3x lebih realistis, penyimpanan raksasa 2TB SSD NVMe, dan Wi-Fi 7 ultra-cepat.',
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [15] Konsol & Handheld
            [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Valve Steam Deck OLED 512GB / 1TB NVMe HDR 90Hz Handheld PC Gaming',
                'slug' => 'valve-steam-deck-oled-512gb-1tb',
                'sku' => 'HND-VALVE-SDECK-OLED',
                'price' => 10999000,
                'discount_price' => 9999000,
                'stock' => 20,
                'rating_avg' => 4.97,
                'reviews_count' => 115,
                'sales_count' => 310,
                'description' => 'Mainkan seluruh koleksi game Steam PC favorit Anda di mana saja. Layar 7.4 inci HDR OLED dengan refresh rate 90Hz dan kecerahan puncak 1000 nits, baterai lebih tahan lama 50Whr (3-12 jam gameplay), Wi-Fi 6E berkecepatan tinggi, dan APU AMD 6nm kustom yang sangat hemat daya.',
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Kapasitas', 'value' => '512GB NVMe SSD OLED', 'price_adjustment' => 0, 'stock' => 12],
                    ['name' => 'Kapasitas', 'value' => '1TB NVMe SSD Premium Anti-Glare OLED', 'price_adjustment' => 1800000, 'stock' => 8],
                ],
            ],

            // [16] Konsol & Handheld
            [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Nintendo Switch OLED Model Mario Red Edition Garansi Resmi Maxsoft',
                'slug' => 'nintendo-switch-oled-mario-red-edition',
                'sku' => 'CNS-NSW-OLED-RED',
                'price' => 4799000,
                'discount_price' => 4299000,
                'stock' => 35,
                'rating_avg' => 4.93,
                'reviews_count' => 98,
                'sales_count' => 350,
                'description' => 'Edisi spesial Nintendo Switch OLED berbalut warna merah ikonik Mario dengan detail siluet Mario di bagian belakang dock. Layar OLED 7 inci warna cerah tajam, wide adjustable stand, port LAN ethernet terintegrasi di dock TV, dan penyimpanan internal 64GB.',
                'images' => [
                    'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [17] Konsol & Handheld
            [
                'shop_idx' => 0,
                'cat_slug' => 'konsol-handheld',
                'name' => 'ASUS ROG Ally X Handheld 24GB LPDDR5X 1TB AMD Ryzen Z1 Extreme 120Hz',
                'slug' => 'asus-rog-ally-x-handheld-24gb-1tb-z1-extreme',
                'sku' => 'HND-ASUS-ALLY-X',
                'price' => 13999000,
                'discount_price' => 12999000,
                'stock' => 18,
                'rating_avg' => 4.96,
                'reviews_count' => 62,
                'sales_count' => 140,
                'description' => 'Upgrade raksasa handheld Windows gaming PC. Ditenagai prosesor AMD Ryzen Z1 Extreme, RAM monster 24GB LPDDR5X-7500 dual channel, baterai berkapasitas 80Wh (2x lipat generasi sebelumnya), SSD M.2 2280 1TB PCIe 4.0, joystick hall effect anti-drift, dan port USB4 Thunderbolt ganda.',
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [18] PC Rakitan
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Rakitan eSports Pro Arena AMD Ryzen 7 7800X3D + RTX 4070 Ti Super + 32GB DDR5',
                'slug' => 'pc-rakitan-esports-pro-arena-ryzen-7-7800x3d-rtx-4070ti',
                'sku' => 'PC-ESPORT-PRO',
                'price' => 38999000,
                'discount_price' => 35999000,
                'stock' => 12,
                'rating_avg' => 4.98,
                'reviews_count' => 38,
                'sales_count' => 88,
                'description' => 'Rig kompetitif esports standar turnamen dunia. AMD Ryzen 7 7800X3D (prosesor gaming terbaik AM5), GPU NVIDIA GeForce RTX 4070 Ti Super 16GB GDDR6X, RAM Corsair 32GB DDR5 6000MHz CL30, SSD NVMe Gen4 2TB 7400MB/s, Motherboard B650E Gaming WiFi, AIO DeepCool 360mm, Casing NZXT H6 Flow Dual Chamber. Gratis perakitan & instalasi game.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [19] PC Rakitan
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Rakitan Budget King Gen Z AMD Ryzen 5 7600 + RTX 4060 8GB + 16GB RAM DDR5',
                'slug' => 'pc-rakitan-budget-king-gen-z-ryzen-5-rtx-4060',
                'sku' => 'PC-BUDGET-KING',
                'price' => 14499000,
                'discount_price' => 13299000,
                'stock' => 25,
                'rating_avg' => 4.91,
                'reviews_count' => 86,
                'sales_count' => 270,
                'description' => 'Paket PC rakitan paling laris untuk gamer Gen Z. Performa mulus 100+ FPS di game Valorant, GTA V, Dota 2, dan game AAA 1080p Ultra dengan DLSS 3. AMD Ryzen 5 7600 6-core 12-thread, GeForce RTX 4060 8GB GDDR6, RAM 16GB DDR5 5600MHz, SSD NVMe 1TB PCIe 4.0, PSU 650W 80+ Bronze, Casing Panoramic Tempered Glass dengan 4 kipas ARGB.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [20] PC Rakitan
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Rakitan Creator Studio AMD Ryzen 9 7950X + RTX 4080 Super + 64GB DDR5 White Edition',
                'slug' => 'pc-rakitan-creator-studio-ryzen-9-7950x-rtx-4080s',
                'sku' => 'PC-CREATOR-WHITE',
                'price' => 54999000,
                'discount_price' => 51499000,
                'stock' => 10,
                'rating_avg' => 4.97,
                'reviews_count' => 29,
                'sales_count' => 55,
                'description' => 'Setup PC serba putih estetik untuk streamer, video editor 4K/8K, dan desainer 3D. AMD Ryzen 9 7950X 16 Core 32 Thread, GeForce RTX 4080 Super 16GB White, RAM Corsair White RGB 64GB DDR5, SSD NVMe Gen4 2TB Samsung 990 Pro, Casing Lian Li O11 Vision White, PSU 1000W Gold White.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [21] PC & Komponen
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'NVIDIA GeForce RTX 4080 Super 16GB GDDR6X Tri-Fan OC Gaming Graphics Card',
                'slug' => 'nvidia-geforce-rtx-4080-super-16gb-gddr6x',
                'sku' => 'GPU-RTX4080S-16',
                'price' => 18999000,
                'discount_price' => 17499000,
                'stock' => 20,
                'rating_avg' => 4.97,
                'reviews_count' => 62,
                'sales_count' => 195,
                'description' => 'Kartu grafis gaming flagship dengan arsitektur NVIDIA Ada Lovelace. Menghadirkan performa ray tracing generasi ke-3 yang spektakuler, teknologi AI DLSS 3.5 Frame Generation, memori berkecepatan tinggi 16GB GDDR6X 256-bit, dan sistem pendingin tiga kipas vapor chamber.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [22] PC & Komponen
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'NVIDIA GeForce RTX 4090 24GB GDDR6X ROG Strix White Edition Flagship GPU',
                'slug' => 'nvidia-geforce-rtx-4090-24gb-rog-strix-white',
                'sku' => 'GPU-RTX4090-WHT',
                'price' => 38999000,
                'discount_price' => 36499000,
                'stock' => 8,
                'rating_avg' => 5.0,
                'reviews_count' => 35,
                'sales_count' => 82,
                'description' => 'Puncak performa grafis dunia dalam balutan warna putih salju eksklusif. 24GB GDDR6X 384-bit, 16.384 CUDA Cores, sistem pendingin 3.5 slot die-cast frame dengan kipas Axial-tech dan vapor chamber raksasa.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [23] PC & Komponen
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'AMD Ryzen 7 7800X3D Processor 8 Core 16 Thread 5.0GHz 104MB 3D V-Cache',
                'slug' => 'amd-ryzen-7-7800x3d-processor-gaming',
                'sku' => 'CPU-R7-7800X3D',
                'price' => 6899000,
                'discount_price' => 6299000,
                'stock' => 40,
                'rating_avg' => 4.99,
                'reviews_count' => 140,
                'sales_count' => 520,
                'description' => 'Prosesor gaming terbaik di dunia untuk platform AM5 socket. Dilengkapi teknologi revolusioner AMD 3D V-Cache berkapasitas raksasa 104MB cache, 8 core dan 16 thread dengan boost clock hingga 5.0GHz.',
                'images' => [
                    'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Paket', 'value' => 'Box Unit Only', 'price_adjustment' => 0, 'stock' => 25],
                    ['name' => 'Paket', 'value' => 'Bundling Liquid Cooler AIO 360mm', 'price_adjustment' => 1450000, 'stock' => 15],
                ],
            ],

            // [24] PC & Komponen
            [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'Intel Core i9 14900KS 24 Core 32 Thread 6.2GHz Turbo Unlocked LGA1700',
                'slug' => 'intel-core-i9-14900ks-24-core-32-thread-lga1700',
                'sku' => 'CPU-INT-14900KS',
                'price' => 10999000,
                'discount_price' => 9999000,
                'stock' => 15,
                'rating_avg' => 4.96,
                'reviews_count' => 41,
                'sales_count' => 95,
                'description' => 'Prosesor desktop tercepat di planet ini dengan kecepatan rekor 6.2 GHz out-of-the-box. 24 core (8 Performance Cores + 16 Efficient Cores) dan 32 thread dengan Intel Thermal Velocity Boost.',
                'images' => [
                    'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [25] PC & Komponen
            [
                'shop_idx' => 0,
                'cat_slug' => 'pc-komponen',
                'name' => 'Motherboard ASUS ROG Maximus Z790 Dark Hero WiFi 7 LGA1700 PCIe 5.0',
                'slug' => 'motherboard-asus-rog-maximus-z790-dark-hero',
                'sku' => 'MB-ROG-Z790-DH',
                'price' => 12999000,
                'discount_price' => 11899000,
                'stock' => 14,
                'rating_avg' => 4.97,
                'reviews_count' => 28,
                'sales_count' => 64,
                'description' => 'Motherboard enthusiast kelas atas dengan 20+1+2 power stages, slot PCIe 5.0 NVMe onboard, konektivitas Wi-Fi 7 ultra-cepat, port ganda Thunderbolt 4 USB-C, dan pencahayaan Polymo Lighting I/O cover yang memukau.',
                'images' => [
                    'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [26] PC & Komponen
            [
                'shop_idx' => 0,
                'cat_slug' => 'pc-komponen',
                'name' => 'Motherboard ASUS ROG Strix B650E-F Gaming WiFi AMD AM5 DDR5 PCIe 5.0',
                'slug' => 'motherboard-asus-rog-strix-b650e-f-gaming-wifi',
                'sku' => 'MB-ROG-B650E-F',
                'price' => 5499000,
                'discount_price' => 4899000,
                'stock' => 30,
                'rating_avg' => 4.94,
                'reviews_count' => 74,
                'sales_count' => 240,
                'description' => 'Pilihan motherboard terbaik untuk build AMD AM5 Ryzen 7000/9000 series. Dilengkapi 12+2 power stages, dukungan PCIe 5.0 x16 grafis & PCIe 5.0 M.2 SSD, WiFi 6E, audio SupremeFX ALC4080, dan BIOS FlashBack praktis.',
                'images' => [
                    'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [27] Monitor Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'Samsung Odyssey OLED G9 49 Inch Dual QHD 240Hz 0.03ms Curved Gaming Monitor',
                'slug' => 'samsung-odyssey-oled-g9-49-inch-dual-qhd-240hz',
                'sku' => 'MON-ODY-G9-49',
                'price' => 24999000,
                'discount_price' => 21999000,
                'stock' => 12,
                'rating_avg' => 4.96,
                'reviews_count' => 45,
                'sales_count' => 88,
                'description' => 'Pengalaman visual gaming imersif tanpa tanding. Monitor ultrawide 32:9 berukuran 49 inci beresolusi Dual QHD (5120 x 1440) dengan panel QD-OLED yang menghasilkan hitam pekat sempurna, response time ultra-instan 0.03ms (GtG), refresh rate 240Hz, dan kelengkungan 1800R ergonomis.',
                'images' => [
                    'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [28] Monitor Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'ASUS ROG Swift PG27AQDM OLED 27 Inch 240Hz 0.03ms QHD G-Sync Esports',
                'slug' => 'asus-rog-swift-pg27aqdm-oled-27-240hz',
                'sku' => 'MON-ROG-PG27',
                'price' => 15799000,
                'discount_price' => 14299000,
                'stock' => 18,
                'rating_avg' => 4.94,
                'reviews_count' => 58,
                'sales_count' => 130,
                'description' => 'Monitor gaming kompetitif tingkat turnamen esports profesional. Layar OLED 27 inci resolusi QHD (2560x1440) dengan refresh rate 240Hz dan response time 0.03ms. Dilengkapi custom heatsink besar dan kontrol voltase pintar untuk mencegah risiko burn-in serta kecerahan puncak 1000 nits.',
                'images' => [
                    'https://images.unsplash.com/photo-1551645120-d70bfe84c826?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [29] Monitor Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'LG UltraGear 27GR95QE OLED 27 Inch 240Hz 0.03ms QHD Gaming Monitor',
                'slug' => 'lg-ultragear-27gr95qe-oled-27-inch-240hz',
                'sku' => 'MON-LG-27OLED',
                'price' => 14499000,
                'discount_price' => 12999000,
                'stock' => 20,
                'rating_avg' => 4.93,
                'reviews_count' => 50,
                'sales_count' => 115,
                'description' => 'Monitor OLED 240Hz dengan rasio kontras 1.500.000:1, gamut warna DCI-P3 98.5%, sertifikasi NVIDIA G-SYNC Compatible dan AMD FreeSync Premium, anti-glare low reflection coating, dan remote kontroler eksklusif.',
                'images' => [
                    'https://images.unsplash.com/photo-1551645120-d70bfe84c826?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [30] Monitor Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'Alienware AW3423DWF QD-OLED 34 Inch Curved WQHD 165Hz 0.1ms HDR TrueBlack',
                'slug' => 'alienware-aw3423dwf-qd-oled-34-curved-wqhd',
                'sku' => 'MON-ALW-34DWF',
                'price' => 18999000,
                'discount_price' => 16999000,
                'stock' => 11,
                'rating_avg' => 4.97,
                'reviews_count' => 38,
                'sales_count' => 65,
                'description' => 'Panel Quantum Dot OLED 34 inci lengkung 1800R dengan resolusi WQHD 3440x1440, refresh rate 165Hz, response time 0.1ms, DisplayHDR TrueBlack 400, dan garansi resmi 3 tahun termasuk perlindungan burn-in.',
                'images' => [
                    'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [31] Monitor Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'Xiaomi Gaming Monitor G27i 27 Inch 165Hz Fast IPS 1ms HDR10 FreeSync',
                'slug' => 'xiaomi-gaming-monitor-g27i-27-inch-165hz-fast-ips',
                'sku' => 'MON-XIA-G27I',
                'price' => 2199000,
                'discount_price' => 1899000,
                'stock' => 60,
                'rating_avg' => 4.89,
                'reviews_count' => 210,
                'sales_count' => 680,
                'description' => 'Monitor gaming Fast IPS paling terjangkau dengan spesifikasi impian gamer kompetitif. 27 inci Full HD 165Hz, response time 1ms GTG, 99% sRGB color gamut dengan kalibrasi warna pabrik Delta E < 2, dan sertifikasi TÜV Low Blue Light.',
                'images' => [
                    'https://images.unsplash.com/photo-1551645120-d70bfe84c826?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [32] Keyboard Mechanical
            [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Keychron Q1 Pro QMK Wireless Custom Mechanical Keyboard Gasket Mount',
                'slug' => 'keychron-q1-pro-qmk-wireless-custom-keyboard',
                'sku' => 'KB-KCR-Q1-PRO',
                'price' => 3299000,
                'discount_price' => 2999000,
                'stock' => 25,
                'rating_avg' => 4.95,
                'reviews_count' => 88,
                'sales_count' => 260,
                'description' => 'Keyboard mekanikal kustom premium berbalut bodi aluminium solid CNC 6063. Desain double-gasket mount untuk feel mengetik empuk dan suara thock berbobot, koneksi nirkabel Bluetooth 5.1 & kabel Type-C, keycaps KSA profile double-shot PBT, dan dukungan pemrograman QMK/VIA penuh.',
                'images' => [
                    'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Pilihan Switch', 'value' => 'K Pro Red (Linear Smooth)', 'price_adjustment' => 0, 'stock' => 15],
                    ['name' => 'Pilihan Switch', 'value' => 'K Pro Brown (Tactile Thocky)', 'price_adjustment' => 0, 'stock' => 10],
                ],
            ],

            // [33] Keyboard Mechanical
            [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'SteelSeries Apex Pro TKL Wireless OmniPoint 2.0 Adjustable Actuation Esports',
                'slug' => 'steelseries-apex-pro-tkl-wireless-omnipoint',
                'sku' => 'KB-STS-APEX-TKL',
                'price' => 4299000,
                'discount_price' => 3899000,
                'stock' => 20,
                'rating_avg' => 4.96,
                'reviews_count' => 65,
                'sales_count' => 190,
                'description' => 'Keyboard gaming tercepat di dunia dengan Switch OmniPoint 2.0 Adjustable HyperMagnetic. Respon 11x lebih cepat, aktuasi variabel dari 0.2mm hingga 3.8mm dengan Rapid Trigger, layar OLED Smart Display terintegrasi, dan koneksi Quantum 2.0 Dual Wireless.',
                'images' => [
                    'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [34] Keyboard Mechanical
            [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Ajazz AK820 Pro 75% Tri-Mode Wireless Gasket Mechanical Keyboard TFT Screen',
                'slug' => 'ajazz-ak820-pro-75-tri-mode-wireless-tft-screen',
                'sku' => 'KB-AJZ-AK820',
                'price' => 849000,
                'discount_price' => 749000,
                'stock' => 60,
                'rating_avg' => 4.90,
                'reviews_count' => 180,
                'sales_count' => 580,
                'description' => 'Keyboard 75% paling viral di komunitas Gen Z. Dilengkapi layar warna TFT 0.85 inci untuk memutar GIF custom, kenop volume metalik, struktur 5 lapis peredam suara gasket mount (sound profile creamy thock), south-facing RGB, dan konektivitas 3 mode (Type-C, 2.4Ghz, Bluetooth 5.1).',
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Pilihan Switch', 'value' => 'Gift Switch Linear Creamy', 'price_adjustment' => 0, 'stock' => 35],
                    ['name' => 'Pilihan Switch', 'value' => 'Flying Fish Switch Deep Thock', 'price_adjustment' => 30000, 'stock' => 25],
                ],
            ],

            // [35] Audio & Headset
            [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'SteelSeries Arctis Nova Pro Wireless Multi-System ANC Hi-Res Spatial Audio',
                'slug' => 'steelseries-arctis-nova-pro-wireless',
                'sku' => 'AUD-STS-NOVA-PRO',
                'price' => 6499000,
                'discount_price' => 5799000,
                'stock' => 20,
                'rating_avg' => 4.97,
                'reviews_count' => 90,
                'sales_count' => 280,
                'description' => 'Headset gaming nirkabel terbaik di kelas audiophile. Active Noise Cancellation (ANC) dengan 4 mikrofon hibrida, Infinity Power System dengan 2 baterai hot-swappable (daya tak terbatas), Base Station OLED ganda port USB untuk switch instan PC dan PS5, serta software Sonar Parametric EQ.',
                'images' => [
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [36] Audio & Headset
            [
                'shop_idx' => 1,
                'cat_slug' => 'audio-headset',
                'name' => 'Razer BlackShark V2 Pro (2024 Edition) Wireless Esports Headset TriForce 50mm',
                'slug' => 'razer-blackshark-v2-pro-2024-edition',
                'sku' => 'AUD-RZR-BSV2P',
                'price' => 3499000,
                'discount_price' => 2999000,
                'stock' => 35,
                'rating_avg' => 4.95,
                'reviews_count' => 140,
                'sales_count' => 460,
                'description' => 'Edisi terbaru headset esports legendaris. Razer HyperClear Super Wideband Mic 32kHz suara vokal studio, Driver TriForce Titanium 50mm, profil audio FPS tuned oleh pemain pro Apex & CS2, isolasi pasif ultra-tebal FlowKnit memory foam, dan baterai hingga 70 jam.',
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna', 'value' => 'Stealth Black Edition', 'price_adjustment' => 0, 'stock' => 20],
                    ['name' => 'Warna', 'value' => 'Mercury White Edition', 'price_adjustment' => 50000, 'stock' => 15],
                ],
            ],

            // [37] Audio & Headset
            [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'HyperX Cloud III Wireless Gaming Headset 120 Jam Battery Life DTS Spatial',
                'slug' => 'hyperx-cloud-iii-wireless-headset-120-jam',
                'sku' => 'AUD-HPX-CLOUD3-WL',
                'price' => 2499000,
                'discount_price' => 2199000,
                'stock' => 45,
                'rating_avg' => 4.94,
                'reviews_count' => 110,
                'sales_count' => 390,
                'description' => 'Kenyamanan legendaris Cloud dengan daya tahan baterai fenomenal hingga 120 jam pemakaian nonstop. Driver miring 53mm yang di-tuning ulang, mikrofon 10mm dengan filter mesh internal & indikator LED mute, serta spatial audio DTS Headphone:X seumur hidup.',
                'images' => [
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [38] Audio & Headset
            [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Moondrop Blessing 3 Hybrid In-Ear Monitor (IEM) Dual Dynamic + 4BA Audiophile',
                'slug' => 'moondrop-blessing-3-hybrid-iem-dual-dynamic-4ba',
                'sku' => 'IEM-MND-BLESS3',
                'price' => 4999000,
                'discount_price' => 4499000,
                'stock' => 18,
                'rating_avg' => 4.98,
                'reviews_count' => 56,
                'sales_count' => 120,
                'description' => 'IEM gaming dan musik kelas referensi tinggi yang dipuja gamer kompetitif untuk pinpoint audio staging langkah kaki musuh. Konfigurasi hybrid 2 Dynamic Driver Horizontally Opposed (H.O.D.D.D.U.S) + 4 Balanced Armature drivers dengan faceplate stainless steel mirror dipoles tangan.',
                'images' => [
                    'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [39] Audio & Headset
            [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Fiio K7 Full Balanced Desktop DAC & Headphone Amplifier Dual AK4493S XMOS',
                'slug' => 'fiio-k7-balanced-desktop-dac-headphone-amplifier',
                'sku' => 'DAC-FIO-K7',
                'price' => 3299000,
                'discount_price' => 2899000,
                'stock' => 22,
                'rating_avg' => 4.96,
                'reviews_count' => 48,
                'sales_count' => 140,
                'description' => 'DAC/Amp desktop sejati dengan sirkuit full balanced ganda AK4493SEQ dan amplifier THX AAA 788+ ganda. Menghasilkan tenaga dorong masif hingga 2000mW untuk mengangkat headphone dan IEM planar dengan distorsi nol dan lampu indikator RGB sampling rate.',
                'images' => [
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [40] Smartphone Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'ASUS ROG Phone 8 Pro 5G Snapdragon 8 Gen 3 16GB/512GB 165Hz AMOLED',
                'slug' => 'asus-rog-phone-8-pro-5g-snapdragon-8-gen-3',
                'sku' => 'HP-ROG-PH8-PRO',
                'price' => 14999000,
                'discount_price' => 13999000,
                'stock' => 25,
                'rating_avg' => 4.95,
                'reviews_count' => 82,
                'sales_count' => 220,
                'description' => 'Monster smartphone gaming masa kini. Ditenagai Snapdragon 8 Gen 3 dengan sistem pendingin GameCool 8 konduksi termal boron nitrida, layar Samsung Flexible AMOLED 6.78 inci 165Hz LTPO 2500 nits, tombol sentuh ultrasonic AirTrigger, dan tahan air IP68.',
                'images' => [
                    'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Varian Memory', 'value' => '16GB RAM / 512GB ROM Phantom Black', 'price_adjustment' => 0, 'stock' => 15],
                    ['name' => 'Varian Memory', 'value' => '24GB RAM / 1TB ROM + AeroActive Cooler X Bundle', 'price_adjustment' => 3500000, 'stock' => 10],
                ],
            ],

            // [41] Smartphone Gaming
            [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'Nubia RedMagic 9 Pro 5G Snapdragon 8 Gen 3 16GB/512GB Under-Display Cam',
                'slug' => 'nubia-redmagic-9-pro-5g-snapdragon-8-gen-3',
                'sku' => 'HP-REDMAGIC-9P',
                'price' => 12999000,
                'discount_price' => 11799000,
                'stock' => 20,
                'rating_avg' => 4.92,
                'reviews_count' => 64,
                'sales_count' => 180,
                'description' => 'Desain kamera belakang rata sempurna tanpa modul tonjolan dan layar 100% fullscreen tanpa poni kamera depan (UDC). Dilengkapi kipas pendingin turbofan fisik 22.000 RPM berlampu RGB, baterai 6500mAh tahan lama, dan shoulder triggers 520Hz super responsif.',
                'images' => [
                    'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [42] Streaming Gear
            [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Elgato Stream Deck MK.2 15 LCD Keys Customizable Live Content Controller',
                'slug' => 'elgato-stream-deck-mk2-15-lcd-keys',
                'sku' => 'STR-ELG-SDMK2',
                'price' => 2499000,
                'discount_price' => 2199000,
                'stock' => 40,
                'rating_avg' => 4.94,
                'reviews_count' => 67,
                'sales_count' => 280,
                'description' => 'Alat wajib bagi streamer, podcaster, dan content creator profesional. Dilengkapi 15 tombol LCD dinamis yang dapat diprogram untuk mengontrol OBS Studio, Twitch, YouTube, Discord, Spotify, dan shortcut software editing hanya dengan satu sentuhan jari.',
                'images' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [43] Streaming Gear
            [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Shure SM7B Cardioid Dynamic Vocal & Podcasting Microphone Studio Grade',
                'slug' => 'shure-sm7b-cardioid-dynamic-microphone',
                'sku' => 'MIC-SHU-SM7B',
                'price' => 6899000,
                'discount_price' => 6199000,
                'stock' => 15,
                'rating_avg' => 4.99,
                'reviews_count' => 52,
                'sales_count' => 140,
                'description' => 'Standar industri siaran podcast dan vokal gaming dunia. Mikrofon dinamis dengan pola penangkapan cardioid presisi yang menolak noise latar belakang, isolasi shock internal udara, dan pelindung elektromagnetik canggih untuk meredam dengung dari monitor komputer.',
                'images' => [
                    'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [44] Streaming Gear
            [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Elgato Wave XLR Microphone Interface & 75dB Ultra Low-Noise Preamp USB-C',
                'slug' => 'elgato-wave-xlr-microphone-interface-preamp',
                'sku' => 'STR-ELG-WVXLR',
                'price' => 2699000,
                'discount_price' => 2399000,
                'stock' => 25,
                'rating_avg' => 4.93,
                'reviews_count' => 45,
                'sales_count' => 160,
                'description' => 'Audio interface XLR ringkas bertenaga gain hingga 75dB tanpa perlu Cloudlifter tambahan. Dilengkapi teknologi anti-clipping eksklusif Clipguard, kapasitor phantom power 48V, tombol mute kapasitif, dan kontrol mixer software Wave Link.',
                'images' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [45] Streaming Gear
            [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Elgato Game Capture 4K X External Capture Card HDMI 2.1 4K144 VRR HDR10',
                'slug' => 'elgato-game-capture-4k-x-external-capture-card',
                'sku' => 'STR-ELG-GC4KX',
                'price' => 4399000,
                'discount_price' => 3999000,
                'stock' => 16,
                'rating_avg' => 4.96,
                'reviews_count' => 32,
                'sales_count' => 90,
                'description' => 'Capture card eksternal generasi terbaru dengan konektivitas HDMI 2.1 dan USB 3.2 Gen 2 (10Gbps). Mendukung passthrough hingga 4K144 VRR atau 1080p240 untuk streaming PS5 Pro, Xbox Series X, dan dual-PC setup tanpa input lag.',
                'images' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [46] Kursi & Setup Meja
            [
                'shop_idx' => 4,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Secretlab TITAN Evo 2024 Gaming Chair Ergonomic 4D Armrest Magnetic Cushion',
                'slug' => 'secretlab-titan-evo-2024-gaming-chair',
                'sku' => 'CHR-SEC-TITAN-EVO',
                'price' => 7999000,
                'discount_price' => 6999000,
                'stock' => 18,
                'rating_avg' => 4.96,
                'reviews_count' => 78,
                'sales_count' => 190,
                'description' => 'Kursi gaming teruji ergonomis terbaik di dunia. Dilengkapi sistem lumbar support adaptif 4 arah L-ADAPT, bantal leher magnetik memory foam pendingin gel, armrest 4D CloudSwap teknologi magnetik, dan reclining hingga 165 derajat.',
                'images' => [
                    'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Bahan Pelapis', 'value' => 'Stealth PRIME 2.0 PU Leather', 'price_adjustment' => 0, 'stock' => 10],
                    ['name' => 'Bahan Pelapis', 'value' => 'SoftWeave Plus Fabric Breathable Black³', 'price_adjustment' => 500000, 'stock' => 8],
                ],
            ],

            // [47] Kursi & Setup Meja
            [
                'shop_idx' => 4,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Ergotec Dual-Motor Smart Electric Standing Desk 160x80cm Memory Preset Cable Tray',
                'slug' => 'ergotec-dual-motor-electric-standing-desk-160',
                'sku' => 'DSK-ERG-STND160',
                'price' => 5499000,
                'discount_price' => 4799000,
                'stock' => 15,
                'rating_avg' => 4.93,
                'reviews_count' => 45,
                'sales_count' => 110,
                'description' => 'Meja kerja dan gaming elektrik dengan motor ganda senyap (<45dB). Ketinggian dapat diatur halus dari 60cm hingga 125cm dengan 4 tombol memori preset ketinggian, kapasitas beban kokoh hingga 125kg, dan tabletop kayu solid tebal 2.5cm.',
                'images' => [
                    'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Daun Meja', 'value' => 'Walnut Wood Solid Top', 'price_adjustment' => 0, 'stock' => 8],
                    ['name' => 'Warna Daun Meja', 'value' => 'Pure Matte White Top', 'price_adjustment' => 0, 'stock' => 7],
                ],
            ],

            // [48] Kursi & Setup Meja
            [
                'shop_idx' => 4,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'BenQ ScreenBar Halo Wireless Controller LED Monitor Lightbar Eye-Care RGB',
                'slug' => 'benq-screenbar-halo-wireless-controller-lightbar',
                'sku' => 'LGT-BNQ-HALO',
                'price' => 2899000,
                'discount_price' => 2599000,
                'stock' => 30,
                'rating_avg' => 4.95,
                'reviews_count' => 60,
                'sales_count' => 175,
                'description' => 'Lampu monitor gaming nirkabel terbaik dunia. Dilengkapi remote kontrol putar presisi nirkabel, sensor auto-dimming pintar, lampu ambient backlight belakang untuk mereduksi kontras gelap di malam hari, dan optik asimetris tanpa pantulan silau pada layar.',
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [49] Storage RAM & SSD
            [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'Corsair Vengeance RGB DDR5 32GB (2x16GB) 6000MHz CL30 EXPO/XMP 3.0',
                'slug' => 'corsair-vengeance-rgb-ddr5-32gb-6000mhz',
                'sku' => 'RAM-COR-DDR5-32',
                'price' => 2250000,
                'discount_price' => 1950000,
                'stock' => 60,
                'rating_avg' => 4.93,
                'reviews_count' => 77,
                'sales_count' => 310,
                'description' => 'Modul memori RAM DDR5 performa tinggi dengan pencahayaan RGB dinamis sepuluh zona terpisah. Profil timing ketat CL30-36-36-76 pada 6000MHz, dioptimalkan sempurna untuk platform AMD EXPO dan Intel XMP 3.0 dengan heatspreader aluminium padat pendingin maksimal.',
                'images' => [
                    'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Heatspreader', 'value' => 'Stealth Black', 'price_adjustment' => 0, 'stock' => 35],
                    ['name' => 'Warna Heatspreader', 'value' => 'Arctic White', 'price_adjustment' => 50000, 'stock' => 25],
                ],
            ],

            // [50] Storage RAM & SSD
            [
                'shop_idx' => 2,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'Samsung 990 PRO 2TB PCIe Gen4 x4 NVMe M.2 SSD Heatsink (7450 MB/s)',
                'slug' => 'samsung-990-pro-2tb-pcie-gen4-nvme-ssd-heatsink',
                'sku' => 'SSD-SAM-990P-2T',
                'price' => 3299000,
                'discount_price' => 2899000,
                'stock' => 45,
                'rating_avg' => 4.99,
                'reviews_count' => 118,
                'sales_count' => 420,
                'description' => 'Raja kecepatan SSD PCIe 4.0 dunia. Kecepatan baca sekuensial hingga 7450 MB/s dan tulis 6900 MB/s. Dilengkapi heatsink ramping bersertifikasi PlayStation 5 dan efisiensi daya 50% lebih baik per watt dibanding 980 Pro.',
                'images' => [
                    'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [51] Storage RAM & SSD
            [
                'shop_idx' => 2,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'Corsair MP600 PRO LPX 2TB PCIe Gen4 x4 NVMe M.2 SSD Heatsink PS5 Ready',
                'slug' => 'corsair-mp600-pro-lpx-2tb-pcie-gen4-nvme-ssd',
                'sku' => 'SSD-COR-MP600-2T',
                'price' => 2899000,
                'discount_price' => 2499000,
                'stock' => 50,
                'rating_avg' => 4.95,
                'reviews_count' => 64,
                'sales_count' => 270,
                'description' => 'Penyimpanan SSD super kencang PCIe Gen4 x4 NVMe 1.4 dengan kecepatan baca sekuensial hingga 7.100 MB/s dan tulis 6.800 MB/s. Dilengkapi heatsink aluminium profil rendah yang dirancang khusus pas untuk slot ekspansi PlayStation 5 dan motherboard PC gaming modern.',
                'images' => [
                    'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Kapasitas', 'value' => '1TB Heatsink Edition', 'price_adjustment' => 0, 'stock' => 25],
                    ['name' => 'Kapasitas', 'value' => '2TB Heatsink Edition', 'price_adjustment' => 1100000, 'stock' => 25],
                ],
            ],

            // [52] Cooling & Power Supply
            [
                'shop_idx' => 2,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'NZXT Kraken Elite 360 RGB AIO Liquid Cooler 2.36 Inch Wide-Angle LCD Display',
                'slug' => 'nzxt-kraken-elite-360-rgb-aio-liquid-cooler',
                'sku' => 'COL-NZXT-KRK360',
                'price' => 4899000,
                'discount_price' => 4399000,
                'stock' => 20,
                'rating_avg' => 4.97,
                'reviews_count' => 55,
                'sales_count' => 150,
                'description' => 'Sistem pendingin cair AIO 360mm tercanggih dengan layar LCD 2.36 inci beresolusi tajam 640x640 60Hz. Dapat menampilkan GIF bergerak favorit, suhu sistem CPU/GPU real-time, pompa Asetek generasi ke-7 yang senyap, dan 3 kipas F120 RGB Core.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Radiator', 'value' => 'Matte Black Edition', 'price_adjustment' => 0, 'stock' => 12],
                    ['name' => 'Warna Radiator', 'value' => 'Matte White Edition', 'price_adjustment' => 100000, 'stock' => 8],
                ],
            ],

            // [53] Cooling & Power Supply
            [
                'shop_idx' => 2,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'Seasonic Prime TX-1000 1000W 80+ Titanium Full Modular ATX 3.0 PCIe 5.0',
                'slug' => 'seasonic-prime-tx-1000-1000w-80-plus-titanium',
                'sku' => 'PSU-SEA-TX1000',
                'price' => 4999000,
                'discount_price' => 4499000,
                'stock' => 18,
                'rating_avg' => 4.99,
                'reviews_count' => 38,
                'sales_count' => 95,
                'description' => 'Catu daya (Power Supply) terbaik di bumi dengan efisiensi rekor 80 PLUS Titanium 94%. Dilengkapi konektor native 12VHPWR 16-pin PCIe 5.0 untuk kartu grafis RTX 4080/4090, regulasi beban mikro (MTLR 0.5%), kabel modular hitam jalinan, dan garansi resmi 12 tahun.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [54] Racing Sim & VR
            [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Logitech G923 TRUEFORCE Sim Racing Wheel & Pedals + Driving Force Shifter',
                'slug' => 'logitech-g923-trueforce-sim-racing-wheel-pedals',
                'sku' => 'SIM-LOG-G923-SET',
                'price' => 6499000,
                'discount_price' => 5799000,
                'stock' => 18,
                'rating_avg' => 4.94,
                'reviews_count' => 58,
                'sales_count' => 160,
                'description' => 'Paket setir balap simulator lengkap dengan teknologi umpan balik gaya TRUEFORCE berkecepatan tinggi 4000Hz. Setir berbalut kulit jahit tangan premium, pedal rem progresif pegas non-linear, kontrol peluncuran kopling ganda, dan tuas persneling manual Driving Force 6-percepatan.',
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Kompatibilitas Platform', 'value' => 'PlayStation 5 / PS4 & PC', 'price_adjustment' => 0, 'stock' => 10],
                    ['name' => 'Kompatibilitas Platform', 'value' => 'Xbox Series X/S & PC', 'price_adjustment' => 0, 'stock' => 8],
                ],
            ],

            // [55] Racing Sim & VR
            [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Meta Quest 3 512GB Breakthrough Mixed Reality Next-Gen VR Headset',
                'slug' => 'meta-quest-3-512gb-mixed-reality-vr-headset',
                'sku' => 'VR-META-QUEST3-512',
                'price' => 10499000,
                'discount_price' => 9499000,
                'stock' => 15,
                'rating_avg' => 4.96,
                'reviews_count' => 47,
                'sales_count' => 125,
                'description' => 'Headset realitas campuran (Mixed Reality) tercanggih. Ditenagai prosesor Snapdragon XR2 Gen 2 dengan performa grafis 2x lipat, lensa optik Pancake 4K+ Infinite Display yang 40% lebih ramping, kamera Passthrough warna penuh beresolusi tinggi, dan pengontrol Touch Plus haptic TruTouch.',
                'images' => [
                    'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [56] Aksesoris & Modding
            [
                'shop_idx' => 2,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Lian Li O11 Dynamic EVO RGB Dual-Chamber Tempered Glass Gaming PC Case',
                'slug' => 'lian-li-o11-dynamic-evo-rgb-dual-chamber',
                'sku' => 'CSE-LL-O11D-RGB',
                'price' => 2650000,
                'discount_price' => 2350000,
                'stock' => 25,
                'rating_avg' => 4.92,
                'reviews_count' => 48,
                'sales_count' => 160,
                'description' => 'Casing PC gaming show-case legendaris dengan desain dual-chamber. Panel kaca tempered ganda tanpa pilar sudut untuk pandangan tanpa halangan ke komponen interior PC Anda. Strip RGB diffuser ganda atas dan bawah dengan kontrol mode bawaan, serta dukungan radiator water cooling hingga 3x 360mm.',
                'images' => [
                    'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    ['name' => 'Warna Casing', 'value' => 'Matte Black', 'price_adjustment' => 0, 'stock' => 15],
                    ['name' => 'Warna Casing', 'value' => 'Pure Snow White', 'price_adjustment' => 100000, 'stock' => 10],
                ],
            ],

            // [57] Aksesoris & Modding
            [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Artisan Hayate Otsu FX Soft XL Esports Gaming Mousepad Made in Japan',
                'slug' => 'artisan-hayate-otsu-fx-soft-xl-mousepad',
                'sku' => 'PAD-ART-HAYATE-XL',
                'price' => 999000,
                'discount_price' => 879000,
                'stock' => 35,
                'rating_avg' => 4.98,
                'reviews_count' => 74,
                'sales_count' => 260,
                'description' => 'Mousepad gaming buatan tangan terbaik di dunia dari Jepang. Menggunakan tenunan khusus bertekstur mikro yang memberikan kecepatan luncur halus (glide) dipadu daya henti (stopping power) luar biasa presisi, base karet PORON anti-geser super lengket, dan jahitan tepi ultra-rata.',
                'images' => [
                    'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],

            // [58] Aksesoris & Modding
            [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Gateron Magnetic Jade Switches Pack (70 Pcs) for Rapid Trigger Keyboard',
                'slug' => 'gateron-magnetic-jade-switches-pack-70-pcs',
                'sku' => 'SWT-GAT-JADE-70',
                'price' => 849000,
                'discount_price' => 749000,
                'stock' => 40,
                'rating_avg' => 4.95,
                'reviews_count' => 62,
                'sales_count' => 220,
                'description' => 'Switch magnetik Hall Effect paling dicari untuk modding keyboard Wooting 60HE, Keychron HE, dan keyboard magnetik analog lainnya. Desain kotak bawah tertutup menghasilkan suara ketikan clack akustik padat tanpa wobbling, magnet berkekuatan tinggi untuk akurasi Rapid Trigger 0.02mm.',
                'images' => [
                    'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [],
            ],
        ];

        $additionalProductsData = [
            0 => [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'MSI Titan 18 HX A14V Intel Core i9-14900HX 128GB RAM 4TB SSD RTX 4090 18" 4K Mini-LED 120Hz',
                'slug' => 'msi-titan-18-hx-a14v-i9-14900hx-rtx-4090',
                'sku' => 'MSI-TITAN-18',
                'price' => 89999000,
                'discount_price' => 84999000,
                'stock' => 5,
                'rating_avg' => 5.0,
                'reviews_count' => 19,
                'sales_count' => 42,
                'description' => 'Monster laptop gaming performa puncak dunia. Ditenagai prosesor Intel Core i9-14900HX 24-core, GPU NVIDIA GeForce RTX 4090 16GB GDDR6 175W Full Power dengan MSI OverBoost Ultra (total 270W), RAM 128GB DDR5 quad-channel, storage 4TB PCIe Gen5 NVMe, serta layar 18-inch 4K UHD+ 120Hz Mini-LED 100% DCI-P3 dengan 1000 nits peak brightness.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                    1 => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    0 => [
                        'name' => 'Kapasitas',
                        'value' => '128GB RAM / 4TB SSD (Flagship)',
                        'price_adjustment' => 0,
                        'stock' => 5,
                    ],
                ],
            ],
            1 => [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'Alienware m16 R2 Gaming Laptop Intel Core Ultra 7 155H 16GB 1TB SSD RTX 4070 240Hz QHD+',
                'slug' => 'alienware-m16-r2-ultra-7-rtx-4070',
                'sku' => 'ALW-M16-R2',
                'price' => 34999000,
                'discount_price' => 32499000,
                'stock' => 12,
                'rating_avg' => 4.93,
                'reviews_count' => 48,
                'sales_count' => 95,
                'description' => 'Desain Stealth Mode baru yang 15% lebih ringkas dengan pendingin Cryo-Tech canggih. Mengusung Intel Core Ultra 7 155H dengan NPU akselerasi AI, NVIDIA GeForce RTX 4070 8GB GDDR6 140W, layar 16-inch QHD+ 240Hz 100% sRGB dengan ComfortView Plus dan audio Dolby Atmos.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            2 => [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'HP OMEN Transcend 14 OLED Intel Core Ultra 9 185H 32GB 1TB RTX 4070 120Hz 2.8K Ultra Thin',
                'slug' => 'hp-omen-transcend-14-oled-ultra-9',
                'sku' => 'HP-OMEN-14T',
                'price' => 36499000,
                'discount_price' => 33999000,
                'stock' => 10,
                'rating_avg' => 4.96,
                'reviews_count' => 32,
                'sales_count' => 78,
                'description' => 'Laptop gaming 14 inci paling tipis dan ringan di dunia dengan bobot hanya 1.63kg. Dilengkapi layar 2.8K 120Hz OLED 0.2ms bersertifikasi IMAX Enhanced, keyboard lattice-less RGB, prosesor Intel Core Ultra 9 185H, dan NVIDIA GeForce RTX 4070.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            3 => [
                'shop_idx' => 0,
                'cat_slug' => 'laptop-gaming',
                'name' => 'Gigabyte AORUS 17X Intel Core i9-13980HX 32GB 2TB SSD RTX 4090 240Hz QHD WINDFORCE Infinity',
                'slug' => 'gigabyte-aorus-17x-i9-13980hx-rtx-4090',
                'sku' => 'AOR-17X-4090',
                'price' => 62999000,
                'discount_price' => 58999000,
                'stock' => 8,
                'rating_avg' => 4.97,
                'reviews_count' => 25,
                'sales_count' => 60,
                'description' => 'Laptop gaming desktop replacement dengan sasis aluminium CNC unibody tipis 21.8mm. Pendingin WINDFORCE Infinity vapor chamber 4 kipas, Intel Core i9-13980HX 24-core, GPU NVIDIA GeForce RTX 4090 16GB GDDR6 175W, dan layar 17.3-inch QHD 240Hz 100% DCI-P3.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            4 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Gaming Budget 10 Juta AMD Ryzen 5 5600 16GB DDR4 512GB NVMe GeForce RTX 3060 12GB',
                'slug' => 'pc-gaming-budget-10-juta-ryzen-5-rtx-3060',
                'sku' => 'PC-BUDGET-10M',
                'price' => 10999000,
                'discount_price' => 9999000,
                'stock' => 25,
                'rating_avg' => 4.9,
                'reviews_count' => 180,
                'sales_count' => 530,
                'description' => 'Paket PC Rakitan Best Value untuk gaming 1080p kompetitif rata kanan. AMD Ryzen 5 5600 6-Core 12-Thread, GeForce RTX 3060 12GB GDDR6 VRAM lega, RAM 16GB 3200MHz Dual Channel, SSD NVMe 512GB ultra-fast, PSU 550W 80+ Bronze, dan casing tempered glass mesh airflow dengan 3 fan ARGB.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                    0 => [
                        'name' => 'Varian Storage',
                        'value' => '512GB NVMe SSD',
                        'price_adjustment' => 0,
                        'stock' => 15,
                    ],
                    1 => [
                        'name' => 'Varian Storage',
                        'value' => '1TB NVMe SSD (+ Upgrade)',
                        'price_adjustment' => 600000,
                        'stock' => 10,
                    ],
                ],
            ],
            5 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Gaming White Aesthetic Panoramic Lian Li O11 Vision Intel Core i7 14700KF 32GB DDR5 RTX 4070 Ti Super',
                'slug' => 'pc-gaming-white-aesthetic-vision-rtx-4070-ti',
                'sku' => 'PC-WHITE-VISION',
                'price' => 38999000,
                'discount_price' => 36499000,
                'stock' => 8,
                'rating_avg' => 4.99,
                'reviews_count' => 42,
                'sales_count' => 88,
                'description' => 'Kombinasi estetika akuarium kaca 3 sisi tanpa pilar dan performa buas gaming 1440p & 4K. Casing Lian Li O11 Vision White, prosesor Intel Core i7 14700KF 20-core, GPU GeForce RTX 4070 Ti Super 16GB White Edition, Liquid AIO LCD 360 White, 32GB DDR5 6000MHz RGB White, dan cable management custom sleeve putih rapi.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            6 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Gaming Esports 240FPS Intel Core i5 13400F 32GB DDR5 1TB NVMe RTX 4060 8GB Ready Valorant & CS2',
                'slug' => 'pc-gaming-esports-240fps-i5-rtx-4060',
                'sku' => 'PC-ESPORT-240FPS',
                'price' => 14899000,
                'discount_price' => 13499000,
                'stock' => 20,
                'rating_avg' => 4.92,
                'reviews_count' => 95,
                'sales_count' => 310,
                'description' => 'Dirakit spesifik untuk push rank Valorant, CS2, Apex Legends, dan Dota 2 pada monitor 240Hz stabil. Intel Core i5 13400F 10-core, GeForce RTX 4060 8GB GDDR6 DLSS 3, RAM 32GB DDR5 5600MHz, NVMe Gen4 1TB, pendingin tower dual fan ARGB, dan garansi perakitan seumur hidup.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            7 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-rakitan',
                'name' => 'PC Monster Workstation Creator AMD Ryzen Threadripper 7960X 24-Core 128GB DDR5 ECC Dual RTX 4090 48GB',
                'slug' => 'pc-monster-workstation-threadripper-dual-rtx-4090',
                'sku' => 'PC-WS-THREADRIPPER',
                'price' => 189999000,
                'discount_price' => 179999000,
                'stock' => 3,
                'rating_avg' => 5.0,
                'reviews_count' => 11,
                'sales_count' => 16,
                'description' => 'Workstation komputasi ilmiah, 3D Rendering Unreal Engine 5, dan AI Training Machine. AMD Ryzen Threadripper 7960X 24-Core 48-Thread sTR5, Dual NVIDIA GeForce RTX 4090 24GB NVLink Ready (total 48GB VRAM), RAM 128GB DDR5 ECC Registered, 8TB NVMe Gen5 RAID, dan PSU Corsair AX1600i Titanium 1600W.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            8 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'Intel Core i7-14700KF 20-Core up to 5.6GHz LGA1700 Unlocked Gaming Processor',
                'slug' => 'intel-core-i7-14700kf-gaming-processor',
                'sku' => 'CPU-INT-14700KF',
                'price' => 6899000,
                'discount_price' => 6499000,
                'stock' => 30,
                'rating_avg' => 4.95,
                'reviews_count' => 110,
                'sales_count' => 420,
                'description' => 'Prosesor gaming dan multitasking favorit para antusias PC. Memiliki total 20-core (8 Performance-cores + 12 Efficient-cores) dan 28-threads, turbo boost frekuensi hingga 5.6GHz, 33MB Intel Smart Cache, kompatibel socket LGA1700 dengan motherboard seri Z790/B760.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            9 => [
                'shop_idx' => 0,
                'cat_slug' => 'pc-komponen',
                'name' => 'ASUS TUF Gaming GeForce RTX 4070 Ti Super 16GB GDDR6X OC Edition',
                'slug' => 'asus-tuf-gaming-rtx-4070-ti-super-16gb',
                'sku' => 'VGA-ASUS-4070TIS',
                'price' => 16499000,
                'discount_price' => 15299000,
                'stock' => 18,
                'rating_avg' => 4.97,
                'reviews_count' => 64,
                'sales_count' => 210,
                'description' => 'Kartu grafis GPU 1440p/4K monster dengan durabilitas tingkat militer TUF. Arsitektur Ada Lovelace, 16GB GDDR6X 256-bit bus, dual ball bearing fans Axial-tech, backplate ventilasi kokoh, dan switch Dual BIOS untuk mode Quiet atau Performance.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            10 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'MSI GeForce RTX 4060 Ti Gaming X 8GB GDDR6 Twin Frozr 9',
                'slug' => 'msi-geforce-rtx-4060-ti-gaming-x-8gb',
                'sku' => 'VGA-MSI-4060TI-GX',
                'price' => 7499000,
                'discount_price' => 6899000,
                'stock' => 35,
                'rating_avg' => 4.91,
                'reviews_count' => 88,
                'sales_count' => 340,
                'description' => 'Kartu grafis gaming ringkas bertenaga tinggi dengan sistem pendingin legendaris TWIN FROZR 9 dan TORX FAN 5.0. Mendukung teknologi NVIDIA DLSS 3 Frame Generation, AV1 hardware encoding, dan efisiensi daya luar biasa hemat hanya 160W.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            11 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'AMD Radeon RX 7900 XTX 24GB GDDR6 RDNA 3 Flagship Graphics Card',
                'slug' => 'amd-radeon-rx-7900-xtx-24gb-rdna3',
                'sku' => 'VGA-AMD-7900XTX',
                'price' => 17999000,
                'discount_price' => 16499000,
                'stock' => 12,
                'rating_avg' => 4.93,
                'reviews_count' => 52,
                'sales_count' => 135,
                'description' => 'GPU flagship terbaik dari AMD dengan arsitektur chiplet RDNA 3. VRAM raksasa 24GB GDDR6 384-bit bus, 96 Compute Units dengan Ray Accelerators generasi ke-2, output DisplayPort 2.1 untuk 8K 165Hz, dan performa raster murni tanpa kompromi.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            12 => [
                'shop_idx' => 2,
                'cat_slug' => 'pc-komponen',
                'name' => 'Motherboard MSI MAG B650 Tomahawk WiFi AM5 ATX Gaming Motherboard DDR5 PCIe 4.0',
                'slug' => 'motherboard-msi-mag-b650-tomahawk-wifi',
                'sku' => 'MB-MSI-B650-TOMAHAWK',
                'price' => 3899000,
                'discount_price' => 3599000,
                'stock' => 28,
                'rating_avg' => 4.94,
                'reviews_count' => 76,
                'sales_count' => 290,
                'description' => 'Motherboard AM5 serba bisa untuk Ryzen 7000, 8000, dan 9000 series. Sistem daya 14+2+1 Duet Rail Power System 80A Smart Power Stage, heatsink VRM masif berlapisan termal 7W/mK, 3x M.2 slot dengan Shield Frozr, Wi-Fi 6E, dan Realtek 2.5G LAN.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            13 => [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'G.Skill Trident Z5 RGB DDR5 32GB (2x16GB) 6400MHz CL32 Intel XMP 3.0 Black',
                'slug' => 'gskill-trident-z5-rgb-ddr5-32gb-6400mhz',
                'sku' => 'RAM-GSK-6400-32',
                'price' => 2499000,
                'discount_price' => 2249000,
                'stock' => 40,
                'rating_avg' => 4.97,
                'reviews_count' => 84,
                'sales_count' => 310,
                'description' => 'RAM DDR5 flagship kecepatan tinggi 6400 MT/s dengan latensi rendah CL32-39-39-102. Menggunakan chip SK Hynix A-die pilihan terbaik untuk overclocking, aluminium heatspreader dual-texture hitam elegan, dan lightbar RGB mulus yang tersinkronisasi software motherboard.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            14 => [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'TeamGroup T-Force Delta RGB DDR5 32GB (2x16GB) 6000MHz CL30 White Edition',
                'slug' => 'teamgroup-tforce-delta-rgb-ddr5-32gb-white',
                'sku' => 'RAM-TF-DELTA-WHT',
                'price' => 2199000,
                'discount_price' => 1999000,
                'stock' => 45,
                'rating_avg' => 4.95,
                'reviews_count' => 96,
                'sales_count' => 380,
                'description' => 'RAM favorit rakitan PC bertema White Aesthetic. Desain geometris stealth fighter dengan pencahayaan ultra-wide 120 derajat RGB, timing agresif CL30-36-36-76 1.35V bersertifikasi AMD EXPO dan Intel XMP 3.0.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            15 => [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'WD Black SN850X 2TB NVMe M.2 PCIe Gen4 Internal SSD with Heatsink 7300MB/s',
                'slug' => 'wd-black-sn850x-2tb-nvme-pcie-gen4-heatsink',
                'sku' => 'SSD-WDB-SN850X-2TB',
                'price' => 2999000,
                'discount_price' => 2699000,
                'stock' => 35,
                'rating_avg' => 4.98,
                'reviews_count' => 140,
                'sales_count' => 520,
                'description' => 'SSD gaming papan atas dengan kecepatan baca hingga 7.300 MB/s dan tulis 6.600 MB/s. Dilengkapi heatsink aluminium terintegrasi dengan pencahayaan RGB yang kompatibel untuk PC Desktop maupun ekspansi penyimpanan Sony PlayStation 5.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            16 => [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'Crucial T700 2TB PCIe Gen5 NVMe M.2 SSD 12400MB/s with Premium Heatsink',
                'slug' => 'crucial-t700-2tb-pcie-gen5-nvme-heatsink',
                'sku' => 'SSD-CRU-T700-2TB',
                'price' => 5499000,
                'discount_price' => 4999000,
                'stock' => 15,
                'rating_avg' => 4.96,
                'reviews_count' => 38,
                'sales_count' => 90,
                'description' => 'SSD PCIe Gen5 generasi mutakhir dengan kecepatan baca tembus 12.400 MB/s dan tulis 11.800 MB/s. Memangkas waktu loading game AAA dan rendering video 8K secara instan dengan dukungan Microsoft DirectStorage.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            17 => [
                'shop_idx' => 3,
                'cat_slug' => 'ram-ssd-storage',
                'name' => 'Seagate FireCuda 530 4TB NVMe M.2 PCIe Gen4 High-End SSD with Heatsink',
                'slug' => 'seagate-firecuda-530-4tb-nvme-pcie-gen4',
                'sku' => 'SSD-SEA-FC530-4TB',
                'price' => 7499000,
                'discount_price' => 6799000,
                'stock' => 10,
                'rating_avg' => 4.99,
                'reviews_count' => 24,
                'sales_count' => 65,
                'description' => 'Kapasitas raksasa 4TB dengan daya tahan endurance tertinggi di kelasnya hingga 5100 TBW. Menggunakan heatsink kolaborasi EKWB berbahan aluminium anodized untuk suhu operasional stabil saat beban baca tulis ekstrem.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            18 => [
                'shop_idx' => 3,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'Lian Li Galahad II LCD 360 Trinity SL-INF ARGB AIO Liquid Cooler 360mm White',
                'slug' => 'lian-li-galahad-ii-lcd-360-aio-cooler',
                'sku' => 'CLR-LL-GA2-LCD',
                'price' => 4299000,
                'discount_price' => 3899000,
                'stock' => 18,
                'rating_avg' => 4.97,
                'reviews_count' => 58,
                'sales_count' => 145,
                'description' => 'Watercooling AIO tercantik dengan layar 2.88-inch IPS LCD 480x480 pada pompa untuk menampilkan animasi GIF kustom, video MP4, atau suhu CPU/GPU real-time. Dilengkapi 3 kipas daisy-chain UNI FAN SL-Infinity dengan efek kaca cermin infinity mirror spektakuler.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            19 => [
                'shop_idx' => 2,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'DeepCool LT720 360mm High-Performance Liquid Cooler Multidimensional Block',
                'slug' => 'deepcool-lt720-360mm-liquid-cooler-argb',
                'sku' => 'CLR-DC-LT720-360',
                'price' => 1999000,
                'discount_price' => 1799000,
                'stock' => 30,
                'rating_avg' => 4.93,
                'reviews_count' => 92,
                'sales_count' => 310,
                'description' => 'AIO cooler 360mm dengan rasio performa dan harga terbaik. Desain pompa kubus infinity mirror geometris multidimensi, pompa generasi ke-4 berkecepatan 3100 RPM, dan 3 kipas FK120 FDB bertekanan statis tinggi yang hening.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            20 => [
                'shop_idx' => 3,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'Noctua NH-D15 chromax.black Dual-Tower 140mm Premium CPU Cooler',
                'slug' => 'noctua-nh-d15-chromax-black-dual-tower',
                'sku' => 'CLR-NOC-D15-BLK',
                'price' => 1949000,
                'discount_price' => 1799000,
                'stock' => 25,
                'rating_avg' => 4.99,
                'reviews_count' => 135,
                'sales_count' => 450,
                'description' => 'Raja air cooler prosesor dunia dalam balutan warna all-black stealth. Desain dual-tower dengan 6 heatpipes tembaga, 2 kipas legendaris NF-A15 HS-PWM 140mm, pasta termal NT-H1, dan keandalan operasional tanpa risiko kebocoran seumur hidup.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            21 => [
                'shop_idx' => 3,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'Corsair RM1000x Shift Fully Modular 1000W 80+ Gold ATX 3.0 Side Interface PSU',
                'slug' => 'corsair-rm1000x-shift-modular-1000w-gold',
                'sku' => 'PSU-COR-RM1000X-SHF',
                'price' => 3299000,
                'discount_price' => 2999000,
                'stock' => 22,
                'rating_avg' => 4.98,
                'reviews_count' => 74,
                'sales_count' => 240,
                'description' => 'Inovasi revolusioner letak soket kabel di samping bodi PSU untuk memudahkan instalasi dan cable management. Bersertifikasi ATX 3.0 dan PCIe 5.0 dengan kabel bawaan 12VHPWR 600W untuk kartu grafis RTX 4080/4090.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            22 => [
                'shop_idx' => 3,
                'cat_slug' => 'cooling-power-supply',
                'name' => 'be quiet! Dark Power Pro 13 1300W 80+ Titanium ATX 3.0 PCIe 5.0 Fully Modular PSU',
                'slug' => 'bequiet-dark-power-pro-13-1300w-titanium',
                'sku' => 'PSU-BQ-DPP13-1300',
                'price' => 6799000,
                'discount_price' => 6199000,
                'stock' => 10,
                'rating_avg' => 5.0,
                'reviews_count' => 28,
                'sales_count' => 55,
                'description' => 'Power supply kelas antusias tertinggi dengan efisiensi Titanium hingga 94.4%. Sasis full aluminium mewah, teknologi full digital control, kipas frameless Silent Wings tanpa suara motorik, dan switch Overclocking Key untuk beralih mode multi-rail ke single-rail.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            23 => [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Ducky One 3 RGB TKL Hot-Swap Mechanical Keyboard Cherry MX Red QUACK Mechanics',
                'slug' => 'ducky-one-3-rgb-tkl-cherry-mx-quack',
                'sku' => 'KB-DUCKY-O3-TKL',
                'price' => 1849000,
                'discount_price' => 1649000,
                'stock' => 30,
                'rating_avg' => 4.93,
                'reviews_count' => 84,
                'sales_count' => 260,
                'description' => 'Filosofi desain QUACK Mechanics menghadirkan pengalaman mengetik solid tanpa getaran berlebih. Keycaps Double-shot PBT True Colors, soket Kailh Hot-Swap 5-pin, dual-layer sound dampener silicone pad, dan konektivitas USB Type-C detachable.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            24 => [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'NuPhy Halo75 Wireless Mechanical Keyboard 75% Tri-Mode Night Breeze Switch',
                'slug' => 'nuphy-halo75-wireless-mechanical-keyboard',
                'sku' => 'KB-NUPHY-HALO75',
                'price' => 2399000,
                'discount_price' => 2199000,
                'stock' => 25,
                'rating_avg' => 4.96,
                'reviews_count' => 62,
                'sales_count' => 190,
                'description' => 'Keyboard mekanikal kustom dengan Halo band ring light 360 derajat yang memukau. Bodi aluminium anodized solid, baterai 4000mAh tahan ratusan jam, switch linear halus factory lubed Night Breeze, dan keycaps PBT ergonomis KOP profile.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            25 => [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Rainy 75 CNC Aluminium Tri-Mode Wireless Mechanical Keyboard HMX Violet Switch',
                'slug' => 'rainy-75-cnc-aluminium-wireless-mechanical',
                'sku' => 'KB-RAINY-75',
                'price' => 2149000,
                'discount_price' => 1899000,
                'stock' => 35,
                'rating_avg' => 4.98,
                'reviews_count' => 120,
                'sales_count' => 410,
                'description' => 'Sensasi keyboard custom paling viral di komunitas mech-keeb. Bodi full aluminium CNC anodized berbobot 1.8kg, struktur PCB gasket mount dengan 5 lapis peredam busa IXPE/PORON/PET, dan switch HMX Violet yang renyah dan clack maksimal.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            26 => [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'MelGeek Mojo68 Transparent Wireless Gasket Mount Mechanical Keyboard Plastic Switch',
                'slug' => 'melgeek-mojo68-transparent-wireless-keyboard',
                'sku' => 'KB-MELGEEK-MOJO68',
                'price' => 3299000,
                'discount_price' => 2899000,
                'stock' => 15,
                'rating_avg' => 4.94,
                'reviews_count' => 45,
                'sales_count' => 120,
                'description' => 'Keyboard desainer transparan bergaya retro-futuristik cyberpunk. Tata letak 68-key ringkas dengan gasket mount internal lembut, koneksi wireless 2.4GHz + Bluetooth 5.2 + kabel Type-C, dan profil keycaps MelGeek MDA yang nyaman di jari.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            27 => [
                'shop_idx' => 3,
                'cat_slug' => 'keyboard-mechanical',
                'name' => 'Epomaker RT100 Retro Mechanical Keyboard with Mini Smart TV Display Hot-Swap',
                'slug' => 'epomaker-rt100-retro-tv-display-keyboard',
                'sku' => 'KB-EPO-RT100',
                'price' => 1799000,
                'discount_price' => 1599000,
                'stock' => 40,
                'rating_avg' => 4.91,
                'reviews_count' => 88,
                'sales_count' => 280,
                'description' => 'Keyboard unik bernuansa retro 97-key dengan layar mini TV lepas-pasang yang menampilkan jam, kalender, baterai, suhu CPU, dan custom GIF animasi. Dilengkapi knob kontrol media dan struktur gasket dampening empuk.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            28 => [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Lamzu Maya 4K Wireless Ultralight Gaming Mouse 45g Nordic MCU PAW3395 Black',
                'slug' => 'lamzu-maya-4k-wireless-ultralight-mouse',
                'sku' => 'MS-LAMZU-MAYA-4K',
                'price' => 1649000,
                'discount_price' => 1499000,
                'stock' => 30,
                'rating_avg' => 4.97,
                'reviews_count' => 68,
                'sales_count' => 240,
                'description' => 'Mouse ultralight berbobot bulu hanya 45 gram dengan bentuk universal claw/fingertip grip. Didukung chip Nordic 52840 MCU kompatibel 4000Hz polling rate, sensor optik PixArt PAW3395 26.000 DPI, dan optical switch Huano blue transparent pink dot.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            29 => [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'VGN Dragonfly F1 Pro Max 4K Ultra Lightweight Wireless Mouse 54g PAW3395',
                'slug' => 'vgn-dragonfly-f1-pro-max-wireless-paw3395',
                'sku' => 'MS-VGN-F1-PROMAX',
                'price' => 949000,
                'discount_price' => 849000,
                'stock' => 50,
                'rating_avg' => 4.92,
                'reviews_count' => 195,
                'sales_count' => 670,
                'description' => 'Mouse gaming wireless 4K budget king dengan performa esports kelas atas. Bobot seimbang 54 gram, baterai besar tahan hingga 130 jam, sensor flagship PAW3395, switch Kailh Golden Black Mamba 90 juta klik, dan skate 100% PTFE mulus.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            30 => [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Pulsar Xlite V3 Wireless Ergonomic Esports Gaming Mouse PAW3395 Size 2',
                'slug' => 'pulsar-xlite-v3-wireless-esports-mouse',
                'sku' => 'MS-PULSAR-XLITE-V3',
                'price' => 1599000,
                'discount_price' => 1399000,
                'stock' => 28,
                'rating_avg' => 4.96,
                'reviews_count' => 74,
                'sales_count' => 220,
                'description' => 'Bentuk ergonomis tangan kanan yang dirancang khusus untuk kenyamanan palm grip jangka panjang saat turnamen. Bobot hanya 55g tanpa lubang luar, optical switch bebas double-click, scroll wheel Pulsar Blue encoder, dan polling rate hingga 4K.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            31 => [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'ZOWIE EC2-CW Wireless Ergonomic Esports Gaming Mouse 24-Step Scroll Wheel',
                'slug' => 'zowie-ec2-cw-wireless-esports-mouse',
                'sku' => 'MS-ZOWIE-EC2-CW',
                'price' => 2499000,
                'discount_price' => 2299000,
                'stock' => 20,
                'rating_avg' => 4.98,
                'reviews_count' => 92,
                'sales_count' => 310,
                'description' => 'Standar emas mouse pro player CS2 dan Valorant di seluruh turnamen LAN dunia. Dilengkapi antena enhanced receiver independen anti-interferensi sinyal nirkabel, bentuk kurva asimetris legendaris EC series, dan scroll wheel 24-step taktil berkarakter tegas.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            32 => [
                'shop_idx' => 3,
                'cat_slug' => 'mouse-gaming',
                'name' => 'Endgame Gear OP1we Wireless Gaming Mouse Kailh GO Optical Switches 58g White',
                'slug' => 'endgame-gear-op1we-wireless-optical-mouse',
                'sku' => 'MS-EGG-OP1WE-WHT',
                'price' => 1449000,
                'discount_price' => 1299000,
                'stock' => 25,
                'rating_avg' => 4.95,
                'reviews_count' => 54,
                'sales_count' => 175,
                'description' => 'Bentuk ramping presisi claw grip idaman para gamer penembak jitu (aim god). Switch optik Kailh GO khusus dengan actuation latency instan dan klik mantap, sensor PixArt PAW3370 yang disetel tanpa smoothing, dan coating bodi matte anti-keringat.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            33 => [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Beyerdynamic DT 990 Pro 250 Ohm Open Studio Reference Headphones Black Limited',
                'slug' => 'beyerdynamic-dt-990-pro-250-ohm-studio',
                'sku' => 'AUD-BEYER-DT990-250',
                'price' => 2799000,
                'discount_price' => 2499000,
                'stock' => 20,
                'rating_avg' => 4.98,
                'reviews_count' => 115,
                'sales_count' => 360,
                'description' => 'Headphone open-back legendaris buatan Jerman yang menjadi standar streamer dan pro player untuk akurasi posisi suara tapak kaki (footsteps). Soundstage tiga dimensi yang sangat luas, bantalan telinga beludru velour super nyaman, dan kabel pegas tahan banting.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            34 => [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Tangzu Wan\'er S.G Studio Edition HiFi Dynamic Driver IEM Earphone with Mic',
                'slug' => 'tangzu-waner-sg-studio-edition-iem',
                'sku' => 'AUD-TANGZU-WANER-SG',
                'price' => 299000,
                'discount_price' => 249000,
                'stock' => 100,
                'rating_avg' => 4.94,
                'reviews_count' => 320,
                'sales_count' => 1400,
                'description' => 'IEM gaming budget sejuta umat dengan tuning audio natural netral seimbang. Driver dinamis diafragma PET 10mm dengan magnet N52, kabel tembaga OFC 4-core dapat dilepas pasang 2-pin 0.78mm, dan fitting ergonomis nyaman dipakai berjam-jam.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            35 => [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Sennheiser HD 660S2 Audiophile Open-Back Dynamic Headphones Made in Ireland',
                'slug' => 'sennheiser-hd-660s2-audiophile-open-back',
                'sku' => 'AUD-SENN-HD660S2',
                'price' => 8499000,
                'discount_price' => 7799000,
                'stock' => 8,
                'rating_avg' => 5.0,
                'reviews_count' => 36,
                'sales_count' => 70,
                'description' => 'Mahakarya headphone audiofil generasi baru dengan sub-bass lebih dalam dan kejernihan vokal luar biasa intim. Transduser 38mm berventilasi magnet neodymium, koil suara aluminium ultra-ringan, dan detail spasial mikro yang menakjubkan.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            36 => [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => 'Creative Sound BlasterX G6 32-bit/384kHz Hi-Res Gaming DAC and External USB Sound Card',
                'slug' => 'creative-sound-blasterx-g6-gaming-dac',
                'sku' => 'AUD-CRE-SBX-G6',
                'price' => 2499000,
                'discount_price' => 2199000,
                'stock' => 25,
                'rating_avg' => 4.96,
                'reviews_count' => 78,
                'sales_count' => 280,
                'description' => 'Sound card DAC eksternal paling terbukti untuk PC, PS5, Xbox, dan Switch. Fitur Scout Mode khusus memperkuat suara langkah kaki musuh, amplifier bi-amp headphone diskrit Xamp menggerakkan impedansi hingga 600 Ohm, dan resolusi audio 130dB DNR 32-bit 384kHz.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            37 => [
                'shop_idx' => 3,
                'cat_slug' => 'audio-headset',
                'name' => '7Hz Timeless 14.2mm Planar HiFi IEM Earphone CNC Aluminium Aviation Shell',
                'slug' => 'sevenhz-timeless-planar-hifi-iem-earphone',
                'sku' => 'AUD-7HZ-TIMELESS',
                'price' => 3199000,
                'discount_price' => 2849000,
                'stock' => 15,
                'rating_avg' => 4.97,
                'reviews_count' => 44,
                'sales_count' => 110,
                'description' => 'IEM planar magnetik legendaris dengan kecepatan transient tak tertandingi. Diafragma planar 14.2mm ultra-tipis dengan susunan magnet dua sisi N52, distorsi harmonik mendekati nol, serta pemisahan instrumen dan efek game FPS yang sangat jelas.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            38 => [
                'shop_idx' => 3,
                'cat_slug' => 'monitor-gaming',
                'name' => 'AOC 24G2SP 23.8" Fast IPS 165Hz 1ms MPRT Gaming Monitor (Best Budget Choice)',
                'slug' => 'aoc-24g2sp-24-inch-fast-ips-165hz',
                'sku' => 'MON-AOC-24G2SP',
                'price' => 2199000,
                'discount_price' => 1899000,
                'stock' => 60,
                'rating_avg' => 4.93,
                'reviews_count' => 240,
                'sales_count' => 890,
                'description' => 'Monitor gaming terlaris pilihan jutaan gamers esport pemula dan warnet pro. Panel Fast IPS 23.8 inch FHD 1920x1080, refresh rate 165Hz, response time 1ms MPRT, 126% sRGB gamut warna kaya, dukungan Adaptive-Sync, dan stand ergonomis fully adjustable (pivot, swivel, height).',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            39 => [
                'shop_idx' => 0,
                'cat_slug' => 'monitor-gaming',
                'name' => 'ASUS TUF Gaming VG27AQ 27" WQHD 2K 165Hz IPS G-Sync Compatible ELMB Sync',
                'slug' => 'asus-tuf-gaming-vg27aq-2k-165hz-g-sync',
                'sku' => 'MON-ASUS-VG27AQ',
                'price' => 4999000,
                'discount_price' => 4499000,
                'stock' => 30,
                'rating_avg' => 4.96,
                'reviews_count' => 110,
                'sales_count' => 380,
                'description' => 'Kombinasi resolusi tajam 2K WQHD (2560x1440) dan kecepatan 165Hz. Teknologi revolusioner ASUS ELMB Sync memungkinkan Extreme Low Motion Blur dan G-Sync aktif bersamaan untuk visual bebas ghosting dan tearing.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            40 => [
                'shop_idx' => 3,
                'cat_slug' => 'monitor-gaming',
                'name' => 'MSI MAG 274UPF 27" 4K UHD 144Hz 1ms Rapid IPS Type-C 65W Gaming Monitor',
                'slug' => 'msi-mag-274upf-4k-144hz-rapid-ips',
                'sku' => 'MON-MSI-274UPF-4K',
                'price' => 8499000,
                'discount_price' => 7799000,
                'stock' => 15,
                'rating_avg' => 4.97,
                'reviews_count' => 48,
                'sales_count' => 120,
                'description' => 'Ketajaman visual 4K UHD 3840x2160 dipadu panel Rapid IPS 144Hz 1ms GtG. Dilengkapi port HDMI 2.1 full bandwidth untuk PlayStation 5 4K 120Hz VRR, sertifikasi VESA DisplayHDR 400, dan USB Type-C 65W Power Delivery.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            41 => [
                'shop_idx' => 3,
                'cat_slug' => 'monitor-gaming',
                'name' => 'ViewSonic OMNI VX2728J 27" Fast IPS 180Hz 0.5ms HDR10 Height Adjustable Stand',
                'slug' => 'viewsonic-omni-vx2728j-27-inch-180hz',
                'sku' => 'MON-VS-VX2728J-180',
                'price' => 2899000,
                'discount_price' => 2499000,
                'stock' => 35,
                'rating_avg' => 4.92,
                'reviews_count' => 72,
                'sales_count' => 260,
                'description' => 'Monitor 27 inch 180Hz kencang dengan response time ekstrem 0.5ms MPRT. Mendukung AMD FreeSync Premium, VESA ClearMR untuk kejernihan gerak bersertifikat, dan stand ergonomis fleksibel.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            42 => [
                'shop_idx' => 3,
                'cat_slug' => 'monitor-gaming',
                'name' => 'BenQ ZOWIE XL2566K 24.5" 360Hz DyAc+ Esports Gaming Monitor Tournament Grade',
                'slug' => 'benq-zowie-xl2566k-360hz-dyac-plus-esports',
                'sku' => 'MON-ZOWIE-XL2566K',
                'price' => 12499000,
                'discount_price' => 11499000,
                'stock' => 12,
                'rating_avg' => 4.99,
                'reviews_count' => 86,
                'sales_count' => 240,
                'description' => 'Senjata utama para pemain profesional turnamen dunia VCT dan CS Major. Refresh rate native 360Hz pada panel Fast TN dengan teknologi eksklusif Dynamic Accuracy Plus (DyAc+) yang membuat semprotan recoil senjata tetap jernih dan mudah dikontrol.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            43 => [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Microsoft Xbox Series X 1TB Console 4K 120FPS 12 Teraflops Velocity Architecture',
                'slug' => 'microsoft-xbox-series-x-1tb-console',
                'sku' => 'CON-XBX-SERIES-X',
                'price' => 8499000,
                'discount_price' => 7899000,
                'stock' => 18,
                'rating_avg' => 4.95,
                'reviews_count' => 64,
                'sales_count' => 190,
                'description' => 'Konsol Xbox terkuat dengan performa grafis 12 Teraflops dan SSD kustom NVMe 1TB. Fitur Quick Resume memungkinkan beralih instan antar banyak game tanpa loading ulang, didukung ekosistem Xbox Game Pass Ultimate.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            44 => [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Lenovo Legion Go 8.8" QHD+ 144Hz AMD Ryzen Z1 Extreme 16GB 512GB Handheld PC',
                'slug' => 'lenovo-legion-go-8-8-qhd-z1-extreme',
                'sku' => 'CON-LEN-LEGION-GO',
                'price' => 12999000,
                'discount_price' => 11899000,
                'stock' => 14,
                'rating_avg' => 4.94,
                'reviews_count' => 52,
                'sales_count' => 140,
                'description' => 'Handheld gaming PC dengan layar sentuh terbesar 8.8-inch QHD+ 144Hz 500 nits. Pengontrol Legion TrueStrike yang dapat dilepas dengan mode FPS mouse optic unik, prosesor AMD Ryzen Z1 Extreme, dan sistem operasi Windows 11 murni.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            45 => [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Nintendo Switch Lite Handheld Gaming Console Turquoise Resmi Maxindo',
                'slug' => 'nintendo-switch-lite-turquoise-handheld',
                'sku' => 'CON-NSW-LITE-TRQ',
                'price' => 2599000,
                'discount_price' => 2299000,
                'stock' => 35,
                'rating_avg' => 4.91,
                'reviews_count' => 145,
                'sales_count' => 620,
                'description' => 'Konsol portabel ringan dan kompak khusus gaming genggam di mana saja. Kompatibel dengan semua game Nintendo Switch yang mendukung handheld mode seperti Zelda, Mario, Pokemon, dan Animal Crossing.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1578303512597-8be9abb94d76?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            46 => [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Anbernic RG556 5.48" AMOLED Android Retro Gaming Handheld Unisoc T820 Hall Triggers',
                'slug' => 'anbernic-rg556-amoled-android-retro',
                'sku' => 'CON-ANB-RG556-OLED',
                'price' => 3199000,
                'discount_price' => 2799000,
                'stock' => 20,
                'rating_avg' => 4.93,
                'reviews_count' => 48,
                'sales_count' => 160,
                'description' => 'Konsol retro Android premium dengan layar 5.48-inch AMOLED 1080p yang kontras pekat. Lancar memainkan emulator PS2, GameCube, Wii, 3DS, hingga streaming Moonlight/PS Remote Play dengan joystick Hall Effect anti-drifting.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            47 => [
                'shop_idx' => 4,
                'cat_slug' => 'konsol-handheld',
                'name' => 'Sony DualSense Edge Wireless Controller Pro PlayStation 5 Custom Profile Modul',
                'slug' => 'sony-dualsense-edge-wireless-controller-ps5',
                'sku' => 'CON-SONY-DSEDGE',
                'price' => 3599000,
                'discount_price' => 3249000,
                'stock' => 25,
                'rating_avg' => 4.97,
                'reviews_count' => 78,
                'sales_count' => 280,
                'description' => 'Controller profesional berperforma tinggi buatan Sony untuk PS5 dan PC. Tombol belakang yang dapat dipetakan ulang, modul stick analog yang dapat diganti jika rusak, stopper trigger yang dapat disesuaikan jarak kedalamannya, dan kabel USB braided dengan lock housing.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            48 => [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'POCO F6 Pro 16GB/512GB Snapdragon 8 Gen 2 120Hz WQHD+ Flow AMOLED 120W HyperCharge',
                'slug' => 'poco-f6-pro-16gb-512gb-snapdragon-8-gen-2',
                'sku' => 'HP-POCO-F6PRO',
                'price' => 7999000,
                'discount_price' => 7299000,
                'stock' => 30,
                'rating_avg' => 4.94,
                'reviews_count' => 112,
                'sales_count' => 450,
                'description' => 'Flagship killer idaman gamers mobile. Ditenagai prosesor Snapdragon 8 Gen 2 4nm, RAM 16GB LPDDR5X, ROM 512GB UFS 4.0, layar 120Hz WQHD+ 4000 nits, pendingin LiquidCool 4.0 dengan IceLoop system, dan baterai 5000mAh dengan charger 120W terisi penuh 19 menit.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            49 => [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'iQOO 12 5G 16GB/512GB Snapdragon 8 Gen 3 Q1 Supercomputing Chip 144Hz AMOLED',
                'slug' => 'iqoo-12-5g-16gb-512gb-snapdragon-8-gen-3',
                'sku' => 'HP-IQOO-12-5G',
                'price' => 10999000,
                'discount_price' => 9999000,
                'stock' => 20,
                'rating_avg' => 4.96,
                'reviews_count' => 84,
                'sales_count' => 320,
                'description' => 'Monster performa sejati dengan Snapdragon 8 Gen 3 dan chip gaming kustom Supercomputing Q1 untuk interpolasi frame 144 FPS pada Genshin Impact dan MLBB. Vapor Chamber pendingin 6K seluas 6010mm² dan pengisian daya 120W FlashCharge.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            50 => [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'Xiaomi Black Shark 5 Pro 12GB/256GB Magnetic Pop-up Triggers Snapdragon 8 Gen 1',
                'slug' => 'xiaomi-black-shark-5-pro-magnetic-triggers',
                'sku' => 'HP-BS-5PRO-12',
                'price' => 8999000,
                'discount_price' => 7999000,
                'stock' => 15,
                'rating_avg' => 4.91,
                'reviews_count' => 68,
                'sales_count' => 210,
                'description' => 'HP gaming sejati dengan tombol trigger fisik pop-up magnetik di samping bodi yang memberikan tactile click seperti kontroler konsol. Layar OLED 144Hz 720Hz touch sampling rate dan dual stereo speaker bersertifikasi Hi-Res.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            51 => [
                'shop_idx' => 1,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'Razer Kishi V2 Pro Mobile Gaming Controller for Android with HyperSense Haptics',
                'slug' => 'razer-kishi-v2-pro-mobile-controller-android',
                'sku' => 'ACC-RAZER-KISHI-PRO',
                'price' => 2199000,
                'discount_price' => 1899000,
                'stock' => 35,
                'rating_avg' => 4.95,
                'reviews_count' => 58,
                'sales_count' => 240,
                'description' => 'Ubah smartphone Android Anda menjadi konsol gaming genggam sejati. Menggunakan microswitch buttons presisi, tombol trigger analog, getaran haptic Razer HyperSense realistis, direct USB-C zero-latency, dan audio jack 3.5mm.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            52 => [
                'shop_idx' => 0,
                'cat_slug' => 'smartphone-gaming',
                'name' => 'Black Shark Magnetic Cooler 4 Pro 27W Fast Cooling Radiator RGB for Gaming Phone',
                'slug' => 'black-shark-magnetic-cooler-4-pro-radiator',
                'sku' => 'ACC-BS-COOLER-4PRO',
                'price' => 599000,
                'discount_price' => 499000,
                'stock' => 70,
                'rating_avg' => 4.96,
                'reviews_count' => 180,
                'sales_count' => 720,
                'description' => 'Pendingin magnetik peltier berkekuatan 27W yang mampu menurunkan suhu bodi smartphone hingga di bawah titik beku dalam hitungan detik. Mencegah thermal throttling dan frame drop saat main game berat kompetitif.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            53 => [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Rode RodeCaster Duo Compact Integrated Audio Studio Production Console for Streamers',
                'slug' => 'rode-rodecaster-duo-audio-mixer-streamer',
                'sku' => 'STR-RODE-CASTER-DUO',
                'price' => 8499000,
                'discount_price' => 7899000,
                'stock' => 12,
                'rating_avg' => 4.99,
                'reviews_count' => 42,
                'sales_count' => 95,
                'description' => 'Studio audio mixer all-in-one paling mutakhir untuk content creator dan streamer. Dua pre-amp mikrofon Revolution Preamps ultra-low noise, pemrosesan audio APHEX kelas siaran, 6 SMART pads yang dapat diprogram, dan konektivitas USB-C ganda untuk streaming 2 PC sekaligus.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            54 => [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Elgato Key Light Air Professional 1400 Lumens Studio LED Light Wi-Fi Control',
                'slug' => 'elgato-key-light-air-studio-led-light',
                'sku' => 'STR-ELG-KEYLIGHT-AIR',
                'price' => 2199000,
                'discount_price' => 1949000,
                'stock' => 25,
                'rating_avg' => 4.97,
                'reviews_count' => 76,
                'sales_count' => 280,
                'description' => 'Lampu studio profesional bebas silau dengan teknologi pencahayaan edge-lit multi-layer diffusion. Kecerahan hingga 1400 lumens, suhu warna 2900K - 7000K, dan kontrol penuh nirkabel via aplikasi PC/Mac/iOS/Android atau Elgato Stream Deck.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            55 => [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Sony Alpha ZV-E10 Mirrorless Vlog Camera 4K dengan Lensa 16-50mm OSS Garansi Resmi',
                'slug' => 'sony-alpha-zv-e10-mirrorless-4k-camera',
                'sku' => 'STR-SONY-ZVE10-KIT',
                'price' => 9999000,
                'discount_price' => 9199000,
                'stock' => 10,
                'rating_avg' => 4.98,
                'reviews_count' => 54,
                'sales_count' => 135,
                'description' => 'Kamera mirrorless favorit streaming live YouTube dan Twitch dengan sensor APS-C 24.2MP dan bokeh latar belakang alami. Fitur Product Showcase Setting instan fokus ke objek, Real-time Eye AF, dan koneksi USB webcam plug-and-play tanpa capture card.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            56 => [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Audio-Technica AT2020 Cardioid Condenser Studio Microphone XLR Version',
                'slug' => 'audio-technica-at2020-cardioid-condenser-mic',
                'sku' => 'STR-AT-AT2020-XLR',
                'price' => 1499000,
                'discount_price' => 1299000,
                'stock' => 40,
                'rating_avg' => 4.95,
                'reviews_count' => 140,
                'sales_count' => 580,
                'description' => 'Mikrofon kondensor legendaris standar industri recording dan podcast. Pola penangkapan suara cardioid yang mengisolasi kebisingan samping dan belakang, diafragma low-mass 16mm bersuara jernih hangat, dan ketahanan SPL tinggi.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            57 => [
                'shop_idx' => 3,
                'cat_slug' => 'streaming-gear',
                'name' => 'Elgato Wave:3 Premium USB Condenser Mic with Clipguard & Wave Link Software',
                'slug' => 'elgato-wave-3-premium-usb-condenser-mic',
                'sku' => 'STR-ELG-WAVE3-MIC',
                'price' => 2499000,
                'discount_price' => 2199000,
                'stock' => 30,
                'rating_avg' => 4.96,
                'reviews_count' => 95,
                'sales_count' => 340,
                'description' => 'Mikrofon kondensor USB dengan teknologi anti-distorsi Clipguard eksklusif yang mencegah suara pecah saat teriak kaget saat gaming. Terintegrasi software mixer digital Wave Link untuk mengatur hingga 9 channel audio independen.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            58 => [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Fanatec Gran Turismo DD Pro 8Nm Direct Drive Wheel Base & CSL Pedals PC/PS5',
                'slug' => 'fanatec-gt-dd-pro-8nm-direct-drive-wheel',
                'sku' => 'SIM-FAN-GTDD-8NM',
                'price' => 16999000,
                'discount_price' => 15499000,
                'stock' => 8,
                'rating_avg' => 4.99,
                'reviews_count' => 38,
                'sales_count' => 85,
                'description' => 'Sistem kemudi Direct Drive resmi Gran Turismo untuk PlayStation 5 dan PC. Motor direct drive 8Nm torsi instan 100% tanpa delay sabuk/gir, stir kemudi OLED display, rev meter LED, dan pedal baja kokoh presisi tinggi.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            59 => [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Moza R9 V2 Direct Drive Wheelbase 9Nm & CS V2P Steering Wheel Rim Alcantara',
                'slug' => 'moza-r9-v2-direct-drive-cs-v2p-wheel',
                'sku' => 'SIM-MOZA-R9-CSV2P',
                'price' => 13499000,
                'discount_price' => 12299000,
                'stock' => 10,
                'rating_avg' => 4.97,
                'reviews_count' => 42,
                'sales_count' => 92,
                'description' => 'Kombinasi wheelbase direct drive torsi 9Nm berbadan aluminium aviasi dan lingkar stir kemudi 13-inci berbalut kulit suede microfiber Alcantara mewah. Dilengkapi paddle shifter serat karbon magnetik dengan sensor fotolistrik.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            60 => [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Thrustmaster T300 RS GT Edition Force Feedback Racing Wheel PC & PlayStation',
                'slug' => 'thrustmaster-t300-rs-gt-edition-racing-wheel',
                'sku' => 'SIM-TM-T300RS-GT',
                'price' => 6999000,
                'discount_price' => 6299000,
                'stock' => 20,
                'rating_avg' => 4.92,
                'reviews_count' => 110,
                'sales_count' => 380,
                'description' => 'Kemudi force feedback dual-belt brushless 1080 derajat terfavorit para sim racer pemula hingga menengah. Dilengkapi pedal set 3-pedal T3PA GT Edition berbahan metal dan sistem Quick Release untuk mengganti lingkar setir.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            61 => [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Simagic P1000 Hydraulic Load Cell 3-Pedal Set Modular CNC Aluminium',
                'slug' => 'simagic-p1000-hydraulic-load-cell-pedals',
                'sku' => 'SIM-SMG-P1000-HYD',
                'price' => 11999000,
                'discount_price' => 10799000,
                'stock' => 6,
                'rating_avg' => 5.0,
                'reviews_count' => 22,
                'sales_count' => 48,
                'description' => 'Pedal balap hidrolik load cell presisi tinggi untuk sensasi pengereman realistis mobil balap GT3 dan Formula 1. Bodi full aluminium CNC anodized, sensor tekanan beban 100kg load cell, dan getaran motor haptic feedback pada pedal rem.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            62 => [
                'shop_idx' => 4,
                'cat_slug' => 'racing-sim-vr',
                'name' => 'Next Level Racing F-GT Lite Foldable Simulator Cockpit Formula & GT Position',
                'slug' => 'next-level-racing-f-gt-lite-simulator-cockpit',
                'sku' => 'SIM-NLR-FGTLITE',
                'price' => 5499000,
                'discount_price' => 4999000,
                'stock' => 12,
                'rating_avg' => 4.93,
                'reviews_count' => 45,
                'sales_count' => 115,
                'description' => 'Kokpit simulator balap lipat portabel yang dapat diubah posisinya antara posisi rebah Formula 1 atau posisi tegak GT Racing. Rangka baja kokoh tahan beban pedal load cell, mudah dilipat disimpan di bawah ranjang saat tidak digunakan.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            63 => [
                'shop_idx' => 3,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Herman Miller x Logitech G Embody Ergonomic Gaming Chair Cyan Blue',
                'slug' => 'herman-miller-logitech-g-embody-cyan',
                'sku' => 'CHR-HM-EMBODY-CYAN',
                'price' => 29999000,
                'discount_price' => 27499000,
                'stock' => 5,
                'rating_avg' => 5.0,
                'reviews_count' => 38,
                'sales_count' => 65,
                'description' => 'Kursi gaming ergonomis termewah di dunia hasil kolaborasi pakar ergonomi Herman Miller dan Logitech G. Fitur busa pendingin tembaga, dukungan tulang belakang dinamis PostureFit, dan distribusi tekanan otomatis yang menjamin punggung bebas pegal meski duduk 14 jam.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            64 => [
                'shop_idx' => 3,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Noblechairs HERO Real Leather Black Edition Ergonomic Office & Gaming Chair',
                'slug' => 'noblechairs-hero-real-leather-black-edition',
                'sku' => 'CHR-NOB-HERO-LTHR',
                'price' => 10499000,
                'discount_price' => 9499000,
                'stock' => 10,
                'rating_avg' => 4.96,
                'reviews_count' => 52,
                'sales_count' => 120,
                'description' => 'Kursi gaming eksekutif berbahan kulit asli premium dengan pori sirkulasi udara breathable. Lumbar support terintegrasi yang dapat diputar, sandaran tangan 4D fleksibel, rangka baja kokoh, dan sertifikasi kursi kantor DIN EN 1335.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            65 => [
                'shop_idx' => 3,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Meja Gaming Z-Shape Carbon Fiber RGB 140x60cm dengan Headphone Hook & Cup Holder',
                'slug' => 'meja-gaming-z-shape-carbon-fiber-rgb-140',
                'sku' => 'DSK-ZSHAPE-140-RGB',
                'price' => 1899000,
                'discount_price' => 1599000,
                'stock' => 25,
                'rating_avg' => 4.91,
                'reviews_count' => 98,
                'sales_count' => 340,
                'description' => 'Meja gaming bertekstur serat karbon tahan gores seluas 140x60cm. Kaki baja struktur Z-shape stabil menahan beban hingga 150kg, lampu RGB dinamis di kedua sisi meja, gantungan headset, tempat gelas minum, dan lubang manajemen kabel rapi.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            66 => [
                'shop_idx' => 3,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Pegboard Modular Wall Organizer Gaming Setup Matte Black Metal 80x60cm Kit',
                'slug' => 'pegboard-modular-wall-organizer-gaming-setup',
                'sku' => 'DSK-PGB-MATTE-BLK',
                'price' => 749000,
                'discount_price' => 649000,
                'stock' => 40,
                'rating_avg' => 4.95,
                'reviews_count' => 125,
                'sales_count' => 460,
                'description' => 'Solusi dinding setup gaming estetik untuk memajang koleksi keyboard, controller game, headset, kabel, dan pernak-pernik gaming. Terbuat dari lembaran baja tebal anti-karat dengan paket gantungan lengkap dan rak mini.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            67 => [
                'shop_idx' => 3,
                'cat_slug' => 'kursi-setup-meja',
                'name' => 'Ergotron LX Single Monitor Arm Desk Mount Matte Black Heavy Duty up to 34 Inch',
                'slug' => 'ergotron-lx-single-monitor-arm-desk-mount',
                'sku' => 'ACC-ERG-LX-BLK',
                'price' => 2799000,
                'discount_price' => 2499000,
                'stock' => 20,
                'rating_avg' => 4.99,
                'reviews_count' => 84,
                'sales_count' => 290,
                'description' => 'Lengan monitor hidrolik terbaik di dunia bergaransi 10 tahun. Menggunakan teknologi Constant Force eksklusif yang memungkinkan monitor digerakkan seringan sentuhan jari tanpa kendur, mendukung monitor hingga 34 inci dengan bobot 11.3 kg.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            68 => [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'SteelSeries QcK Heavy XXL Gaming Mousepad 900x400x6mm Micro-Woven Cloth',
                'slug' => 'steelseries-qck-heavy-xxl-gaming-mousepad',
                'sku' => 'ACC-SS-QCK-HVY-XXL',
                'price' => 649000,
                'discount_price' => 549000,
                'stock' => 50,
                'rating_avg' => 4.96,
                'reviews_count' => 165,
                'sales_count' => 610,
                'description' => 'Mousepad berukuran meja 900x400mm dengan ketebalan ekstra 6mm yang meredam ketidakrataan permukaan meja. Kain tenun mikro mikro legendaris SteelSeries QcK yang memberikan kontrol stopping power sempurna.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            69 => [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Pulsar Superglide 2 Aluminosilicate Glass Mouse Skates for Logitech Superlight 2',
                'slug' => 'pulsar-superglide-2-glass-skates-superlight',
                'sku' => 'ACC-PLS-SPGLIDE2-GPX',
                'price' => 399000,
                'discount_price' => 349000,
                'stock' => 60,
                'rating_avg' => 4.94,
                'reviews_count' => 88,
                'sales_count' => 350,
                'description' => 'Kaki mouse (mouse feet) dari kaca aluminosilikat kaca temper dengan tepi melengkung 2.5D. Memberikan luncuran sehalus es tanpa friksi statis, tahan abrasi seumur hidup tanpa pernah aus seperti skate PTFE biasa.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            70 => [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'GMK Laser R2 Custom Doubleshot ABS Cherry Profile Keycaps Set Cyberpunk',
                'slug' => 'gmk-laser-r2-doubleshot-cherry-keycaps',
                'sku' => 'ACC-GMK-LASER-R2',
                'price' => 2599000,
                'discount_price' => 2299000,
                'stock' => 15,
                'rating_avg' => 4.98,
                'reviews_count' => 42,
                'sales_count' => 110,
                'description' => 'Keycaps artisan papan atas karya desainer MiTo buatan pabrik GMK di Jerman. Plastik tebal doubleshot ABS 1.5mm dengan warna neon cyberpunk ungu-merah muda yang legendaris, profil klasik Cherry yang pas di jari.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            71 => [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Krytox 205g0 Switch & Stabilizer Lube Kit 10g with Fine Brush and Stem Picker',
                'slug' => 'krytox-205g0-switch-stabilizer-lube-kit',
                'sku' => 'ACC-KRYTOX-205G0',
                'price' => 179000,
                'discount_price' => 149000,
                'stock' => 80,
                'rating_avg' => 4.97,
                'reviews_count' => 210,
                'sales_count' => 840,
                'description' => 'Pelumas mekanikal switch nomor satu di dunia buatan Chemours USA. Menghilangkan bunyi gesekan scratchy dan rattly pada stabilizer, menghasilkan suara thock padat dan buttery smooth pada setiap ketukan.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
            72 => [
                'shop_idx' => 3,
                'cat_slug' => 'aksesoris-gaming',
                'name' => 'Coiled Aviator Cable USB-C to USB-A Mechanical Keyboard Custom Paracord Black',
                'slug' => 'coiled-aviator-cable-usb-c-custom-black',
                'sku' => 'ACC-COIL-AVIATOR-BLK',
                'price' => 289000,
                'discount_price' => 249000,
                'stock' => 50,
                'rating_avg' => 4.93,
                'reviews_count' => 135,
                'sales_count' => 520,
                'description' => 'Kabel gulung spiral kustom keyboard mekanikal dengan konektor metal GX16 4-pin aviator plug. Lapisan ganda selongsong paracord dan techflex PET kaku yang awet, memberikan estetika setup meja gaming pro yang clean.',
                'images' => [
                    0 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [
                ],
            ],
        ];
        $productsData = array_merge($productsData, $additionalProductsData);

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $shop = $shops[$pData['shop_idx']];
            $category = $categories[$pData['cat_slug']];

            $product = Product::firstOrCreate(
                ['slug' => $pData['slug']],
                [
                    'shop_id' => $shop->id,
                    'category_id' => $category->id,
                    'name' => $pData['name'],
                    'sku' => $pData['sku'],
                    'price' => $pData['price'],
                    'discount_price' => $pData['discount_price'],
                    'stock' => $pData['stock'],
                    'weight' => 1500,
                    'condition' => 'new',
                    'rating_avg' => $pData['rating_avg'],
                    'reviews_count' => $pData['reviews_count'],
                    'sales_count' => $pData['sales_count'],
                    'views_count' => $pData['sales_count'] * 7,
                    'description' => $pData['description'],
                    'is_active' => true,
                ]
            );

            // Images
            foreach ($pData['images'] as $idx => $img) {
                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'sort_order' => $idx],
                    [
                        'image_path' => $img,
                        'is_primary' => $idx === 0,
                    ]
                );
            }

            // Variants
            if (! empty($pData['variants'])) {
                foreach ($pData['variants'] as $v) {
                    ProductVariant::firstOrCreate(
                        ['product_id' => $product->id, 'value' => $v['value']],
                        [
                            'name' => $v['name'],
                            'price_adjustment' => $v['price_adjustment'],
                            'stock' => $v['stock'],
                            'sku' => $product->sku.'-'.Str::slug($v['value']),
                        ]
                    );
                }
            }

            $createdProducts[] = $product;
        }

        // 7. Flash Sales (Top 6 exciting gaming gear on active discount)
        for ($i = 0; $i < min(6, count($createdProducts)); $i++) {
            $prod = $createdProducts[$i];
            FlashSale::firstOrCreate(
                ['product_id' => $prod->id],
                [
                    'discount_price' => round($prod->price * 0.75, -3), // 25% off flash discount
                    'stock_quota' => 20,
                    'sold_count' => 12 + ($i * 2),
                    'start_time' => now()->subHours(2),
                    'end_time' => now()->addHours(10),
                    'is_active' => true,
                ]
            );
        }

        // 8. Banners for Hero Slider
        $bannersData = [
            [
                'title' => 'ROG & GeForce RTX 40 Series Master Gaming Festival',
                'image_path' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200&auto=format&fit=crop&q=80',
                'target_url' => '/products?category=laptop-gaming',
                'position' => 'hero',
                'sort_order' => 1,
            ],
            [
                'title' => 'Super Flash Sale Gaming Gear & Komponen PC Diskon Spesial',
                'image_path' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1200&auto=format&fit=crop&q=80',
                'target_url' => '/products?filter=flash_sale',
                'position' => 'hero',
                'sort_order' => 2,
            ],
            [
                'title' => 'PlayStation 5 Slim & Handheld Console Hub Resmi Indonesia',
                'image_path' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=1200&auto=format&fit=crop&q=80',
                'target_url' => '/products?category=konsol-handheld',
                'position' => 'hero',
                'sort_order' => 3,
            ],
            [
                'title' => 'Kustom PC Rakitan Impian & Simulasi Spek High-End Online',
                'image_path' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=1200&auto=format&fit=crop&q=80',
                'target_url' => '/products?category=pc-rakitan',
                'position' => 'hero',
                'sort_order' => 4,
            ],
        ];

        foreach ($bannersData as $b) {
            Banner::firstOrCreate(['title' => $b['title']], $b);
        }

        // 9. Gaming Vouchers
        $vouchersData = [
            [
                'code' => 'GAMERSEJATI',
                'name' => 'Diskon Gamers Rp 150.000',
                'type' => 'fixed',
                'amount' => 150000,
                'min_purchase' => 1000000,
                'max_discount' => null,
                'quota' => 500,
                'used_count' => 18,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'ONGKIRGAMING',
                'name' => 'Potongan Ongkir Rp 25.000 Seluruh Indonesia',
                'type' => 'fixed',
                'amount' => 25000,
                'min_purchase' => 150000,
                'max_discount' => null,
                'quota' => 1000,
                'used_count' => 74,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'MEGACASHBACK',
                'name' => 'Cashback Kilat Rp 200.000 Hardware & Gear',
                'type' => 'fixed',
                'amount' => 200000,
                'min_purchase' => 2500000,
                'max_discount' => null,
                'quota' => 300,
                'used_count' => 12,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(15),
                'is_active' => true,
            ],
        ];

        foreach ($vouchersData as $v) {
            Voucher::firstOrCreate(['code' => $v['code']], $v);
        }

        // 10. Sample Customer Cart
        $cart = Cart::firstOrCreate(['user_id' => $customerUser->id]);
        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $createdProducts[7]->id], // Razer Huntsman V3 Pro
            [
                'quantity' => 1,
                'is_selected' => true,
            ]
        );
        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $createdProducts[9]->id], // Razer DeathAdder V3 Pro
            [
                'quantity' => 1,
                'is_selected' => true,
            ]
        );

        // 11. Sample Wishlist
        Wishlist::firstOrCreate([
            'user_id' => $customerUser->id,
            'product_id' => $createdProducts[0]->id, // ROG Zephyrus G16
        ]);
        Wishlist::firstOrCreate([
            'user_id' => $customerUser->id,
            'product_id' => $createdProducts[13]->id, // PS5 Slim
        ]);

        // 12. Sample Orders (Completed with Review & Shipped)
        $p1 = $createdProducts[0]; // ROG Zephyrus G16
        $order1 = Order::firstOrCreate(
            ['order_number' => 'ORD-'.date('Ymd').'-000001'],
            [
                'user_id' => $customerUser->id,
                'subtotal' => $p1->final_price,
                'shipping_cost' => 50000,
                'discount_amount' => 150000,
                'grand_total' => $p1->final_price + 50000 - 150000,
                'status' => 'completed',
                'shipping_address' => [
                    'recipient_name' => 'Budi Santoso',
                    'phone' => '081200000003',
                    'address_line' => 'Jl. Senopati Raya No. 45 RT 02/RW 03',
                    'city' => 'Jakarta Selatan',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12190',
                ],
                'shipping_courier' => 'Kargo Cepat (J&T Cargo Proteksi Kayu)',
                'tracking_number' => 'JTC-9821839281',
                'notes' => 'Tolong packaging kayu ekstra tebal dan asuransi penuh ya min, makasih!',
            ]
        );

        $orderItem1 = OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_id' => $p1->id],
            [
                'shop_id' => $p1->shop_id,
                'product_name' => $p1->name,
                'variant_name' => '32GB RAM / 1TB SSD / RTX 4080',
                'price' => $p1->final_price,
                'quantity' => 1,
                'subtotal' => $p1->final_price,
                'is_reviewed' => true,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order1->id],
            [
                'payment_number' => 'PAY-'.date('Ymd').'-000001',
                'payment_method' => 'Virtual Account',
                'payment_channel' => 'BCA Virtual Account',
                'amount' => $order1->grand_total,
                'status' => 'paid',
                'transaction_id' => 'TRX-'.Str::random(12),
                'paid_at' => now()->subDays(2),
            ]
        );

        Review::firstOrCreate(
            ['order_item_id' => $orderItem1->id],
            [
                'user_id' => $customerUser->id,
                'product_id' => $p1->id,
                'rating' => 5,
                'comment' => 'Performa laptop ROG ini luar biasa kencang! Layar OLED 240Hz super tajam dan mulus, gaming Cyberpunk 2077 mentok kanan 120+ FPS. Packaging kayu tebal aman bergaransi resmi ASUS Indonesia. Seller paling mantap!',
                'photo_path' => null,
            ]
        );

        // Another order: Paid & Shipped (PS5 Slim)
        $p2 = $createdProducts[13]; // PS5 Slim
        $order2 = Order::firstOrCreate(
            ['order_number' => 'ORD-'.date('Ymd').'-000002'],
            [
                'user_id' => $customerUser->id,
                'subtotal' => $p2->final_price,
                'shipping_cost' => 35000,
                'discount_amount' => 25000,
                'grand_total' => $p2->final_price + 35000 - 25000,
                'status' => 'shipped',
                'shipping_address' => [
                    'recipient_name' => 'Budi Santoso',
                    'phone' => '081200000003',
                    'address_line' => 'Jl. Senopati Raya No. 45 RT 02/RW 03',
                    'city' => 'Jakarta Selatan',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12190',
                ],
                'shipping_courier' => 'Next Day (SiCepat BEST)',
                'tracking_number' => '004128918291',
                'notes' => 'Segel Sony utuh jangan sampai rusak ya seller.',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_id' => $p2->id],
            [
                'shop_id' => $p2->shop_id,
                'product_name' => $p2->name,
                'variant_name' => 'Standar (1 DualSense Controller)',
                'price' => $p2->final_price,
                'quantity' => 1,
                'subtotal' => $p2->final_price,
                'is_reviewed' => false,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order2->id],
            [
                'payment_number' => 'PAY-'.date('Ymd').'-000002',
                'payment_method' => 'E-Wallet',
                'payment_channel' => 'GoPay',
                'amount' => $order2->grand_total,
                'status' => 'paid',
                'transaction_id' => 'TRX-'.Str::random(12),
                'paid_at' => now()->subDay(),
            ]
        );
    }
}

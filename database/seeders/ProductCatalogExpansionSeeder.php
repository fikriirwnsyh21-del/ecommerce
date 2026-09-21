<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCatalogExpansionSeeder extends Seeder
{
    /**
     * Seed realistic gaming and electronics products until total products in database reaches at least 500.
     */
    public function run(): void
    {
        $targetCount = 500;
        $currentCount = Product::count();

        if ($currentCount >= $targetCount) {
            $this->command->info("Database already contains {$currentCount} products (target: {$targetCount}). No expansion needed.");

            return;
        }

        $needed = $targetCount - $currentCount;
        $this->command->info("Currently {$currentCount} products exist. Adding {$needed} realistic items to reach {$targetCount} products...");

        $categories = Category::all()->keyBy('id');
        $shops = Shop::all();

        if ($shops->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Shops or Categories are empty. Please run DatabaseSeeder first.');

            return;
        }

        // Curated Image URLs per Category for authentic visual presentation
        $imagePool = [
            1 => [ // Laptop Gaming
                'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&auto=format&fit=crop&q=80',
            ],
            2 => [ // PC Rakitan
                'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1547082299-de196ea013d6?w=800&auto=format&fit=crop&q=80',
            ],
            3 => [ // PC & Komponen
                'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1562976540-1502c2145186?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
            ],
            4 => [ // Monitor Gaming
                'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1551645120-d70bfe84c826?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1547119957-637f8679db1e?w=800&auto=format&fit=crop&q=80',
            ],
            5 => [ // Keyboard Mechanical
                'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1601445638532-3c6f6c3aa1d6?w=800&auto=format&fit=crop&q=80',
            ],
            6 => [ // Mouse Gaming
                'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1563297007-0686b7003af7?w=800&auto=format&fit=crop&q=80',
            ],
            7 => [ // Audio & Headset
                'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
            ],
            8 => [ // Konsol & Handheld
                'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=800&auto=format&fit=crop&q=80',
            ],
            9 => [ // Smartphone Gaming
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80',
            ],
            10 => [ // Streaming Gear
                'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?w=800&auto=format&fit=crop&q=80',
            ],
            11 => [ // Kursi & Setup Meja
                'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format&fit=crop&q=80',
            ],
            12 => [ // Storage RAM & SSD
                'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=800&auto=format&fit=crop&q=80',
            ],
            13 => [ // Cooling & Power Supply
                'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=800&auto=format&fit=crop&q=80',
            ],
            14 => [ // Racing Sim & VR
                'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1622979135225-d2ba269bc1df?w=800&auto=format&fit=crop&q=80',
            ],
            15 => [ // Aksesoris & Modding
                'https://images.unsplash.com/photo-1601445638532-3c6f6c3aa1d6?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        // Specific, realistic catalog blueprint templates across all 15 categories
        $catalogTemplates = [
            // 1. Laptop Gaming
            1 => [
                ['name' => 'ASUS ROG Strix SCAR 16 Core i9 14900HX 32GB 2TB RTX 4080 240Hz Mini-LED', 'base_price' => 48999000, 'variants' => ['32GB RAM / 1TB SSD', '64GB RAM / 2TB SSD']],
                ['name' => 'ASUS ROG Strix SCAR 18 Core i9 14900HX 64GB 2TB RTX 4090 240Hz QHD+', 'base_price' => 64999000, 'variants' => ['Standard 64GB/2TB', 'Deluxe Bundle Cooler']],
                ['name' => 'Lenovo Legion 7i Gen 9 Intel Core i9 14900HX RTX 4070 32GB 1TB WQXGA', 'base_price' => 37999000, 'variants' => ['Glacier White', 'Eclipse Black']],
                ['name' => 'Lenovo Legion Pro 5 Gen 9 AMD Ryzen 7 7745HX RTX 4060 16GB 1TB 165Hz', 'base_price' => 22499000, 'variants' => ['16GB RAM / 512GB SSD', '32GB RAM / 1TB SSD']],
                ['name' => 'Lenovo LOQ 15 Essential Intel Core i5 13450HX RTX 4050 16GB 512GB FHD 144Hz', 'base_price' => 13499000, 'variants' => ['Grey Luna Standard']],
                ['name' => 'Acer Predator Helios Neo 16 Intel Core i7 14700HX RTX 4060 16GB 1TB WQXGA', 'base_price' => 21999000, 'variants' => ['16GB RAM Single', '32GB RAM Dual Channel']],
                ['name' => 'Acer Nitro V 15 Intel Core i5 13420H RTX 4050 16GB 512GB FHD 144Hz', 'base_price' => 11999000, 'variants' => ['Obsidian Black']],
                ['name' => 'MSI Titan 18 HX Core i9 14900HX 64GB 4TB RTX 4090 4K 120Hz Mini-LED Flagship', 'base_price' => 84999000, 'variants' => ['Dragon Collector Edition']],
                ['name' => 'MSI Raider GE78 HX Core i9 14900HX 32GB 2TB RTX 4080 Matrix RGB', 'base_price' => 51999000, 'variants' => ['Core Black']],
                ['name' => 'MSI Katana 15 B13VFK Intel Core i7 13620H RTX 4060 16GB 1TB FHD 144Hz', 'base_price' => 16499000, 'variants' => ['Black']],
                ['name' => 'HP OMEN Transcend 14 OLED Intel Core Ultra 9 185H RTX 4070 32GB 1TB Shadow Black', 'base_price' => 33999000, 'variants' => ['Shadow Black', 'Ceramic White']],
                ['name' => 'HP Victus 16 AMD Ryzen 7 7840HS RTX 4060 16GB 1TB FHD 165Hz Performance', 'base_price' => 17999000, 'variants' => ['Mica Silver', 'Performance Blue']],
                ['name' => 'Alienware m18 R2 Gaming Laptop Intel Core i9 14900HX RTX 4090 64GB 4TB QHD+ 165Hz', 'base_price' => 74999000, 'variants' => ['Dark Metallic Moon']],
                ['name' => 'Alienware x16 R2 Ultra-Thin Intel Core Ultra 9 RTX 4080 32GB 1TB Cryo-tech', 'base_price' => 49999000, 'variants' => ['Lunar Silver']],
                ['name' => 'Gigabyte AORUS 16X Intel Core i7 14650HX RTX 4070 16GB 1TB WQXGA 165Hz G-Sync', 'base_price' => 25999000, 'variants' => ['Aurora Gray']],
                ['name' => 'ASUS ROG Flow Z13 Gaming Tablet Intel Core i9 13900H RTX 4060 16GB 1TB Nebula 165Hz', 'base_price' => 34999000, 'variants' => ['Tablet + Detachable Keyboard']],
                ['name' => 'ASUS ROG Flow X16 2-in-1 AMD Ryzen 9 7940HS RTX 4070 32GB 1TB Mini-LED Touchscreen', 'base_price' => 42999000, 'variants' => ['Off Black']],
                ['name' => 'Razer Blade 16 Dual-Mode Mini-LED UHD+ 120Hz / FHD+ 240Hz Core i9 RTX 4080', 'base_price' => 59999000, 'variants' => ['Matte Black CNC']],
                ['name' => 'Razer Blade 18 4K 200Hz Intel Core i9 14900HX RTX 4090 64GB 2TB Thunderbolt 5', 'base_price' => 79999000, 'variants' => ['Black Anodized']],
                ['name' => 'MSI Cyborg 15 Translucent Chassis Intel Core i7 12650H RTX 4060 16GB 512GB 144Hz', 'base_price' => 14999000, 'variants' => ['Translucent Black']],
            ],

            // 2. PC Rakitan
            2 => [
                ['name' => 'PC Rakitan Sultan Tier Ultra 4K Intel Core i9 14900K + RTX 4090 24GB + 64GB DDR5 Liquid', 'base_price' => 72999000, 'variants' => ['Dual Chamber White', 'Stealth Black']],
                ['name' => 'PC Rakitan Esports God Tier AMD Ryzen 7 7800X3D + RTX 4080 Super 16GB + 32GB DDR5 CL30', 'base_price' => 43999000, 'variants' => ['Lian Li O11 EVO', 'NZXT H6 Flow']],
                ['name' => 'PC Rakitan 1440p High FPS Arena AMD Ryzen 5 7600X + RTX 4070 Ti Super 16GB + 32GB DDR5', 'base_price' => 29999000, 'variants' => ['Full ARGB Fan Pack']],
                ['name' => 'PC Rakitan Streamer & Render King Intel Core i7 14700K + RTX 4070 Super 12GB + 32GB DDR5', 'base_price' => 27499000, 'variants' => ['Capture Card Elgato Included', 'Standard Rig']],
                ['name' => 'PC Rakitan Mini ITX Compact Beast AMD Ryzen 7 7800X3D + RTX 4070 Dual Fan SFF A4-H2O', 'base_price' => 31999000, 'variants' => ['SFF Aluminium Silver', 'SFF Matte Black']],
                ['name' => 'PC Rakitan Budget 15 Juta All-Rounder Intel Core i5 13400F + RTX 4060 8GB + 16GB DDR5', 'base_price' => 15499000, 'variants' => ['Tempered Glass Panoramic']],
                ['name' => 'PC Rakitan Budget 10 Juta Gen Z AMD Ryzen 5 5600 + RTX 3060 12GB + 16GB RAM + 512GB NVMe', 'base_price' => 10499000, 'variants' => ['Aero Black Case', 'Snow White Case']],
                ['name' => 'PC Rakitan Pelajar & Kantor Gaming AMD Ryzen 5 5600G Radeon Vega 7 + 16GB RAM Dual Channel', 'base_price' => 4999000, 'variants' => ['512GB NVMe SSD', '1TB NVMe SSD']],
                ['name' => 'PC Rakitan All White Snow Edition AMD Ryzen 7 7700X + RTX 4070 Super White + 32GB White RGB', 'base_price' => 32499000, 'variants' => ['Vision Glass Edition']],
                ['name' => 'PC Rakitan AI & Deep Learning Station AMD Ryzen 9 7950X + Dual RTX 4090 24GB + 128GB DDR5', 'base_price' => 125000000, 'variants' => ['Industrial Server Rackmount', 'Tower Chassis']],
                ['name' => 'PC Rakitan Valorant & CS2 500+ FPS AMD Ryzen 7 5700X3D + RTX 4060 8GB + 32GB DDR4 3600MHz', 'base_price' => 16999000, 'variants' => ['Esports Ready Tuning']],
                ['name' => 'PC Rakitan Blackout Silent Edition be quiet! Intel Core i7 14700 + RTX 4070 Ti Super Non-RGB', 'base_price' => 33999000, 'variants' => ['Pure Sound-Dampened']],
                ['name' => 'PC Rakitan Custom Hard-Tube Liquid Cooled Intel i9 14900KS + RTX 4090 Distro Plate EKWB', 'base_price' => 95000000, 'variants' => ['Clear UV Coolant', 'Blood Red Coolant']],
            ],

            // 3. PC & Komponen
            3 => [
                ['name' => 'NVIDIA GeForce RTX 4070 Ti Super 16GB GDDR6X OC Edition Tri-Fan GPU', 'base_price' => 15499000, 'variants' => ['ASUS TUF Gaming', 'MSI Gaming X Slim', 'Gigabyte Gaming OC']],
                ['name' => 'NVIDIA GeForce RTX 4070 Super 12GB GDDR6X Dual Fan Compact GPU', 'base_price' => 11499000, 'variants' => ['ASUS Dual White', 'ZOTAC Twin Edge']],
                ['name' => 'NVIDIA GeForce RTX 4060 Ti 16GB GDDR6 OC Ray Tracing Graphics Card', 'base_price' => 8499000, 'variants' => ['16GB VRAM OC Edition']],
                ['name' => 'AMD Radeon RX 7900 XTX 24GB GDDR6 384-bit RDNA 3 Flagship Graphics Card', 'base_price' => 17999000, 'variants' => ['Sapphire Nitro+ Vapor-X', 'PowerColor Hellhound']],
                ['name' => 'AMD Radeon RX 7800 XT 16GB GDDR6 256-bit Dual BIOS Gaming GPU', 'base_price' => 9299000, 'variants' => ['ASUS TUF Gaming', 'Sapphire Pure White']],
                ['name' => 'AMD Radeon RX 7600 XT 16GB GDDR6 1080p Ultra High Memory GPU', 'base_price' => 5999000, 'variants' => ['Standard Black']],
                ['name' => 'Prosesor Intel Core i7 14700K 20 Core 28 Thread 5.6GHz LGA1700 Unlocked', 'base_price' => 6999000, 'variants' => ['Box Resmi Distributor']],
                ['name' => 'Prosesor Intel Core i5 14600K 14 Core 20 Thread 5.3GHz LGA1700 Unlocked', 'base_price' => 5199000, 'variants' => ['Box Resmi Distributor']],
                ['name' => 'Prosesor AMD Ryzen 9 7950X3D 16 Core 32 Thread 144MB Cache AM5 Processor', 'base_price' => 10999000, 'variants' => ['Box Unit 3-Year Warranty']],
                ['name' => 'Prosesor AMD Ryzen 5 7600X 6 Core 12 Thread 5.3GHz Boost Socket AM5', 'base_price' => 3899000, 'variants' => ['Box Unit Only']],
                ['name' => 'Prosesor AMD Ryzen 7 5700X3D 8 Core 16 Thread 100MB 3D V-Cache Socket AM4', 'base_price' => 3699000, 'variants' => ['Box Unit AM4 Upgrade King']],
                ['name' => 'Motherboard MSI MAG B650 TOMAHAWK WIFI Socket AM5 DDR5 PCIe 4.0', 'base_price' => 3899000, 'variants' => ['ATX Form Factor']],
                ['name' => 'Motherboard Gigabyte B650 AORUS ELITE AX V2 AM5 DDR5 Triple M.2 WiFi 6E', 'base_price' => 4199000, 'variants' => ['ATX Stealth Black', 'ICE White']],
                ['name' => 'Motherboard ASUS ROG Strix Z790-E Gaming WiFi II LGA1700 DDR5 PCIe 5.0', 'base_price' => 8499000, 'variants' => ['Full RGB Polymo']],
                ['name' => 'Casing PC Lian Li O11 Vision Dual Chamber 3-Sided Panoramic Tempered Glass', 'base_price' => 2499000, 'variants' => ['Snow White', 'Matte Black', 'Chrome Mirror']],
                ['name' => 'Casing PC NZXT H9 Flow Dual-Chamber Mid-Tower High Airflow Perforated', 'base_price' => 2699000, 'variants' => ['Matte Black', 'Matte White']],
                ['name' => 'Casing PC Fractal Design North Charcoal Black with Real Walnut Wood Front Panel', 'base_price' => 2899000, 'variants' => ['Walnut Wood Front / Mesh', 'Oak Wood Front / Glass']],
            ],

            // 4. Monitor Gaming
            4 => [
                ['name' => 'ASUS ROG Swift OLED PG32UCDM 32 Inch 4K UHD 240Hz 0.03ms QD-OLED Gaming Monitor', 'base_price' => 23999000, 'variants' => ['Standard Stand', 'Arm Ergonomic Edition']],
                ['name' => 'ASUS ROG Swift 360Hz PG27AQN 27 Inch 1440p Fast IPS Ultrafast Esports Monitor', 'base_price' => 15999000, 'variants' => ['Black']],
                ['name' => 'LG UltraGear 27GR95QE-B 27 Inch QHD OLED 240Hz 0.03ms G-Sync Compatible', 'base_price' => 12999000, 'variants' => ['Titan Black']],
                ['name' => 'LG UltraGear 34GP950G-B 34 Inch Curved UltraWide QHD Nano IPS 180Hz G-Sync Ultimate', 'base_price' => 17499000, 'variants' => ['Curved 1900R']],
                ['name' => 'Samsung Odyssey Neo G9 57 Inch Dual UHD 7680x2160 Mini-LED 240Hz 1000R 1ms', 'base_price' => 38999000, 'variants' => ['Giant Dual 4K']],
                ['name' => 'Samsung Odyssey G7 28 Inch 4K UHD IPS 144Hz 1ms HDMI 2.1 Smart Gaming Monitor', 'base_price' => 9499000, 'variants' => ['Matte Black']],
                ['name' => 'BenQ ZOWIE XL2566K 24.5 Inch 360Hz DyAc+ Fast TN Pro Esports Tournament Monitor', 'base_price' => 11499000, 'variants' => ['With Shield Flaps']],
                ['name' => 'BenQ ZOWIE XL2546K 24.5 Inch 240Hz DyAc Fast TN Esports Monitor FPS Valorant', 'base_price' => 7499000, 'variants' => ['Shield Side']],
                ['name' => 'AOC Gaming 24G2SP 24 Inch 165Hz IPS 1ms Adaptive-Sync Height Adjustable Stand', 'base_price' => 2199000, 'variants' => ['Black & Red Accent']],
                ['name' => 'ViewSonic Omni VX2728J-2K 27 Inch 2K QHD 180Hz Fast IPS Ergonomic Gaming Monitor', 'base_price' => 3499000, 'variants' => ['Pivot Stand Full Function']],
                ['name' => 'Xiaomi Curved Gaming Monitor G34WQi 34 Inch WQHD 180Hz 1500R FreeSync Premium', 'base_price' => 4599000, 'variants' => ['Curved Ultrawide 21:9']],
                ['name' => 'MSI MAG 274QRF QD E2 27 Inch WQHD Rapid IPS 180Hz Quantum Dot DCI-P3 98%', 'base_price' => 4799000, 'variants' => ['Black Ergonomic']],
            ],

            // 5. Keyboard Mechanical
            5 => [
                ['name' => 'Wooting 80HE Hall Effect Rapid Trigger 80% True 8000Hz Polling Custom Keyboard', 'base_price' => 4699000, 'variants' => ['Ghost Zinc Alloy Case', 'Standard Polycarbonate']],
                ['name' => 'SteelSeries Apex Pro TKL Gen 3 OmniPoint 3.0 Adjustable HyperMagnetic Keyboard', 'base_price' => 4199000, 'variants' => ['Wired Esports Edition', 'Wireless Quantum 2.0']],
                ['name' => 'Keychron Q1 Pro QMK/VIA Wireless Custom Mechanical Keyboard Full CNC Aluminium Gasket', 'base_price' => 3499000, 'variants' => ['Carbon Black - Red Switch', 'Silver Grey - Brown Switch', 'Shell White - Banana Switch']],
                ['name' => 'Keychron V1 Max QMK 75% 2.4GHz Wireless Acoustic Gasket Mount Mechanical Keyboard', 'base_price' => 1699000, 'variants' => ['Gateron Jupiter Red', 'Gateron Jupiter Brown']],
                ['name' => 'Aula F75 Tri-Mode Gasket 75% Mechanical Keyboard Pre-Lubed Reaper Switch HiFi Sound', 'base_price' => 749000, 'variants' => ['Ice Blue - Reaper Switch', 'Glacier Purple - LEOBOG Graywood', 'Retro White - TTC Crescent']],
                ['name' => 'MonsGeek M1 V3 QMK DIY Kit Full CNC Aluminium 75% South-Facing ARGB Gasket Mount', 'base_price' => 1499000, 'variants' => ['Anodized Black Kit', 'Anodized Silver Kit', 'Anodized Purple Kit']],
                ['name' => 'Ajazz AK820 Pro 75% Gasket TFT Color Smart Screen & Knob Tri-Mode Wireless Keyboard', 'base_price' => 799000, 'variants' => ['Flying Fish Switch', 'Gift Switch', 'Sea Salt Switch']],
                ['name' => 'VortexSeries GT-65 65% Aluminium Case Tri-mode Rapid Trigger Magnetic Switch Keyboard', 'base_price' => 1299000, 'variants' => ['Black HE', 'White HE']],
                ['name' => 'Akko MOD007B HE Hall Effect Magnetic Switch Tokyo R2 Theme Rapid Trigger 8K', 'base_price' => 2499000, 'variants' => ['Tokyo World Tour Pink', 'Santorini Blue']],
                ['name' => 'Corsair K70 MAX RGB Magnetic-Mechanical Keyboard MGX Adjustable Switches Palm Rest', 'base_price' => 3899000, 'variants' => ['Charcoal Steel']],
                ['name' => 'Razer BlackWidow V4 Pro Mechanical Gaming Keyboard Green/Yellow Switch Command Dial', 'base_price' => 3799000, 'variants' => ['Green Tactile Clicky', 'Yellow Linear Silent']],
                ['name' => 'Epomaker TH80 Pro 75% Hot-Swappable 2.4Ghz/Bluetooth 5.0 RGB Gasket Mechanical Keyboard', 'base_price' => 1199000, 'variants' => ['Budgerigar Switch', 'Flamingo Switch']],
            ],

            // 6. Mouse Gaming
            6 => [
                ['name' => 'Finalmouse UltralightX Lion Medium 31g Carbon Fiber Composite Wireless 8K Gaming Mouse', 'base_price' => 4599000, 'variants' => ['Phantom Matte', 'Cheetah Small 29g', 'Tiger Large 33g']],
                ['name' => 'WLmouse Beast X Max Magnesium Alloy 42g PAW3395 8000Hz Wireless Gaming Mouse', 'base_price' => 2499000, 'variants' => ['Silver Sword Metallic', 'Black Matte', 'Purple Violet']],
                ['name' => 'Ninjutso Sora V2 Wireless Gaming Mouse 39g SnappyFire Optical 8000Hz Polling', 'base_price' => 1799000, 'variants' => ['Ghost White', 'Midnight Black']],
                ['name' => 'Lamzu Maya 4K Wireless Optical Esports Mouse 45g PAW3395 Ergonomic Symmetric', 'base_price' => 1699000, 'variants' => ['Imperial Red', 'Cloud White', 'Charcoal Black']],
                ['name' => 'Lamzu Atlantis Mini Pro 4K Wireless Superlight 49g Huano Blue Pink Dot Switch', 'base_price' => 1599000, 'variants' => ['Polar White', 'Charcoal Black', 'Elegant Blue']],
                ['name' => 'VGN Dragonfly F1 Pro Max 49g Nordic 52840 PAW3395 Ultra-Fast Wireless Gaming Mouse', 'base_price' => 799000, 'variants' => ['Black', 'White']],
                ['name' => 'ATK Blazing Sky F1 Ultimate 38g Magnesium Base PAW3950 Ultra Sensor 8K Wireless', 'base_price' => 1499000, 'variants' => ['Matte Black 38g', 'Ghost White 38g']],
                ['name' => 'Scyrox V8 8000Hz Polling Rate PAW3950 Ultra Lightweight 36g Ergonomic Wireless Mouse', 'base_price' => 1199000, 'variants' => ['Yellow Canary', 'Black', 'White']],
                ['name' => 'Logitech G502 X PLUS LIGHTSPEED Wireless RGB Gaming Mouse LIGHTFORCE Hybrid Switch', 'base_price' => 2299000, 'variants' => ['Black Edition', 'White Edition']],
                ['name' => 'Razer Basilisk V3 Pro 35K Customizable Wireless Gaming Mouse Chroma RGB HyperScroll', 'base_price' => 2699000, 'variants' => ['Classic Black', 'Mercury White']],
                ['name' => 'SteelSeries Aerox 3 Wireless Ghost Edition Ultra-lightweight 68g Water Resistant RGB', 'base_price' => 1399000, 'variants' => ['Ghost Translucent White']],
                ['name' => 'Zaopin Z2 4K Dual Mode Hot-Swappable Switch Wireless Ergonomic Esports Mouse', 'base_price' => 999000, 'variants' => ['Black', 'White', 'Orange Sunset']],
            ],

            // 7. Audio & Headset
            7 => [
                ['name' => 'SteelSeries Arctis Nova Pro Wireless Multi-System ANC Hi-Res Infinity Power Headset', 'base_price' => 5999000, 'variants' => ['PC / PlayStation Edition', 'Xbox Edition']],
                ['name' => 'HyperX Cloud III Wireless Gaming Headset 120-Hour Battery Life DTS Headphone:X Spatial', 'base_price' => 2399000, 'variants' => ['Black & Red', 'All Black']],
                ['name' => 'HyperX Cloud Alpha Wireless Gaming Headset Rekor 300 Jam Baterai Dual Chamber Drivers', 'base_price' => 2799000, 'variants' => ['Black / Red Signature']],
                ['name' => 'Razer BlackShark V2 Pro 2023 Edition Esports Wireless HyperClear Super Wideband Mic', 'base_price' => 3199000, 'variants' => ['Black Pro', 'White Mercury']],
                ['name' => 'Logitech G PRO X 2 LIGHTSPEED Wireless Gaming Headset 50mm Graphene Drivers Blue VO!CE', 'base_price' => 3699000, 'variants' => ['Black', 'White', 'Magenta Pink']],
                ['name' => 'Sennheiser EPOS H6PRO Open-Back High Performance Acoustic Gaming Headset Detachable Mic', 'base_price' => 2799000, 'variants' => ['Sebring Black', 'Ghost White', 'Racing Green']],
                ['name' => 'Beyerdynamic DT 990 Pro 250 Ohm Open-Back Studio Reference & Competitive Gaming Headphone', 'base_price' => 2499000, 'variants' => ['Black Special Edition', 'Silver Grey']],
                ['name' => 'Sony INZONE H9 Wireless Noise Cancelling Gaming Headset 360 Spatial Sound for PS5 & PC', 'base_price' => 4499000, 'variants' => ['White']],
                ['name' => 'Audio-Technica ATH-GDL3 Open-Back High-Fidelity Gaming Headset Ultra-Lightweight 220g', 'base_price' => 1899000, 'variants' => ['Black', 'White']],
                ['name' => 'Moondrop Chu II DSP Type-C Hi-Fi In-Ear Monitor Gaming IEM with Microphone', 'base_price' => 349000, 'variants' => ['Type-C DSP Cable with Mic', '3.5mm Jack Standard']],
                ['name' => 'Tangzu Wan\'er S.G Studio Grade 10mm Dynamic Driver IEM Esports IEM Earphone', 'base_price' => 289000, 'variants' => ['Clear White with Mic', 'Jade Green with Mic', 'Obsidian Black']],
                ['name' => '7Hz Sonus Hybrid 1DD+1BA Hi-Fi In-Ear Monitor Audiophile & Competitive Gaming IEM', 'base_price' => 849000, 'variants' => ['Black Gunmetal', 'Silver Red']],
            ],

            // 8. Konsol & Handheld
            8 => [
                ['name' => 'PlayStation Portal Remote Player for PS5 Console 8 Inch 1080p 60FPS LCD Screen', 'base_price' => 3599000, 'variants' => ['Standard White Garansi Resmi']],
                ['name' => 'PlayStation VR2 Horizon Call of the Mountain Bundle 4K HDR OLED 120Hz Eye Tracking', 'base_price' => 9999000, 'variants' => ['VR2 Headset + Sense Controller + Horizon Code']],
                ['name' => 'Microsoft Xbox Series X 1TB Gaming Console 4K 120FPS Velocity Architecture Quick Resume', 'base_price' => 8999000, 'variants' => ['Carbon Black 1TB', 'Robot White 1TB Digital']],
                ['name' => 'Microsoft Xbox Series S 1TB Carbon Black Compact Next-Gen Digital Console', 'base_price' => 5899000, 'variants' => ['1TB Carbon Black']],
                ['name' => 'Nintendo Switch OLED Zelda Tears of the Kingdom Special Collector Limited Edition', 'base_price' => 5199000, 'variants' => ['Zelda Edition Maxsoft Resmi']],
                ['name' => 'Nintendo Switch Lite Handheld Console Portable Lightweight 5.5 Inch', 'base_price' => 2699000, 'variants' => ['Turquoise', 'Coral Pink', 'Blue', 'Yellow', 'Grey']],
                ['name' => 'Lenovo Legion Go 8.8 Inch QHD+ 144Hz AMD Ryzen Z1 Extreme Detachable TrueStrike Controllers', 'base_price' => 12499000, 'variants' => ['512GB NVMe SSD', '1TB NVMe SSD']],
                ['name' => 'MSI Claw A1M Handheld Gaming PC Intel Core Ultra 7 155H 16GB 512GB 120Hz Cooler Boost', 'base_price' => 11499000, 'variants' => ['Black']],
                ['name' => 'Anbernic RG35XX H Retro Handheld Gaming Console Linux Dual OS Horizontal 3.5 Inch IPS', 'base_price' => 899000, 'variants' => ['Transparent Black 64GB', 'Transparent Purple', 'White']],
                ['name' => 'Miyoo Mini Plus Portable Retro Gaming Handheld 3.5 Inch IPS Screen WiFi OnionOS Ready', 'base_price' => 749000, 'variants' => ['Retro Grey 64GB', 'Transparent Black', 'Pure White']],
            ],

            // 9. Smartphone Gaming
            9 => [
                ['name' => 'ASUS ROG Phone 8 Pro 24GB RAM 1TB Storage Snapdragon 8 Gen 3 AniMe Vision Display', 'base_price' => 18999000, 'variants' => ['Phantom Black Pro Bundle AeroActive Cooler']],
                ['name' => 'ASUS ROG Phone 8 16GB RAM 512GB Storage Snapdragon 8 Gen 3 165Hz AMOLED LTPO', 'base_price' => 14499000, 'variants' => ['Phantom Black', 'Rebel Grey']],
                ['name' => 'Nubia RedMagic 9 Pro+ 16GB 512GB Snapdragon 8 Gen 3 Built-in Cooling Fan 6500mAh', 'base_price' => 15999000, 'variants' => ['Sleet Black', 'Snowfall Silver', 'Cyclone Transparent']],
                ['name' => 'POCO F6 Pro 16GB 512GB Flagship Snapdragon 8 Gen 2 WQHD+ 120Hz Flow AMOLED 120W', 'base_price' => 8499000, 'variants' => ['Black Star', 'White Comet']],
                ['name' => 'POCO X6 Pro 5G 12GB 512GB Dimensity 8300 Ultra 120Hz CrystalRes 1.5K AMOLED', 'base_price' => 4799000, 'variants' => ['POCO Yellow Vegan Leather', 'Black', 'Grey']],
                ['name' => 'Infinix GT 20 Pro 5G 12GB 256GB Dimensity 8200 Ultimate Pixelworks Gaming Display Chip', 'base_price' => 4199000, 'variants' => ['Mecha Silver', 'Mecha Blue', 'Mecha Orange']],
                ['name' => 'Samsung Galaxy S24 Ultra 12GB 512GB Snapdragon 8 Gen 3 For Galaxy Titanium AI Ray Tracing', 'base_price' => 21999000, 'variants' => ['Titanium Gray', 'Titanium Black', 'Titanium Violet']],
                ['name' => 'iPhone 15 Pro Max 256GB Titanium A17 Pro Chip Hardware Ray Tracing Console Gaming', 'base_price' => 22499000, 'variants' => ['Natural Titanium', 'Blue Titanium', 'Black Titanium', 'White Titanium']],
            ],

            // 10. Streaming Gear
            10 => [
                ['name' => 'Elgato Stream Deck MK.2 15 Customizable LCD Keys Smart Studio Controller', 'base_price' => 2399000, 'variants' => ['Matte Black', 'White Edition']],
                ['name' => 'Elgato Stream Deck + with Rotary Dials Touch Strip LCD Keys Audio Control Station', 'base_price' => 3499000, 'variants' => ['Black', 'White']],
                ['name' => 'Elgato Wave:3 Premium Studio Quality USB Condenser Microphone Anti-Clipping Clipguard', 'base_price' => 2499000, 'variants' => ['Matte Black', 'White Edition']],
                ['name' => 'Elgato Facecam Pro True 4K60 Ultra HD PTZ Studio Webcam Sony STARVIS Sensor', 'base_price' => 4999000, 'variants' => ['Pro Mount Black']],
                ['name' => 'Elgato 4K X External Capture Card HDMI 2.1 4K144 HDR VRR Zero-Lag Passthrough USB-C', 'base_price' => 4499000, 'variants' => ['Standard Box']],
                ['name' => 'Shure SM7B Cardioid Dynamic Vocal Microphone Legendary Broadcast & Streaming Mic', 'base_price' => 6999000, 'variants' => ['Standard Black with Windscreen']],
                ['name' => 'Shure MV7+ Podcast Microphone Hybrid XLR/USB-C Multi-color LED Touch Panel DSP', 'base_price' => 4999000, 'variants' => ['Black', 'White']],
                ['name' => 'Rode PodMic USB Versatile Dynamic Broadcast Quality Microphone for Content Creators', 'base_price' => 3199000, 'variants' => ['Black', 'White']],
                ['name' => 'TC-Helicon GoXLR Broadcast Mixer 4-Channel Sampler Motorized Faders Voice Effects', 'base_price' => 6499000, 'variants' => ['GoXLR Full 4-Fader', 'GoXLR Mini']],
                ['name' => 'Elgato Key Light Air Professional 1400 Lumens Studio LED Desk Light App Control', 'base_price' => 2199000, 'variants' => ['Single Unit with Telescopic Pole']],
                ['name' => 'Razer Kiyo Pro Ultra Large 1/1.2 Inch Sony STARVIS 2 Sensor 4K 30FPS Streaming Webcam', 'base_price' => 4799000, 'variants' => ['Pro Ultra Black']],
            ],

            // 11. Kursi & Setup Meja
            11 => [
                ['name' => 'Secretlab TITAN Evo 2024 Series Stealth Neo Hybrid Leatherette Ergonomic Gaming Chair', 'base_price' => 8499000, 'variants' => ['Size Regular (170-189cm)', 'Size Small (<169cm)', 'Size XL (181-205cm)']],
                ['name' => 'Secretlab TITAN Evo SoftWeave Plus Fabric Breathable Arctic White Ergonomic Gaming Chair', 'base_price' => 8999000, 'variants' => ['Size Regular', 'Size XL']],
                ['name' => 'Herman Miller x Logitech G Embody Gaming Chair Pressure Distribution Cyan Accent', 'base_price' => 26999000, 'variants' => ['Black Cyan Sync Fabric', 'Galaxy Purple']],
                ['name' => 'Herman Miller Aeron Chair Remastered Ergonomic Chair Pellicle Mesh Forward Tilt', 'base_price' => 21999000, 'variants' => ['Size B Medium Mineral', 'Size B Medium Graphite']],
                ['name' => 'Sihoo Doro S300 Ergonomic Dual Dynamic Lumbar Support Aerospace Anti-Gravity Chair', 'base_price' => 9999000, 'variants' => ['Space Grey', 'Obsidian Black']],
                ['name' => 'Secretlab MAGNUS Pro XL Sit-to-Stand Metal Desk Integrated Power Cable Management', 'base_price' => 15999000, 'variants' => ['1.77m Desk + Black Leatherette MAGPAD']],
                ['name' => 'Oxihom Dual Motor Electric Standing Desk 140x70cm Solid Wood Ergonomic Desk', 'base_price' => 4599000, 'variants' => ['Walnut Top / Black Frame', 'Oak Natural / White Frame']],
                ['name' => 'BenQ ScreenBar Halo Wireless Controller LED Curved & Flat Monitor Light Eye-Care', 'base_price' => 2899000, 'variants' => ['Metallic Grey']],
                ['name' => 'Fantech OCA258 Breathable Ergonomic High Back Mesh Chair 3D Armrest Recline', 'base_price' => 1799000, 'variants' => ['Black Frame', 'White Grey Frame']],
            ],

            // 12. Storage RAM & SSD
            12 => [
                ['name' => 'Samsung 990 Pro 2TB NVMe M.2 PCIe 4.0 Internal SSD with Heatsink 7450MB/s PS5 Ready', 'base_price' => 3199000, 'variants' => ['1TB with Heatsink', '2TB with Heatsink', '4TB with Heatsink']],
                ['name' => 'Crucial T700 2TB PCIe 5.0 NVMe M.2 SSD Up to 12,400MB/s DirectStorage Ready Gen5', 'base_price' => 5299000, 'variants' => ['2TB with Premium Heatsink', '1TB Standard']],
                ['name' => 'WD_BLACK SN850X 2TB NVMe M.2 PCIe Gen4 Gaming SSD Game Mode 2.0 7300MB/s', 'base_price' => 2899000, 'variants' => ['2TB Heatsink RGB', '2TB Non-Heatsink']],
                ['name' => 'Kingston KC3000 2TB PCIe 4.0 NVMe M.2 SSD Graphene Aluminium Heat Spreader 7000MB/s', 'base_price' => 2599000, 'variants' => ['1TB KC3000', '2TB KC3000']],
                ['name' => 'Corsair Dominator Titanium RGB 64GB (2x32GB) DDR5 6000MHz CL30 AMD EXPO / XMP 3.0', 'base_price' => 5499000, 'variants' => ['First Edition White', 'Stealth Grey Black']],
                ['name' => 'G.SKILL Trident Z5 Neo RGB 64GB (2x32GB) DDR5 6000MHz CL30 AMD EXPO Dual Channel', 'base_price' => 3899000, 'variants' => ['Matte Black Dual Kit']],
                ['name' => 'Team T-Force Delta RGB 32GB (2x16GB) DDR5 6000MHz CL38 120-Degree Ultra-Wide Angle', 'base_price' => 1899000, 'variants' => ['White Edition', 'Black Edition']],
                ['name' => 'SanDisk Extreme Portable SSD V2 2TB External Type-C Rugged IP65 Drop Protection', 'base_price' => 2499000, 'variants' => ['2TB Monterey Blue', '2TB Black Carbon']],
            ],

            // 13. Cooling & Power Supply
            13 => [
                ['name' => 'NZXT Kraken Elite 360 RGB 2.36 Inch Wide-Angle LCD Display Liquid AIO Cooler', 'base_price' => 4999000, 'variants' => ['Matte Black', 'Matte White']],
                ['name' => 'ASUS ROG Ryujin III 360 ARGB Liquid Cooler 3.5 Inch Full Color LCD Asetek 8th Gen', 'base_price' => 5799000, 'variants' => ['Black ROG', 'White Edition']],
                ['name' => 'Corsair iCUE LINK H150i LCD Liquid CPU Cooler 360mm Magnetic Daisy Chain Fans', 'base_price' => 4899000, 'variants' => ['Black iCUE Link', 'White']],
                ['name' => 'DeepCool LT720 360mm High-Performance Liquid CPU Cooler Multidimensional Infinity Mirror', 'base_price' => 1899000, 'variants' => ['DeepCool LT720 Black', 'DeepCool LT720 White']],
                ['name' => 'Thermalright Peerless Assassin 120 SE Dual Tower 6 Heatpipes CPU Air Cooler', 'base_price' => 599000, 'variants' => ['Black ARGB', 'White ARGB', 'Standard Non-RGB']],
                ['name' => 'Noctua NH-D15 chromax.black Dual-Tower 140mm Premium Quiet CPU Air Cooler', 'base_price' => 1999000, 'variants' => ['All-Black Chromax']],
                ['name' => 'Lian Li UNI FAN TL LCD 120 Reverse Blade ARGB Daisy-Chain Fan 1.6 Inch LCD Display', 'base_price' => 1899000, 'variants' => ['Single Pack White', 'Single Pack Black', '3-Pack Controller Bundle']],
                ['name' => 'Seasonic Prime TX-1300 1300W 80 PLUS Titanium Full Modular ATX 3.0 PCIe 5.0 12VHPWR', 'base_price' => 6799000, 'variants' => ['1300W Titanium Flagship']],
                ['name' => 'Corsair RM1000x Shift 1000W 80 PLUS Gold ATX 3.0 Side Cable Interface Modular PSU', 'base_price' => 3199000, 'variants' => ['Black Modular']],
                ['name' => 'ASUS ROG Thor 1200W Platinum II OLED Power Display Aura Sync Modular Power Supply', 'base_price' => 5899000, 'variants' => ['1200W Platinum II']],
            ],

            // 14. Racing Sim & VR
            14 => [
                ['name' => 'Fanatec Gran Turismo DD Pro Direct Drive 8Nm Force Feedback Wheel Base Pedals Bundle', 'base_price' => 14999000, 'variants' => ['8Nm Boost Kit Bundle (PC / PS5)', 'Standard 5Nm Kit']],
                ['name' => 'Moza R9 V2 Direct Drive Wheel Base 9Nm Aviation Aluminium + CS V2P Steering Wheel', 'base_price' => 11499000, 'variants' => ['Base + Leather Wheel Bundle']],
                ['name' => 'Moza R5 Racing Bundle Direct Drive Simulator 5.5Nm Base Wheel Pedals Desk Clamp', 'base_price' => 7899000, 'variants' => ['Complete PC Bundle']],
                ['name' => 'Logitech G PRO Racing Wheel Direct Drive 11Nm TRUEFORCE Feedback Pro Load Cell Pedals', 'base_price' => 19999000, 'variants' => ['PC / PlayStation Edition']],
                ['name' => 'Logitech G29 Driving Force Racing Wheel Dual-Motor Force Feedback 900 Degree Rotation', 'base_price' => 3899000, 'variants' => ['Wheel + Pedals + Shifter Bundle', 'Standard Wheel + Pedals']],
                ['name' => 'Thrustmaster T300 RS GT Edition Racing Wheel 1080 Force Feedback Dual Belt System', 'base_price' => 6499000, 'variants' => ['GT Edition 3 Pedals']],
                ['name' => 'Meta Quest 3 512GB Next-Gen Mixed Reality VR Headset 4K+ Infinite Display Pancake Lens', 'base_price' => 10999000, 'variants' => ['512GB VR Bundle', '128GB Compact']],
                ['name' => 'Next Level Racing GT-Lite Foldable Cockpit Simulator Driving Rig Ergonomic Seat', 'base_price' => 3799000, 'variants' => ['Foldable Cockpit Stand']],
            ],

            // 15. Aksesoris & Modding
            15 => [
                ['name' => 'Artisan Hayate Otsu FX Soft XL Japanese Esports Gaming Mousepad High Precision', 'base_price' => 1199000, 'variants' => ['Black XL (49x42cm)', 'Wine Red XL']],
                ['name' => 'Artisan Ninja FX Zero XSoft XL Black Japan Import Anti-Humid Esports Mousepad', 'base_price' => 1199000, 'variants' => ['Black XSoft XL', 'Black Soft XL']],
                ['name' => 'Elgato Wave Mic Arm LP Low Profile Premium Aluminium Boom Arm with Cable Channels', 'base_price' => 1699000, 'variants' => ['Matte Black', 'White Edition']],
                ['name' => 'BenQ ZOWIE Camade II Esports Mouse Bungee Flexible Cable Management Zero Drag', 'base_price' => 449000, 'variants' => ['Esports Red / Black']],
                ['name' => 'Superglide 2 High Precision Aluminosilicate Glass Mouse Skates Speed & Control', 'base_price' => 389000, 'variants' => ['For Logitech G PRO X Superlight 2', 'For Razer Viper V3 Pro', 'For Razer DeathAdder V3 Pro']],
                ['name' => 'Custom Coiled Aviator Cable Type-C Mechanical Keyboard Double Sleeved Paracord Techflex', 'base_price' => 249000, 'variants' => ['Laser Cyberpunk Purple/Cyan', 'Matcha Green', 'All-White Pure', 'Carbon Black']],
                ['name' => 'Lian Li Strimer Plus V2 24-Pin ARGB Motherboard Extension Cable Wireless L-Connect 3', 'base_price' => 899000, 'variants' => ['24-Pin ATX Cable', 'Triple 8-Pin GPU Cable', '12VHPWR 16-Pin RTX 40']],
                ['name' => 'DualSense Edge Wireless Controller PlayStation 5 Custom Profiles Mappable Back Buttons', 'base_price' => 3499000, 'variants' => ['Original Sony Box']],
                ['name' => 'Krytox 205g0 Switch & Stabilizer Lubricant Mechanical Keyboard Modding Grease 10g', 'base_price' => 149000, 'variants' => ['10g Jar + Brush Kit', '20g Jar Pro']],
                ['name' => 'Thermal Grizzly Kryonaut Extreme High Performance Thermal Paste 2g Non-Conductive', 'base_price' => 289000, 'variants' => ['2g Syringe + Spatula Applicator']],
            ],
        ];

        // Brands / Modifiers for permutations
        $modifiers = [
            'Pro Edition',
            'Max Performance',
            'White Edition',
            'ARGB Special',
            'Elite Gaming',
            'Overclocked',
            'Tournament Spec',
            'Ultra Series',
            'Stealth Edition',
            'Signature Series',
        ];

        $totalAdded = 0;
        $categoryIds = array_keys($catalogTemplates);
        $catIndex = 0;
        $templatePointers = array_fill_keys($categoryIds, 0);

        DB::beginTransaction();

        try {
            while (Product::count() < $targetCount) {
                $catId = $categoryIds[$catIndex % count($categoryIds)];
                $templates = $catalogTemplates[$catId];
                $ptr = $templatePointers[$catId] % count($templates);
                $tpl = $templates[$ptr];
                $templatePointers[$catId]++;

                // Calculate cycle count to add unique suffix if cycling through templates
                $cycle = intdiv($templatePointers[$catId] - 1, count($templates));
                $prodName = $tpl['name'];
                if ($cycle > 0) {
                    $mod = $modifiers[($cycle - 1) % count($modifiers)];
                    $prodName = $tpl['name'].' - '.$mod.' V'.($cycle + 1);
                }

                $baseSlug = Str::slug($prodName);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                $sku = strtoupper(Str::slug(substr($prodName, 0, 8))).'-'.rand(1000, 9999);
                while (Product::where('sku', $sku)->exists()) {
                    $sku = strtoupper(Str::slug(substr($prodName, 0, 8))).'-'.rand(1000, 9999);
                }

                $shop = $shops->random();
                $price = $tpl['base_price'];
                $hasDiscount = rand(1, 100) <= 65; // 65% items have discount
                $discountPrice = $hasDiscount ? round($price * (1 - (rand(5, 20) / 100)), -3) : null;
                $stock = rand(8, 65);
                $weight = match ($catId) {
                    1 => rand(2100, 3400),
                    2 => rand(12000, 18000),
                    4 => rand(6500, 14000),
                    11 => rand(18000, 32000),
                    5 => rand(950, 1600),
                    6 => rand(150, 350),
                    default => rand(250, 1800),
                };

                $ratingAvg = round(4.75 + (rand(0, 25) / 100), 2);
                if ($ratingAvg > 5.0) {
                    $ratingAvg = 5.0;
                }

                $reviewsCount = rand(12, 180);
                $salesCount = rand(35, 850);
                $viewsCount = rand(300, 9500);

                $description = "{$prodName} dirancang khusus untuk gamer, kreator konten, dan tech enthusiast yang menginginkan performa maksimal tanpa kompromi. Diproduksi dengan material premium, teknologi mutakhir, serta didukung garansi resmi Indonesia.\n\nKeunggulan Utama:\n• Performa andal kelas turnamen dan gaming AAA\n• Durabilitas tinggi dan build quality kelas flagship\n• Kompatibilitas luas dan kemudahan instalasi\n• 100% Produk Original bergaransi resmi dari {$shop->name}";

                $product = Product::create([
                    'shop_id' => $shop->id,
                    'category_id' => $catId,
                    'name' => $prodName,
                    'slug' => $slug,
                    'sku' => $sku,
                    'description' => $description,
                    'price' => $price,
                    'discount_price' => $discountPrice,
                    'stock' => $stock,
                    'weight' => $weight,
                    'condition' => 'new',
                    'rating_avg' => $ratingAvg,
                    'reviews_count' => $reviewsCount,
                    'sales_count' => $salesCount,
                    'views_count' => $viewsCount,
                    'is_active' => true,
                ]);

                // Attach 1 to 3 images from the category pool
                $pool = $imagePool[$catId] ?? $imagePool[1];
                $primaryImg = $pool[array_rand($pool)];
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $primaryImg,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);

                if (count($pool) > 1 && rand(1, 100) <= 60) {
                    $secondaryImgs = array_diff($pool, [$primaryImg]);
                    if (! empty($secondaryImgs)) {
                        $secImg = $secondaryImgs[array_rand($secondaryImgs)];
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $secImg,
                            'is_primary' => false,
                            'sort_order' => 2,
                        ]);
                    }
                }

                // Attach variants if specified in template
                if (! empty($tpl['variants'])) {
                    foreach ($tpl['variants'] as $vIdx => $vVal) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => 'Varian',
                            'value' => $vVal,
                            'price_adjustment' => $vIdx === 0 ? 0 : round($price * (0.05 * $vIdx), -3),
                            'stock' => max(5, intdiv($stock, count($tpl['variants']))),
                            'sku' => $sku.'-V'.($vIdx + 1),
                        ]);
                    }
                }

                $totalAdded++;
                $catIndex++;
            }

            DB::commit();
            $finalCount = Product::count();
            $this->command->info("Success! Added {$totalAdded} new products. Total products in catalog now: {$finalCount}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('Error seeding products: '.$e->getMessage());
            throw $e;
        }
    }
}

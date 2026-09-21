# Total UI Transformation: Modern Violet Floating App Canvas & Sleek Marketplace Experience

This plan details the complete UI overhaul of the e-commerce storefront to match the modern, clean, rounded aesthetic shown in the user-provided reference design (`media_1789941395416.webp`).

## User Review Required

> [!IMPORTANT]
> - **Visual Identity**: The new design introduces a rich purple/violet gradient canvas (`#6D28D9` to `#4338CA` / `#5B3BF5`) with a floating, high-contrast white rounded application shell (`rounded-[32px]` to `rounded-[40px]`).
> - **Category Pill Bar**: Prominently displays horizontal scrollable category pills with an active purple pill indicator.
> - **Sidebar Filters**: Features a price range histogram/waveform graphic, dual price badge inputs, star rating selector, brand filters, and delivery options toggle.
> - **Product Cards**: Ultra-clean soft grey/white rounded cards (`rounded-[26px]`), circular wishlist heart button, badge tags ("Top item" / "Flash Sale"), centered product images, and vibrant pill price/cart buttons.
> - **Backwards Compatibility**: All existing functionality (cart count, wishlist toggling, authentication menus, search autocomplete, category filters, and address checkout flows) is 100% preserved.

---

## Proposed Changes

### Styling & Theme Configuration

#### [MODIFY] [resources/css/app.css](file:///d:/ecommerce/resources/css/app.css)
- Extend `@theme` with the modern violet/purple palette:
  - `--color-brand-50` through `--color-brand-700` (`#5B3BF5`, `#4F2DE9`, `#7B5BF2`, etc.).
  - Add soft surface colors (`--color-surface: #F8F9FD`, `--color-surface-card: #F4F5FB`).
- Custom scrollbar utilities for smooth horizontal category pill navigation.
- Subtle glassmorphism and card elevation shadows.

---

### Layout & Global Shell

#### [MODIFY] [resources/views/layouts/app.blade.php](file:///d:/ecommerce/resources/views/layouts/app.blade.php)
- Transform the body into an immersive canvas with violet/purple gradient backdrop on medium-to-large displays (`bg-gradient-to-br from-[#4C1D95] via-[#5B3BF5] to-[#3B0764] md:p-6 lg:p-8 xl:p-10`).
- Wrap the main application inside a floating white rounded card shell:
  `max-w-[1640px] mx-auto bg-white rounded-none md:rounded-[36px] lg:rounded-[44px] shadow-2xl shadow-indigo-950/40 min-h-[95vh] flex flex-col overflow-hidden`
- Modern Top Navigation Bar:
  - Sleek **KGZ** brand badge typography with modern font weight.
  - Centered rounded-full pill search bar (`rounded-full bg-slate-100/90 border border-slate-200/80 px-5 py-2.5`) with instant suggestion tags.
  - Header right navigation icons:
    - **Orders** link with icon.
    - **Favourites / Wishlist** button with badge counter.
    - **Cart** button with purple counter badge (`bg-[#5B3BF5] text-white`).
    - User avatar badge with full profile dropdown (preserving seller/admin switcher & logout).
- Seamless Category Pill Bar integrated right beneath the header:
  - Horizontal scrollable pills: "Semua Kategori", "🔥 Deals", "💻 Laptop & PC", "⌨️ Keyboard", "🖱️ Mouse", "🎧 Audio", "📺 Monitor", "🎮 Konsol", "🏎️ Sim Racing".
  - Active category highlighted with `bg-[#5B3BF5] text-white shadow-md shadow-purple-500/25 rounded-full`.
- Clean minimal footer embedded within the base of the white application card.

---

### Product Card Component

#### [MODIFY] [resources/views/components/product-card.blade.php](file:///d:/ecommerce/resources/views/components/product-card.blade.php)
- Redesign the card into a modern rounded-3xl (`rounded-[24px]` / `rounded-[28px]`) soft-surfaced card (`bg-[#F8F9FD]` hover:bg-white hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300`).
- Top elements:
  - Left: "Top item" / "Flash Sale" badge in vibrant amber or rose rounded pill.
  - Right: Floating circular heart wishlist button (`w-9 h-9 rounded-full bg-white shadow-xs text-slate-400 hover:text-rose-500 hover:scale-110`).
- Center:
  - High-res padded product image with subtle scale transition on hover.
- Information section:
  - Product title in clean bold font.
  - Star rating with gold star and review count `★ 4.9 (120)`.
  - Store name with verified badge.
- Bottom Action:
  - Pill-shaped button or price badge: `[ 🛒 Rp xxx.xxx ]` with purple gradient accent (`bg-[#5B3BF5] hover:bg-[#4B2AE0] text-white font-black text-xs px-4 py-2.5 rounded-full flex items-center justify-between shadow-md shadow-purple-500/20`).

---

### Catalog & Filter Experience

#### [MODIFY] [resources/views/products/index.blade.php](file:///d:/ecommerce/resources/views/products/index.blade.php)
- Left Sidebar Filter (matching reference design):
  - **Price Range Filter**:
    - Stylized purple bar histogram/waveform visualization representing price distribution.
    - Dual price input pills: `[ Rp Min ]` to `[ Rp Max ]`.
  - **Rating Filter**:
    - 5-star preview with gold stars and count.
  - **Delivery Options**:
    - Segmented toggle pill: `[ Reguler | Instan ]`.
  - **Brands**:
    - Checkbox list with brand logos / badges (ASUS ROG, Logitech, Razer, Corsair, etc.).
- Right Column:
  - Header with item count and clean dropdown sort.
  - Responsive 3-to-4 column product grid rendering the revamped `x-product-card`.
  - Clean pagination styling with rounded pill numbers.

#### [MODIFY] [resources/views/home.blade.php](file:///d:/ecommerce/resources/views/home.blade.php)
- Hero banner section styled with large rounded borders (`rounded-[32px]`) to match the new fluid geometry.
- Section headers with category links and view-all buttons in the new violet theme.

---

## Verification Plan

### Automated Tests
- Run full PHPUnit suite to verify no regressions in routes, cart, checkout, or auth:
  ```bash
  php artisan test
  ```
- Run Laravel Pint formatter to maintain PSR-12 and clean code standards:
  ```bash
  vendor/bin/pint --format agent
  ```

### Asset Compilation & Cache Clearing
- Compile frontend assets with Vite:
  ```bash
  npm run build
  ```
- Clear compiled views to ensure immediate rendering:
  ```bash
  php artisan view:clear
  ```

### Visual Verification
- Verify layout structure, responsive scaling on desktop and mobile.
- Verify search pill, category pills, filter histogram, and product card pill buttons.

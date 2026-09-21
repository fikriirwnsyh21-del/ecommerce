# Implementation Plan: Dramatic Hero Landing View with Previous Violet Tech Theme

This plan outlines the implementation of the new hero landing view inspired by the user's reference image (`media_1789942346712.png`), translated into our previous purple/violet gaming & tech theme (`#5B3BF5` / `#3B168C` / `#1E0C4F`).

## User Review Required

> [!IMPORTANT]
> - **Aesthetic Translation**: The crimson butcher-market aesthetic from the reference image will be translated into an ultra-premium **violet/purple tech & gaming flagship hero banner**:
>   - Deep violet gradient stage backdrop with ambient purple backlight glow.
>   - Colossal bold headline in the background ("GAMING" / "MARKET") intertwined with a 3D flagship centerpiece product (e.g. flagship ROG / RTX 4090 / PS5 setup).
>   - Floating dark glass pill navigation bar (`::`, Home, Katalog, Rakit PC, Flash Sale, Search).
>   - Metrics: **4.9 ⭐⭐⭐⭐⭐ Customer Rating** on the left, **5K+ Gamers Avatar Stack** on the right.
>   - Floating glass pill badges around the centerpiece (`[ ✔️ 100% Original ]`, `[ 🛡️ Garansi Resmi ]`).
>   - Bottom-left "Get in Touch" contact buttons and bottom-right crisp white pill button `[ Shop Gaming Gear 🛒 ]`.
> - **Preserved Functionality**: All existing marketplace sections (Flash Sale with live timer, category pills, trust bar, best sellers, PC builder, and footer) remain fully functional and beautifully integrated directly beneath the hero canvas.

---

## Proposed Changes

### Home Page Hero Transformation

#### [MODIFY] [resources/views/home.blade.php](file:///d:/ecommerce/resources/views/home.blade.php)
- Replace the current carousel with the dramatic new hero landing section:
  1. **Hero Container**:
     - Giant rounded canvas (`rounded-[36px]` / `rounded-[40px]`) with rich deep purple-to-violet radial lighting (`bg-gradient-to-br from-[#451B9E] via-[#2E1065] to-[#160636]`), dark textured surface at the bottom with subtle reflection.
  2. **Internal Floating Top Bar**:
     - Left: Circular glowing brand badge ("KGZ").
     - Center: Floating dark glassmorphic pill bar with menu links ("Home", "Katalog", "Flash Sale", "Rakit PC") and purple search trigger.
     - Right: "Akun / Log In 👤" pill button.
  3. **Background Colossal Typography**:
     - Top subtitle: `ULTIMATE TECH & GEAR`
     - Colossal headline: `MARKET` (or `GAMING`) in giant bold distressed/stencil white lettering (`text-7xl sm:text-9xl md:text-[140px] lg:text-[180px] font-black opacity-90 tracking-tighter`).
  4. **Dramatic Centerpiece**:
     - Hero gaming setup / flagship gear resting on the surface with realistic shadow and purple neon rim light, positioned right in the center, overlapping the giant text.
  5. **Interactive Floating Glass Badges**:
     - Floating pill `[ ✔️ 100% Original Tech ]` and `[ 🛡️ Garansi Distributor Resmi ]` anchored near the centerpiece.
  6. **Left & Right Stats**:
     - Left: `4.9` + 5 Gold Stars + "Customer Rating".
     - Right: `5K+` + Overlapping customer avatars stack + "Satisfied Gamers".
  7. **Bottom Controls**:
     - Left: `Get in Touch:` with circular icons (Location & WhatsApp).
     - Right: High-contrast white CTA pill button `[ Shop Gaming Gear 🛒 ]` linking directly to catalog.

---

## Verification Plan

### Automated Tests
- Run PHPUnit tests to ensure no regressions:
  ```bash
  php artisan test
  ```
- Run Laravel Pint formatter:
  ```bash
  vendor/bin/pint --format agent
  ```

### Asset Compilation & Cache Clearing
- Recompile frontend assets:
  ```bash
  npm run build
  ```
- Clear compiled views:
  ```bash
  php artisan view:clear
  ```

### Visual Verification
- Inspect the landing hero view across desktop, tablet, and mobile breakpoints to verify proper responsiveness, text overlapping, and pill button interactions.

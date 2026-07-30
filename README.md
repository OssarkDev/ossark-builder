# Ossark Builder — WordPress Theme Boilerplate

A modern, reusable WordPress theme boilerplate built with **ACF blocks**, **Webpack 5**, and a **component-based architecture**. Designed as a starting point for custom WordPress projects.

---

## Requirements

- **Node.js** 22+
- **npm** 10+
- **WordPress** 6.x
- **ACF Pro** plugin

---

## Getting Started

```bash
# Install dependencies
npm install

# Development (watch mode)
npm run dev

# Development build (with source maps)
npm run build:dev

# Production build (minified & compressed)
npm run build
```

> **Always run `npm run build` before deploying** — this minifies and compresses all assets.

---

## Folder Structure

```
assets/              → Images, icons, fonts, block previews, form templates
components/
  blocks/            → ACF Gutenberg block templates
  parts/             → Reusable PHP partials (header, footer, etc.)
config/              → Webpack & Babel configuration
include/             → PHP functionality modules
src/
  main.js            → Entry point (vendor imports, Lenis, SCSS)
  js/
    index.js          → Imports & calls all JS components
    components/
      animations/     → scroll, splitLines, splitText, numbers, typewriter, parallax, lottie
      blocks/         → slider, video, map
      parts/          → hamburger, backToTop, contact, scrollToAnchor, etc.
  scss/
    index.scss        → Main SCSS entry
    include/          → Variables, mixins, shared, layout, reset, fonts, animations
    components/       → Block & part styles
templates/           → WordPress page templates
acf-json/            → ACF field group JSON (auto-synced)
```

### Key Conventions

- **Images, icons & fonts** go in `assets/`
- **JS & SCSS imports** are added to their respective `index.js` / `index.scss` files
- **Vendor imports** go in `main.js` only — do **not** duplicate in `index.js`
- **Each JS component** should have an early-exit guard if its DOM elements are absent

---

## Layout System

The layout uses a Bootstrap-like grid: `section → container → row → col`

```html
<section class="component-name">
  <div class="container">
    <div class="row">
      <div class="col-6-offset-2">Content</div>
    </div>
  </div>
</section>
```

### Column Classes

| Breakpoint | Class prefix |
|-----------|-------------|
| Desktop   | `col`       |
| Tablet    | `col-md`    |
| Mobile    | `col-sm`    |
| HD Screen | `col-lg`    |

- Offset: `col-6-offset-2` (6 columns wide, offset by 2 from start)
- Configure column count, section & container paddings in `_variables.scss`

### Utility Classes

- Spacing: `.mt-48` → `margin-top: 48px`, `.pb-48` → `padding-bottom: 48px`
- Responsive variations: configure sizes in `$laptop-change` & `$mobile-change` in `_variables.scss`

---

## SCSS

### Shared Imports

Variables and mixins are available in any SCSS file via:

```scss
@use "shared" as *;
```

- `_shared.scss` forwards `_variables.scss` and `_mixins.scss`
- Webpack `loadPaths` resolves `src/scss/include/` automatically

### Mixins

- `+max-screen($size)` — max-width breakpoint
- `+min-screen($size)` — min-width breakpoint

---

## JavaScript

### Component Pattern

All components use vanilla JS with an early-exit guard:

```js
export function myComponent() {
    const elements = document.querySelectorAll('.my-selector');
    if (!elements.length) return;
    // component logic
}
```

jQuery is **only** used where required by dependencies (Slick Carousel, Google Maps ACF).

### Scroll Animations

Use `data-scroll` for IntersectionObserver-powered animations:

```html
<section data-scroll>                                    <!-- Adds .in-view class -->
<section data-scroll data-scroll-call="myFunction">      <!-- Triggers window.myFunction() -->
<section data-scroll data-scroll-offset="100">           <!-- Custom trigger offset -->
<section data-scroll data-scroll-switch>                 <!-- Toggles .in-view on/off -->
```

Functions for `data-scroll-call` must be on the `window` object.

### Animation Components

| Component | Selector / Attribute | Description |
|-----------|---------------------|-------------|
| `scroll.js` | `[data-scroll]` | IntersectionObserver, adds `.in-view` |
| `splitLines.js` | `.split-lines` | Splits text into animated lines (re-runs on resize) |
| `splitText.js` | `.split-text` | Splits words into animated wrappers |
| `numbers.js` | `[data-animate-number]` | Animates numbers on scroll |
| `typewriter.js` | `.typewriter` | Typewriter effect on `.in-view` |
| `parallax.js` | `.parallax` | Parallax with `data-parallax-speed`, `data-parallax-direction`, `data-parallax-root` |
| `lottie.js` | `.lottie` | Lottie animations with `data-path` |

### Libraries

- **Lenis** — smooth scrolling (initialized in `main.js`)
- **Slick Carousel** — sliders (imported in `main.js`)
- **Lottie Web** — animations
- **jQuery** — externalized from CDN, used only by Slick & Maps

---

## ACF Blocks

Blocks are colocated under `components/blocks/{slug}/` and auto-registered by `ossark_register_blocks_from_json()` in `include/acf.php`.

### Registering a New Block

1. Create `components/blocks/{slug}/block.json` with `apiVersion: 3`, `name: "acf/{slug}"`, and an `acf` section pointing at `render.php`.
2. Create `components/blocks/{slug}/render.php` for the template.
3. Add ACF fields — they auto-sync to `acf-json/`.
4. Add the slug (e.g. `acf/my-block`) to the whitelist array in the `allowed_block_types_all` filter.

### Block Template Structure

```php
<?php
$field = get_field( 'field_name' );
?>
<section data-scroll class="my-block">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ( $field ) : ?>
                    <?= $field; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
```

---

## PHP Includes

| File | Purpose |
|------|---------|
| `acf.php` | Block registration, categories, options pages, allowed blocks |
| `enqueue_scripts.php` | jQuery CDN, main.min.js, vendors, CSS |
| `theme_functions.php` | Utility functions (`get_svg`, `console_log`, `excerpt`, `returnYoutubeUrl`) |
| `ui_kit.php` | `get_image()`, `get_button()`, `get_part()`, `get_block()` |
| `custom_post_types.php` | Custom post type definitions |
| `custom_taxonomies.php` | Custom taxonomies (commented out by default) |
| `cleanup.php` | WP bloat removal, conditional CF7 script loading |
| `setup_theme.php` | Theme config, image settings, mime types, admin cleanup |
| `headers.php` | Security: CSP with nonces, SRI, universal headers |
| `coming_soon.php` | Coming soon mode via ACF toggle |
| `debug.php` | Debug mode toggle |
| `woocommerce.php` | WooCommerce support (commented out by default) |

---

## Security

- **CSP headers** with nonce-based inline script control
- **SRI hashes** for CDN scripts (jQuery)
- **Universal headers**: X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy
- **Output buffer** nonce injection for inline scripts
- Configured in `include/headers.php`

---

## Webpack

- Webpack 5 with automatic vendor code splitting
- SASS compilation via `sass-loader` v16 (modern Dart Sass API)
- Source maps in development only
- Production builds are minified and compressed
- jQuery externalized for WordPress compatibility
- `loadPaths` configured for SCSS shared imports
```

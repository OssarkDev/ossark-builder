# Ossark Builder WordPress Theme - Copilot Instructions

## Architecture Overview
This is a modern WordPress theme boilerplate built with **ACF blocks**, **Webpack bundling**, and **component-based architecture**. The theme uses a Bootstrap-like grid system with custom SCSS/JS workflow. It is designed as a reusable starting point for WordPress projects.

### Key Directories
- `components/blocks/` - ACF Gutenberg blocks (PHP templates)
- `components/parts/` - Reusable PHP partials (header, footer, etc.)
- `src/js/components/` - Modular JavaScript components (vanilla JS preferred)
- `src/scss/` - SASS styles with component organization
- `include/` - PHP functionality modules
- `acf-json/` - ACF field definitions (auto-synced)
- `assets/` - Images, icons, fonts, block previews, form templates
- `templates/` - WordPress page templates
- `config/` - Webpack & Babel config

## Development Workflow

### Build Commands
- `npm run dev` - Development mode with file watching
- `npm run build` - Production build (required before deployment)
- `npm run build:dev` - Development build with source maps
- Entry point: `src/main.js` → outputs to `dist/main.min.js`

### File Structure Patterns
- **All images/icons/fonts** go in `assets/` folder
- **JS/SCSS imports** added to corresponding `index.js`/`index.scss` files
- **Vendor imports** go in `main.js` only (e.g., `import 'slick-carousel'`) — do NOT duplicate in `index.js`
- **Each JS component** should have an early-exit guard if its DOM elements are absent

## Grid System & Layout

### Bootstrap-like Structure
```html
<section class="component-name">
  <div class="container">
    <div class="row">
      <div class="col-6-offset-2">Content</div>
    </div>
  </div>
</section>
```

### Responsive Column Classes
- `col` (desktop), `col-sm` (mobile), `col-md` (tablet), `col-lg` (hd-screen)
- Offset syntax: `col-6-offset-2` (6 columns wide, offset by 2)

### Utility Classes
- Spacing: `.mt-48` (margin-top: 48px), `.pb-48` (padding-bottom: 48px)
- Responsive variations available - configure in `_variables.scss`

## ACF Block System

### Block Registration Pattern
Blocks auto-register from array in `include/acf.php`:
```php
$blocks = [
    'thank-you' => 'Thank You',
    // Add new blocks: 'block-folder-name' => 'Block Title'
    // Template must exist at components/blocks/{block-folder-name}.php
];
```
- Templates: `components/blocks/{block-name}.php`
- ACF fields: Auto-sync to `acf-json/` directory
- Allowed blocks per post type configured in `allowed_block_types_all` filter

### Block Template Structure
```php
<?php 
if (get_field('is_preview')) { 
    previewImage($block['name']);
    return;
}
$field = get_field('field_name');
?>
<section data-scroll class="block-name">
    <!-- Bootstrap grid structure -->
</section>
```

## JavaScript Architecture

### Component System
- Entry: `src/main.js` imports vendor libs + SCSS, initializes Lenis, calls `runAfterDomLoad()`
- `src/js/index.js` imports and calls all component functions
- Pattern: Each component exports a named function with an early-exit guard
- **Vanilla JS preferred** — only use jQuery where required (Slick Carousel, Google Maps ACF)

### Component Pattern
```js
export function myComponent() {
    const elements = document.querySelectorAll('.my-selector');
    if (!elements.length) return;
    // ... component logic
}
```

### Scroll Animation System
Use `data-scroll` attribute for intersection observer (see `scroll.js`):
```html
<section data-scroll>Auto gets 'in-view' class</section>
<section data-scroll data-scroll-call="myFunction">Triggers custom function</section>
<section data-scroll data-scroll-offset="100">Custom trigger offset</section>
<section data-scroll data-scroll-switch>Toggles in-view on/off</section>
```
**Critical**: Functions for `data-scroll-call` must be on `window` object: `window.myFunction = () => {}`

### Animation Components
- `scroll.js` - IntersectionObserver, adds `.in-view` class, supports `data-scroll-call`
- `splitLines.js` - Splits text into animated lines (auto re-runs on resize)
- `splitText.js` - Splits words into animated wrappers
- `numbers.js` - Animates numbers on scroll (use `data-animate-number` attribute)
- `typewriter.js` - Typewriter effect triggered by `.in-view` class
- `parallax.js` - Parallax scrolling with `data-parallax-speed`, `data-parallax-direction`, `data-parallax-root`
- `lottie.js` - Lottie animations on `.lottie` elements with `data-path`

### Key Libraries
- **Lenis** for smooth scrolling (initialized in `main.js` inside DOMContentLoaded)
- **Slick Carousel** for sliders (imported in `main.js` only)
- **Lottie** for animations
- **jQuery** externalized from CDN (available as `$`, used only by Slick & Maps)

## SCSS Organization

### Shared Imports
Variables and mixins are available globally via `@use "shared" as *;` in any SCSS file.
- `_shared.scss` forwards `_variables.scss` and `_mixins.scss`
- Webpack `loadPaths` resolves `src/scss/include/` automatically

### Variable System
Configure in `src/scss/include/_variables.scss`:
- Breakpoints: `$mobile: 576px`, `$tablet: 992px`, `$laptop: 1440px`
- Grid columns and spacing for each breakpoint
- Color palette and typography

### Mixins Available
- `+max-screen($size)` - max-width breakpoint
- `+min-screen($size)` - min-width breakpoint

## WordPress Integration

### Functions.php Structure
Modular includes from `include/` directory:
- `acf.php` - Block registration, categories, options pages, allowed blocks
- `enqueue_scripts.php` - Asset loading (jQuery CDN, main.min.js, vendors, CSS)
- `theme_functions.php` - Utility functions
- `custom_post_types.php` - CPT definitions
- `custom_taxonomies.php` - Custom taxonomies (commented out by default)
- `cleanup.php` - WP bloat removal, conditional CF7 script loading
- `setup_theme.php` - Theme config, image settings, SVG/JSON/WebP uploads, admin cleanup
- `headers.php` - Security headers (CSP with nonces, SRI, universal headers)
- `ui_kit.php` - Reusable UI components (`get_button`, `get_part`, `get_block`, `previewImage`)
- `woocommerce.php` - WooCommerce support (commented out by default)
- `coming_soon.php` - Coming soon mode via ACF toggle
- `debug.php` - Debug mode toggle

### Helper Functions
- `get_svg($name)` - Load SVGs from `assets/`
- `console_log($data)` - PHP debugging to browser console
- `previewImage($name)` - ACF block previews
- `get_button($button, $classes)` - Render ACF link as button
- `get_part($template, $args)` - Include a part template with args
- `get_block($template, $args)` - Include a block template with args
- `returnYoutubeUrl($url)` - Convert any YouTube URL format to embed URL
- `excerpt($limit, $post_id)` - Truncated excerpt
- `get_acf_block_data($post_id, $block_name, $field_name)` - Read ACF block field from post content

### Security
- CSP headers with nonce-based script control (`include/headers.php`)
- SRI hashes for CDN scripts
- Universal security headers (X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy)
- Output buffer nonce injection for inline scripts

## Node/Webpack Config
- **Node 22+** required
- Webpack 5 splits vendor code automatically
- SASS compilation with modern API (`sass-loader` v16 + Dart Sass)
- Source maps in development only (`devtool: 'source-map'` in dev, `false` in prod)
- File compression for production builds
- jQuery externalized for WordPress compatibility
- `loadPaths` configured for SCSS shared imports
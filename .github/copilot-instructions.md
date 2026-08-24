# Ossark Builder WordPress Theme - Copilot Instructions

## Architecture Overview
This is a modern WordPress theme boilerplate built with **ACF blocks**, **Webpack bundling**, and **component-based architecture**. The theme uses a Bootstrap-like grid system with custom SCSS/JS workflow. It is designed as a reusable starting point for WordPress projects.

### Key Directories
- `blocks/` - ACF Gutenberg blocks (PHP templates)
- `components/` - Reusable PHP partials (header, footer, etc.)
- `src/js/modules/` - Modular JavaScript (animations, ui, vendor — vanilla JS preferred)
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

### Block Registration (block.json auto-discovery)
Blocks are colocated under `blocks/{slug}/` with a `block.json` manifest. `ossark_register_blocks_from_json()` in `include/acf.php` globs the folder on `init` and calls `register_block_type()` on each `block.json`. To add a block:
1. Create `blocks/{slug}/block.json` (`apiVersion: 3`, `name: "acf/{slug}"`, `acf.mode: "auto"`, `acf.renderTemplate: "{slug}.php"`).
2. Create `blocks/{slug}/{slug}.php` — the render template.
3. (Optional) Create `blocks/{slug}/_{slug}.scss` — auto-imported via `require.context` in `src/main.js` and `src/editor.js`.
4. Add the slug to the whitelist in the `allowed_block_types_all` filter in `include/acf.php`.
- ACF fields: Auto-sync to `acf-json/` directory.
- Scaffold with `npm run make:block -- {slug} ["Title"] [--js]`.

### PHP Parts (components/)
Parts mirror the blocks pattern: each part is colocated under `components/{slug}/` with `{slug}.php` + an auto-imported `_{slug}.scss` (frontend + editor via `require.context` in `src/main.js`/`src/editor.js`). Render with `get_part( '{slug}' )`. Scaffold with `npm run make:part -- {slug} ["Title"]`.

### Block Template Structure
```php
<?php
$field = get_field( 'field_name' );
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
- Breakpoints: `$mobile: 768px`, `$tablet: 1024px`, `$laptop: 1440px`
- Grid columns and spacing for each breakpoint
- Color palette and typography

### Mixins Available
- `+max-screen($size)` - max-width breakpoint
- `+min-screen($size)` - min-width breakpoint

## WordPress Integration

### Functions.php Structure
Modular includes from `include/` directory:
- `acf.php` - Block registration, categories, options pages, allowed blocks
- `editor_template_parts.php` - Auto-detects and renders `get_part()` calls in single/page templates inside Gutenberg canvas
- `enqueue_scripts.php` - Asset loading (jQuery CDN, main.min.js, vendors, CSS)
- `theme_functions.php` - Utility functions
- `custom_post_types.php` - CPT definitions
- `custom_taxonomies.php` - Custom taxonomies (commented out by default)
- `cleanup.php` - WP bloat removal, conditional CF7 script loading
- `setup_theme.php` - Theme config, image settings, SVG/JSON/WebP uploads, admin cleanup
- `headers.php` - Security headers (CSP with nonces, SRI, universal headers)
- `ui_kit.php` - Reusable UI components (`get_image`, `get_button`, `get_part`, `get_block`)
- `woocommerce.php` - WooCommerce support (commented out by default)
- `coming_soon.php` - Coming soon mode via ACF toggle
- `debug.php` - Debug mode toggle

### Helper Functions
- `get_svg($name)` - Load SVGs from `assets/icons/`
- `console_log($data)` - PHP debugging to browser console
- `get_image($image, $args)` - Render an ACF image field with attributes
- `get_button($button, $classes)` - Render ACF link as button
- `get_part($template, $args)` - Include a part template from `components/{template}/{template}.php`
- `get_block($template, $args)` - Include a block template from `blocks/{template}/{template}.php`
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
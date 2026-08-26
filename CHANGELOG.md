# Changelog & Upgrade Guide

All notable changes to the OSSARK Builder WordPress theme are documented in this file.
This project follows [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

Theme version is tracked in `style.css` (`Version: X.XX`) and `package.json` (`"version": "X.Y.Z"`).

---

## How to Upgrade Older Websites Using this Changelog

1. **Check the Current Version**: Check `style.css` in the target website (e.g. `Version: 2.10` or `Version: 3.10`).
2. **Review Step-by-Step Milestones**: Identify the versions between the site's current version and the latest version.
3. **Follow the Migration Recipes**: Each milestone below provides an explicit **Migration Recipe** detailing the exact files to copy, code snippets to update, and build commands to run.
4. **AI-Assisted Upgrade**: You can copy this `CHANGELOG.md` into an older project repository and prompt GitHub Copilot:
   > *"Please read CHANGELOG.md and upgrade this theme from version X.XX to 3.21 by following the migration recipes in order."*

---

## [3.21.0] - 2026-08-24 (Kelbuild Update)

### Added
- **Editor Template Parts Live Preview**:
  - `include/editor_template_parts.php`: Tokenizes single/page PHP templates and renders `get_part(...)` calls situated before and after `the_content()` directly in the Gutenberg canvas.
  - `src/js/modules/editor/templateParts.js`: Injects template parts preview markup into `iframe[name="editor-canvas"]` dynamically.
  - `ossark_get_editor_template_parts` AJAX endpoint with dynamic refresh on template changes.
- **Draggable Block Inspector Sidebar**:
  - `assets/editor.css` & `assets/editor.js`: Added 6px resize grab handle on the left edge of `.interface-interface-skeleton__sidebar` with `localStorage` width persistence (`--ossark-inspector-width`).
- **SCSS Helpers**: Added `.pos-abs-cover`, `.pos-rel`, `.pos-center`, `.overflow-hidden`, `.w-100`, `.h-100`, `.fit-cover` to `src/scss/include/_helpers.scss`.

### Changed
- **Slick Slider Teardown in Editor**: Updated `src/js/modules/vendor/slider.js` to automatically teardown existing slick instances (`slider.slick('unslick')`) before re-initializing on ACF preview render.
- **Google Maps API Loader**: Switched Google Maps in `include/enqueue_scripts.php` to only enqueue when `google_maps_api_key` option field is set.

---

#### 🛠️ Migration Recipe (Upgrading to v3.21.0)
1. **Copy New Files**:
   - `include/editor_template_parts.php`
   - `src/js/modules/editor/templateParts.js`
   - `assets/editor.css`
   - `assets/editor.js`
2. **Update `functions.php`**:
   Add `'editor_template_parts'` to `$ossark_theme_includes`:
   ```php
   $ossark_theme_includes = [
       'cleanup',
       'setup_theme',
       'acf',
       'custom_post_types',
       'enqueue_scripts',
       'theme_functions',
       'headers',
       'ui_kit',
       'editor_template_parts', // <-- Add here
       'coming_soon',
       'debug',
   ];
   ```
3. **Update `src/editor.js`**:
   ```javascript
   import { initEditorTemplateParts } from './js/modules/editor/templateParts';
   
   $(function () {
       initSlider();
       initEditorTemplateParts();
   });
   ```
4. **Update `include/acf.php`**:
   Enqueue `assets/editor.css` and `assets/editor.js` in `enqueue_block_editor_assets`:
   ```php
   add_action('enqueue_block_editor_assets', function () {
       $css_path = get_template_directory() . '/assets/editor.css';
       $js_path  = get_template_directory() . '/assets/editor.js';
       if (file_exists($css_path)) {
           wp_enqueue_style('ossark-editor-chrome', get_template_directory_uri() . '/assets/editor.css', array(), filemtime($css_path));
       }
       if (file_exists($js_path)) {
           wp_enqueue_script('ossark-editor-chrome', get_template_directory_uri() . '/assets/editor.js', array(), filemtime($js_path), true);
       }
   });
   ```
5. **Rebuild Assets**: `npm run build`

---

## [3.20.0] - 2026-08-13 (iProperty Radio Update)

### Added
- **Colocated Components Architecture**:
  - Parts migrated to `components/{slug}/{slug}.php` and `components/{slug}/_{slug}.scss`.
  - Blocks migrated to `blocks/{slug}/{slug}.php`, `blocks/{slug}/block.json`, and `blocks/{slug}/_{slug}.scss`.
- **Make Part CLI Tool**: Added `scripts/make-part.js` (`npm run make:part -- {slug} ["Title"]`).
- **Modular JS Directory Structure**: Reorganized JS into `src/js/modules/animations/`, `src/js/modules/ui/`, and `src/js/modules/vendor/`.
- **Global Forms SCSS**: Extracted standalone form styles into `src/scss/global/_form.scss`.

### Changed
- `scripts/make-block.js` updated to scaffold directly into `blocks/{slug}/` with API version 3 `block.json`.
- `footer.php` and `header.php` updated to use `get_part('footer')` and `get_part('header')`.

---

#### 🛠️ Migration Recipe (Upgrading to v3.20.0)
1. **Reorganize Directories**:
   - Move block folders from `components/blocks/{slug}` to `blocks/{slug}`.
   - Rename `render.php` in each block folder to `{slug}.php`.
   - Update `block.json` in each block to set `"renderTemplate": "{slug}.php"`.
   - Move reusable parts from `components/parts/{slug}.php` into `components/{slug}/{slug}.php`.
2. **Add Part Generator Script**: Copy `scripts/make-part.js` and add `"make:part": "node scripts/make-part.js"` to `package.json`.
3. **Update Webpack Globbing in `src/main.js` and `src/editor.js`**:
   ```javascript
   const blockStyles = require.context('../blocks', true, /_[^/]+\.scss$/);
   blockStyles.keys().forEach(blockStyles);

   const partStyles = require.context('../components', true, /_[^/]+\.scss$/);
   partStyles.keys().forEach(partStyles);
   ```

---

## [3.15.0] - 2026-07-30 (ACF API v3 & Block Editor Modernization)

### Added
- **Dedicated Gutenberg Editor Bundle**:
  - `src/editor.js` & `src/scss/editor.scss` compiling to `dist/editor.min.js` and `dist/editor.min.css`.
  - Added `assets/editor-styles.css` with canvas resets, full-width `.wp-block` un-clamping, and forced `.in-view` visibility overrides.
- **Theme Support for Editor Styles**:
  - `add_theme_support('editor-styles')` and `add_editor_style(['assets/editor-styles.css', 'dist/editor.min.css'])` in `include/acf.php`.
- **ACF Auto-Discovery**:
  - `ossark_register_blocks_from_json()` dynamically discovering all `blocks/*/block.json` files on `init`.
  - Dynamic whitelisting via `allowed_block_types_all` filter.
- **`theme.json`**: Added theme manifest with `"contentSize": "100%"` and `"wideSize": "100%"` to avoid Gutenberg width constraints.
- **ACF Block Lifecycle Hook**: Connected `window.acf.addAction('render_block_preview', ...)` in `src/editor.js`.

### Changed
- Webpack split chunks configuration updated so that vendor chunk extraction (`vendors.min.js`) only targets `main.js`, keeping `editor.min.js` self-contained.

---

#### 🛠️ Migration Recipe (Upgrading to v3.15.0)
1. **Add `theme.json`**: Copy `theme.json` to theme root.
2. **Add Editor Assets**: Copy `assets/editor-styles.css`, `src/editor.js`, and `src/scss/editor.scss`.
3. **Update `config/webpack.config.js`**:
   ```javascript
   entry: {
       main: "./src/main.js",
       editor: "./src/editor.js",
   },
   optimization: {
       splitChunks: {
           cacheGroups: {
               commons: {
                   test: /[\\/]node_modules[\\/]/,
                   name: 'vendors',
                   chunks: chunk => chunk.name === 'main'
               }
           }
       }
   }
   ```
4. **Update `include/acf.php`**: Add `add_theme_support('editor-styles')` and `enqueue_block_editor_assets` hooks.
5. **Rebuild Assets**: `npm run build`

---

## [3.10.0] - 2026-04-24 (Tooling, Docs & WooCommerce Suite)

### Added
- **Comprehensive Documentation**: Expanded `README.md` and `.github/copilot-instructions.md`.
- **Sass LoadPaths Configuration**: Exposed `src/scss/include` in Webpack so partials can use `@use "shared" as *;`.
- **WooCommerce Template Suite**: Added `woocommerce.php` and full override templates under `woocommerce/` (`cart/`, `checkout/`, `myaccount/`, `archive-product.php`, `single-product.php`).
- **YouTube Embed URL Sanitizer**: Added `returnYoutubeUrl()` helper function in `include/theme_functions.php`.
- **Development Source Maps**: Added `devtool: mode === 'development' ? 'source-map' : false` to Webpack.

### Changed
- Migrated package management from Yarn to **npm** (`package-lock.json`).
- Updated minimum engine to Node 22+.

---

#### 🛠️ Migration Recipe (Upgrading to v3.10.0)
1. **Switch to npm**: Remove `yarn.lock`, run `npm install`, and commit `package-lock.json`.
2. **Update `config/webpack.config.js`**:
   Add `loadPaths` inside `sassOptions`:
   ```javascript
   sassOptions: {
       loadPaths: [
           path.resolve(dir, "./src/scss/include"),
           path.resolve(dir, "./node_modules"),
       ],
   }
   ```
3. **Update Shared SCSS**: Ensure `src/scss/include/_shared.scss` forwards `_variables.scss` and `_mixins.scss`.

---

## [3.0.0] - 2025-08-28 to 2025-11-20 (Animations & Smooth Scroll Overhaul)

### Added
- **Lenis Smooth Scroll**: Added Lenis smooth scrolling library in `src/main.js`.
- **Scroll Observer Enhancements**: Added `data-scroll-switch` support in `scroll.js` to toggle `.in-view` off when scrolled out of view.
- **Parallax Engine**: Added `src/js/modules/animations/parallax.js` with `data-parallax-speed` and `data-parallax-direction`.
- **Number Counter Animation**: Added `src/js/modules/animations/numbers.js` with `data-animate-number`.
- **SVG Helper**: Added `get_svg($name)` in `include/theme_functions.php` to inline SVGs from `assets/icons/`.

### Changed
- Replaced AOS animation library with native `IntersectionObserver` in `scroll.js`.

---

#### 🛠️ Migration Recipe (Upgrading to v3.0.0)
1. **Install Lenis**: `npm install lenis`
2. **Update `src/main.js`**:
   ```javascript
   import Lenis from 'lenis';
   const lenis = new Lenis();
   function raf(time) {
       lenis.raf(time);
       requestAnimationFrame(raf);
   }
   requestAnimationFrame(raf);
   ```
3. **Update Animation Modules**: Copy `src/js/modules/animations/` (`scroll.js`, `parallax.js`, `numbers.js`, `splitLines.js`, `splitText.js`, `typewriter.js`, `lottie.js`).

---

## [2.5.0] - 2024-07-26 (AJAX Architecture & Security Headers)

### Added
- **Theme AJAX Framework**: Added `include/theme_ajax.php` with localized nonces and secure `admin-ajax.php` handlers.
- **Security Headers & CSP**: Added `include/headers.php` with CSP nonces, SRI script integrity, and X-Frame-Options.
- **UI Kit Helper Functions**: Added `include/ui_kit.php` with `get_image()`, `get_button()`, `get_part()`, and `get_block()`.

---

#### 🛠️ Migration Recipe (Upgrading to v2.5.0)
1. **Copy Files**: `include/headers.php`, `include/ui_kit.php`, and `include/theme_ajax.php`.
2. **Update `include/enqueue_scripts.php`**: Add `wp_localize_script` passing `ajax_url` and `ajax_nonce`.
3. **Include in `functions.php`**: Add `'headers'` and `'ui_kit'` to `$ossark_theme_includes`.

---

## [2.0.0] - 2023-09-12 to 2024-03-20 (Modular SCSS & Include System)

### Added
- **Modular Includes System**: Replaced monolithic `functions.php` with modular files in `include/` (`setup_theme.php`, `cleanup.php`, `acf.php`, `custom_post_types.php`, `theme_functions.php`).
- **Modern Grid System**: Replaced heavy offset classes with empty responsive column spans and CSS grid utilities.
- **SVG & WebP Uploads**: Added MIME type handlers and admin thumbnail support in `include/setup_theme.php`.

---

## [1.0.0] - 2023-07-15 (Initial Boilerplate Release)

### Added
- Initial theme release with Webpack, Babel, Dart Sass, ACF integration, and basic blocks.

# Changelog & Upgrade Guide

All notable changes to the OSSARK Builder WordPress theme are documented in this file.
This project follows [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

Theme version is tracked in `style.css` (`Version: X.XX`) and `package.json` (`"version": "X.Y.Z"`).

---

## 🤖 How an AI Agent Should Upgrade Older Websites Using this Changelog

When upgrading an older theme to the latest boilerplate structure, an AI agent should follow this protocol:

1. **Detect Current Version & Baseline State**:
   - Check `style.css` for `Version: X.XX` and `package.json` for `"version": "X.Y.Z"`.
   - Inspect existing directory structure (e.g. `components/blocks/` vs `blocks/`, `components/parts/` vs `components/{slug}/`, `functions.php` includes, and Webpack config).
2. **Determine Target Migration Path**:
   - Identify all version milestones between the source version and target version (`3.22.0`).
   - Execute the **Migration Recipes** chronologically from oldest to newest.
3. **Execute Step-by-Step File & Code Changes**:
   - Create missing directories and move files according to the architectural milestones.
   - Update `functions.php` and its `$ossark_theme_includes` array to match the target version order.
   - Update `package.json` scripts, dependencies, engines, and version number.
   - Update `config/webpack.config.js` entry points, `loadPaths`, and optimization rules.
   - Ensure `src/main.js`, `src/editor.js`, and `src/js/index.js` include all required `require.context` globbing.
4. **Build & Validate**:
   - Run `npm install` to install/update dependencies (`lenis`, `slick-carousel`, `lottie-web`, etc.).
   - Run `npm run build` (or `npm run build:dev`) and ensure Webpack compiles without errors.
   - Verify that all block and part templates resolve with `get_block()` and `get_part()`.
   - Ensure `style.css` and `package.json` are bumped to `Version: 3.22` and `"version": "3.22.0"`.

### AI Agent Prompt Template
> *"Please read CHANGELOG.md and upgrade this WordPress theme from its current version to version 3.22.0 by executing the migration recipes in chronological order. Ensure directory structures, Webpack configuration, theme includes, editor integration, and build scripts are fully aligned with the latest architecture, and run `npm run build` to verify."*

---

## Target Architecture Overview (v3.22.0)

```text
theme-root/
├── 404.php, archive.php, footer.php, functions.php, header.php, index.php, page.php, search.php, single.php
├── style.css                      (Version: 3.22)
├── package.json                   (version: 3.22.0, scripts for dev/build/make:block/make:part)
├── theme.json                     (contentSize: 100%, wideSize: 100%)
├── webpack.config.js              (entry: main & editor, Sass loadPaths, splitChunks)
├── acf-json/                      (Auto-synced ACF field groups)
├── assets/
│   ├── editor-styles.css          (Inside-iframe canvas un-clamping & layout reset)
│   ├── editor.css                 (Outer admin UI & draggable sidebar styles)
│   ├── editor.js                  (Draggable inspector sidebar logic)
│   ├── fonts/, forms/, icons/, images/
├── blocks/{slug}/                 (Colocated ACF Gutenberg blocks)
│   ├── block.json                 (v3 manifest: auto-discovered on init)
│   ├── {slug}.php                 (Render template: get_field(), get_button(), get_image())
│   ├── _{slug}.scss               (Auto-imported by main.js & editor.js via require.context)
│   └── {slug}.js                  (Optional block JS: auto-run by src/js/index.js)
├── components/{slug}/             (Colocated PHP parts & UI components)
│   ├── {slug}.php                 (Render template: rendered via get_part('{slug}'))
│   └── _{slug}.scss               (Auto-imported by main.js & editor.js via require.context)
├── config/
│   ├── babel.config.json, eslintrc.json, webpack.config.js
├── docs/
│   └── editor-integration-guide.md
├── include/
│   ├── acf.php                    (Auto-discovery, options pages, editor assets)
│   ├── cleanup.php                (WP bloat removal, conditional CF7 asset loading)
│   ├── coming_soon.php            (Coming soon mode via ACF toggle)
│   ├── cookie_banner.php          (Server-side cookie notice on wp_footer)
│   ├── custom_post_types.php      (CPT registration)
│   ├── custom_taxonomies.php      (Custom taxonomies, optional)
│   ├── debug.php                  (console_log(), debug toggle)
│   ├── editor_template_parts.php  (Live preview of get_part() templates in Gutenberg)
│   ├── enqueue_scripts.php        (jQuery, vendors.min.js, main.min.js, Maps loader)
│   ├── headers.php                (Nonce-based CSP, SRI hashes, security headers)
│   ├── setup_theme.php            (Theme supports, SVG/WebP uploads, menus)
│   ├── theme_ajax.php             (Secure admin-ajax handlers & nonces)
│   ├── theme_functions.php        (get_svg(), returnYoutubeUrl(), icon(), excerpt())
│   ├── ui_kit.php                 (get_image(), get_button(), get_part(), get_block())
│   └── woocommerce.php            (WooCommerce support hooks, optional)
├── scripts/
│   ├── make-block.js              (npm run make:block -- {slug} ["Title"] [--js])
│   └── make-part.js               (npm run make:part -- {slug} ["Title"])
├── src/
│   ├── main.js                    (Frontend entry: Lenis, vendor imports, require.context)
│   ├── editor.js                  (Editor entry: ACF block preview hook, template parts preview)
│   ├── js/
│   │   ├── index.js               (runAfterDomLoad, block JS auto-runner)
│   │   └── modules/               (animations/, editor/, ui/, vendor/)
│   └── scss/
│       ├── index.scss             (Frontend master SCSS)
│       ├── editor.scss            (Editor master SCSS bundle)
│       ├── global/                (_buttons.scss, _form.scss)
│       └── include/               (_shared.scss, _variables.scss, _mixins.scss, _layout.scss, _helpers.scss)
├── templates/
│   ├── coming-soon.php
│   └── cookie-statement.php       (Variable-driven cookie statement)
└── woocommerce/                   (Full override templates for shop, cart, checkout, myaccount)
```

---

## [3.22.0] - 2026-09-15 (Custom Cookie Banner)

### Added
- **Custom cookie consent banner** (replaces external Cookiebot): a lightweight, self-contained, informational notice bar managed entirely from Theme Options.
  - `include/cookie_banner.php`: renders the banner on `wp_footer`, but only when the feature is enabled AND the visitor has not yet dismissed it (server-side `$_COOKIE['cookie_consent']` check — no flash, zero DOM output for returning visitors who already accepted).
  - `components/cookie-banner/cookie-banner.php` + `_cookie-banner.scss`: accessible fixed bottom bar (`role="dialog"`, `aria-live`), message, optional "learn more" link, and accept button. SCSS auto-imported via `require.context`.
  - `src/js/modules/ui/cookie-banner.js`: wires the accept button, sets `cookie_consent` for 365 days (`SameSite=Lax`), and smoothly animates the bar out.
  - `src/js/modules/ui/cookie.js`: refactored into reusable `setCookie(name, value, days)` / `getCookie(name)` helper functions.
- **Theme Options → Cookies** ACF options sub-page (`acf-json/group_cookiesettings01.json`):
  - Banner tab: enable toggle, message, accept button label, learn-more link.
  - Statement tab: editable variables (company/site name, website URL, contact email, last updated) plus an optional WYSIWYG for additional content.
- **Cookie Statement template content**: `templates/cookie-statement.php` now ships a complete, generic cookie policy built from editable ACF option variables with fallback defaults, followed by an optional appended WYSIWYG section.

### Removed
- **Cookiebot integration**: `src/scss/global/_cookiebot.scss`, its `@use` in `src/scss/index.scss` and `src/scss/editor.scss`, and the `https://consent.cookiebot.com` `frame-src` entry in `include/headers.php`.

---

#### 🛠️ Migration Recipe (Upgrading from v3.21.0 to v3.22.0)
1. **Add Cookie Banner PHP Include**:
   Copy `include/cookie_banner.php` and add `'cookie_banner'` to the `$ossark_theme_includes` array in `functions.php`:
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
       'editor_template_parts',
       'coming_soon',
       'cookie_banner', // <-- Add here
       'debug',
   ];
   ```
2. **Add Cookie Banner Component**:
   Copy the `components/cookie-banner/` directory (`cookie-banner.php` and `_cookie-banner.scss`).
3. **Register Cookies Options Sub-Page & Import ACF Fields**:
   - In `include/acf.php`, add the `Cookies` options sub-page inside the `acf_add_options_page` block:
     ```php
     acf_add_options_sub_page([
         'page_title' => 'Cookies',
         'menu_title' => __( 'Cookies', 'ossark-builder' ),
         'menu_slug'  => 'cookies',
         'parent'     => 'theme-options'
     ]);
     ```
   - Copy `acf-json/group_cookiesettings01.json` to enable field sync in WP Admin.
4. **Update Cookie JavaScript**:
   - Copy `src/js/modules/ui/cookie-banner.js`.
   - Update `src/js/modules/ui/cookie.js` to export `setCookie` and `getCookie`.
   - In `src/js/index.js`, import `cookieBanner` and call it inside `runAfterDomLoad()`:
     ```javascript
     import { cookieBanner } from './modules/ui/cookie-banner';

     export function runAfterDomLoad() {
         // ... other modules
         cookieBanner();
     }
     ```
5. **Update Cookie Statement Template**:
   Replace `templates/cookie-statement.php` with the variable-driven template.
6. **Remove Cookiebot Integration**:
   - Delete `src/scss/global/_cookiebot.scss` if present.
   - Remove any `@use "global/cookiebot";` lines from `src/scss/index.scss` and `src/scss/editor.scss`.
   - In `include/headers.php`, remove `https://consent.cookiebot.com` from the CSP `frame-src` directive.
7. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.22` and `package.json` to `"version": "3.22.0"`.
   - Run `npm run build`.

---

## [3.21.0] - 2026-08-26 (Kelbuild & Accessibility Update)

### Added
- **Editor Template Parts Live Preview**:
  - `include/editor_template_parts.php`: Tokenizes single/page PHP templates and renders `get_part(...)` calls situated before and after `the_content()` directly in the Gutenberg canvas.
  - `src/js/modules/editor/templateParts.js`: Injects template parts preview markup into `iframe[name="editor-canvas"]` dynamically.
  - `ossark_get_editor_template_parts` AJAX endpoint with dynamic refresh on template changes.
- **Draggable Block Inspector Sidebar**:
  - `assets/editor.css` & `assets/editor.js`: Added 6px resize grab handle on the left edge of `.interface-interface-skeleton__sidebar` with `localStorage` width persistence (`--ossark-inspector-width`).
- **Mobile Header Accessibility & Navigation**:
  - Added keyboard accessibility (Enter/Space) to mobile hamburger navigation.
  - Enhanced ARIA state attributes (`aria-expanded`, `aria-label`) and escaped menu/logo output in header templates.
  - Aligned mobile drawer close logic with the `$tablet` breakpoint (`1024px`).
- **SCSS Helpers**: Added `.pos-abs-cover`, `.pos-rel`, `.pos-center`, `.overflow-hidden`, `.w-100`, `.h-100`, `.fit-cover` to `src/scss/include/_helpers.scss`.
- **Project Documentation**:
  - `docs/editor-integration-guide.md`: Complete exportable guide for modern Gutenberg & ACF setup.
  - `going-live-checklist.md`: Step-by-step checklist for production deployment.

### Changed
- **Slick Slider Teardown in Editor**: Updated `src/js/modules/vendor/slider.js` to automatically teardown existing slick instances (`slider.slick('unslick')`) before re-initializing on ACF preview render.
- **Google Maps API Loader**: Switched Google Maps in `include/enqueue_scripts.php` to only enqueue when `google_maps_api_key` option field is set.

---

#### 🛠️ Migration Recipe (Upgrading from v3.20.0 to v3.21.0)
1. **Copy New Files**:
   - `include/editor_template_parts.php`
   - `src/js/modules/editor/templateParts.js`
   - `assets/editor.css`
   - `assets/editor.js`
   - `docs/editor-integration-guide.md`
   - `going-live-checklist.md`
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
   Enqueue `assets/editor.css`, `assets/editor.js`, and `dist/editor.min.js` in `enqueue_block_editor_assets`:
   ```php
   add_action('enqueue_block_editor_assets', function () {
       $css_path        = get_template_directory() . '/assets/editor.css';
       $js_path         = get_template_directory() . '/assets/editor.js';
       $dist_editor_js  = get_template_directory() . '/dist/editor.min.js';

       if (file_exists($css_path)) {
           wp_enqueue_style('ossark-editor-chrome', get_template_directory_uri() . '/assets/editor.css', array(), filemtime($css_path));
       }
       if (file_exists($js_path)) {
           wp_enqueue_script('ossark-editor-chrome', get_template_directory_uri() . '/assets/editor.js', array(), filemtime($js_path), true);
       }
       if (file_exists($dist_editor_js)) {
           wp_enqueue_script('ossark-editor-bundle', get_template_directory_uri() . '/dist/editor.min.js', array('jquery', 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-data', 'wp-element'), filemtime($dist_editor_js), true);
       }
   });
   ```
5. **Update Mobile Header & Accessibility**:
   - Update `components/header/header.php`, `components/header/hamburger.php`, `components/header/_hamburger.scss`, and `src/js/modules/ui/hamburger.js`.
   - Update `src/scss/include/_helpers.scss` with layout utilities.
6. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.21` and `package.json` to `"version": "3.21.0"`.
   - Run `npm run build`.

---

## [3.20.0] - 2026-08-13 (iProperty Radio Update)

### Added
- **Colocated Components & Blocks Architecture**:
  - Parts migrated to `components/{slug}/{slug}.php` and `components/{slug}/_{slug}.scss`.
  - Blocks migrated to `blocks/{slug}/{slug}.php`, `blocks/{slug}/block.json`, and `blocks/{slug}/_{slug}.scss`.
- **Automatic Block JavaScript Runner**:
  - `src/js/index.js` uses `require.context('../../blocks', true, /\.js$/)` to automatically run any colocated `blocks/{slug}/{slug}.js` files that `export default` an init function.
- **Make Block & Part CLI Tools**:
  - Added `scripts/make-block.js` (`npm run make:block -- {slug} ["Title"] [--js]`).
  - Added `scripts/make-part.js` (`npm run make:part -- {slug} ["Title"]`).
- **Modular JS Directory Structure**: Reorganized JS into `src/js/modules/animations/`, `src/js/modules/ui/`, and `src/js/modules/vendor/`.
- **Global Forms & Buttons SCSS**: Extracted standalone styles into `src/scss/global/_form.scss` and `src/scss/global/_buttons.scss`.

### Changed
- `footer.php` and `header.php` updated to use `get_part('footer')` and `get_part('header')`.
- All `get_block()` and `get_part()` helper implementations updated in `include/ui_kit.php` to resolve `blocks/{slug}/{slug}.php` and `components/{slug}/{slug}.php`.

---

#### 🛠️ Migration Recipe (Upgrading from v3.15.0 to v3.20.0)
1. **Reorganize Directories**:
   - Move block folders from `components/blocks/{slug}` to `blocks/{slug}`.
   - Rename `render.php` in each block folder to `{slug}.php`.
   - Update `block.json` in each block to set `"renderTemplate": "{slug}.php"`.
   - Move reusable parts from `components/parts/{slug}.php` into `components/{slug}/{slug}.php`.
   - Rename part styles to `components/{slug}/_{slug}.scss`.
2. **Update Helper Functions in `include/ui_kit.php`**:
   Ensure `get_part()` and `get_block()` resolve the new paths:
   ```php
   function get_part( $template, $args = [] ) {
       $template_path = 'components/' . $template . '/' . $template . '.php';
       $resolved = locate_template( $template_path );
       if ( ! $resolved ) {
           trigger_error( "Template not found: $template_path", E_USER_WARNING );
           return;
       }
       include $resolved;
   }

   function get_block( $template, $args = [] ) {
       $template_path = 'blocks/' . $template . '/' . $template . '.php';
       $resolved = locate_template( $template_path );
       if ( ! $resolved ) {
           trigger_error( "Block template not found: $template_path", E_USER_WARNING );
           return;
       }
       include $resolved;
   }
   ```
3. **Add Scaffolding CLI Scripts**:
   Copy `scripts/make-block.js` and `scripts/make-part.js`, and add scripts to `package.json`:
   ```json
   "scripts": {
       "make:block": "node scripts/make-block.js",
       "make:part": "node scripts/make-part.js"
   }
   ```
4. **Update Webpack Globbing in `src/main.js` and `src/editor.js`**:
   ```javascript
   const blockStyles = require.context('../blocks', true, /_[^/]+\.scss$/);
   blockStyles.keys().forEach(blockStyles);

   const partStyles = require.context('../components', true, /_[^/]+\.scss$/);
   partStyles.keys().forEach(partStyles);
   ```
5. **Update Block JS Auto-Discovery in `src/js/index.js`**:
   ```javascript
   const blockScripts = require.context('../../blocks', true, /\.js$/);

   export function runAfterDomLoad() {
       // ... UI & animation inits
       blockScripts.keys().forEach(key => {
           const mod = blockScripts(key);
           if (typeof mod.default === 'function') mod.default();
       });
   }
   ```
6. **Reorganize JS Files**: Move animation files into `src/js/modules/animations/`, UI scripts into `src/js/modules/ui/`, vendor scripts into `src/js/modules/vendor/`.
7. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.20` and `package.json` to `"version": "3.20.0"`.
   - Run `npm run build`.

---

## [3.15.0] - 2026-07-30 (ACF API v3 & Block Editor Modernization)

### Added
- **Dedicated Gutenberg Editor Bundle**:
  - `src/editor.js` & `src/scss/editor.scss` compiling to `dist/editor.min.js` and `dist/editor.min.css`.
  - Added `assets/editor-styles.css` with canvas resets, full-width `.wp-block` un-clamping, and forced `.in-view` visibility overrides.
- **Theme Support for Editor Styles**:
  - `add_theme_support('editor-styles')` and `add_editor_style(['assets/editor-styles.css', 'dist/editor.min.css'])` in `include/acf.php`.
- **ACF Auto-Discovery from `block.json` Manifests**:
  - `ossark_register_blocks_from_json()` dynamically discovering all `blocks/*/block.json` files on `init`.
  - Dynamic whitelisting via `allowed_block_types_all` filter reading all `block.json` files.
- **`theme.json`**: Added theme manifest with `"contentSize": "100%"` and `"wideSize": "100%"` to avoid Gutenberg width constraints.
- **ACF Block Lifecycle Hook**: Connected `window.acf.addAction('render_block_preview', ...)` in `src/editor.js`.

### Changed
- Webpack split chunks configuration updated so that vendor chunk extraction (`vendors.min.js`) only targets `main.js`, keeping `editor.min.js` self-contained.

---

#### 🛠️ Migration Recipe (Upgrading from v3.10.0 to v3.15.0)
1. **Add `theme.json`**: Copy `theme.json` to theme root:
   ```json
   {
       "$schema": "https://schemas.wp.org/trunk/theme.json",
       "version": 3,
       "settings": {
           "layout": {
               "contentSize": "100%",
               "wideSize": "100%"
           }
       }
   }
   ```
2. **Add Editor Assets**:
   - Copy `assets/editor-styles.css`.
   - Create `src/editor.js` and `src/scss/editor.scss`.
3. **Update `config/webpack.config.js`**:
   Add `editor` entry point and configure `splitChunks`:
   ```javascript
   entry: {
       main: "./src/main.js",
       editor: "./src/editor.js",
   },
   optimization: {
       minimize: true,
       minimizer: [new TerserPlugin({ extractComments: false })],
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
4. **Update `include/acf.php`**:
   - Add editor style support:
     ```php
     add_action('after_setup_theme', function () {
         add_theme_support('editor-styles');
         add_editor_style('assets/editor-styles.css');
         if ( file_exists( get_template_directory() . '/dist/editor.min.css' ) ) {
             add_editor_style( 'dist/editor.min.css' );
         }
     });
     ```
   - Add dynamic block registration and whitelisting:
     ```php
     if (function_exists('acf_register_block_type')) {
         add_action('init', 'ossark_register_blocks_from_json');
     }

     function ossark_register_blocks_from_json() {
         $blocks_dir = get_template_directory() . '/blocks';
         if ( ! is_dir( $blocks_dir ) ) return;
         foreach ( glob( $blocks_dir . '/*/block.json' ) as $manifest ) {
             register_block_type( dirname( $manifest ) );
         }
     }

     add_filter( 'allowed_block_types_all', 'allowed_block_types', 10, 2 );
     function allowed_block_types( $allowed_blocks, $editor_context ) {
         $all_blocks = [];
         foreach ( glob( get_template_directory() . '/blocks/*/block.json' ) as $manifest ) {
             $data = json_decode( file_get_contents( $manifest ), true );
             if ( ! empty( $data['name'] ) ) {
                 $all_blocks[] = $data['name'];
             }
         }
         return $all_blocks;
     }
     ```
5. **Convert Existing Blocks to `block.json` (API Version 3)**:
   For each block, ensure `block.json` contains:
   ```json
   {
       "$schema": "https://schemas.wp.org/trunk/block.json",
       "apiVersion": 3,
       "name": "acf/block-slug",
       "title": "Block Title",
       "category": "content",
       "icon": "block-default",
       "acf": {
           "mode": "auto",
           "renderTemplate": "block-slug.php"
       }
   }
   ```
6. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.15` and `package.json` to `"version": "3.15.0"`.
   - Run `npm run build`.

---

## [3.10.0] - 2026-04-24 (Tooling, Docs & WooCommerce Suite)

### Added
- **Comprehensive Documentation**: Expanded `README.md` and `.github/copilot-instructions.md`.
- **Sass LoadPaths Configuration**: Exposed `src/scss/include` in Webpack so partials can use `@use "shared" as *;`.
- **WooCommerce Template Suite**: Added `woocommerce.php` and full override templates under `woocommerce/` (`cart/`, `checkout/`, `myaccount/`, `archive-product.php`, `single-product.php`).
- **YouTube Embed URL Sanitizer**: Added `returnYoutubeUrl($url)` helper function in `include/theme_functions.php`.
- **Development Source Maps**: Added `devtool: mode === 'development' ? 'source-map' : false` to Webpack.

### Changed
- Migrated package management from Yarn to **npm** (`package-lock.json`).
- Updated minimum engine to Node 22+.

---

#### 🛠️ Migration Recipe (Upgrading from v3.0.0 to v3.10.0)
1. **Switch to npm & Update Node Engine**:
   - Remove `yarn.lock` if present.
   - Update `package.json` `"engines"` field:
     ```json
     "engines": {
         "node": ">=22"
     }
     ```
   - Run `npm install`.
2. **Update `config/webpack.config.js`**:
   Add `loadPaths` inside `sassOptions` and configure `devtool`:
   ```javascript
   const config = (dir = __dirname, mode = 'production') => ({
       devtool: mode === 'development' ? 'source-map' : false,
       // ...
       module: {
           rules: [
               {
                   test: /\.s[ac]ss$/i,
                   use: [
                       MiniCssExtractPlugin.loader,
                       "css-loader",
                       {
                           loader: "sass-loader",
                           options: {
                               sassOptions: {
                                   loadPaths: [
                                       path.resolve(dir, "./src/scss/include"),
                                       path.resolve(dir, "./node_modules"),
                                   ],
                               },
                           },
                       },
                   ],
               },
           ],
       },
   });
   ```
3. **Configure Shared SCSS**:
   Ensure `src/scss/include/_shared.scss` forwards variables and mixins:
   ```scss
   @forward "variables";
   @forward "mixins";
   ```
4. **Add YouTube Sanitizer Helper**:
   In `include/theme_functions.php`, add `returnYoutubeUrl()`:
   ```php
   function returnYoutubeUrl($url) {
       $rx = '~(?:https?://)?(?:www\.)?(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([\w-]{11})~x';
       if (preg_match($rx, $url, $matches)) {
           return 'https://www.youtube.com/embed/' . $matches[1];
       }
       return $url;
   }
   ```
5. **(Optional) WooCommerce Integration**:
   If the project requires WooCommerce, add `woocommerce.php` to root, copy the `woocommerce/` templates directory, and uncomment `'woocommerce'` in `functions.php`.
6. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.10` and `package.json` to `"version": "3.10.0"`.
   - Run `npm run build`.

---

## [3.0.0] - 2025-08-28 to 2025-11-20 (Animations & Smooth Scroll Overhaul)

### Added
- **Lenis Smooth Scroll**: Added Lenis smooth scrolling library in `src/main.js`.
- **Scroll Observer Enhancements**: Added `data-scroll-switch` support in `scroll.js` to toggle `.in-view` off when scrolled out of view, and `data-scroll-call` for invoking window functions.
- **Parallax Engine**: Added `src/js/modules/animations/parallax.js` with `data-parallax-speed` and `data-parallax-direction`.
- **Number Counter Animation**: Added `src/js/modules/animations/numbers.js` with `data-animate-number`.
- **Text Animation Modules**: Added `splitLines.js`, `splitText.js`, `typewriter.js`.
- **Lottie Player Module**: Added `lottie.js` using `lottie-web`.
- **SVG Helper**: Added `get_svg($name)` in `include/theme_functions.php` to inline SVGs from `assets/icons/`.

### Changed
- Replaced AOS (Animate on Scroll) animation library with native `IntersectionObserver` in `scroll.js`.

---

#### 🛠️ Migration Recipe (Upgrading from v2.5.0 to v3.0.0)
1. **Install Dependencies**:
   ```bash
   npm install lenis lottie-web
   npm uninstall aos
   ```
2. **Update `src/main.js`**:
   Initialize Lenis and DOMContentLoaded listeners:
   ```javascript
   import Lenis from 'lenis';
   import { runAfterDomLoad } from './js';

   document.addEventListener('DOMContentLoaded', () => {
       runAfterDomLoad();

       const lenis = new Lenis();
       function raf(time) {
           lenis.raf(time);
           requestAnimationFrame(raf);
       }
       requestAnimationFrame(raf);

       window.addEventListener('load', () => {
           document.querySelectorAll('[data-scroll]:not(.in-view)').forEach(el => {
               if (el.getBoundingClientRect().top < window.innerHeight) {
                   el.classList.add('in-view');
               }
           });
       });
   });
   ```
3. **Add Animation Modules**:
   Copy animation scripts to `src/js/modules/animations/`:
   - `scroll.js`, `parallax.js`, `numbers.js`, `splitLines.js`, `splitText.js`, `typewriter.js`, `lottie.js`.
4. **Add `get_svg()` to `include/theme_functions.php`**:
   ```php
   function get_svg($name) {
       if ($name) {
           $file_name = (substr($name, -4) === '.svg') ? $name : $name . '.svg';
           $svg_path  = get_template_directory() . '/assets/icons/' . $file_name;
           if (file_exists($svg_path)) {
               return file_get_contents($svg_path);
           }
       }
       return '';
   }
   ```
5. **Update SCSS**:
   Add `src/scss/include/_smooth-scroll.scss` and `src/scss/include/_animations.scss`.
6. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 3.0` and `package.json` to `"version": "3.0.0"`.
   - Run `npm run build`.

---

## [2.5.0] - 2024-07-26 (AJAX Architecture & Security Headers)

### Added
- **Theme AJAX Framework**: Added `include/theme_ajax.php` with localized nonces, secure `admin-ajax.php` handlers, and `testAjax.js` demo.
- **Security Headers & CSP Engine**: Added `include/headers.php` with per-request cryptographic CSP nonces, SRI script integrity, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, and Permissions-Policy.
- **UI Kit Helper Functions**: Added `include/ui_kit.php` with `get_image()`, `get_button()`, `get_part()`, `get_block()`, and `ui_title()`.

---

#### 🛠️ Migration Recipe (Upgrading from v2.0.0 to v2.5.0)
1. **Copy PHP Include Files**:
   Copy `include/headers.php`, `include/ui_kit.php`, and `include/theme_ajax.php`.
2. **Update `functions.php`**:
   Add `'headers'` and `'ui_kit'` (and optionally `'theme_ajax'`) to `$ossark_theme_includes`:
   ```php
   $ossark_theme_includes = [
       'cleanup',
       'setup_theme',
       'acf',
       'custom_post_types',
       'enqueue_scripts',
       'theme_functions',
       'headers',   // <-- Add here
       'ui_kit',    // <-- Add here
       'coming_soon',
       'debug',
       // 'theme_ajax',
   ];
   ```
3. **Update `include/enqueue_scripts.php`**:
   Pass localized AJAX object to `main.min.js`:
   ```php
   wp_localize_script( 'main', 'customjs_ajax_object', [
       'ajax_url'   => admin_url( 'admin-ajax.php' ),
       'ajax_nonce' => wp_create_nonce( 'secure_nonce_name' ),
       'site_url'   => get_site_url(),
       'theme_url'  => get_template_directory_uri()
   ]);
   ```
4. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 2.5` and `package.json` to `"version": "2.5.0"`.
   - Run `npm run build`.

---

## [2.0.0] - 2023-09-12 to 2024-03-20 (Modular Architecture & UI Kit Foundation)

### Added
- **Modular Includes Architecture**: Refactored monolithic `functions.php` into modular single-responsibility files in `include/` (`setup_theme.php`, `cleanup.php`, `acf.php`, `custom_post_types.php`, `enqueue_scripts.php`, `theme_functions.php`, `debug.php`).
- **Theme Debug Engine**: Added `include/debug.php` with `console_log()` debugging, ACF debug toggle option, and PHP error logging.
- **Modern Flex Grid System**: Replaced rigid offset classes with flex column spans, responsive breakpoints (`$mobile: 768px`, `$tablet: 1024px`, `$laptop: 1440px`), and `+min-screen()` / `+max-screen()` mixins in `src/scss/include/`.
- **SVG & WebP Media Support**: Added upload MIME type handlers and SVG thumbnail previews in `include/setup_theme.php`.
- **Form Redirect Utility**: Added form redirect handling in `include/theme_functions.php` and `src/js/modules/ui/contact.js`.

---

#### 🛠️ Migration Recipe (Upgrading from v1.1.0 to v2.0.0)
1. **Create `include/` Directory & Modularize `functions.php`**:
   - Create `include/` folder.
   - Extract `functions.php` logic into:
     - `include/cleanup.php` (remove emojis, WP embeds, bloat)
     - `include/setup_theme.php` (theme supports, nav menus, SVG/WebP upload filters)
     - `include/acf.php` (ACF options pages, block categories)
     - `include/custom_post_types.php` (CPT registration)
     - `include/enqueue_scripts.php` (script and style enqueuing)
     - `include/theme_functions.php` (utility helpers, excerpt limiter)
     - `include/debug.php` (debug functions)
   - Replace `functions.php` with the loader array:
     ```php
     <?php
     defined( 'ABSPATH' ) || exit;

     $ossark_theme_includes = [
         'cleanup',
         'setup_theme',
         'acf',
         'custom_post_types',
         'enqueue_scripts',
         'theme_functions',
         'coming_soon',
         'debug',
     ];

     foreach ( $ossark_theme_includes as $ossark_theme_include ) {
         require_once get_template_directory() . '/include/' . $ossark_theme_include . '.php';
     }
     unset( $ossark_theme_include, $ossark_theme_includes );
     ```
2. **Update SCSS Grid & Breakpoint Variables**:
   Update `src/scss/include/_variables.scss`, `_mixins.scss`, and `_layout.scss` with the standard responsive flex grid.
3. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 2.0` and `package.json` to `"version": "2.0.0"`.
   - Run `npm run build`.

---

## [1.1.0] - 2023-07-29 to 2023-08-09 (Core Blocks & Navigation)

### Added
- **Coming Soon Mode**: Added `templates/coming-soon.php` and ACF toggle support in `include/coming_soon.php`.
- **Hamburger Navigation**: Added overlay hamburger menu component with toggle animations (`components/header/hamburger.php`, `src/js/modules/ui/hamburger.js`).
- **Starter ACF Blocks**: Added Hero, Text, Video, and Image-Text blocks.
- **CF7 Form Integration**: Added contact block template and styling in `assets/forms/contact.html` and `src/scss/global/_form.scss`.

---

#### 🛠️ Migration Recipe (Upgrading from v1.0.0 to v1.1.0)
1. **Add Coming Soon System**:
   - Copy `templates/coming-soon.php`.
   - Add `include/coming_soon.php` and add `'coming_soon'` to `$ossark_theme_includes` in `functions.php`.
2. **Add Header Hamburger Component**:
   - Copy `components/header/hamburger.php` and `src/js/modules/ui/hamburger.js`.
   - Hook hamburger trigger into header template.
3. **Add Starter Blocks & Forms**:
   - Copy starter block templates (`hero`, `text`, `video`).
   - Copy `assets/forms/contact.html` and `src/scss/global/_form.scss`.
4. **Bump Version & Rebuild**:
   - Update `style.css` to `Version: 1.1` and `package.json` to `"version": "1.1.0"`.
   - Run `npm run build`.

---

## [1.0.0] - 2023-07-15 (Initial Boilerplate Release)

### Added
- Initial theme boilerplate release with Webpack, Babel, Dart Sass, ACF block registration, and baseline WordPress setup.

### Added
- **Custom cookie consent banner** (replaces Cookiebot): a lightweight, self-contained, informational notice bar managed entirely from Theme Options.
  - `include/cookie_banner.php`: renders the banner on `wp_footer`, but only when the feature is enabled AND the visitor has not yet dismissed it (server-side `$_COOKIE['cookie_consent']` check — no flash, nothing output for returning visitors).
  - `components/cookie-banner/cookie-banner.php` + `_cookie-banner.scss`: accessible fixed bottom bar (`role="dialog"`, `aria-live`), message, optional "learn more" link and accept button. SCSS auto-imported via `require.context`.
  - `src/js/modules/ui/cookie-banner.js`: wires the accept button, stores `cookie_consent` for 365 days (`SameSite=Lax`) and removes the bar.
  - `src/js/modules/ui/cookie.js`: refactored from demo code into reusable `setCookie` / `getCookie` exports.
- **Theme Options → Cookies** ACF options sub-page (`acf-json/group_cookiesettings01.json`):
  - Banner tab: enable toggle, message, accept button label, learn-more link.
  - Statement tab: editable variables (company/site name, website URL, contact email, last updated) plus an optional WYSIWYG for additional content.
- **Cookie Statement template content**: `templates/cookie-statement.php` now ships a full, generic cookie policy built from the editable variables above (each falls back to a sensible site default), with an optional appended WYSIWYG section.

### Removed
- **Cookiebot integration**: `src/scss/global/_cookiebot.scss`, its `@use` in `src/scss/index.scss` and `src/scss/editor.scss`, and the `https://consent.cookiebot.com` `frame-src` entry in `include/headers.php`.

### Migration Recipe (from 3.21 → 3.22)
1. Copy `include/cookie_banner.php` and add `'cookie_banner'` to the `$ossark_theme_includes` array in `functions.php` (after `'coming_soon'`).
2. Copy `components/cookie-banner/` (both `cookie-banner.php` and `_cookie-banner.scss`).
3. Copy `acf-json/group_cookiesettings01.json` and add the `Cookies` options sub-page block in `include/acf.php` (`menu_slug => 'cookies'`, `parent => 'theme-options'`).
4. Copy `src/js/modules/ui/cookie-banner.js`, replace `src/js/modules/ui/cookie.js` with the exports-only version, then import `cookieBanner` in `src/js/index.js` and call it inside `runAfterDomLoad()`.
5. Replace `templates/cookie-statement.php` with the updated variable-driven template.
6. Remove Cookiebot: delete `src/scss/global/_cookiebot.scss`, drop its `@use` from `src/scss/index.scss` and `src/scss/editor.scss`, and remove `https://consent.cookiebot.com` from the `frame-src` directive in `include/headers.php`.
7. Run `npm run build`, then enable the banner and fill in the variables under **Theme Options → Cookies**.

---

## [3.21.0] - 2026-08-26 (Kelbuild & Accessibility Update)

### Added
- **Editor Template Parts Live Preview**:
  - `include/editor_template_parts.php`: Tokenizes single/page PHP templates and renders `get_part(...)` calls situated before and after `the_content()` directly in the Gutenberg canvas.
  - `src/js/modules/editor/templateParts.js`: Injects template parts preview markup into `iframe[name="editor-canvas"]` dynamically.
  - `ossark_get_editor_template_parts` AJAX endpoint with dynamic refresh on template changes.
- **Draggable Block Inspector Sidebar**:
  - `assets/editor.css` & `assets/editor.js`: Added 6px resize grab handle on the left edge of `.interface-interface-skeleton__sidebar` with `localStorage` width persistence (`--ossark-inspector-width`).
- **Mobile Header Accessibility & Navigation**:
  - Added keyboard accessibility (Enter/Space) to mobile hamburger navigation.
  - Enhanced ARIA state attributes (`aria-expanded`, `aria-label`) and escaped menu/logo output in header templates.
  - Aligned mobile drawer close logic with the `$tablet` breakpoint (`1024px`).
- **SCSS Helpers**: Added `.pos-abs-cover`, `.pos-rel`, `.pos-center`, `.overflow-hidden`, `.w-100`, `.h-100`, `.fit-cover` to `src/scss/include/_helpers.scss`.
- **Project Documentation**:
  - `docs/editor-integration-guide.md`: Complete exportable guide for modern Gutenberg & ACF setup.
  - `going-live-checklist.md`: Step-by-step checklist for production deployment.

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
   - `docs/editor-integration-guide.md`
   - `going-live-checklist.md`
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
5. **Update Mobile Header**: Copy `components/header/` and `src/js/modules/ui/hamburger.js` for enhanced mobile drawer accessibility.
6. **Rebuild Assets**: `npm run build`

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

## [2.0.0] - 2023-09-12 to 2024-03-20 (Modular Architecture & UI Kit Foundation)

### Added
- **Modular Includes Architecture**: Refactored monolithic `functions.php` into modular single-responsibility files in `include/` (`setup_theme.php`, `cleanup.php`, `acf.php`, `custom_post_types.php`, `theme_functions.php`, `debug.php`).
- **Theme Debug Engine**: Added `include/debug.php` with `console_log()` debugging, ACF debug toggle option, and PHP error logging.
- **Modern Grid System**: Replaced rigid offset classes (`col-6-offset-2`) with flex/empty column spans and responsive breakpoints.
- **SVG & WebP Media Support**: Added upload MIME type handlers and SVG thumbnail previews in `include/setup_theme.php`.
- **UI Kit Core**: Initialized `include/ui_kit.php` with standardized image and button helpers (`get_image()`, `get_button()`).
- **Form Redirect Utility**: Added form redirect handling function in `include/theme_functions.php`.

---

## [1.1.0] - 2023-07-29 to 2023-08-09 (Core Blocks & Navigation)

### Added
- **Coming Soon Mode**: Added `templates/coming-soon.php` and ACF toggle support in `include/coming_soon.php`.
- **Hamburger Navigation**: Added overlay hamburger menu component with toggle animations (`components/header/hamburger.php`, `src/js/modules/ui/hamburger.js`).
- **Starter ACF Blocks**: Added Hero, Text, Video, and Image-Text blocks.
- **CF7 Form Integration**: Added contact block template and basic styling in `assets/forms/contact.html`.

---

## [1.0.0] - 2023-07-15 (Initial Boilerplate Release)

### Added
- Initial theme boilerplate release with Webpack, Babel, Dart Sass, ACF block registration, and baseline WordPress setup.

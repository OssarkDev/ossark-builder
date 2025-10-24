# Ossark Builder WordPress Theme - Copilot Instructions

## Architecture Overview
This is a modern WordPress theme built with **ACF blocks**, **Webpack bundling**, and **component-based architecture**. The theme uses a Bootstrap-like grid system with custom SCSS/JS workflow.

### Key Directories
- `components/blocks/` - ACF Gutenberg blocks (PHP templates)
- `src/js/components/` - Modular JavaScript components
- `src/scss/` - SASS styles with component organization
- `include/` - PHP functionality modules
- `acf-json/` - ACF field definitions (auto-synced)

## Development Workflow

### Build Commands
- `yarn watch` - Development mode with file watching
- `yarn build` - Production build (required before deployment)
- Entry point: `src/main.js` → outputs to `dist/main.min.js`

### File Structure Patterns
- **All images/icons/fonts** go in `assets/` folder
- **JS/SCSS imports** added to corresponding `index.js`/`index.scss` files
- **Vendor imports** go in `main.js` (e.g., `import 'slick-carousel'`)

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
    'hero-homepage' => 'Hero Homepage',
    'text-white' => 'Text-White'
];
```
- Templates: `components/blocks/{block-name}.php`
- ACF fields: Auto-sync to `acf-json/` directory

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
- Entry: `src/js/index.js` imports all components
- Pattern: Each component exports named function
- Initialization: `runAfterDomLoad()` in `main.js`

### Scroll Animation System
Use `data-scroll` attribute for intersection observer:
```html
<section data-scroll>Auto gets 'in-view' class</section>
<section data-scroll data-scroll-call="myFunction">Triggers custom function</section>
```
**Critical**: Functions must be on `window` object: `window.myFunction = () => {}`

### Key Libraries
- **Lenis** for smooth scrolling (auto-initialized)
- **Slick Carousel** for sliders
- **Lottie** for animations
- **jQuery** externalized (available as `$`)

## SCSS Organization

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
- `acf.php` - Block registration and ACF config
- `enqueue_scripts.php` - Asset loading
- `theme_functions.php` - Utility functions
- `custom_post_types.php` - CPT definitions

### Helper Functions
- `get_inline_svg($name)` - Load SVGs from `assets/`
- `console_log($data)` - PHP debugging to browser console
- `previewImage($name)` - ACF block previews

## Node/Webpack Config
- **Node 16.17+** required
- Webpack splits vendor code automatically
- SASS compilation with autoprefixer
- File compression for production builds
- jQuery externalized for WordPress compatibility
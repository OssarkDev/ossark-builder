#!/usr/bin/env node
/**
 * Scaffold a new ACF block folder under blocks/{slug}/.
 *
 * Usage:
 *   npm run make:block -- hero
 *   npm run make:block -- hero "Hero Banner"
 *   npm run make:block -- hero "Hero Banner" --js --category=hero --icon=cover-image
 *
 * Generates:
 *   blocks/{slug}/block.json   — auto-registered on init
 *   blocks/{slug}/{slug}.php   — render template
 *   blocks/{slug}/_{slug}.scss — auto-imported (frontend + editor)
 *   blocks/{slug}/{slug}.js    — (--js only) auto-run block JS
 *
 * No further wiring needed: registration, whitelisting, SCSS and JS
 * pickup are all glob/require.context driven.
 */

const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const flags = args.filter(a => a.startsWith('--'));
const positional = args.filter(a => !a.startsWith('--'));

const slug = positional[0];

if (!slug || !/^[a-z][a-z0-9-]*$/.test(slug)) {
	console.error('Usage: npm run make:block -- <slug> ["Title"] [--js] [--category=content] [--icon=block-default]');
	console.error('Slug must be lowercase letters, numbers and hyphens (e.g. "hero", "logo-slider").');
	process.exit(1);
}

const title = positional[1] || slug.split('-').map(w => w[0].toUpperCase() + w.slice(1)).join(' ');
const getFlag = (name, fallback) => {
	const match = flags.find(f => f.startsWith(`--${name}=`));
	return match ? match.split('=')[1] : fallback;
};
const category = getFlag('category', 'content');
const icon = getFlag('icon', 'block-default');
const withJs = flags.includes('--js');

const themeRoot = path.resolve(__dirname, '..');
const blockDir = path.join(themeRoot, 'blocks', slug);

if (fs.existsSync(blockDir)) {
	console.error(`Block already exists: blocks/${slug}/`);
	process.exit(1);
}

const blockJson = `{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 3,
    "name": "acf/${slug}",
    "title": "${title}",
    "description": "${title} block.",
    "category": "${category}",
    "icon": "${icon}",
    "keywords": ["${slug}"],
    "textdomain": "ossark-builder",
    "supports": {
        "jsx": true,
        "align": false,
        "anchor": true
    },
    "acf": {
        "mode": "auto",
        "renderTemplate": "${slug}.php"
    }
}
`;

const renderPhp = `<?php
$title = get_field( 'title' );
?>
<section data-scroll class="${slug}">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ( $title ) : ?>
                    <h2 class="${slug}__title"><?= esc_html( $title ); ?></h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
`;

const scss = `@use "shared" as *;

.${slug} {
}
`;

const js = `// Colocated block JS — auto-run via require.context in src/js/index.js.
// Must export default an init function.
export default function ${slug.replace(/-([a-z0-9])/g, (_, c) => c.toUpperCase())}() {
    const elements = document.querySelectorAll('.${slug}');
    if (!elements.length) return;

    // ...
}
`;

fs.mkdirSync(blockDir, { recursive: true });
fs.writeFileSync(path.join(blockDir, 'block.json'), blockJson);
fs.writeFileSync(path.join(blockDir, `${slug}.php`), renderPhp);
fs.writeFileSync(path.join(blockDir, `_${slug}.scss`), scss);
if (withJs) {
	fs.writeFileSync(path.join(blockDir, `${slug}.js`), js);
}

console.log(`Created blocks/${slug}/`);
console.log('  block.json');
console.log(`  ${slug}.php`);
console.log(`  _${slug}.scss`);
if (withJs) console.log(`  ${slug}.js`);
console.log('\nNext steps:');
console.log(`  1. Create an ACF field group for the block (Location: Block is equal to ${title}) — it syncs to acf-json/.`);
console.log('  2. npm run build (or leave "npm run dev" watching).');

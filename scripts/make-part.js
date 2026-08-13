#!/usr/bin/env node
/**
 * Scaffold a new PHP part folder under components/{slug}/.
 *
 * Usage:
 *   npm run make:part -- newsletter
 *   npm run make:part -- newsletter "Newsletter Signup"
 *
 * Generates:
 *   components/{slug}/{slug}.php   — the partial template (rendered via get_part)
 *   components/{slug}/_{slug}.scss — auto-imported (frontend + editor)
 *
 * No further wiring needed: SCSS pickup is require.context driven.
 * Render the part anywhere with: get_part( '{slug}' );
 */

const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const positional = args.filter(a => !a.startsWith('--'));

const slug = positional[0];

if (!slug || !/^[a-z][a-z0-9-]*$/.test(slug)) {
	console.error('Usage: npm run make:part -- <slug> ["Title"]');
	console.error('Slug must be lowercase letters, numbers and hyphens (e.g. "newsletter", "social-links").');
	process.exit(1);
}

const title = positional[1] || slug.split('-').map(w => w[0].toUpperCase() + w.slice(1)).join(' ');

const themeRoot = path.resolve(__dirname, '..');
const partDir = path.join(themeRoot, 'components', slug);

if (fs.existsSync(partDir)) {
	console.error(`Part already exists: components/${slug}/`);
	process.exit(1);
}

const partPhp = `<?php
$title = $args['title'] ?? get_field( 'title' );
?>
<section class="${slug}">
    <div class="container">
        <div class="row">
            <div class="col-10-offset-1">
                <?php if ( ! empty( $title ) ) : ?>
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

fs.mkdirSync(partDir, { recursive: true });
fs.writeFileSync(path.join(partDir, `${slug}.php`), partPhp);
fs.writeFileSync(path.join(partDir, `_${slug}.scss`), scss);

console.log(`Created components/${slug}/`);
console.log(`  ${slug}.php`);
console.log(`  _${slug}.scss`);
console.log('\nRender it with:');
console.log(`  get_part( '${slug}' );`);
console.log(`  // or with args: get_part( '${slug}', [ 'title' => '${title}' ] );`);

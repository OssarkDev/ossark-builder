// CSS-only entry: MiniCssExtractPlugin will emit dist/editor.min.css.
// The tiny sibling dist/editor.min.js is not enqueued anywhere.
import './scss/editor.scss';

// Auto-import every _*.scss inside components/blocks/*/ so block styles
// also cascade inside the block-editor iframe.
const blockStyles = require.context('../components/blocks', true, /_[^/]+\.scss$/);
blockStyles.keys().forEach(blockStyles);

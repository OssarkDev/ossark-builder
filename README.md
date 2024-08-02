## Node version
`18.0 or above. Preferably 22.2.0`
## Yarn Version
`1.22.22`

---

## Install packages
`yarn install`

---

## Watch files while developing 
`yarn watch`

---

## Build CSS and JS for pushing to production
`yarn build`

---

*Make sure to run the build command before pushing, as this minifies and compresses the files*

---

## Folder structure
  `All images, icons & fonts are in the assets folder`
  `JS & SCSS are added to corresponding index files in the src folder`
  `Import all vendors in the main.js file`
  `All new JS is to be imported and declared in index.js`

## ACF
  `include/acf.php - create new block and add to block arrays at the bottom of the file. Array is called $all_blocks. WordPress will only show blocks from this Array`

---

## Layout Structure
- The layout is built using the same strucutr as bootstrap: `section->container->row->col`  
- You can offset columns by defining the col length and then the offset. The offset will be the amount of columns it's offset from the first, or col-1: `col-6-offset-2`
- In `_variables.scss` set the amount of colunns for laptop & mobile. The section and container paddings for different sizes are also set here.   
- There is a list of utility classes available for spacing. `.mt-48` is `margin-top: 48px`, `.pb-48` is `padding-bottom: 48px`, etc.  
- These classes are responsive, so you can set the mobile spacing variation of these classes. Add the corresponding sizes to `$desktop-change` & `$mobile-change` in `_variables.scss`
- There are column classes for dektop and mobile. You can uncomment the tablet if needed `line 101-118 in _layout.scss`

## Breakpoints & column classes
- mobile = col-sm
- tablet = col-md
- desktop = col
- hd-screen = col-lg

---

## SCSS
`all scss files to be listed in index.scss`

## SCSS Mixins 
`There is a collection of useful SCSS mixins and functions available`
- @include max-screen($size) `max-width breakpoint`
- @include min-screen($size) `min-width breakpoint`

---

## SVG Output
`Use the PHP function get_inline_svg(path). This path already begins in assetts. So it would be (icons/arrow.svg).`

---

## UI Kit
`There are some PHP fuinctions for simplifying front-end elements such as buttons. inc/ui_kit.php`

## Scroll Trigger
`There is a custom scroll trigger. use this by adding data-scroll to any div. When this div comes in to view, the class .in-view will be added.`

## Node version
`16.17 or above`

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

---

## Layout Structure
- The layout is built using the same strucutr as bootstrap: `section->container->row->col`  
- You can offset columns by defining the col length and then the offset: `col-6-offset-2`
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

## SCSS Mixins 
`There is a collection of useful SCSS mixins and functions available`
- +max-screen($size) `max-width breakpoint`
- +min-screen($size) `min-width breakpoint`

*You can enable extra Webpack features:*  
- `Compression plugin: dist files will be compressed to gzip, brotli, or choice`  
- `you can add extra entry points to split code chunks (main.js, chunk.js, etc)`  
- `Sprite functionalty is available, but not implemented.`


## WPML
- To use WPML, you should uncomment line 11 in `include\javascript_variables.php`
```

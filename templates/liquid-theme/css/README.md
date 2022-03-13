# LIQUID THEME

# PROJECT OVERVIEW

Liquid Theme Framework

## Description
LIQUID THEME based on the liquid precept

## Visuals

In progress

## Installation
Look DOC

## Usage
To liquidize theming !!!

## Support
By Deliwrapp Production team

## Roadmap
Currently in development

## Contributing
As you want !!!

## Authors and acknowledgment
By the Deliwrapp Production Team

## License
LGPL

## Project status
Currently in development


# DOC

## Introduction
The DELIWRAPP CLIENT APP

## Getting Started
Install locally or downland directly in you dev directory.

A CSS Liquid Framework using Flexbox.

- **Simple**: Provides just 2 base classes `liquid-grid` and `col` and some modifiers.
- **Flexible**: Easy to use Flexbox features.

[Flexible Box Layout Module](http://caniuse.com/#search=flex) and [`calc()` as CSS unit value](http://caniuse.com/#search=calc) used in Liquid-Theme are available on modern browsers (Chrome, Firefox, Safari, Opera, Edge and IE11).

### The Grid and the Col SKELETON

```html
<div class="liquid-grid">
  <div class="col -col-3">3of12</div>
  <div class="col -col-9">9of12</div>
</div>
```

#### `liquid-grid` modifiers

| Vertical layout | Description |
|---|---|
| `-top` | Pull items to top |
| `-middle` |  Pull items to middle |
| `-bottom` |  Pull items to bottom |
| `-stretch` | Stretch items |
| `-baseline` |  Pull items to baseline |

| Horizontal layout | Description |
|---|---|
| `-left` | Layout items to left |
| `-center` | Layout items To center |
| `-right` | Layout items to right |
| `-between` | Add spaces between items |
| `-around` | Add spaces around items |

#### `col` modifiers

| Col width | Description |
|---|---|
| `-fill` | Set item width to left |
| `-col-1` | Set item width to 8.3% |
| `-col-2` | Set item width to 16.7% |
| `-col-3` | Set item width to 25% |
| `-col-4` | Set item width to 33% |
| `-col-5` | Set item width to 41.7% |
| `-col-6` | Set item width to 50% |
| `-col-7` | Set item width to 58.3% |
| `-col-8_` | Set item width to 66.7% |
| `-col-9` | Set item width to 75% |
| `-col-10` | Set item width to 83.3% |
| `-col-11` | Set item width to 91.7% |
| `-col-12` | Set item width to 100% |


#### Skeleton `GRID` Full / Tablett / Desktop

```css
.liquid-grid            { display: flex; flex-wrap: wrap; }
.liquid-grid.\-top      { align-items: flex-start; }
.liquid-grid.\-middle   { align-items: center; }
.liquid-grid.\-bottom   { align-items: flex-end; }
.liquid-grid.\-stretch  { align-items: stretch; }
.liquid-grid.\-baseline { align-items: baseline; }
.liquid-grid.\-left     { justify-content: flex-start; }
.liquid-grid.\-center   { justify-content: center; }
.liquid-grid.\-right    { justify-content: flex-end; }
.liquid-grid.\-between  { justify-content: space-between; }
.liquid-grid.\-around   { justify-content: space-around; }

.col          { box-sizing: border-box; flex-shrink: 0; padding: 0.5rem; }
.col.\-fill   { width: 0; min-width: 0; flex-grow: 1; }
.col.\-col-1  { width: calc(100% * 1 / 12); }
.col.\-col-2  { width: calc(100% * 2 / 12); }
.col.\-col-3  { width: calc(100% * 3 / 12); }
.col.\-col-4  { width: calc(100% * 4 / 12); }
.col.\-col-5  { width: calc(100% * 5 / 12); }
.col.\-col-6  { width: calc(100% * 6 / 12); }
.col.\-col-7  { width: calc(100% * 7 / 12); }
.col.\-col-8  { width: calc(100% * 8 / 12); }
.col.\-col-9  { width: calc(100% * 9 / 12); }
.col.\-col-10 { width: calc(100% * 10 / 12); }
.col.\-col-11 { width: calc(100% * 11 / 12); }
.col.\-col-12 { width: 100%; }

.col.\-xs1  { width: calc(100% * 1 / 12); }
.col.\-xs2  { width: calc(100% * 2 / 12); }
.col.\-xs3  { width: calc(100% * 3 / 12); }
.col.\-xs4  { width: calc(100% * 4 / 12); }
.col.\-xs5  { width: calc(100% * 5 / 12); }
.col.\-xs6  { width: calc(100% * 6 / 12); }
.col.\-xs7  { width: calc(100% * 7 / 12); }
.col.\-xs8  { width: calc(100% * 8 / 12); }
.col.\-xs9  { width: calc(100% * 9 / 12); }
.col.\-xs10 { width: calc(100% * 10 / 12); }
.col.\-xs11 { width: calc(100% * 11 / 12); }
.col.\-xs12 { width: 100%; }

.grid {
    display: grid;
    grid-gap: 0.5rem;
    grid-template-columns: repeat(12, 1fr);
    margin: 0 auto;
    width: 100%;
    max-width: 100x;
}
.grid.\-text-center {text-align: center;}
.grid.\-text-left {text-align: left;}
.grid.\-text-right {text-align: right;}
.grid.\-text-end {text-align: end;}
.grid.\-text-start {text-align: start;}
.grid.\-baseline {justify-items: baseline;}
.grid.\-stretch {justify-items: stretch;}
.grid.\-center {justify-items: center;}
.grid.\-left {justify-items: left;}
.grid.\-right {justify-items: right;}
.grid.\-end {justify-items: flex-end;}
.grid.\-start {justify-items: flex-start;}
.grid.\-baseline {align-items: baseline;}
.grid.\-align-center {align-items: center;}
.grid.\-align-stretch {align-items: stretch;}
.grid.\-align-end {align-items: flex-end;}
.grid.\-align-start {align-items: flex-start;}

.g-1, .g-2, .g-3, .g-4, .g-5, .g-6,
.g-7, .g-8, .g-9, .g-10, .g-11, .g-12 {
    grid-column-end: span 12;
}

.grid-nested {
    display: grid;
    grid-gap: 10px;
    grid-template-columns: repeat(12, 1fr);
}

.g-2-rows { grid-row-end: span 2 }
.g-3-rows { grid-row-end: span 3 }
.g-4-rows { grid-row-end: span 4 }
.g-5-rows { grid-row-end: span 5 }
.g-6-rows { grid-row-end: span 6 }
.g-7-rows { grid-row-end: span 7 }
.g-8-rows { grid-row-end: span 8 }
.g-9-rows { grid-row-end: span 9 }
.g-10-rows { grid-row-end: span 10 }
.g-11-rows { grid-row-end: span 11 }
.g-12-rows { grid-row-end: span 12 }

.g-1 { grid-column-end: span 1 }
.g-2 { grid-column-end: span 2 }
.g-3 { grid-column-end: span 3 }
.g-4 { grid-column-end: span 4 }
.g-5 { grid-column-end: span 5 }
.g-6 { grid-column-end: span 6 }
.g-7 { grid-column-end: span 7 }
.g-8 { grid-column-end: span 8 }
.g-9 { grid-column-end: span 9 }
.g-10 { grid-column-end: span 10 }
.g-11 { grid-column-end: span 11 }
.g-12 { grid-column-end: span 12 }


@media (min-width: 476px) {

    .col.\-sm1  { width: calc(100% * 1 / 12); }
    .col.\-sm2  { width: calc(100% * 2 / 12); }
    .col.\-sm3  { width: calc(100% * 3 / 12); }
    .col.\-sm4  { width: calc(100% * 4 / 12); }
    .col.\-sm5  { width: calc(100% * 5 / 12); }
    .col.\-sm6  { width: calc(100% * 6 / 12); }
    .col.\-sm7  { width: calc(100% * 7 / 12); }
    .col.\-sm8  { width: calc(100% * 8 / 12); }
    .col.\-sm9  { width: calc(100% * 9 / 12); }
    .col.\-sm10 { width: calc(100% * 10 / 12); }
    .col.\-sm11 { width: calc(100% * 11 / 12); }
    .col.\-sm12 { width: 100%; }
}


```

TABLETT
```css

```

DESKTOP
```css

@media (min-width: 996px) {   
    .col.\-l1  { width: calc(100% * 1 / 12); }
    .col.\-l2  { width: calc(100% * 2 / 12); }
    .col.\-l3  { width: calc(100% * 3 / 12); }
    .col.\-l4  { width: calc(100% * 4 / 12); }
    .col.\-l5  { width: calc(100% * 5 / 12); }
    .col.\-l6  { width: calc(100% * 6 / 12); }
    .col.\-l7  { width: calc(100% * 7 / 12); }
    .col.\-l8  { width: calc(100% * 8 / 12); }
    .col.\-l9  { width: calc(100% * 9 / 12); }
    .col.\-l10 { width: calc(100% * 10 / 12); }
    .col.\-l11 { width: calc(100% * 11 / 12); }
    .col.\-l12 { width: 100%; } 
}

@media (min-width: 1250px) { 
    
    .col.\-xl1  { width: calc(100% * 1 / 12); }
    .col.\-xl2  { width: calc(100% * 2 / 12); }
    .col.\-xl3  { width: calc(100% * 3 / 12); }
    .col.\-xl4  { width: calc(100% * 4 / 12); }
    .col.\-xl5  { width: calc(100% * 5 / 12); }
    .col.\-xl6  { width: calc(100% * 6 / 12); }
    .col.\-xl7  { width: calc(100% * 7 / 12); }
    .col.\-xl8  { width: calc(100% * 8 / 12); }
    .col.\-xl9  { width: calc(100% * 9 / 12); }
    .col.\-xl10 { width: calc(100% * 10 / 12); }
    .col.\-xl11 { width: calc(100% * 11 / 12); }
    .col.\-xl12 { width: 100%; }
    
}

```

#### Skeleton `CONTAINER` 

```css
.liquid-page-container {
    padding-top: var(--liquid-page-padding-t);
    padding-bottom: var(--liquid-page-padding-b);
    padding-right: var(--liquid-page-padding-r);
    padding-left: var(--liquid-page-padding-l);
}
.liquid-container {
    margin: 0 auto;
    padding: 0;
    height: 100%;
    width: var(--liquid-container-width);
}
.dynamic-container {
    display: flex;
    flex-wrap: nowrap;
    overflow: hidden;
}
.dynamic-container .\-dynamic-section {
    display: var(--dynamic-section-display-default);
    flex-direction: var(--dynamic-section-direction);
    justify-content: var(--dynamic-section-align-content);
    align-items: var(--dynamic-section-align-items);
    position: relative;
    top: var(--dynamic-section-top);
    height: var(--dynamic-section-height);
    width: var(--dynamic-section-width);
    opacity: var(--dynamic-section-default-opacity);
    transition: var(--dynamic-section-trans);
}
/* Styles applied on trigger */
.dynamic-container .\-dynamic-section:target,
.dynamic-container .\-dynamic-section.\-active {
    display: var(--dynamic-section-display-active-default);
    opacity: var(--dynamic-section-default-active-opacity);
    position: relative;
    left: var(--dynamic-section-active-left);
    width: var(--dynamic-section-active-width);
    height: var(--dynamic-section-active-height);
    z-index: var(--dynamic-section-active-index);
}
.dynamic-container .\-dynamic-section:target.\-anim *,
.dynamic-container .\-dynamic-section.\-active.\-anim *  {
    opacity: var(--dynamic-section-active-default-opacity);
    animation: var(--dynamic-section-active-default-anim);
}
.\-limit-to-screen {
    max-height: 100vw;
    max-width: 100vw;
}
.\-container-xl {
    max-width: var(--container-xl-max-width);
}
.\-container-lg {
    max-width: var(--container-lg-max-width);
}
.\-container-md {
    max-width: var(--container-md-max-width);
}
.\-container-sm {
    max-width: var(--container-sm-max-width);
}
.\-container-xs {
    max-width: var(--container-xs-max-width);
}

```

#### Skeleton `FLUID` 

```css
in progress


```


### SKELETON PURPOSES

#### Skeleton `BASE` 

```css
/*------ROOT------*/
html {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
    -moz-osx-font-smoothing: grayscale;
    -moz-font-smoothing: antialiased;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: var(--global-scroll-behavior);
}
html:focus-within {
    scroll-behavior: var(--global-scroll-behavior);
}
body {
    margin: 0;
    font-family: var(--font-familly);
    font-size: var(--font-size);
    line-height: var(--line-height);
    color: var(--font-color);
}
html,
body {
    min-height: 100%;
    margin: 0;
}
/*------ELEMENTS------*/
a {
    text-decoration: none;
    color: var(--link-color);
}
a:hover, a:focus {
    text-decoration: underline;
    color: var(--link-color);
}

input,
button,
optgroup,
select,
textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}

hr {
    height: 0;
    border: 0;
    border-top: 1px solid var(--hr-border-color);
    margin-top: var(--hr-margin-top);
    margin-bottom: var(--hr-margin-bottom);
    width: var(--hr-w);
}

ul {
    list-style: disc inside;
}
ul ul {
    list-style-type: circle;
}
ol {
    list-style: decimal inside;
}
ol ol {
    list-style-type: lower-alpha;
}
ul, ol {
    margin-top: 0;
    margin-bottom: 0.25rem;
}
ul, ol {
    margin-bottom: 0;
}
ul.unstyled, ol.unstyled {
    padding-left: 0;
    list-style: none;
}
ul.inline, ol.inline {
    padding-left: 0;
    list-style: none;
    margin-left: -0.25rem;
}
ul.inline  > li, ol.inline > li {
    display: inline-block;
    padding-left: 0.25rem;
    padding-right: O.25rem;
}
dl dt {
    font-weight: bold;
}
dl dd {
    margin: .4rem 0 .8rem 0;
}
p {
    margin: 0 0 O.25rem 0;
}
p.light {
    color: var(--liquid-color-light);
}
article,
aside,
details,
figcaption,
figure,
footer,
header,
main,
menu,
nav,
section,
summary {
    display: block;
}
audio,
canvas,
progress,
video {
    display: inline-block;
}
/** Add the correct display in iOS 4-7. */
audio:not([controls]) {
    display: none;
    height: 0;
}
/** Remove the border on images inside links in IE 10-. */
img {
    border-style: none;
}
/** Hide the overflow in IE. */
svg:not(:root) {
    overflow: hidden;
}
progress {
    vertical-align: baseline;
}
object,
embed,
iframe {
    max-width: 100%;
}
a:active,a:hover {
    outline: 0;
}
blockquote,
blockquote:before,
blockquote:after,
q,
q:before,
q:after {
    content: '';
    content: none;
}
code, pre {
    word-break: break-word;
}
sub,
sup {
    position: relative;
    font-size: 75%;
    line-height: 0;
}
sup {top: -0.5em;}
sub {bottom: -0.25em;}

a,
ins,
u {
  -webkit-text-decoration-skip: ink edges;
  text-decoration-skip: ink edges;
}
blockquote {
  border-left: .1rem solid #dadee4;
  margin-left: 0;
  padding: .4rem .8rem;
}
blockquote p:last-child {
  margin-bottom: 0;
}
/*------SPECIAL------*/
code,
kbd,
pre,
samp {
  font-family: "SF Mono", "Segoe UI Mono", "Roboto Mono", Menlo, Courier, monospace; /* 1 (changed) */
  font-size: 1rem; /* 2 */
}
dfn {
  font-style: italic;
}
pre {
    display: block;
    text-align: left;
    width:-webkit-fill-available;
    font-family: monospace, Consolas;
    white-space:pre-wrap;
    overflow-x:auto;
    margin: 1rem 0;
    border: solid 1px rgb(220 220 220);
    transition: all 0.25s ease;
}
pre[data-lang]::before { 
    content: attr(data-lang); 
    display: block;
    background: var(--liquid-color-primary);
    padding: 0.5rem;
    margin: 0;
}
pre[data-lang="html"]::before { 
    background: orange;
}
pre[data-lang="css"]::before { 
    background: blueviolet;
}
pre[data-lang="js"]::before { 
    background: yellow;
}
pre[data-lang="vue"]::before { 
    background: greenyellow;
}
pre[data-lang="react"]::before { 
    background: blue;
}
pre:hover, pre:focus {padding: 0.25rem; cursor: help;}
code {
    display: block;
    padding: 0.5rem;
    color: #F60;
    background:#f5f5f5;
    margin: 0.1rem;
}
p code {
    display: inline-block;
}
/* Interactive ========================================================================== */
/* Add the correct display in IE 9-. 1. Add the correct display in Edge, IE, and Firefox. */
details,
menu {
  display: block;
}
/* Add the correct display in all browsers. */
summary {
  display: list-item;
  outline: none;
}
/* Scripting ========================================================================== */
/** Add the correct display in IE 9-. */
canvas {
  display: inline-block;
}
/** Add the correct display in IE. */
template {
  display: none;
}
/* Hidden ========================================================================== */
/** Add the correct display in IE 10-. */
[hidden] {
  display: none;
}

*,
*::before,
*::after {
  box-sizing: inherit;
}
/*------SCROLL------*/
.\-scroll-auto {
    scroll-behavior: auto;
}
.\-scroll-smooth {
    scroll-behavior: auto;
}
.\-scroll-unset {
    scroll-behavior: unset;
}
.\-scroll-initial {
    scroll-behavior: initial;
}
.\-overflow-auto {
    overflow: auto;
}
.\-overflow-hidden {
    overflow: hidden;
}
.\-overflow-visible {
    overflow: visible;
}
.\-overflow-scroll {
    overflow: scroll;
}
.\-overflow-x-auto {
    overflow-x: auto;
}
.\-overflow-x-hidden {
    overflow-x: hidden;
}
.\-overflow-x-visible {
    overflow-x: visible;
}
.\-overflow-x-scroll {
    overflow-x: scroll;
}
.\-overflow-y-auto {
    overflow-y: auto;
}
.\-overflow-y-hidden {
    overflow-y: hidden;
}
.\-overflow-y-visible {
    overflow-y: visible;
}
.\-overflow-y-scroll {
    overflow-y: scroll;
}
/*------DIRECTION------*/
.\-rtl {direction: rtl;}
.\-ltr {direction: ltr;}
/*------OTHERS------*/
.\-user-select-none {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.\-pointer-events-none {pointer-events: none;}

```

#### Skeleton `HEADING` 

```css
h1, .h1 {
    font-family: var(--headings-h1-font-family);
    line-height: var(--headings-h1-line-weight);
    color: var(--headings-h1-color);
    font-weight: var(--headings-h1-weight);
    font-size: var(--headings-h1-size);
    margin: var(--headings-h1-margin-t) 0 var(--headings-h1-margin-b) 0;
    
    /* @include responsive-size(floor($headingsH1Size * .8), $headingsH1Size); */
}
h2, .h2 {
    font-family: var(--headings-h2-font-family);
    line-height: var(--headings-h2-line-weight);
    color: var(--headings-h2-color);
    font-weight: var(--headings-h2-weight);
    font-size: var(--headings-h2-size);
    margin: var(--headings-h2-margin-t) 0 var(--headings-h2-margin-b) 0;
}
h3, .h3 {
    font-family: var(--headings-h3-font-family);
    line-height: var(--headings-h3-line-weight);
    color: var(--headings-h3-color);
    font-weight: var(--headings-h3-weight);
    font-size: var(--headings-h3-size);
    margin: var(--headings-h3-margin-t) 0 var(--headings-h3-margin-b) 0;
}
h4, .h4 {
    font-family: var(--headings-h4-font-family);
    line-height: var(--headings-h4-line-weight);
    color: var(--headings-h4-color);
    font-weight: var(--headings-h4-weight);
    font-size: var(--headings-h4-size);
    margin: var(--headings-h4-margin-t) 0 var(--headings-h4-margin-b) 0;
}
h5, .h5 {
    font-family: var(--headings-h5-font-family);
    line-height: var(--headings-h5-line-weight);
    color: var(--headings-h5-color);
    font-weight: var(--headings-h5-weight);
    font-size: var(--headings-h5-size);
    margin: var(--headings-h5-margin-t) 0 var(--headings-h5-margin-b) 0;
}
h6, .h6 {
    font-family: var(--headings-h6-font-family);
    line-height: var(--headings-h6-line-weight);
    color: var(--headings-h6-color);
    font-weight: var(--headings-h6-weight);
    font-size: var(--headings-h6-size);
    margin: var(--headings-h6-margin-t) 0 var(--headings-h6-margin-b) 0;
}

```

#### Skeleton `TYPO` 

```css
abbr[title] {
    border-bottom: .05rem dotted;
    cursor: help;
    text-decoration: none;
}
kbd {
    background: var(--liquid-color-dark);
    color: var(--liquid-color-dark-contrast);
    font-size: .7rem; 
    line-height: 1.25;
    padding: .1rem .2rem;
    border-radius: .1rem;
}
mark {
    background: var(--mark-bg);
    color: var(--mark-color);
    border-bottom: .1rem solid var(--mark-bg);
    border-radius: var(--mark-radius);
    padding: var(--mark-padding);
}
.\-t-tiny {
    font-size: var(--text-tiny-font-size);
}
.\-t-muted {
    opacity: var(--text-muted-opacity);
}
.\-t-ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.\-t-clip {
    overflow: hidden;
    text-overflow: clip;
    white-space: nowrap;
}
.\-t-break {
    -webkit-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto;
    word-break: break-word;
    word-wrap: break-word;
}
.\-t-u {
    text-transform: uppercase;
}
.\-t-l {
    text-transform: lowercase;
}
.\-t-c {
    text-transform: capitalize;
}
.\-t-o {
    font-style: oblique;
}
.\-t-i {
    font-style: italic;
}
.\-t-n {
    font-style: normal;
}
.\-t-lght {
    font-weight: 200;
}
.\-t-lghtr {
    font-weight: lighter;
}
.\-t-nrm {
    font-weight: 400;
}
.\-t-bld {
    font-weight: bold;
}
.\-t-bldr {
    font-weight: bolder;
}
.\-t-dec-n {
    text-decoration: none;
}
.\-t-dec-u {
    text-decoration: underline;
}
.\-t-dec-o {
    text-decoration: overline;
}
.\-t-dec-lt {
    text-decoration: line-through;
}
.\-t-dec-b {
    text-decoration: blink;
}
.\-t-800 {
    font-weight: 800;
}
.\-t-1rm {
    font-size: 1rem;
}
.\-t-2rm {
    font-size: 2rem;
}
.\-t-3rm {
    font-size: 3rem;
}
.\-t-4rm {
    font-size: 4rem;
}
.\-t-5rm {
    font-size: 5rem;
}
.\-t-indent-05 {
    text-indent: 0.5rem;
}
.\-t-indent-1 {
    text-indent: 1rem;
}
.\-t-indent-15 {
    text-indent: 1.5rem;
}
.\-t-indent-2 {
    text-indent: 2rem;
}
.\-t-indent-25 {
    text-indent: 2.5rem;
}
.\-t-indent-3 {
    text-indent: 3rem;
}
.\-l-sp-n {
    letter-spacing: normal;
}
.\-l-sp-1p {
    letter-spacing: 1px;
}
.\-l-sp-25rm {
    letter-spacing: 0.25rem;
}
.\-l-sp-50rm {
    letter-spacing: 0.5rem;
}
.\-l-sp-75rm {
    letter-spacing: 0.75rem;
}
.\-l-sp-100rm {
    letter-spacing: 1rem;
}
.\-l-sp-125rm {
    letter-spacing: 1.25rem;
}
.\-l-sp-150rm {
    letter-spacing: 1.5rem;
}
.\-l-sp-175rm {
    letter-spacing: 1.75rem;
}
.\-l-sp-200 {
    letter-spacing: 2rem;
}
.\-line-h-n {
    line-height: normal;
}
.\-line-h-1 {
    line-height: 1;
}
.\-line-h-1-25 {
    line-height: 1.25;
}
.\-line-h-1-5 {
    line-height: 1.5;
}
.\-line-h-1-75 {
    line-height: 1.75;
}
.\-line-h-2 {
    line-height: 2;
}
.\-line-h-2-25 {
    line-height: 2.25;
}
.\-line-h-2-5 {
    line-height: 2.5;
}
.\-line-h-2-75 {
    line-height: 2.75;
}
.\-line-h-3 {
    line-height: 3;
}
.\-line-h-3-25 {
    line-height: 3.25;
}
.\-line-h-3-5 {
    line-height: 3.5;
}
.\-line-h-3-75 {
    line-height: 3.75;
}
.\-line-h-4 {
    line-height: 4;
}
.\-line-h-4-25 {
    line-height: 1.25;
}
.\-line-h-4-5 {
    line-height: 4.5;
}
.\-line-h-4-75 {
    line-height: 4.75;
}
.\-line-h-5 {
    line-height: 5;
}
.\-line-b-a {
    line-break: auto;
}
.\-line-b-l {
    line-break: loose;
}
.\-line-b-n {
    line-break: normal;
}
.\-line-b-s {
    line-break: strict;
}
.\-over-wrap-b-w {
    overflow-wrap: break-word;
}
.\-over-wrap-n {
    overflow-wrap: normal;
}
.\-w-break-all {
    word-break: break-all;
}
.\-w-keep-all {
    word-break: keep-all;
}
.\-w-norm {
    word-break: normal;
}
.\-hyp-m {
    hyphens: manual;
}
.\-hyp-n {
    hyphens: none;
}
.\-hyp-a {
    hyphens: auto;
}
.\-hyp-r {
    hyphens: revert;
}
.\-hyp-r {
    hyphens: revert;
}
.\-wh-sp-nw {
    white-space: nowrap;
}
.\-wh-sp-pre {
    white-space: pre;
}
.\-wh-sp-pre-w {
    white-space: pre-wrap;
}
.\-wh-sp-pre-l {
    white-space: pre-line;
}
.\-wh-sp-br-sp {
    white-space: break-spaces;
}
.text-primary {
    color: var(--liquid-color-primary) !important;
}
a.text-primary:focus,
a.text-primary:hover {
    color: var(--liquid-color-primary-shade);
}
a.text-primary:visited {
    color: var(--liquid-color-primary-shade);
}
.text-secondary {
    color: var(--liquid-color-secondary) !important;
}
a.text-secondary:focus,
a.text-secondary:hover {
    color: var(--liquid-color-secondary-shade);
}
a.text-secondary:visited {
    color: var(--liquid-color-secondary-shade);
}
.text-tertiary {
    color: var(--liquid-color-tertiary) !important;
}
a.text-tertiary:focus,
a.text-tertiary:hover {
    color: var(--liquid-color-tertiary-shade);
}
a.text-tertiary:visited {
    color: var(--liquid-color-tertiary-shade);
}
.text-gray {
    color: var(--liquid-color-medium) !important;
}
a.text-gray:focus,
a.text-gray:hover {
    color: var(--liquid-color-medium-shade);
}
a.text-gray:visited {
    color: var(--liquid-color-medium-shade);
}
.text-light {
    color: var(--liquid-color-light) !important;
}
a.text-light:focus,
a.text-light:hover {
    color: var(--liquid-color-light-shade);
}
a.text-light:visited {
    color: var(--liquid-color-light-shade);
}
.text-dark {
    color: var(--liquid-color-dark) !important;
}
a.text-dark:focus,
a.text-dark:hover {
    color: var(--liquid-color-dark-shade);
}
a.text-dark:visited {
    color: var(--liquid-color-dark-shade);
}
.text-success {
    color: var(--liquid-color-success) !important;
}
a.text-success:focus,
a.text-success:hover {
    color: var(--liquid-color-success-shade);
}
a.text-success:visited {
    color: var(--liquid-color-success-shade);
}
.text-warning {
    color: var(--liquid-color-warning) !important;
}
a.text-warning:focus,
a.text-warning:hover {
    color: var(--liquid-color-warning-shade);
}
a.text-warning:visited {
    color: var(--liquid-color-warning-shade);
}
.text-error {
    color: var(--liquid-color-danger) !important;
}
a.text-error:focus,
a.text-error:hover {
    color: var(--liquid-color-danger-shade);
}
a.text-error:visited {
    color: var(--liquid-color-danger-shade);
}
.divider,
.divider-vert {
  display: block;
  position: relative;
}

```

#### Skeleton `Z-INDEX` 

```css
.\-z-auto {z-index: auto;}

.\-z-500 {z-index: -500;}
.\-z-400 {z-index: -400;}
.\-z-300 {z-index: -300;}
.\-z-200 {z-index: -200;}
.\-z-100 {z-index: -100;}
.\-z-5 {z-index: -5;}
.\-z-4 {z-index: -4;}
.\-z-3 {z-index: -3;}
.\-z-2 {z-index: -2;}
.\-z-1 {z-index: -1;}
.\-z0 {z-index: 0;}
.\-z1 {z-index: 1;}
.\-z2 {z-index: 2;}
.\-z3 {z-index: 3;}
.\-z4 {z-index: 4;}
.\-z5 {z-index: 5;}

.\-z100 {z-index: 100;}
.\-z200 {z-index: 200;}
.\-z300 {z-index: 300;}
.\-z400 {z-index: 400;}
.\-z500 {z-index: 500;}
.\-z600 {z-index: 600;}
.\-z700 {z-index: 700;}
.\-z800 {z-index: 800;}
.\-z900 {z-index: 900;}
.\-z1000 {z-index: 1000;}

.\-z2000 {z-index: 2000;}
.\-z3000 {z-index: 3000;}
.\-z4000 {z-index: 4000;}
.\-z5000 {z-index: 5000;}
.\-z6000 {z-index: 6000;}
.\-z7000 {z-index: 7000;}
.\-z8000 {z-index: 8000;}
.\-z9000 {z-index: 9000;}
.\-z10000 {z-index: 10000;}

/* Valeurs globales */
.\-z-inherit{z-index: inherit;}
.\-z-init {z-index: initial;}
.\-z-rev{z-index: revert;}
.\-z-unset {z-index: unset;}

```

### HELPER 

#### Helper`POSITION` 

```css
.\-relative  { position: relative;}
.\-absolute  { position: absolute;}
.\-fixed     { position: fixed;}
.\-static    { position: static;}
.\-sticky    { position: sticky;}
.\-pos-unset { position: unset;}
.\-obj-l-t { object-position: left top;}
.\-obj-l-c { object-position: left center;}
.\-obj-l-b { object-position: left bottom;}
.\-obj-c-t { object-position: center top;}
.\-obj-c   { object-position: center center;}
.\-obj-c-b { object-position: center bottom;}
.\-obj-r-t { object-position: right top;}
.\-obj-r-c { object-position: right center;}
.\-obj-r-b { object-position: right bottom;}

.\-top{top: 0;}
.\-bottom{bottom: 0;}
.\-right{right: 0;}
.\-left{left: 0;}
.\-centered {
    display: block;
    float: none;
    margin-left: auto;
    margin-right: auto;
  }

.\-top-1rm {top: 1rem;}
.\-bottom-1rm {bottom: 1rem;}
.\-right-1rm {right: 1rem;}
.\-left-1rm {left: 1rem;}

.\-top-2rm {top: 2rem;}
.\-bottom-2rm {bottom: 2rem;}
.\-right-2rm {right: 2rem;}
.\-left-2rm {left: 2rem;}

.\-top-3rm {top: 3rem;}
.\-bottom-3rm {bottom: 3rem;}
.\-right-3rm {right: 3rem;}
.\-left-3rm {left: 3rem;}

.\-top-4rm {top: 4rem;}
.\-bottom-4rm {bottom: 4rem;}
.\-right-4rm {right: 4rem;}
.\-left-4rm {left: 4rem;}

.\-top-5rm {top: 5rem;}
.\-bottom-5rm {bottom: 5rem;}
.\-right-5rm {right: 5rem;}
.\-left-5rm {left: 5rem;}

.\-top-1 {top: 1%;}
.\-bottom-1 {bottom: 1%;}
.\-right-1 {right: 1%;}
.\-left-1 {left: 1%;}

.\-top-2 {top: 2%;}
.\-bottom-2 {bottom: 2%;}
.\-right-2 {right: 2%;}
.\-left-2 {left: 2%;}

.\-top-3 {top: 3%;}
.\-bottom-3 {bottom: 3%;}
.\-right-3 {right: 3%;}
.\-left-3 {left: 3%;}

.\-top-4 {top: 4%;}
.\-bottom-4 {bottom: 4%;}
.\-right-4 {right: 4%;}
.\-left-4 {left: 4%;}

.\-top-5 {top: 5%;}
.\-bottom-5 {bottom: 5%;}
.\-right-5 {right: 5%;}
.\-left-5 {left: 5%;}

.\-top-10 {top: 10%;}
.\-bottom-10 {bottom: 10%;}
.\-right-10 {right: 10%;}
.\-left-10 {left: 10%;}

.\-top-15 {top: 15%;}
.\-bottom-15 {bottom: 15%;}
.\-right-15 {right: 15%;}
.\-left-15 {left: 15%;}

.\-top-20 {top: 20%;}
.\-bottom-20 {bottom: 20%;}
.\-right-20 {right: 20%;}
.\-left-20 {left: 20%;}

.\-top-25 {top: 25%;}
.\-bottom-25 {bottom: 25%;}
.\-right-25 {right: 25%;}
.\-left-25 {left: 25%;}

.\-top-30 {top: 30%;}
.\-bottom-30 {bottom: 30%;}
.\-right-30 {right: 30%;}
.\-left-30 {left: 30%;}

.\-top-33 {top: 33%;}
.\-bottom-33 {bottom: 33%;}
.\-right-33 {right: 33%;}
.\-left-33 {left: 33%;}

.\-top-35 {top: 35%;}
.\-bottom-35 {bottom: 35%;}
.\-right-35 {right: 35%;}
.\-left-35 {left: 35%;}

.\-top-40 {top: 40%;}
.\-bottom-40 {bottom: 40%;}
.\-right-40 {right: 40%;}
.\-left-40 {left: 40%;}

.\-top-45 {top: 45%;}
.\-bottom-45 {bottom: 45%;}
.\-right-45 {right: 45%;}
.\-left-45 {left: 45%;}

.\-top-50 {top: 50%;}
.\-bottom-50 {bottom: 50%;}
.\-right-50 {right: 50%;}
.\-left-50 {left: 50%;}

.\-float-c {
    float: center;
}
.\-float-r {
    float: right;
}
.\-float-l {
    float: left;
}
.\-float-s {
    float: inline-start;
}
.\-float-e {
    float: inline-end;
}

.\-ord-1 {order: 1}
.\-ord-2 {order: 2}
.\-ord-3 {order: 3}
.\-ord-4 {order: 4}
.\-ord-5 {order: 5}
.\-ord-6 {order: 6}
.\-ord-7 {order: 7}
.\-ord-8 {order: 8}
.\-ord-9 {order: 9}
.\-ord-10 {order: 10}
.\-ord-11 {order: 11}
.\-ord-12 {order: 12}

.\-ord-neg-1 {order: -1}
.\-ord-neg-2 {order: -2}
.\-ord-neg-3 {order: -3}
.\-ord-neg-4 {order: -4}
.\-ord-neg-5 {order: -5}
.\-ord-neg-6 {order: -6}
.\-ord-neg-7 {order: -7}
.\-ord-neg-8 {order: -8}
.\-ord-neg-9 {order: -9}
.\-ord-neg-10 {order: -10}
.\-ord-neg-11 {order: -11}
.\-ord-neg-12 {order: -12}

```

#### Helper `FLEX HELPERS` 

```css
.\-flex { display: flex;}
.\-flex-inline {display: inline-flex}
.\-flex-fade {flex: 0 1 auto}
.\-flex-fame {flex: 1 0 auto}
.\-flex-auto {flex: 1 1 auto}
.\-flex-none {flex: 0 0 auto}
.\-flex-row {flex-direction: row;}
.\-flex-row-reverse{flex-direction: row-reverse;}
.\-flex-column {flex-direction: column;}
.\-flex-row-reverse{flex-direction: column-reverse;}

.\-wrap{flex-wrap: wrap;}
.\-nowrap{flex-wrap: nowrap;}
.\-wrap-reverse{flex-wrap: wrap-reverse;}

.\-flow-wrap {flex-flow: wrap;}
.\-flow-nowrap {flex-flow: nowrap;}
.\-flow-wrap-reverse {flex-flow: wrap-reverse;}
.\-flow-row {flex-flow: row;}
.\-flow-row-reverse {flex-flow: row-reverse;}
.\-flow-column {flex-flow: column;}
.\-flow-column-reverse {flex-flow: column-reverse;}

.\-grow-0 {flex-grow: 0}
.\-grow-1 {flex-grow: 1}
.\-grow-2 {flex-grow: 2}
.\-grow-3 {flex-grow: 3}
.\-grow-4 {flex-grow: 4}
.\-grow-5 {flex-grow: 5}
.\-grow-6 {flex-grow: 6}
.\-grow-7 {flex-grow: 7}
.\-grow-8 {flex-grow: 8}
.\-grow-9 {flex-grow: 9}
.\-grow-10 {flex-grow: 10}
.\-grow-11 {flex-grow: 11}
.\-grow-12 {flex-grow: 12}

.\-shrink-0 {flex-shrink: 0}
.\-shrink-1 {flex-shrink: 1}
.\-shrink-2 {flex-shrink: 2}
.\-shrink-3 {flex-shrink: 3}
.\-shrink-4 {flex-shrink: 4}
.\-shrink-5 {flex-shrink: 5}
.\-shrink-6 {flex-shrink: 6}
.\-shrink-7 {flex-shrink: 7}
.\-shrink-8 {flex-shrink: 8}
.\-shrink-9 {flex-shrink: 9}
.\-shrink-10 {flex-shrink: 10}
.\-shrink-11 {flex-shrink: 11}
.\-shrink-12 {flex-shrink: 12}

.\-basis-0 {flex-basis: 0%}
.\-basis-1 {flex-basis: 8.333333333%}
.\-basis-2 {flex-basis: 16.6666666666%}
.\-basis-3 {flex-basis: 25%}
.\-basis-4 {flex-basis: 33.3333333333%}
.\-basis-5 {flex-basis: 41.6666666666%}
.\-basis-6 {flex-basis: 50%}
.\-basis-7 {flex-basis: 58.333333333%}
.\-basis-8 {flex-basis: 66.6666666666%}
.\-basis-9 {flex-basis: 75%}
.\-basis-10 {flex-basis: 83.3333333333%}
.\-basis-11 {flex-basis: 91.6666666666%}
.\-basis-12 {flex-basis: 100%}
.\-basis-100vw {flex-basis: 100vw}
.\-basis-100vh {flex-basis: 100vh}
.\-basis-100vmax {flex-basis: 100vmax}
.\-basis-100vmin {flex-basis: 100vmin}
.\-basis-golden {flex-basis: 61.803398875%}
.\-basis-auto {flex-basis: auto}
.\-basis-content {flex-basis: content}

.\-flex-container{display: flex; flex-flow: row wrap;  width: 80%; margin: 0 auto}
.\-fc-10,.\-fc-12,.\-fc-17,.\-fc-20,.\-fc-25,.\-fc-33,.\-fc-40,.\-fc-50,.\-fc-60,.\-fc-67,.\-fc-75,.\-fc-80,.\-fc-83,.\-fc-100,.\-f-fluid {width:100%; margin:0}

.\-fc-fluid{flex:2}
.\-fc-clear{width: 100%}
.\-fc-nomargin{display: flex; flex-flow: row wrap; padding:0 !important; margin:0 !important}


@media (min-width: 576px) {
  .\-fc-10,.\-fc-12,.\-fc-17,.\-fc-20,.\-fc-25,.\-fc-33,.\-fc-40,.\-fc-50,.\-fc-60,.\-fc-67,.\-fc-75,.\-fc-80,.\-fc-83,.\-fc-100,.\-f-fluid{margin:0 0 0 var(--m)}
  .\-fc-10{width:calc(10% - var(--flex-container-margin))}
  .\-fc-12{width:calc(12.5% - var(--flex-container-margin))}
  .\-fc-17{width:calc(16.6667% - var(--flex-container-margin))}
  .\-fc-20{width:calc(20% - var(--flex-container-margin))}
  .\-fc-25{width:calc(25% - var(--flex-container-margin))}
  .\-fc-33{width:calc(33.3334% - var(--flex-container-margin))}
  .\-fc-40{width:calc(40% - var(--flex-container-margin))}
  .\-fc-50{width:calc(50% - var(--flex-container-margin))}
  .\-fc-60{width:calc(60% - var(--flex-container-margin))}
  .\-fc-67{width:calc(66.6667% - var(--flex-container-margin))}
  .\-fc-75{width:calc(75% - var(--flex-container-margin))}
  .\-fc-80{width:calc(80% - var(--flex-container-margin))}
  .\-fc-83{width:calc(83.3334% - var(--flex-container-margin))}
  .\-fc-100{width:calc(100% - var(--flex-container-margin))}
}

@media (orientation: portrait) {
  .\-flex-inline\@portrait {display: inline-flex}
  .\-flex\@portrait {display: flex}
  .\-flex-row\@portrait {flex-direction: row}
  .\-flex-row-reverse\@portrait {flex-direction: row-reverse}
  .\-flex-column\@portrait {flex-direction: column}
  .\-flex-column-reverse\@portrait {flex-direction: column-reverse}
  .\-nowrap\@portrait {flex-wrap: nowrap}
  .\-wrap\@portrait {flex-wrap: wrap}
  .\-wrap-reverse\@portrait {flex-wrap: wrap-reverse}
}
  
@media (orientation: landscape) {
  .\-flex-inline\@landscape {display: inline-flex}
  .\-flex\@landscape {display: flex}
  .\-flex-row\@landscape {flex-direction: row}
  .\-flex-row-reverse\@landscape {flex-direction: row-reverse}
  .\-flex-column\@landscape {flex-direction: column}
  .\-flex-column-reverse\@landscape {flex-direction: column-reverse}
  .\-nowrap\@landscape {flex-wrap: nowrap}
  .\-wrap\@landscape {flex-wrap: wrap}
  .\-wrap-reverse\@landscape {flex-wrap: wrap-reverse}
}
```

###### [`display`](https://www.w3.org/TR/css-flexbox-1/#flex-containers)
- `-flex-inline` for `inline-flex`
- `-flex` for `flex`

###### [`flex-flow`](https://www.w3.org/TR/css-flexbox-1/#flex-flow-property)

- Compose [`flex-direction`](#flex-direction) [`flex-wrap`](#flex-wrap)
- Default is `-row -nowrap`

###### [`flex-direction`](https://www.w3.org/TR/css-flexbox-1/#flex-direction-property)

- `-flex-row`
- `-flex-row-reverse`
- `-flex-column`
- `-flex-column-reverse`

###### [`flex-wrap`](https://www.w3.org/TR/css-flexbox-1/#flex-wrap-property)

- `-nowrap` for `nowrap`
- `-wrap` for `wrap`
- `-wrap-reverse` for `wrap-reverse`


###### [`order`](https://www.w3.org/TR/css-flexbox-1/#order-property)
- `-order-before`
- `-order-after`

###### [`align-items`](https://www.w3.org/TR/css-flexbox-1/#align-items-property)

###### [`align-self`](https://www.w3.org/TR/css-flexbox-1/#align-items-property)

###### [`justify-content`](https://www.w3.org/TR/css-flexbox-1/#justify-content-property)

###### [`align-content`](https://www.w3.org/TR/css-flexbox-1/#align-content-property)

###### [`flex`](https://www.w3.org/TR/css-flexbox-1/#flex-property)

<a name="flex-presets"></a>
<a name="flex-shorthand"></a>

Shorthand classes supply [common presets](https://www.w3.org/TR/css-flexbox-1/#flex-common)

- `.flex-fade` for `0 1 auto` aka shrinkable
- `.flex-fame` for `1 0 auto` aka growable
- `.flex-auto` for `1 1 auto` aka flexible
- `.flex-none` for `none` aka inflexible

Compose with [`grow`](#flex-grow) [`shrink`](#flex-shrink) [`basis`](#flex-basis)

###### [`flex-grow`](https://www.w3.org/TR/css-flexbox-1/#flex-grow-property)
- `-grow-0`
- `-grow-1`
- `-grow-2`
- `-grow-3`
- `-grow-4`
- `-grow-5`
- `-grow-6`
- `-grow-8`
- `-grow-7`
- `-grow-9`
- `-grow-10`
- `-grow-11`
- `-grow-12`

###### [`flex-shrink`](https://www.w3.org/TR/css-flexbox-1/#flex-shrink-property)
- `-shrink-0`
- `-shrink-1`
- `-shrink-2`
- `-shrink-3`
- `-shrink-4`
- `-shrink-5`
- `-shrink-6`
- `-shrink-7`
- `-shrink-8`
- `-shrink-9`
- `-shrink-10`
- `-shrink-11`
- `-shrink-12`

###### [`flex-basis`](https://www.w3.org/TR/css-flexbox-1/#flex-basis-property)
- `-basis-0` 0/12 grid
- `-basis-1` 1/12 grid
- `-basis-2` 2/12 grid
- `-basis-3` 3/12 grid
- `-basis-4` 4/12 grid
- `-basis-5` 5/12 grid
- `-basis-6` 6/12 grid
- `-basis-7` 7/12 grid
- `-basis-8` 8/12 grid
- `-basis-9` 9/12 grid
- `-basis-10` 10/12 grid
- `-basis-11` 11/12 grid
- `-basis-12` 12/12 grid
- `-basis-100vw`
- `-basis-100vh`
- `-basis-100vmax`
- `-basis-100vmin`
- `-basis-golden`
- `-basis-content`
- `-basis-auto`

###### area
<a name="size-control"></a>

Some [flexbugs](https://github.com/philipwalton/flexbugs) are solvable via min or max width or height

- `-area-min` sets both mins to `0` [re: nesting](https://goo.gl/3IZRMt)
- `-area-max` sets both maxes to `100%`

###### `@media`

Responsive [`orientation`](https://drafts.csswg.org/mediaqueries-4/#orientation) classes are available for [`flex-flow`](#flex-flow) and [`display`](#display) classes. Append [`@portrait`](#portrait) or [`@landscape`](#landscape) to these classes to limit them to that orientation. This affords layouts that flow or wrap differently based on viewport orientation or layouts that only flex in one orientation. Try the [#fitting](https://ryanve.github.io/flexboxes/#fitting) example in both portrait and landscape to see how it adapts. You can do this on a phone by rotating the phone or on a computer by resizing the browser window.

```html
class="flex flex-row@portrait flex-column@landscape"
```

###### `portrait`

- `-flex-row@portrait`
- `-flex-row-reverse@portrait`
- `-flex-column@portrait`
- `-flex-column-reverse@portrait`
- `-wrap@portrait`
- `-wrap-reverse@portrait`
- `-flex-inline@portrait`
- `-flex@portrait`

###### `landscape`

- `-flex-row@landscape`
- `-flow-row-reverse@landscape`
- `-flex-column@landscape`
- `-flow-column-reverse@landscape`
- `-wrap@landscape`
- `-wrap-reverse@landscape`
- `-flex-inline@landscape`
- `-flex@landscape`

##### Flex Container overview
Flex Container is minimal CSS framework made with Flex

* Minimal
* Responsive. 
* No unnecessary nesting.
* Fluid Column (even multiple times in one row)
* Flexible the main width can be any number or uint ex: 960px, 90% or whatever you like.

###### Code Demo

```html
<div class="fc-50">50%</div>
<div class="fc-50">50%</div>

<div class="fc-33">33,3%</div>
<div class="fc-33">33,3%</div>
<div class="fc-33">33,3%</div>

<div class="fc-25">25%</div>
<div class="fc-fluid">Fluid</div>
<div class="fc-17">17%</div>
<div class="fc-clear"></div> /* => Use clear when you have fluid column */

/* Multiple Fluid columns in one row */

<div class="fc-fluid">Fluid</div>
<div class="fc-25">25%</div>
<div class="fc-25">25%</div>
<div class="fc-fluid">Fluid</div>
<div class="fc-clear"></div>
```

#### Helper `PADDING` 

```css
.\-box-border {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.\-box-content	{
    -webkit-box-sizing: content-box;
    box-sizing: content-box;
}
.\-p-auto { padding: auto; }
.\-p1  { padding: 0.25rem; }
.\-p2  { padding: 0.50rem; }
.\-p3  { padding: 0.75rem; }
.\-p4  { padding: 1rem; }
.\-p5  { padding: 1.25rem; }
.\-p6  { padding: 1.5rem; }
.\-p7  { padding: 1.75; }
.\-p8  { padding: 2rem; }
.\-p9  { padding: 2.25rem; }
.\-p10 { padding: 2.5rem; }
.\-p10 { padding: 2.5rem; }

.\-pt1  { padding-top: 0.25rem; }
.\-pt2  { padding-top: 0.50rem; }
.\-pt3  { padding-top: 0.75rem; }
.\-pt4  { padding-top: 1rem; }
.\-pt5  { padding-top: 1.25rem; }
.\-pt6  { padding-top: 1.5rem; }
.\-pt7  { padding-top: 1.75; }
.\-pt8  { padding-top: 2rem; }
.\-pt9  { padding-top: 2.25rem; }
.\-pt10 { padding-top: 2.5rem; }

.\-pb1  { padding-bottom: 0.25rem; }
.\-pb2  { padding-bottom: 0.50rem; }
.\-pb3  { padding-bottom: 0.75rem; }
.\-pb4  { padding-bottom: 1rem; }
.\-pb5  { padding-bottom: 1.25rem; }
.\-pb6  { padding-bottom: 1.5rem; }
.\-pb7  { padding-bottom: 1.75; }
.\-pb8  { padding-bottom: 2rem; }
.\-pb9  { padding-bottom: 2.25rem; }
.\-pb10 { padding-bottom: 2.5rem; }

.\-pl1  { padding-left: 0.25rem; }
.\-pl2  { padding-left: 0.50rem; }
.\-pl3  { padding-left: 0.75rem; }
.\-pl4  { padding-left: 1rem; }
.\-pl5  { padding-left: 1.25rem; }
.\-pl6  { padding-left: 1.5rem; }
.\-pl7  { padding-left: 1.75; }
.\-pl8  { padding-left: 2rem; }
.\-pl9  { padding-left: 2.25rem; }
.\-pl10 { padding-left: 2.5rem; }

.\-pr1  { padding-right: 0.25rem; }
.\-pr2  { padding-right: 0.50rem; }
.\-pr3  { padding-right: 0.75rem; }
.\-pr4  { padding-right: 1rem; }
.\-pr5  { padding-right: 1.25rem; }
.\-pr6  { padding-right: 1.5rem; }
.\-pr7  { padding-right: 1.75; }
.\-p8  { padding-right: 2rem; }
.\-pr9  { padding-right: 2.25rem; }
.\-pr10 { padding-right: 2.5rem; }

.liquid-padding.\-p1  { padding: 10%; }
.liquid-padding.\-p2  { padding: 20%; }
.liquid-padding.\-p3  { padding: 30%; }
.liquid-padding.\-p4  { padding: 40%; }
.liquid-padding.\-p5  { padding: 50%; }
.liquid-padding.\-p6  { padding: 60%; }
.liquid-padding.\-p7  { padding: 70%; }
.liquid-padding.\-p8  { padding: 80%; }
.liquid-padding.\-p9  { padding: 90%; }
.liquid-padding.\-p10 { padding: 100%; }

.liquid-padding.\-pt1  { padding-top: 10%; }
.liquid-padding.\-pt2  { padding-top: 20%; }
.liquid-padding.\-pt3  { padding-top: 30%; }
.liquid-padding.\-pt4  { padding-top: 40%; }
.liquid-padding.\-pt5  { padding-top: 50%; }
.liquid-padding.\-pt6  { padding-top: 60%; }
.liquid-padding.\-pt7  { padding-top: 70%; }
.liquid-padding.\-pt8  { padding-top: 80%; }
.liquid-padding.\-pt9  { padding-top: 90%; }
.liquid-padding.\-pt10 { padding-top: 100%; }

.liquid-padding.\-pb1  { padding-bottom: 10%; }
.liquid-padding.\-pb2  { padding-bottom: 20%; }
.liquid-padding.\-pb3  { padding-bottom: 30%; }
.liquid-padding.\-pb4  { padding-bottom: 40%; }
.liquid-padding.\-pb5  { padding-bottom: 50%; }
.liquid-padding.\-pb6  { padding-bottom: 60%; }
.liquid-padding.\-pb7  { padding-bottom: 70%; }
.liquid-padding.\-pb8  { padding-bottom: 80%; }
.liquid-padding.\-pb9  { padding-bottom: 90%; }
.liquid-padding.\-pb10 { padding-bottom: 100%; }

.liquid-padding.\-pl1  { padding-left: 10%; }
.liquid-padding.\-pl2  { padding-left: 20%; }
.liquid-padding.\-pl3  { padding-left: 30%; }
.liquid-padding.\-pl4  { padding-left: 40%; }
.liquid-padding.\-pl5  { padding-left: 50%; }
.liquid-padding.\-pl6  { padding-left: 60%; }
.liquid-padding.\-pl7  { padding-left: 70%; }
.liquid-padding.\-pl8  { padding-left: 80%; }
.liquid-padding.\-pl9  { padding-left: 90%; }
.liquid-padding.\-pl10 { padding-left: 100%; }

.liquid-padding.\-pr1  { padding-right: 10%; }
.liquid-padding.\-pr2  { padding-right: 20%; }
.liquid-padding.\-pr3  { padding-right: 30%; }
.liquid-padding.\-pr4  { padding-right: 40%; }
.liquid-padding.\-pr5  { padding-right: 50%; }
.liquid-padding.\-pr6  { padding-right: 60%; }
.liquid-padding.\-pr7  { padding-right: 70%; }
.liquid-padding.\-pr8  { padding-right: 80%; }
.liquid-padding.\-pr9  { padding-right: 90%; }
.liquid-padding.\-pr10 { padding-right: 100%; }

```

#### Helper `MARGIN` 

```css
.\-m-auto { margin: auto; }
.\-m1  { margin: 0.25rem; }
.\-m2  { margin: 0.50rem; }
.\-m3  { margin: 0.75rem; }
.\-m4  { margin: 1rem; }
.\-m5  { margin: 1.25rem; }
.\-m6  { margin: 1.5rem; }
.\-m7  { margin: 1.75; }
.\-m8  { margin: 2rem; }
.\-m9  { margin: 2.25rem; }
.\-m10 { margin: 2.5rem; }

.\-mt1  { margin-top: 0.25rem; }
.\-mt2  { margin-top: 0.50rem; }
.\-mt3  { margin-top: 0.75rem; }
.\-mt4  { margin-top: 1rem; }
.\-mt5  { margin-top: 1.25rem; }
.\-mt6  { margin-top: 1.5rem; }
.\-mt7  { margin-top: 1.75; }
.\-mt8  { margin-top: 2rem; }
.\-mt9  { margin-top: 2.25rem; }
.\-mt10 { margin-top: 2.5rem; }

.\-mb1  { margin-bottom: 0.25rem; }
.\-mb2  { margin-bottom: 0.50rem; }
.\-mb3  { margin-bottom: 0.75rem; }
.\-mb4  { margin-bottom: 1rem; }
.\-mb5  { margin-bottom: 1.25rem; }
.\-mb6  { margin-bottom: 1.5rem; }
.\-mb7  { margin-bottom: 1.75; }
.\-mb8  { margin-bottom: 2rem; }
.\-mb9  { margin-bottom: 2.25rem; }
.\-mb10 { margin-bottom: 2.5rem; }

.\-ml1  { margin-left: 0.25rem; }
.\-ml2  { margin-left: 0.50rem; }
.\-ml3  { margin-left: 0.75rem; }
.\-ml4  { margin-left: 1rem; }
.\-ml5  { margin-left: 1.25rem; }
.\-ml6  { margin-left: 1.5rem; }
.\-ml7  { margin-left: 1.75; }
.\-ml8  { margin-left: 2rem; }
.\-ml9  { margin-left: 2.25rem; }
.\-ml10 { margin-left: 2.5rem; }

.\-mr1  { margin-right: 0.25rem; }
.\-mr2  { margin-right: 0.50rem; }
.\-mr3  { margin-right: 0.75rem; }
.\-mr4  { margin-right: 1rem; }
.\-mr5  { margin-right: 1.25rem; }
.\-mr6  { margin-right: 1.5rem; }
.\-mr7  { margin-right: 1.75; }
.\-m8  { margin-right: 2rem; }
.\-mr9  { margin-right: 2.25rem; }
.\-mr10 { margin-right: 2.5rem; }

.liquid-margin.\-m1  { margin: 10%; }
.liquid-margin.\-m2  { margin: 20%; }
.liquid-margin.\-m3  { margin: 30%; }
.liquid-margin.\-m4  { margin: 40%; }
.liquid-margin.\-m5  { margin: 50%; }
.liquid-margin.\-m6  { margin: 60%; }
.liquid-margin.\-m7  { margin: 70%; }
.liquid-margin.\-m8  { margin: 80%; }
.liquid-margin.\-m9  { margin: 90%; }
.liquid-margin.\-m10 { margin: 100%; }

.liquid-margin.\-mt1  { margin-top: 10%; }
.liquid-margin.\-mt2  { margin-top: 20%; }
.liquid-margin.\-mt3  { margin-top: 30%; }
.liquid-margin.\-mt4  { margin-top: 40%; }
.liquid-margin.\-mt5  { margin-top: 50%; }
.liquid-margin.\-mt6  { margin-top: 60%; }
.liquid-margin.\-mt7  { margin-top: 70%; }
.liquid-margin.\-mt8  { margin-top: 80%; }
.liquid-margin.\-mt9  { margin-top: 90%; }
.liquid-margin.\-mt10 { margin-top: 100%; }

.liquid-margin.\-mb1  { margin-bottom: 10%; }
.liquid-margin.\-mb2  { margin-bottom: 20%; }
.liquid-margin.\-mb3  { margin-bottom: 30%; }
.liquid-margin.\-mb4  { margin-bottom: 40%; }
.liquid-margin.\-mb5  { margin-bottom: 50%; }
.liquid-margin.\-mb6  { margin-bottom: 60%; }
.liquid-margin.\-mb7  { margin-bottom: 70%; }
.liquid-margin.\-mb8  { margin-bottom: 80%; }
.liquid-margin.\-mb9  { margin-bottom: 90%; }
.liquid-margin.\-mb10 { margin-bottom: 100%; }

.liquid-margin.\-ml1  { margin-left: 10%; }
.liquid-margin.\-ml2  { margin-left: 20%; }
.liquid-margin.\-ml3  { margin-left: 30%; }
.liquid-margin.\-ml4  { margin-left: 40%; }
.liquid-margin.\-ml5  { margin-left: 50%; }
.liquid-margin.\-ml6  { margin-left: 60%; }
.liquid-margin.\-ml7  { margin-left: 70%; }
.liquid-margin.\-ml8  { margin-left: 80%; }
.liquid-margin.\-ml9  { margin-left: 90%; }
.liquid-margin.\-ml10 { margin-left: 100%; }

.liquid-margin.\-mr1  { margin-right: 10%; }
.liquid-margin.\-mr2  { margin-right: 20%; }
.liquid-margin.\-mr3  { margin-right: 30%; }
.liquid-margin.\-mr4  { margin-right: 40%; }
.liquid-margin.\-mr5  { margin-right: 50%; }
.liquid-margin.\-mr6  { margin-right: 60%; }
.liquid-margin.\-mr7  { margin-right: 70%; }
.liquid-margin.\-mr8  { margin-right: 80%; }
.liquid-margin.\-mr9  { margin-right: 90%; }
.liquid-margin.\-mr10 { margin-right: 100%; }

```

#### Helper `ALIGN` 

```css
.align.\-content-space-around  { align-content: space-around; }
.align.\-content-space-between  { align-content: space-between;}
.align.\-content-center  { align-content: center; }
.align.\-content-start  { align-content: flex-start; }
.align.\-content-end  { align-content: flex-start; }
.align.\-content-stretch  { align-content: stretch; }
.align.\-content-unset  { align-content: unset; }
.align.\-content-inherit  { align-content: inherit; }

.align.\-self-baseline  { align-self: baseline; }
.align.\-self-center  { align-self: center; }
.align.\-self-start  { align-self: flex-start; }
.align.\-self-end  { align-self: flex-end; }
.align.\-self-stretch  { align-self: stretch; }
.align.\-self-unset  { align-self: unset; }
.align.\-self-inherit  { align-self: inherit; }

.align.\-items-baseline  { align-items: baseline!important; }
.align.\-items-center  { align-items: center!important; }
.align.\--items-start  { align-items: flex-start!important; }
.align.\-items-end  { align-items: flex-start!important; }
.align.\-items-stretch  { align-items: stretch!important; }
.align.\-items-unset  { align-items: unset!important; }
.align.\-items-inherit  { align-items: inherit!important; }

.\-justify-center  { justify-content: center!important; }
.\-justify-left  { justify-content: left!important; }
.\-justify-right  { justify-content: right!important; }
.\-justify-space-around  { justify-content: space-around!important; }
.\-justify-space-between  { justify-content: space-between!important; }
.\-justify-start  { justify-content: start!important; }
.\-justify-end  { justify-content: start!important; }
.\-justify-stretch  { justify-content: stretch!important; }
.\-justify-unset  { justify-content: unset!important; }
.\-justify-inherit  { justify-content: inherit!important; }
.\-justify-safe  { justify-content: safe!important; }
.\-justify-space-evenly  { justify-content: space-evenly!important; }

.\-justify-self-center  { justify-self: center; }
.\-justify-self-left  { justify-self: left; }
.\-justify-self-right  { justify-self: right; }
.\-justify-self-space-around  { justify-self: space-around; }
.\-justify-self-space-between  { justify-self: space-between; }
.\-justify-self-start  { justify-self: flex-start; }
.\-justify-self-end  { justify-self: flex-start; }
.\-justify-self-stretch  { justify-self: stretch; }
.\-justify-self-unset  { justify-self: unset; }
.\-justify-self-inherit  { justify-self: inherit; }

.\-justify-items-center  { justify-items: center; }
.\-justify-items-left  { justify-items: left; }
.\-justify-items-right  { justify-items: right; }
.\-justify-items-space-around  { justify-items: space-around; }
.\-justify-items-space-between  { justify-items: space-between; }
.\-justify-items-start  { justify-items: flex-start; }
.\-justify-items-end  { justify-items: flex-start; }
.\-justify-items-stretch  { justify-items: stretch; }
.\-justify-items-unset  { justify-items: unset; }
.\-justify-items-inherit  { justify-items: inherit; }

.\-text-center  {text-align: center;}
.\-text-left    {text-align: left;}
.\-text-right   {text-align: right;}
.\-text-end    {text-align: end;}
.\-text-start   {text-align: start;}
.\-text-justify   {text-align: justify;}
.\-text-align-last-r   {text-align-last: right;}
.\-text-align-last-l   {text-align-last: left;}
.\-text-align-last-c   {text-align-last: center;}

.\-v-baseline  {vertical-align: baseline;}
.\-v-top       {vertical-align: top;}
.\-v-middle    {vertical-align: middle;}
.\-v-bottom    {vertical-align: bottom;}
.\-v-baseline  {vertical-align: baseline;}
.\-v-baseline  {vertical-align: baseline;}
.\-v-t-top     {vertical-align: text-top;}
.\-v-t-bottom  {vertical-align: text-bottom;}

```

#### Helper `SIZING` 

```css
.\-area-min {min-height: 0; min-width: 0!important}
.\-area-max {max-height: 100%; max-width: 100%!important}

.\-w-0 { width: 0!important}
.\-w-f { width: 100%!important}
.\-w-em { width: 1e!important}
.\-w-rm { width: 1rem!important}
.\-w-vp { width: 100vw!important}
.\-w-a { width: auto!important}

.\-h-0 { height: 0!important}
.\-h-f { height: 100%!important}
.\-h-em { height: 1em!important}
.\-h-rm { height: 1rem!important}
.\-h-vp { height: 100vh !important}
.\-h-a { height: auto !important}

.\-max-n { max-width: none; max-height: none !important}
.\-max-0 { max-width: 0; max-height: 0 !important}
.\-min-0 { min-width: 0; min-height: 0 !important}
.\-min-a { min-width: auto; min-height: auto !important}
.\-max-vp { max-width: 100vw; max-height: 100vh !important}
.\-min-vp { min-width: 100vw; min-height: 100vh !important}

.\-w-free { max-width: none !important}
.\-w-fit { max-width: 100% !important}
.\-w-clip { max-width: 0 !important}
.\-w-force { min-width: 100% !important}

.\-h-free { max-height: none !important}
.\-h-fit { max-height: 100% !important}
.\-h-clip { max-height: 0 !important}
.\-h-force { min-height: 100% !important}

```
##### [classes](area.css)

###### `width`

- `-w-0` sets width to `0`
- `-w-f` sets width to `100%`
- `-w-em` sets width to `1em`
- `-w-rem` sets width to `1rem`
- `-w-vp` sets width to `100vw`
- `-w-auto` sets width to `auto`

###### `height`

- `-h-0` sets height to `0`
- `-hf` sets height to `100%`
- `-h-em` sets height to `1em`
- `-h-rem` sets height to `1rem`
- `-h-vp` sets height to `100vh`
- `-h-a` sets height to `auto`

###### `max`
- `-max-n` sets maxes to `none`
- `-max-0` sets maxes to `0`
- `-max-vp` sets maxes to viewport size
- `-w-free` sets max-width to `none`
- `-h-free` sets max-height to `none`
- `-w-fit` sets max-width to `100%`
- `-h-fit` sets max-height to `100%`
- `-w-clip` sets max-width to `0`
- `-h-clip` sets max-height to `0`

###### `min`
- `-min-0` sets mins to `0`
- `-min-a` sets mins to `auto`
- `-min-vp` sets mins to viewport size
- `-w-force` sets min-width to `100%`
- `-h-force` sets min-height to `100%`

###### usage

```html
<img class="-w-fit -h-a" src="example.png" alt="example">
```

#### Helper`LANG` 

```css
html:lang(zh),
html:lang(zh-Hans),
.lang-zh,
.lang-zh-hans {
  font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Helvetica Neue", sans-serif;
}

html:lang(zh-Hant),
.lang-zh-hant {
  font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "PingFang TC", "Hiragino Sans CNS", "Microsoft JhengHei", "Helvetica Neue", sans-serif;
}

html:lang(ja),
.lang-ja {
  font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Sans", "Hiragino Kaku Gothic Pro", "Yu Gothic", YuGothic, Meiryo, "Helvetica Neue", sans-serif;
}

html:lang(ko),
.lang-ko {
  font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Malgun Gothic", "Helvetica Neue", sans-serif;
}

:lang(zh) ins,
:lang(zh) u,
:lang(ja) ins,
:lang(ja) u,
.lang-cjk ins,
.lang-cjk u {
  border-bottom: .05rem solid;
  text-decoration: none;
}

:lang(zh) del + del,
:lang(zh) del + s,
:lang(zh) ins + ins,
:lang(zh) ins + u,
:lang(zh) s + del,
:lang(zh) s + s,
:lang(zh) u + ins,
:lang(zh) u + u,
:lang(ja) del + del,
:lang(ja) del + s,
:lang(ja) ins + ins,
:lang(ja) ins + u,
:lang(ja) s + del,
:lang(ja) s + s,
:lang(ja) u + ins,
:lang(ja) u + u,
.lang-cjk del + del,
.lang-cjk del + s,
.lang-cjk ins + ins,
.lang-cjk ins + u,
.lang-cjk s + del,
.lang-cjk s + s,
.lang-cjk u + ins,
.lang-cjk u + u {
  margin-left: .125em;
}

```

### EFFECTS

#### Effect`BACKGROUND` 

```css
.\-bg-auto {
    background-size: auto;
}
.\-bg-cover {
    background-size: cover;
}
.\-bg-contain {
    background-size: contain;
}
.\-bg-repeat {
    background-repeat: repeat;
}
.\-bg-no-repeat {
    background-repeat: no-repeat;
}
.\-bg-repeat-x {
    background-repeat: repeat-x;
}
.\-bg-repeat-y {
    background-repeat: repeat-y;
}
.\-bg-repeat-round {
    background-repeat: round;
}
.\-bg-repeat-space {
    background-repeat: space;
}
.\-bg-top {
    background-position: top;
}
.\-bg-bottom {
    background-position: bottom;
}
.\-bg-center {
    background-position: center;
}
.\-bg-left {
    background-position: left;
}
.\-bg-left-top {
    background-position: left top;
}
.\-bg-left-bottom {
    background-position: left bottom;
}
.\-bg-right {
    background-position: right;
}
.\-bg-right-top {
    background-position: right top;
}
.\-bg-right-bottom {
    background-position: right bottom;
}
.\-bg-fixed {
    background-attachment: fixed;
}
.\-bg-local {
    background-attachment: local;
}
.\-bg-scroll {
    background-attachment: scroll;
}

```

#### Effect`BORDER` 

```css
/*------COLOR------*/
.\-b-primary {
    border: var(--liquid-color-primary-shade) var(--border-primary-type) var(--border-primary-size);
}
.\-b-secondary {
    border: var(--liquid-color-secondary-shade) var(--border-secondary-type) var(--border-secondary-size);
}
.\-b-tertiary {
    border: var(--liquid-color-tertiary-shade) var(--border-tertiary-type) var(--border-tertiary-size);
}
.\-b-success {
    border: var(--liquid-color-success-shade) var(--border-success-type) var(--border-success-size);
}
.\-b-warning {
    border: var(--liquid-color-warning-shade) var(--border-warning-type) var(--border-warning-size);
}
.\-b-danger {
    border: var(--liquid-color-danger-shade) var(--border-danger-type) var(--border-danger-size);
}
.\-b-dark {
    border: var(--liquid-color-dark-shade) var(--border-dark-type) var(--border-dark-size);
}
.\-b-light {
    border: var(--liquid-color-light-shade) var(--border-light-type) var(--border-light-size);
}
/*------TYPE------*/
.\-b-dotted {border-style: dotted;}
.\-b-dashed {border-style: dashed;}
.\-b-solid {border-style: solid;}
.\-b-double {border-style: double;}
.\-b-groove {border-style: groove;}
.\-b-ridge {border-style: ridge;}
.\-b-inset {border-style: inset;}
.\-b-outset {border-style: outset;}
.\-b-none {border-style: none;}
.\-b-hidden {border-style: hidden;}
.\-b-mix {border-style: dotted dashed solid double;}
/*------SIZE------*/
.\-b-size-1 {border-width: 1px;}
.\-b-size-2 {border-width: 2px;}
.\-b-size-3 {border-width: 3px;}
.\-b-size-4 {border-width: 4px;}
.\-b-size-5 {border-width: 5px;}
.\-b-size-6 {border-width: 6px;}
.\-b-size-7 {border-width: 7px;}
.\-b-size-8 {border-width: 9px;}
.\-b-size-9 {border-width: 9px;}
.\-b-size-10 {border-width: 10px;}
.\-b-size-1rem {border-width: 1rem;}
.\-b-size-2rem {border-width: 2rem;}
.\-b-size-3rem {border-width: 3rem;}
.\-b-size-4rem {border-width: 4rem;}
.\-b-size-5rem {border-width: 5rem;}
/*------ROUNDED------*/
.\-b-rounded-0   {border-radius: 0;}
.\-b-rounded-sm  {border-radius: 5;}
.\-b-rounded     {border-radius: 10;}
.\-b-rounded-md  {border-radius: 15;}
.\-b-rounded-lg  {border-radius: 20;}
.\-b-rounded-100 {border-radius: 100%;}

/*------HOVER-COLOR------*/
.\-b-primary-hover:hover {
    border: var(--liquid-color-primary) var(--border-primary-type) var(--border-primary-size-hover);
}
.\-b-secondary-hover:hover {
    border: var(--liquid-color-secondary) var(--border-secondary-type) var(--border-secondary-size-hover);
}
.\-b-primary-hover:hover {
    border: var(--liquid-color-tertiary) var(--border-tertiary-type) var(--border-tertiary-size-hover);
}
.\-b-success-hover:hover {
    border: var(--liquid-color-success) var(--border-success-type) var(--border-success-size-hover);
}
.\-b-warning-hover:hover {
    border: var(--liquid-color-warning) var(--border-warning-type) var(--border-warning-size-hover);
}
.\-b-danger-hover:hover {
    border: var(--liquid-color-danger) var(--border-danger-type) var(--border-danger-size-hover);
}
.\-b-dark-hover:hover {
    border: var(--liquid-color-dark) var(--border-dark-type) var(--border-dark-size-hover);
}
.\-b-light-hover:hover {
    border: var(--liquid-color-light) var(--border-light-type) var(--border-light-size-hover);
}
/*------TYPE-HOVER------*/
.\-b-dotted-hover:hover {border-style: dotted;}
.\-b-dashed-hover:hover {border-style: dashed;}
.\-b-solid-hover:hover {border-style: solid;}
.\-b-double-hover:hover {border-style: double;}
.\-b-groove-hover:hover {border-style: groove;}
.\-b-ridge-hover:hover {border-style: ridge;}
.\-b-inset-hover:hover {border-style: inset;}
.\-b-outset-hover:hover {border-style: outset;}
.\-b-none-hover:hover {border-style: none;}
.\-b-hidden-hover:hover {border-style: hidden;}
.\-b-mix-hover:hover {border-style: dotted dashed solid double;}
/*------SIZE-HOVER------*/
.\-b-size-1-hover:hover {border-width: 1px;}
.\-b-size-2-hover:hover {border-width: 2px;}
.\-b-size-3-hover:hover {border-width: 3px;}
.\-b-size-4-hover:hover {border-width: 4px;}
.\-b-size-5-hover:hover {border-width: 5px;}
.\-b-size-6-hover:hover {border-width: 6px;}
.\-b-size-7-hover:hover {border-width: 7px;}
.\-b-size-8-hover:hover {border-width: 9px;}
.\-b-size-9-hover:hover {border-width: 9px;}
.\-b-size-10-hover:hover {border-width: 10px;}
.\-b-size-1rem-hover:hover {border-width: 1rem;}
.\-b-size-2rem-hover:hover {border-width: 2rem;}
.\-b-size-3rem-hover:hover {border-width: 3rem;}
.\-b-size-4rem-hover:hover {border-width: 4rem;}
.\-b-size-5rem-hover:hover {border-width: 5rem;}
/*------ROUNDED-HOVER------*/
.\-b-rounded-0-h:hover   {border-radius: 0;}
.\-b-rounded-sm-h:hover  {border-radius: 5;}
.\-b-rounded-h:hover    {border-radius: 10;}
.\-b-rounded-md-h:hover  {border-radius: 15;}
.\-b-rounded-lg-h:hover  {border-radius: 20;}
.\-b-rounded-100-h:hover {border-radius: 100%;}
/*------DIVIDER------*/
.divider[data-content]::after,
.divider-vert[data-content]::after {
  background: var(--liquid-color-light);
  color: var(--liquid-color-medium);
  content: attr(data-content);
  display: inline-block;
  font-size: var(--divider-after-font-size);
  padding: var(--divider-after-padding-v) var(--divider-after-padding-h);
  transform: translateY(var(--divider-after-translate-y));
}
.divider {
  border-top: var(--divider-border-top);
  height: var(--divider-height);
  margin: var(--divider-margin);
}
.divider[data-content] {
    margin: var(--divider-content-margin-v) var(--divider-content-margin-h);
}
.divider-vert {
  display: block;
  padding: var(--divider-vert-padding);
}
.divider-vert::before {
  border-left: var(--divider-vert-before-border-left);
  bottom: var(--divider-vert-before-bottom);
  content: "";
  display: block;
  left: var(--divider-vert-before-left);
  position: absolute;
  top: var(--divider-vert-before-top);
  transform: translateX(var(--divider-vert-before-translate-x));
}
.divider-vert[data-content]::after {
  left: var(--divider-vert-content-after-left);
  padding: var(--divider-vert-content-after-padding-v) var(--divider-vert-content-after-padding-h);
  position: absolute;
  top: var(--divider-vert-content-after-top);
  transform: translate(var(--divider-vert-content-after-translate-x),var(--divider-vert-content-after-translate-y));
}

```

#### Effect`DISPLAY` 

```css
/*------DISPLAY------*/
/* Valeurs de type <display-outside> */
.\-d-block {
    display: block;
}

.\-d-inline {
    display: inline;
}

.\-d-run-in {
    display: run-in;
}

/* Valeurs de type <display-inside> */
.\-d-flow {
    display: flow;
}

.\-d-flow-root {
    display: flow-root;
}

.\-d-table {
    display: table;
}

.\-d-flex {
    display: flex;
}

.\-d-geid {
    display: grid;
}

.\-d-ruby {
    display: ruby;
}

/* Combinaison de valeurs */
/* <display-outside> et <display-inside> */
.\-d-block-flow {
    display: block flow;
}

.\-d-inline-table {
    display: inline table;
}

.\-d-flex-run-in {
    display: flex run-in;
}

/* Valeurs de type <display-listitem> */
.\-d-list-item {
    display: list-item;
}

.\-d-list-item-block {
    display: list-item block;
}

.\-d-list-item-inline {
    display: list-item inline;
}

.\-d-list-item-flow {
    display: list-item flow;
}

.\-d-list-item-flow-root {
    display: list-item flow-root;
}

.\-d-list-item-block-flow {
    display: list-item block flow;
}

.\-d-list-item-block-flow-root {
    display: list-item block flow-root;
}

.\-d-flow-list-item-block {
    display: flow list-item block;
}

/* Valeurs de type <display-internal> */
.\-d-table-row-group {
    display: table-row-group;
}

.\-d-table-header-group {
    display: table-header-group;
}

.\-d-table-footer-group {
    display: table-footer-group;
}

.\-d-table-row {
    display: table-row;
}

.\-d-table-cell {
    display: table-cell;
}

.\-d-table-column-group {
    display: flex;
}

.\-d-table-column {
    display: flex;
}

.\-d-table-caption {
    display: table-caption;
}

.\-d-ruby-base {
    display: ruby-base;
}

.\-d-ruby-text {
    display: ruby-text;
}

.\-d-ruby-base-container {
    display: ruby-base-container;
}

.\-d-ruby-text-container {
    display: ruby-text-container;
}

/* Valeurs de type <display-box> */
.\-d-contents {
    display: contents;
}

.\-d-none {
    display: none;
}

/* Valeurs de type <display-legacy> */
.\-d-inline-block {
    display: inline-block;
}

.\-d-inline-table {
    display: inline-table;
}

.\-d-inline-grid {
    display: inline-grid;
}

/* Valeurs globales */
.\-d-inherit {
    display: inherit;
}

.\-d-initial {
    display: initial;
}

.\-d-unset {
    display: unset;
}

.\-visible {
    visibility: visible;
}

.\-invisible {
    visibility: hidden;
}

.\-backface-visible {
    -webkit-backface-visibility: visible;
    backface-visibility: visible;
}

.\-backface-hidden {
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

.text-hide {
    background: transparent;
    border: 0;
    color: transparent;
    font-size: 0;
    line-height: 0;
    text-shadow: none;
}

.show-xs,
.show-sm,
.show-md,
.show-lg,
.show-xl {
    display: none !important;
}
.empty {
    background: var(--liquid-color-light);
    border-radius: .1rem;
    color: var(--liquid-color-light-shade);
    padding: 3.2rem 1.6rem;
    text-align: center;
}
.empty .empty-icon {
    margin-bottom: .8rem;
}
.empty .empty-title,
.empty .empty-subtitle {
    margin: .4rem auto;
}
.empty .empty-action {
    margin-top: .8rem;
}

@media (max-width: 480px) {
    .hide-xs {
        display: none !important;
    }

    .show-xs {
        display: block !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .hide-sm {
        display: none !important;
    }

    .show-sm {
        display: block !important;
    }
}

@media (min-width: 768px) and (max-width: 995px) {
    .hide-md {
        display: none !important;
    }

    .show-md {
        display: block !important;
    }
}

@media (min-width: 996px) and (max-width: 1249px) {
    .hide-lg {
        display: none !important;
    }

    .show-lg {
        display: block !important;
    }
}

@media (min-width: 1250px) {
    .hide-xl {
        display: none !important;
    }

    .show-xl {
        display: block !important;
    }
}

```

#### Effect`OPACITY` 

```css
.\-opac-10 {
    opacity: 1;
}
.\-opac-9 {
    opacity: 0.9;
}
.\-opac-9 {
    opacity: 0.9;
}
.\-opac-8 {
    opacity: 0.8;
}
.\-opac-7 {
    opacity: 0.7;
}
.\-opac-6 {
    opacity: 0.6;
}
.\-opac-5 {
    opacity: 0.5;
}
.\-opac-4 {
    opacity: 0.4;
}
.\-opac-3 {
    opacity: 0.3;
}
.\-opac-2 {
    opacity: 0.2;
}
.\-opac-1 {
    opacity: 0.1;
}
.\-opac-0 {
    opacity: 0;
}
.\-opac-unset {
    opacity: unset;
}
.\-opac-init {
    opacity: initial;
}

```

#### Effect`COLORS` 

```css
.\-primary, .\-info {
    color: var(--liquid-color-primary-contrast)!important;
    background-color: var(--liquid-color-primary)!important;
}
.\-secondary {
    color: var(--liquid-color-secondary-contrast)!important;
    background-color: var(--liquid-color-secondary)!important;
}
.\-tertiary {
    color: var(--liquid-color-tertiary-contrast)!important;
    background-color: var(--liquid-color-tertiary)!important;
}
.\-dark {
    color: var(--liquid-color-dark-contrast)!important;
    background-color: var(--liquid-color-dark)!important;
}
.\-light {
    color: var(--liquid-color-light-contrast)!important;
    background-color: var(--liquid-color-light)!important;
}
.\-success {
    color: var(--liquid-color-success-contrast)!important;
    background-color: var(--liquid-color-success)!important;
}
.\-warning {
    color: var(--liquid-color-warning-contrast)!important;
    background-color: var(--liquid-color-warning)!important;
}
.\-danger {
    color: var(--liquid-color-danger-contrast)!important;
    background-color: var(--liquid-color-danger)!important;
}
.\-color-primary {
    color: var(--liquid-color-primary)!important;
}
.\-bg-primary {
    background-color: var(--liquid-color-primary)!important;
}
.\-color-primary-content {
    color: var(--liquid-color-primary-contrast)!important;
}

.\-color-secondary {
    color: var(--liquid-color-secondary)!important;
}
.\-bg-secondary {
    background-color: var(--liquid-color-secondary)!important;
}
.\-color-secondary-content {
    color: var(--liquid-color-secondary-contrast)!important;
}

.\-color-tertiary {
    color: var(--liquid-color-tertiary)!important;
}
.\-bg-tertiary {
    background-color: var(--liquid-color-tertiary)!important;
}
.\-color-tertiary-content {
    color: var(--liquid-color-tertiary-contrast)!important;
}

.\-color-success {
    color: var(--liquid-color-success)!important;
}
.\-bg-success {
    background-color: var(--liquid-color-success)!important;
}
.\-color-success-content {
    color: var(--liquid-color-success-contrast)!important;
}

.\-color-warning {
    color: var(--liquid-color-warning)!important;
}
.\-bg-warning {
    background-color: var(--liquid-color-warning)!important;
}
.\-color-warning-content {
    color: var(--liquid-color-warning-contrast)!important;
}

.\-color-danger {
    color: var(--liquid-color-danger)!important;
}
.\-bg-danger {
    background-color: var(--liquid-color-danger)!important;
}
.\-color-danger-content {
    color: var(--liquid-color-danger-contrast)!important;
}

.\-color-dark {
    color: var(--liquid-color-dark)!important;
}
.\-bg-dark {
    background-color: var(--liquid-color-dark)!important;
}
.\-color-dark-content {
    color: var(--liquid-color-dark-contrast)!important;
}

.\-color-light {
    color: var(--liquid-color-light)!important;
}
.\-bg-light {
    background-color: var(--liquid-color-light)!important;
}
.\-color-light-content {
    color: var(--liquid-color-light-contrast)!important;
}
.\-color-transparent {
    color:  transparent!important;
}
.\-bg-transparent {
    background-color: transparent!important;
}
.\-bg-none {
    background: none!important;
    color: var(--liquid-text-color);
}
/*------HOVER------*/
.\-primary.\-hover:hover, .\-info.\-hover:hover {
    color: var(--liquid-color-primary-contrast)!important;
    background-color: var(--liquid-color-primary-shade)!important;
}
.\-secondary.\-hover:hover {
    color: var(--liquid-color-secondary-contrast)!important;
    background-color: var(--liquid-color-secondary-shade)!important;
}
.\-tertiary.\-hover:hover {
    color: var(--liquid-color-tertiary-contrast)!important;
    background-color: var(--liquid-color-tertiary-shade)!important;
}
.\-dark.\-hover:hover {
    color: var(--liquid-color-dark-contrast)!important;
    background-color: var(--liquid-color-dark-shade)!important;
}
.\-light.\-hover:hover {
    color: var(--liquid-color-light-contrast)!important;
    background-color: var(--liquid-color-light-shade)!important;
}
.\-success.\-hover:hover {
    color: var(--liquid-color-success-contrast)!important;
    background-color: var(--liquid-color-success-shade)!important;
}
.\-warning.\-hover:hover {
    color: var(--liquid-color-warning-contrast)!important;
    background-color: var(--liquid-color-warning-shade)!important;
}
.\-danger.\-hover:hover {
    color: var(--liquid-color-danger-contrast)!important;
    background-color: var(--liquid-color-danger-shade)!important;
}
.\-color-primary.\-hover:hover  {
    color: var(--liquid-color-primary-shade)!important;
}
.\-bg-primary.\-hover:hover {
    background-color: var(--liquid-color-primary-contrast)!important;
}
.\-color-primary-content.\-hover:hover {
    color: var(--liquid-color-primary-shade)!important;
}

.\-color-secondary.\-hover:hover {
    color: var(--liquid-color-secondary-shade)!important;
}
.\-bg-secondary.\-hover:hover {
    background-color: var(--liquid-color-secondary-contrast)!important;
}
.\-color-secondary-content.\-hover:hover {
    color: var(--liquid-color-secondary-shade)!important;
}

.\-color-tertiary.\-hover:hover {
    color: var(--liquid-color-tertiary-shade)!important;
}
.\-bg-primary.\-hover:hover {
    background-color: var(--liquid-color-tertiary-contrast)!important;
}
.\-color-primary-content.\-hover:hover {
    color: var(--liquid-color-tertiary-shade)!important;
}

.\-color-success.\-hover:hover {
    color: var(--liquid-color-success-shade)!important;
}
.\-bg-success.\-hover:hover {
    background-color: var(--liquid-color-success-contrast)!important;
}
.\-color-success-content.\-hover:hover {
    color: var(--liquid-color-success-shade)!important;
}

.\-color-warning.\-hover:hover {
    color: var(--liquid-color-warning-shade)!important;
}
.\-bg-warning.\-hover:hover {
    background-color: var(--liquid-color-warning-contrast)!important;
}
.\-color-warning-content.\-hover:hover {
    color: var(--liquid-color-warning-shade)!important;
}

.\-color-dark.\-hover:hover {
    color: var(--liquid-color-dark-shade)!important;
}
.\-bg-dark.\-hover:hover {
    background-color: var(--liquid-color-dark-contrast)!important;
}
.\-color-dark-content.\-hover:hover {
    color: var(--liquid-color-dark-shade)!important;
}

.\-color-light.\-hover:hover {
    color: var(--liquid-color-light-shade)!important;
}
.\-bg-light.\-hover:hover {
    background-color: var(--liquid-color-light-contrast)!important;
}
.\-color-light-content.\-hover:hover {
    color: var(--liquid-color-light-shade)!important;
}
.\-bg-none:hover {
    background: none;
}

```

#### Effect`SHADOWS` 

```css
/* SHADOW BOX */
.\-shad-1, .\-shad-1-h:hover, .\-shad-1-f:focus, .\-shad-1-a:active {
    box-shadow: var(--shad-1);
}
.\-shad-2, .\-shad-2-h:hover, .\-shad-2-f:focus, .\-shad-2-a:active {
    box-shadow: var(--shad-2); 
}
.\-shad-3, .\-shad-3-h:hover, .\-shad-3-f:focus, .\-shad-3-a:active {
    box-shadow: var(--shad-3);
}
.\-shad-4, .\-shad-4-h:hover, .\-shad-4-f:focus, .\-shad-4-a:active {
    box-shadow: var(--shad-4);
}
.\-shad-5, .\-shad-5-h:hover, .\-shad-5-f:focus, .\-shad-5-a:active {
    box-shadow: var(--shad-5);
}
.\-shad-6, .\-shad-6-h:hover, .\-shad-6-f:focus, .\-shad-6-a:active {
    box-shadow: var(--shad-6);
}
.\-shad-7, .\-shad-7-h:hover, .\-shad-7-f:focus, .\-shad-7-a:active {
    box-shadow: var(--shad-7);
}
.\-shad-8, .\-shad-8-h:hover, .\-shad-8-f:focus, .\-shad-8-a:active {
    box-shadow: var(--shad-8);
}
.\-shad-9, .\-shad-8-h:hover, .\-shad-9-f:focus, .\-shad-9-a:active {
    box-shadow: var(--shad-9);
}
.\-shad-10, .\-shad-10-h:hover, .\-shad-10-f:focus, .\-shad-10-a:active {
    box-shadow: var(--shad-10);
}
.\-shad-11, .\-shad-11-h:hover, .\-shad-11-f:focus, .\-shad-11-a:active {
    box-shadow: var(--shad-11);
}
.\-shad-12, .\-shad-12-h:hover, .\-shad-12-f:focus, .\-shad-12-a:active {
    box-shadow: var(--shad-12); 
}
.\-shad-13, .\-shad-13-h:hover, .\-shad-13-f:focus, .\-shad-13-a:active {
    box-shadow: var(--shad-13); 
}
.\-shad-14, .\-shad-14-h:hover, .\-shad-14-f:focus, .\-shad-14-a:active {
    box-shadow: var(--shad-14);
}
.\-shad-15, .\-shad-15-h:hover, .\-shad-15-f:focus, .\-shad-15-a:active {
    box-shadow: var(--shad-15); 
}
.\-shad-16, .\-shad-16-h:hover, .\-shad-16-f:focus, .\-shad-16-a:active {
    box-shadow: var(--shad-16);
}
.\-shad-17, .\-shad-17-h:hover, .\-shad-17-f:focus, .\-shad-17-a:active {
    box-shadow: var(--shad-17); 
}
.\-shad-18, .\-shad-18-h:hover, .\-shad-18-f:focus, .\-shad-18-a:active {
    box-shadow: var(--shad-18);
}
.\-shad-19, .\-shad-19-h:hover, .\-shad-19-f:focus, .\-shad-19-a:active {
    box-shadow: var(--shad-19);
}
.\-shad-20, .\-shad-20-h:hover, .\-shad-20-f:focus, .\-shad-20-a:active {
    box-shadow: var(--shad-20);
}

```

#### Effect`PATH` 

```css
/* Shapes */
.s-rounded {
    border-radius: 0.125rem;
}
.s-circle {
    border-radius: 50%;
}

```

#### Effect`PERSPECTIVE` 

```css
in progress


```

#### Ui-Component`ALL` 

Alert
```css



```

Badge

```css



```

Breadcrumb

```css



```

Button

```css



```

Buttons

```css



```

Dropdown

```css



```

Form
```css



```

Form-label
```css



```

Form-input
```css



```

Form-checkbox
```css



```

Form-radio
```css



```

Form-select
```css



```

Form-range
```css



```


Form-date
```css



```

Icon
```css



```

Icons
```css



```

Placeholders
```css



```

Popovers
```css



```

Progress
```css



```

Tooltip
```css



```


#### Layout-Component `ALL` 

Page

```css



```

Header

```css



```

Footer

```css



```

Main-content

```css



```

Nav

```css



```

Tab

```css



```

Img

```css



```

Figure

```css



```

Bar-side

```css



```

Bar-bottom

```css



```

Bar-top

```css



```

Modal

```css



```

List

```css



```

Table

```css



```

### ANIMATION



#### ANIMATION  `container-animation`

```css
.dynamic-container .\-dynamic-section:target.\-slide-right *,
.dynamic-container .\-dynamic-section.\-active.\-slide-right * {
    animation-duration: var(--dynamic-section-slide-right-duration);
    animation-delay: var(--dynamic-section-slide-right-delay);
    animation-fill-mode: var(--dynamic-section-slide-right-fill);
    animation-name: slide-right;
}
.dynamic-container .\-dynamic-section:target.\-slide-left *,
.dynamic-container .\-dynamic-section.\-active.\-slide-left * {
    animation-duration: var(--dynamic-section-slide-left-duration);
    animation-delay: var(--dynamic-section-slide-left-delay);
    animation-fill-mode: var(--dynamic-section-slide-left-fill);
    animation-name: slide-left;
}
.dynamic-container .\-dynamic-section:target.\-slide-up *,
.dynamic-container .\-dynamic-section.\-active.\-slide-up * {
    animation-duration: var(--dynamic-section-slide-up-duration);
    animation-delay: var(--dynamic-section-slide-up-delay);
    animation-fill-mode: var(--dynamic-section-slide-up-fill);
    animation-name: slide-up;
}
.dynamic-container .\-dynamic-section:target.\-slide-down *,
.dynamic-container .\-dynamic-section.\-active.\-slide-down * {
    animation-duration: var(--dynamic-section-slide-down-duration);
    animation-delay: var(--dynamic-section-slide-down-delay);
    animation-fill-mode: var(--dynamic-section-slide-down-fill);
    animation-name: slide-down;
}
.dynamic-container .\-dynamic-section:target.\-pop-in *,
.dynamic-container .\-dynamic-section.\-active.\-pop-in * {
    animation: 2s pop-in forwards;
    animation-duration: var(--dynamic-section-pop-in-duration);
    animation-delay: var(--dynamic-section-pop-in-delay);
    animation-fill-mode: var(--dynamic-section-pop-in-fill);
    animation-name: pop-in;
}
.dynamic-container .\-dynamic-section:target.\-rotate-from-t-right-left *,
.dynamic-container .\-dynamic-section.\-active.\-rotate-from-t-right-left * {
    animation: 2s rotate-from-t-right-left forwards;
    animation-duration: var(--dynamic-section-rotate-1-duration);
    animation-delay: var(--dynamic-section-rotate-1-delay);
    animation-fill-mode: var(--dynamic-section-rotate-1-fill);
    animation-name: rotate-from-t-right-left;
}
.dynamic-container .\-dynamic-section:target.\-rotate-from-b-right-left * ,
.dynamic-container .\-dynamic-section.\-active.\-rotate-from-b-right-left * {
    animation-duration: var(--dynamic-section-rotate-2-duration);
    animation-delay: var(--dynamic-section-rotate-2-delay);
    animation-fill-mode: var(--dynamic-section-rotate-2-fill);
    animation-name: rotate-from-b-right-left;
}
.dynamic-container .\-dynamic-section:target.\-rotate-from-t-left-right  *,
.dynamic-container .\-dynamic-section.\-active.\-rotate-from-t-left-right  * {
    animation-duration: var(--dynamic-section-rotate-3-duration);
    animation-delay: var(--dynamic-section-rotate-3-delay);
    animation-fill-mode: var(--dynamic-section-rotate-3-fill);
    animation-name: rotate-from-t-left-right;
}
.dynamic-container .\-dynamic-section:target.\-rotate-from-b-left-right  *,
.dynamic-container .\-dynamic-section.\-active.\-rotate-from-b-left-right  * {
    animation-duration: var(--dynamic-section-rotate-4-duration);
    animation-delay: var(--dynamic-section-rotate-4-delay);
    animation-fill-mode: var(--dynamic-section-rotate-4-fill);
    animation-name: rotate-from-b-left-right;
}
.dynamic-container .\-dynamic-section:target.\-fade-in *,
.dynamic-container .\-dynamic-section.\-active.\-fade-in *,
.dynamic-container .\-dynamic-section:target .\-fade-in,
.dynamic-container .\-dynamic-section.\-active .\-fade-in {
    opacity: var(--dynamic-section-fade-in-opacity-default);
    animation-duration: var(--dynamic-section-fade-in-duration);
    animation-delay: var(--dynamic-section-fade-in-delay);
    animation-fill-mode: var(--dynamic-section-fade-in-fill);
    animation-name: fade-in;
}

.dynamic-container .\-dynamic-section:target.\-fade-out *,
.dynamic-container .\-dynamic-section.\-out.\-fade-out * {
    opacity: var(--dynamic-section-fade-out-opacity-default);
    animation-duration: var(--dynamic-section-fade-out-duration);
    animation-delay: var(--dynamic-section-fade-out-delay);
    animation-fill-mode: var(--dynamic-section-fade-out-fill);
    animation-name: fade-out;
}
.dynamic-container .\-dynamic-section:target.\-slide-right-out *,
.dynamic-container .\-dynamic-section.\-out.\-slide-right-out * {
    animation-duration: var(--dynamic-section-slide-right-out-duration);
    animation-delay: var(--dynamic-section-slide-right-out-delay);
    animation-fill-mode: var(--dynamic-section-slide-right-out-fill);
    animation-name: slide-right-out;
}
.dynamic-container .\-dynamic-section:target.\-slide-left-out *,
.dynamic-container .\-dynamic-section.\-out.\-slide-left-out * {
    animation-duration: var(--dynamic-section-slide-left-out-duration);
    animation-delay: var(--dynamic-section-slide-left-out-delay);
    animation-fill-mode: var(--dynamic-section-slide-left-out-fill);
    animation-name: slide-left-out;
}
.dynamic-container .\-dynamic-section:target.\-slide-up-out *,
.dynamic-container .\-dynamic-section.\-out.\-slide-up-out * {
    animation-duration: var(--dynamic-section-slide-up-out-duration);
    animation-delay: var(--dynamic-section-slide-up-out-delay);
    animation-fill-mode: var(--dynamic-section-slide-up-out-fill);
    animation-name: slide-up-out;
}
.dynamic-container .\-dynamic-section:target.\-slide-down-out *,
.dynamic-container .\-dynamic-section.\-out.\-slide-down-out * {
    animation-duration: var(--dynamic-section-slide-down-out-duration);
    animation-delay: var(--dynamic-section-slide-down-out-delay);
    animation-fill-mode: var(--dynamic-section-slide-down-out-fill);
    animation-name: slide-down-out;
}

```

#### ANIMATION  `animate`

```css
/* ---- ANIMATIONS ---- */
/* Direction */
.\-anim-dir-normal {
    animation-direction: normal!important;
}
.\-anim-dir-reverse {
    animation-direction: reverse!important;
}
.\-anim-dir-alternate {
    animation-direction: alternate!important;
}
.\-anim-dir-alternate-reverse {
    animation-direction: alternate-reverse!important;
}
.\-anim-dir-initial {
    animation-direction: initial!important;
}
.\-anim-dir-unset {
    animation-direction: unset!important;
}
.\-anim-dir-inherit {
    animation-direction: inherit!important;
}
/* Fill */
.\-anim-fill-none {
    animation-fill-mode: none!important;
}
.\-anim-fill-forwards {
    animation-fill-mode: forwards!important;
}
.\-anim-fill-backwards {
    animation-fill-mode: backwards!important;
}
.\-anim-fill-both {
    animation-fill-mode: both!important;
}
/* Duration */
.\-anim-0-15s {
    animation-duration: 0.15s!important;
}
.\-anim-0-25s {
    animation-duration: 0.25s!important;
}
.\-anim-0-50s {
    animation-duration: 0.50s!important;
}
.\-anim-0-75s {
    animation-duration: 0.75s!important;
}
.\-anim-1s {
    animation-duration: 1s!important;
}
.\-anim-2s {
    animation-duration: 2s!important;
}
.\-anim-3s {
    animation-duration: 3s!important;
}
.\-anim-4s {
    animation-duration: 4s!important;
}
.\-anim-5s {
    animation-duration: 3s!important;
}
/* Delay */
.\-anim-delay-75 {
    animation-delay: 75ms!important;
}
.\-anim-delay-75 {
    animation-delay: 75ms!important;
}
.\-anim-delay-50ms {
    animation-delay: 50ms!important;
}
.\-anim-delay-75ms {
    animation-delay: 75ms!important;
}
.\-anim-delay-100ms {
    animation-delay: .1s!important;
}
.\-anim-delay-150ms {
    animation-delay: .15s!important;
}
.\-anim-delay-200ms {
    animation-delay: .2s!important;
}
.\-anim-delay-250ms {
    animation-delay: .25s!important;
}
.\-anim-delay-300ms {
    animation-delay: .3s!important;
}
.\-anim-delay-350ms {
    animation-delay: .35s!important;
}
.\-anim-delay-400ms {
    animation-delay: .4s!important;
}
.\-anim-delay-500ms {
    animation-delay: .5s!important;
}
.\-anim-delay-700ms {
    animation-delay: .7s!important;
}
.\-anim-delay-1s {
    animation-delay: 1s!important;
}
.\-anim-delay-2s {
    animation-delay: 2s!important;
}
.\-anim-delay-3s {
    animation-delay: 3s!important;
}
/* ----ANIMATION---- */
.\-rotate-all-infinite-linear-alternate {
    animation: rotate-all 2s 0 infinite linear alternate;
}

.\-anim-confeti {
    animation-name: confetti;
}
@keyframes confetti {
    60% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: scale(1) translate(-50%, -50%)!important;
    }
}

.\-anim-liquify {
    animation-name: liquify;
}
@keyframes liquify {
    0% {
        transform: translate(-50%, -75%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -75%) rotate(360deg);
    }
}

.\-anim-wave {
    animation-name: wave;
}
@keyframes wave {
    50% { transform: translateZ(4.5em); }
}

.\-anim-pop-in {
    animation-name: pop-in;
}
@keyframes pop-in {
    from {
        transform: scale(0.1);
        opacity: 0;
   }
    
    60% {
        transform: scale(1.2);
        opacity: 1;
   }
    
    to {
        transform: scale(1);
   }
}

.\-anim-square-to-circle-1 {
    animation: square-to-circle 2.5s .5s infinite cubic-bezier(1,.015,.295,1.225) alternate-reverse;
}
@keyframes square-to-circle {
    0%  {
        border-radius:0 0 0 0;
        background: none;
        transform:rotate(45deg);
    }
    25%  {
        border-radius:50% 0 0 0;
        transform:rotate(135deg);
    }
    50%  {
        border-radius:50% 50% 0 0;
        transform:rotate(180deg);
    }
    75%  { 
        border-radius:50% 50% 50% 0;
        transform:rotate(315deg);
    }
    100% {  
        border-radius:50%;
        transform:rotate(404deg);
    }
}
.\-anim-square-to-circle-2 {
    animation: square-to-circle2 2.5s .42s infinite cubic-bezier(1,.015,.295,1.225) alternate-reverse;
}
@keyframes square-to-circle2 {
    0%  {
        border-radius:0 0 0 0;
        transform:rotate(45deg);
    }
    25%  {
        border-radius:0 0 50% 0;
        transform:rotate(135deg);
    }
    50%  {
        border-radius:0 0 50% 50%;
        transform:rotate(180deg);
    }
    75%  { 
        border-radius:50% 0 50% 50%;
        transform:rotate(315deg);
    }
    100% {  
        border-radius:50%;
        transform:rotate(404deg);
    }
}

.\-anim-loading {
    animation-name: loading;
}
@keyframes loading {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.\-anim-slide-left {
    animation-name: slide-left;
}
@keyframes slide-left {
    0% {
        transform: translateX(-100vw);
    }
    100% {
        transform: translateX(0);
    }
}
.\-anim-slide-left-out {
    animation-name: slide-left-out;
}
@keyframes slide-left-out {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-100vw);
    }
}
.\-anim-slide-right {
    animation-name: slide-right;
}
@keyframes slide-right {
    0% {
        transform: translateX(100vw);
    }
    100% {
        transform: translateX(0);
    }
}
.\-anim-slide-right-out {
    animation-name: slide-left-out;
}
@keyframes slide-right-out {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(100vw);
    }
}
.\-anim-slide-down {
    animation-name: slide-down;
}
@keyframes slide-down {
    0% {
        transform: translateY(-500%);
    }
    60% {
        transform: translateY(10%);
    }
    70% {
        transform: translateY(-5%);
    }
    80% {
        transform: translateY(5%);
    }
    90% {
        transform: translateY(-2%);
    }
    100% {
        transform: translateY(0);
    }
}
.\-anim-slide-down-out {
    animation-name: slide-down-out;
}
@keyframes slide-down-out {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-500%);
    }
}
.\-anim-slide-up {
    animation-name: slide-up;
}
@keyframes slide-up {
    0% {
        transform: translateY(800%);
    }
    60% {
        transform: translateY(-10%);
    }
    70% {
        transform: translateY(5%);
    }
    80% {
        transform: translateY(-5%);
    }
    90% {
        transform: translateY(2%);
    }
    100% {
        transform: translateY(0);
    }
}
.\-anim-slide-up-out {
    animation-name: slide-up-out;
}
@keyframes slide-up-out {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(800%);
    }
}

.\-anim-spin {
    animation-name: spin;
}
.\-anim-spin-play {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.\-anim-ping {
    animation-name: ping;
}
.\-anim-ping-play {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}
@keyframes ping {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.\-anim-pulse {
    animation-name: pulse;
}
.\-anim-pulse-play {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.\-anim-bounce {
    animation-name: bounce;
}
.\-anim-bounce-play {
    animation: bounce 1s infinite;
}
@keyframes bounce {
    0%, 100% {
        transform: translateY(-25%);
        animationTimingFunction: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animationTimingFunction: cubic-bezier(0, 0, 0.2, 1);
    }
}

.\-anim-fade-in {
    animation-name: fade-in;
}
.\-anim-fade-in-play {
    animation: 2s fade-in forwards .5s;
}
@keyframes fade-in {
    100% { opacity:1 }
}
.\-anim-fade-out {
    animation-name: fade-out;
}
.\-anim-fade-out-play {
    animation: 2s fade-out forwards .5s;
}
@keyframes fade-out {
    0% { opacity:1 }
    100% { opacity:0 }
}

.\-anim-rotate-from-t-right-left {
    animation-name: rotate-from-t-right;
}
@keyframes rotate-from-t-right-left {
    0% {
        transform: translate(90%, -175%) rotate(180deg);
    }
    50% {
        transform: translate(-40%, -10%) rotate(350deg);
    }
    100% {
        transform: translate(0%, 0) rotate(360deg);
    }
}
.\-anim-rotate-from-b-right-left {
    animation-name: rotate-from-b-right;
}
@keyframes rotate-from-b-right-left {
    0% {
        transform: translate(-50%, 175%) rotate(180deg);
    }
    50% {
        transform: translate(-40%, 0) rotate(350deg);
    }
    100% {
        transform: translate(0%, 0) rotate(360deg);
    }
}
.\-anim-rotate-from-t-left-right {
    animation-name: rotate-from-t-left-right;
}
@keyframes rotate-from-t-left-right {
    0% {
        transform: translate(0, 0) rotate(0);
    }
    20% {
        transform: translate(-500%, -500%) rotate(180deg);
    }
    50% {
        transform: translate(-40%, -10%) rotate(350deg);
    }
    100% {
        transform: translate(0%, 0) rotate(360deg);
    }
}
.\-anim-rotate-from-b-left-right {
    animation-name: rotate-from-b-left;
}
@keyframes rotate-from-b-left-right {
    0% {
        transform: translate(-500%, 175%) rotate(180deg);
    }
    50% {
        transform: translate(10%, 0) rotate(350deg);
    }
    100% {
        transform: translate(0%, 0) rotate(360deg);
    }
}

```

#### ANIMATION  `transition`

```css
.\-trans-none {
    transition-property: none;
}
.\-trans-all {
    transition-property: all;
}
.\-trans-colors {
    transition-property: background-color, border-color, color, fill, stroke;
}
.\-trans-opacity {
    transition-property: opacity;
}
.\-trans-shadow {
    transition-property: v;
}
.\-trans-transform {
    transition-property: transform;
}
.\-transition-all-05 {
    transition: all 0.5s ease-in-out;
}
.\-transition-all-1 {
    transition: all 1s ease-in-out;
}
.\-transition-all-2 {
    transition: all 2s ease-in-out;
}
.\-trans-duration-75 {
    transition-duration: 75ms;
}
.\-trans-duration-100 {
    transition-duration: 100ms;
}
.\-trans-duration-150 {
    transition-duration: 150ms;
}
.\-trans-duration-200 {
    transition-duration: 200ms;
}
.\-trans-duration-250 {
    transition-duration: 250ms;
}
.\-trans-duration-300 {
    transition-duration: 300ms;
}
.\-trans-duration-350 {
    transition-duration: 350ms;
}
.\-trans-duration-400 {
    transition-duration: 400ms;
}
.\-trans-duration-500 {
    transition-duration: 500ms;
}
.\-trans-duration-700 {
    transition-duration: 700ms;
}
.\-trans-duration-1000 {
    transition-duration: 1000ms;
}
.\-trans-delay-75 {
    transition-delay: 75ms;
}
.\-trans-delay-100 {
    transition-delay: 100ms;
}
.\-trans-delay-150 {
    transition-delay: 150ms;
}
.\-trans-delay-200 {
    transition-delay: 200ms;
}
.\-trans-delay-250 {
    transition-delay: 250ms;
}
.\-trans-delay-300 {
    transition-delay: 300ms;
}
.\-trans-delay-350 {
    transition-delay: 350ms;
}
.\-trans-delay-400 {
    transition-delay: 400ms;
}
.\-trans-delay-500 {
    transition-delay: 500ms;
}
.\-trans-delay-700 {
    transition-delay: 700ms;
}
.\-trans-delay-1000 {
    transition-delay: 1000ms;
}
.\-linear {
    transition-timing-function: linear;
}
.\-ease {
    transition-timing-function: ease;
}
.\-ease-in {
    transition-timing-function: ease-in;
}
.\-ease-out {
    transition-timing-function: ease-out;
}
.\-ease-in-out {
    transition-timing-function: ease-in-out;
}
.\-step-start {
    transition-timing-function: step-start;
}
.\-step-end {
    transition-timing-function: step-end;
}

```

#### ANIMATION  `translate`

```css
.\-preserve-3d	    {transform-style: preserve-3d;}
.\-translate-y100	{transform: translateY(100%);}
.\-translate-y-100	{transform: translateY(-100%);}
.\-translate-x100	{transform: translateX(100%);}
.\-translate-x-100	{transform: translateX(-100%);}
.\-translate-y50	{transform: translateY(50%);}
.\-translate-y-50	{transform: translateY(-50%);}
.\-translate-x50	{transform: translateX(50%);}
.\-translate-x-50	{transform: translateX(-50%);}
.\-translate-xy-50	{transform: translate(-50%,-50%);}

.\-translate-y100-h:hover	{transform: translateY(100%);}
.\-translate-y-100-h:hover	{transform: translateY(-100%);}
.\-translate-x100-h:hover	{transform: translateX(100%);}
.\-translate-x-100-h:hover	{transform: translateX(-100%);}
.\-translate-y50-h:hover	{transform: translateY(50%);}
.\-translate-y-50-h:hover	{transform: translateY(-50%);}
.\-translate-x50-h:hover	{transform: translateX(50%);}
.\-translate-x-50-h:hover	{transform: translateX(-50%);}
.\-translate-xy-50-h:hover	{transform: translate(-50%,-50%);}

```

## Features Examples

### PAGES

#### PAGE LOGIN

#### PAGE REGISTER

#### PAGE DASHBOARD

#### PAGE POST

#### PAGE 404

#### PAGE 500

## Changelog
- 1.0.0.alpha
- 1.0.0.alpha-2: Currently
    -- Change in border component 
    -- Add Gradient Color in color.css
    -- Remove pre selector .align in align.css
    -- Add m0 and p0 , w0 and h0 utilities for margin, padding and sizing
    -- Add media.css to handdle image and video medias
    -- Fix headding size for large screen
    -- Add Card Component
    -- Add Fun Loader Extensed Component 
    -- New Demo Page Version 2
- 1.0.0.alpha-3: Currently
    -- modified:   @extensed/extensed.css
    -- modified:   basic/effects/border.css
    -- modified:   basic/effects/colors.css
    -- modified:   basic/layout-components/bar-side.css
    -- modified:   basic/main.basic.css
    -- modified:   basic/skeleton/base.css
    -- modified:   basic/skeleton/container.css
    -- modified:   basic/skeleton/heading.css
    -- modified:   basic/skeleton/typo.css
    -- modified:   basic/ui-components/avatar.css
    -- modified:   basic/ui-components/dropdown.css
    -- modified:   variables.css
    -- add:   @demo/extensed-components/fun-loader.html
    -- add:   @demo/skeleton/
    -- add:   @extensed/components/fun-loader.css
    -- add:   basic/skeleton/media.css
    -- add:   variables-base-theme.css.dist
    -- add:   variables-color-theme.css.dist
    -- add:   variables.css.dist

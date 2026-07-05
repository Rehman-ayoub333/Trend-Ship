# TRENDSHIP — Final Website Specification
## LX Hausys 2025 | Complete Reference for All 9 Pages
### Reflects actual index.html as built — version 1.0

> **This is the single source of truth.** Every section, every CSS class, every animation,
> every route, every image URL, and every copy string that exists in the live codebase
> is documented here. Build the other 8 pages from this document — nothing else needed.

---

## TABLE OF CONTENTS

```
PART 1  — Design System (tokens, typography, colours)
PART 2  — File Structure & CDN Versions
PART 3  — Global Components (used on every page)
PART 4  — Page 01: Home — complete section-by-section reference
PART 5  — Pages 02–09: Full build spec for each remaining page
PART 6  — Animation Library (every named animation with exact values)
PART 7  — Image Library (every URL in use)
PART 8  — Navigation & Routing Rules
PART 9  — Performance & Accessibility Checklist
PART 10 — GitHub-Ready File Checklist
```

---

## PART 1 — DESIGN SYSTEM

### 1.1 CSS Custom Properties (`:root`)

These are defined once — at the top of every page's `<style>` block.
Never redefine them. Never add new ones without updating this document.

```css
:root {
  /* ── Colours ── */
  --greige:      #d4c4bc;   /* Hero background */
  --dark:        #0a0a0a;   /* Primary dark bg — most sections */
  --off-white:   #f4efe9;   /* Light sections (About, Materials) */
  --rose:        #d4857a;   /* Primary accent — buttons, CTAs, active states */
  --rose-light:  #e8a8b0;   /* Lighter rose — organism, soft highlights */
  --rose-pale:   #f5dde0;   /* Palest rose — organism tips */
  --rose-dust:   #c4968e;   /* Muted rose — labels, nav overlay numbers */
  --wine:        #8a4a42;   /* Deep rose — hover states */
  --maison:      #bf7268;   /* Exhibition terracotta mid-tone */
  --text-dark:   #1a1614;   /* Text on light backgrounds */
  --text-light:  #f0e8e0;   /* Text on dark backgrounds */
  --gold:        #c8a878;   /* Accent — future use, awards section */

  /* ── Typography ── */
  --ff-display:  'Bebas Neue', sans-serif;   /* Wordmarks, section headings, overlay names */
  --ff-body:     'EB Garamond', serif;        /* All body text, nav links, captions */

  /* ── Easing ── */
  --ease-out:    cubic-bezier(.16, 1, .3, 1);  /* Used on underline reveals */
}
```

### 1.2 Typography Scale

| Use | Font | Size | Weight | Tracking |
|-----|------|------|--------|----------|
| Hero wordmark | Bebas Neue | `clamp(88px, 15.5vw, 230px)` | 400 | `-.03em` |
| Synergy wordmark | Bebas Neue | `clamp(58px, 13.5vw, 185px)` | 400 | default |
| Theme names (slot) | Bebas Neue | `clamp(44px, 7.2vw, 100px)` | 400 | `.05em` |
| Nav overlay names | Bebas Neue | `clamp(32px, 5.5vw, 72px)` | 400 | `-.01em` |
| Stats numbers | Bebas Neue | `clamp(52px, 6vw, 88px)` | 400 | `-.02em` |
| Material card names | Bebas Neue | `18px` | 400 | `.04em` |
| Footer logo | Bebas Neue | `30px` | 400 | `.06em` |
| Section eyebrows | EB Garamond | `10px` | 400 | `.3em` uppercase |
| Body / captions | EB Garamond | `clamp(14px, 1.1vw, 16px)` | 400 | `.05–.08em` |
| Nav links | EB Garamond | `11px` | 400 | `.2em` uppercase |
| CTA buttons | EB Garamond | `11px` | 400 | `.22em` uppercase |
| Philosophy quote | EB Garamond italic | `clamp(22px, 3.2vw, 44px)` | 400 | default |
| Maison "de" title | EB Garamond italic | `clamp(36px, 5.5vw, 72px)` | 400 | default |
| Maison "Synergy" | EB Garamond italic | `clamp(56px, 8.5vw, 116px)` | 700 | default |

### 1.3 Colour Roles

| Token | Where Used |
|-------|-----------|
| `--greige` | Hero section background; transitions to `--dark` on scroll |
| `--dark` | Body default, Photos, Synergy, Stats, Themes, Philosophy, Building, Footer |
| `--off-white` | Materials section bg, About section bg |
| `--rose` | Progress bar, active nav underline, `.sy-ebox` background, CTA hover borders, stat numbers, `.mat-card-theme` tint |
| `--rose-dust` | Nav overlay numbers, section eyebrows, philosophy attribution, `.sy-tag`, inactive theme tags |
| `--text-dark` | Text on `--greige` and `--off-white` backgrounds |
| `--text-light` | Text on `--dark` backgrounds, nav when scrolled |
| `--maison` | `.m-orb` base, Maison section gradient mid-stop |

---

## PART 2 — FILE STRUCTURE & CDN VERSIONS

### 2.1 Folder Structure

```
trendship/                        ← root
├── index.html                    ← Page 01: Home (COMPLETE)
├── README.md
├── .gitignore
│
├── design-trend/
│   └── index.html                ← Page 02: Design Trend (BUILD)
├── themes/
│   └── index.html                ← Page 03: Themes (BUILD)
├── exhibition/
│   └── index.html                ← Page 04: Exhibition (BUILD)
├── collection/
│   └── index.html                ← Page 05: Collection (BUILD)
├── trend-report/
│   └── index.html                ← Page 06: Trend Report (BUILD)
├── lookbook/
│   └── index.html                ← Page 07: Lookbook (BUILD)
├── about/
│   └── index.html                ← Page 08: About (BUILD)
└── contact/
    └── index.html                ← Page 09: Contact (BUILD)
```

No `assets/` folder is required for the current implementation — all CSS and JS live
inline in each page's `<style>` and `<script>` blocks. This makes each page completely
self-contained and deployable by opening a single file.

**Reasoning:** For a static site with no build tools, inline styles/scripts eliminate
broken relative paths and make GitHub Pages deployment trivial.

### 2.2 CDN Versions — Locked, Do Not Change

These exact versions must appear in the `<head>` of all 9 pages.

```html
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,700;1,400;1,700&family=Bebas+Neue&display=swap" rel="stylesheet">

<!-- Libraries — in this exact order -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>
```

**Why these versions:**
- GSAP 3.12.2 — latest stable, no known scroll bugs
- Three.js r128 — `CapsuleGeometry` does not exist in this version, use `CylinderGeometry` + `SphereGeometry` for tentacle tips
- Lenis 1.0.19 — stable, compatible with GSAP ticker pattern used

---

## PART 3 — GLOBAL COMPONENTS

These five HTML blocks appear **identically** on every page.
Copy-paste them unchanged. Only update `href` values relative to each page's depth.

### 3.1 `<head>` Meta Block

```html
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TRENDSHIP | [PAGE NAME] — LX Hausys 2025</title>
<meta name="description" content="[120-160 char description]">
<meta property="og:title"       content="TRENDSHIP — [PAGE NAME]">
<meta property="og:description" content="New energy that flourishes when we come together.">
<meta property="og:type"        content="website">
<meta name="theme-color"        content="#d4c4bc">
[fonts + CDN scripts]
```

### 3.2 Fixed Global Elements (inside `<body>`, before `<nav>`)

```html
<div id="progress-bar"></div>
<div id="cur"   aria-hidden="true"></div>
<div id="grain" aria-hidden="true"></div>
<div id="t-overlay"><div class="t-dot" id="tDot"></div></div>
```

**CSS for these four elements is identical on every page.**
Progress bar: `position:fixed; top:0; left:0; height:2px; width:0%; background:var(--rose); z-index:9998`.

### 3.3 Navigation HTML

```html
<nav id="nav" role="navigation" aria-label="Main navigation">
  <a href="[root]/index.html" class="n-logo">TRENDSHIP</a>
  <div class="n-links">
    <a href="[root]/index.html"              class="n-a [active?]">Home</a>
    <a href="[root]/design-trend/index.html" class="n-a [active?]">Design Trend</a>
    <a href="[root]/themes/index.html"       class="n-a [active?]">Themes</a>
    <a href="[root]/exhibition/index.html"   class="n-a [active?]">Exhibition</a>
    <a href="[root]/collection/index.html"   class="n-a [active?]">Collection</a>
    <a href="[root]/trend-report/index.html" class="n-a [active?]">Report</a>
    <a href="[root]/lookbook/index.html"     class="n-a [active?]">Lookbook</a>
    <button id="nav-menu" aria-label="Open navigation menu" aria-expanded="false">☰</button>
  </div>
</nav>
```

**Path rules:**
- From `index.html` (root): `href="index.html"`, `href="design-trend/index.html"` etc.
- From `design-trend/index.html` (one level deep): `href="../index.html"`, `href="../themes/index.html"` etc.
- Pattern: pages one level deep prepend `../` to all links.

**Active state:** Add class `active` to the `.n-a` link matching the current page.

**Nav states (controlled by JS):**
- Default (on hero/light sections): dark text, no background
- `.dk` class: light text (`var(--text-light)`)
- `.bl` class: `backdrop-filter:blur(18px)` + `background:rgba(10,10,10,.2)`
- On Home: nav starts without `.dk`/`.bl`, gains them when hero bottom crosses `72px` from top
- On all other pages: nav starts with `.dk`/`.bl` immediately (dark page from top)

### 3.4 Navigation Overlay HTML

```html
<div id="nav-overlay" role="dialog" aria-modal="true" aria-label="Navigation">
  <button class="no-close" id="nav-close" aria-label="Close menu">✕</button>
  <div class="no-inner">
    <a href="[root]/index.html"              class="no-item"><span class="no-num">01</span><span class="no-name">Home</span><span class="no-arrow">→</span></a>
    <a href="[root]/design-trend/index.html" class="no-item"><span class="no-num">02</span><span class="no-name">Design Trend</span><span class="no-arrow">→</span></a>
    <a href="[root]/themes/index.html"       class="no-item"><span class="no-num">03</span><span class="no-name">Themes</span><span class="no-arrow">→</span></a>
    <a href="[root]/exhibition/index.html"   class="no-item"><span class="no-num">04</span><span class="no-name">Exhibition</span><span class="no-arrow">→</span></a>
    <a href="[root]/collection/index.html"   class="no-item"><span class="no-num">05</span><span class="no-name">Collection</span><span class="no-arrow">→</span></a>
    <a href="[root]/trend-report/index.html" class="no-item"><span class="no-num">06</span><span class="no-name">Trend Report</span><span class="no-arrow">→</span></a>
    <a href="[root]/lookbook/index.html"     class="no-item"><span class="no-num">07</span><span class="no-name">Lookbook</span><span class="no-arrow">→</span></a>
    <a href="[root]/about/index.html"        class="no-item"><span class="no-num">08</span><span class="no-name">About</span><span class="no-arrow">→</span></a>
    <a href="[root]/contact/index.html"      class="no-item"><span class="no-num">09</span><span class="no-name">Contact</span><span class="no-arrow">→</span></a>
    <div class="no-footer">
      <a href="[root]/about/index.html">About</a>
      <a href="[root]/contact/index.html">Contact</a>
      <a href="https://instagram.com/lxhausys" target="_blank" rel="noopener">Instagram</a>
      <a href="https://linkedin.com/company/lxhausys" target="_blank" rel="noopener">LinkedIn</a>
    </div>
  </div>
</div>
```

**Overlay animation (JS — identical on every page):**
```javascript
// Must run on every page
gsap.set('.no-item', { y: 40, opacity: 0 });
gsap.set('.no-footer', { opacity: 0 });

function openOverlay() {
  overlayOpen = true;
  navMenuBtn.setAttribute('aria-expanded', 'true');
  navOverlay.classList.add('open');
  gsap.to(navOverlay, { scale: 1, opacity: 1, duration: .55, ease: 'power3.inOut' });
  gsap.to('.no-item',  { y: 0, opacity: 1, stagger: .055, duration: .5, ease: 'power3.out', delay: .2 });
  gsap.to('.no-footer', { opacity: 1, duration: .4, delay: .55 });
  lenis.stop();
}
function closeOverlay() {
  overlayOpen = false;
  navMenuBtn.setAttribute('aria-expanded', 'false');
  gsap.to('.no-item, .no-footer', { y: -18, opacity: 0, stagger: .03, duration: .25, ease: 'power2.in' });
  gsap.to(navOverlay, {
    scale: 0, opacity: 0, duration: .38, ease: 'power3.in', delay: .08,
    onComplete: () => {
      navOverlay.classList.remove('open');
      gsap.set('.no-item', { y: 40, opacity: 0 });
      gsap.set('.no-footer', { opacity: 0 });
    }
  });
  lenis.start();
}
const navMenuBtn = document.getElementById('nav-menu');
const navOverlay = document.getElementById('nav-overlay');
const navClose   = document.getElementById('nav-close');
let overlayOpen  = false;
navMenuBtn.addEventListener('click', () => overlayOpen ? closeOverlay() : openOverlay());
navClose.addEventListener('click', closeOverlay);
document.addEventListener('keydown', e => { if (e.key === 'Escape' && overlayOpen) closeOverlay(); });
document.querySelectorAll('.no-item').forEach(a => a.addEventListener('click', closeOverlay));
```

### 3.5 Footer HTML

```html
<footer id="footer" role="contentinfo">
  <div class="ft-top">
    <div class="ft-brand">
      <span class="ft-logo">TRENDSHIP</span>
      <p>New energy that flourishes when we come together. LX Hausys 2025 Design Trend Platform.</p>
      <div class="ft-social">
        <a href="https://instagram.com/lxhausys" target="_blank" rel="noopener" aria-label="Instagram">IG</a>
        <a href="https://linkedin.com/company/lxhausys" target="_blank" rel="noopener" aria-label="LinkedIn">LI</a>
        <a href="https://youtube.com/@lxhausys"  target="_blank" rel="noopener" aria-label="YouTube">YT</a>
      </div>
    </div>
    <div class="ft-col">
      <h4>Explore</h4>
      <a href="[root]/design-trend/index.html">Design Trend</a>
      <a href="[root]/themes/index.html">Themes</a>
      <a href="[root]/exhibition/index.html">Exhibition</a>
      <a href="[root]/collection/index.html">Collection</a>
    </div>
    <div class="ft-col">
      <h4>More</h4>
      <a href="[root]/trend-report/index.html">Trend Report</a>
      <a href="[root]/lookbook/index.html">Lookbook</a>
      <a href="[root]/about/index.html">About</a>
      <a href="[root]/contact/index.html">Contact</a>
    </div>
  </div>
  <div class="ft-bottom">
    <span class="ft-copy">© 2025 LX Hausys. All rights reserved.</span>
    <span class="ft-made">Designed with love in Seoul, 2025</span>
  </div>
</footer>
```

### 3.6 Global JS Init (bottom of `<script>`, every page)

```javascript
// ── Lenis + GSAP ──────────────────────────────────────
gsap.registerPlugin(ScrollTrigger);
const lenis = new Lenis({ lerp: .085 });
gsap.ticker.add(t => lenis.raf(t * 1000));
gsap.ticker.lagSmoothing(0);
lenis.on('scroll', ScrollTrigger.update);

// ── Progress bar ──────────────────────────────────────
lenis.on('scroll', ({ progress }) => {
  document.getElementById('progress-bar').style.width = (progress * 100) + '%';
});

// ── Custom cursor ─────────────────────────────────────
const cur = document.getElementById('cur');
if (cur && window.matchMedia('(hover:hover)').matches) {
  gsap.set(cur, { xPercent: -50, yPercent: -50 });
  window.addEventListener('mousemove', e => {
    gsap.to(cur, { x: e.clientX, y: e.clientY, duration: .1, overwrite: true });
    // Adjust selector list to match light sections on each page
    const el = document.elementFromPoint(e.clientX, e.clientY);
    cur.classList.toggle('lt', !!(el?.closest('[data-cursor-light]')));
  });
  document.querySelectorAll('a, button, [data-cursor="hover"]').forEach(el => {
    el.addEventListener('mouseenter', () => cur.classList.add('xl'));
    el.addEventListener('mouseleave', () => cur.classList.remove('xl'));
  });
}

// ── Resize ────────────────────────────────────────────
let rTimer;
window.addEventListener('resize', () => {
  clearTimeout(rTimer);
  rTimer = setTimeout(() => ScrollTrigger.refresh(true), 220);
});
```

---

## PART 4 — PAGE 01: HOME (`index.html`)

**Status: COMPLETE.** Reference implementation. All other pages follow this pattern.

### Section Map

| # | ID | Background | Min-height | Added in rebuild |
|---|----|-----------|-----------|-|
| 1 | `#hero` | `--greige` → `--dark` on scroll | `100vh` | — |
| 2 | `#photos` | `--dark` | `100vh` | Added eyebrow label + card theme labels |
| 3 | `#synergy` | `--dark` | `100vh` | — |
| 3b | `#syn-desc` | `--dark` | `60vh` | — |
| — | `#stats` | `--dark` | auto | **NEW** — CountUp animation |
| 4 | `#themes` | `--dark` | `100vh` + 1100px pin | `th-cta` now links to `themes/index.html` |
| — | `#philosophy` | `--dark` | `60vh` | **NEW** — editorial quote |
| 5 | `#maison` | terracotta radial gradient | `100vh` | `m-link` now links to `exhibition/index.html` |
| 6 | `#building` | `--dark` | `72vh` | **NEW** — overlay text + "Enter Exhibition →" CTA |
| — | `#materials` | `--off-white` | auto | **NEW** — 4 material cards |
| 7 | `#about` | `--off-white` | `100vh` | Added `ab-cta` → `about/index.html` |
| — | `#footer` | `#060606` | auto | **NEW** — full footer |

### 4.1 §1 HERO — exact specification

**HTML:**
```html
<section id="hero">
  <canvas id="three-canvas"></canvas>
  <h1 class="hero-wordmark" id="hw">TRENDSHIP.</h1>
  <div class="hero-scroll" id="heroScroll">
    <span>Scroll</span>
    <div class="hero-scroll-line"></div>
  </div>
</section>
```

**Key CSS values:**
- `#hero`: `min-height:100vh`, `background:var(--greige)`, `display:flex`, `align-items:flex-end`
- `.hero-wordmark`: `font-size:clamp(88px,15.5vw,230px)`, `letter-spacing:-.03em`, `padding:0 0 44px 52px`
- `#three-canvas`: `position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:1`
- `.hero-scroll`: `position:absolute;bottom:48px;right:56px;z-index:3;opacity:0` (fades in at t=1.4s)

**Three.js organism — exact parameters:**
```
Camera:        PerspectiveCamera(52, aspect, 0.1, 200)  z=7.5
Lights:        AmbientLight(0xffeedd, 1.1)
               DirectionalLight(0xffffff, 1.8) pos=(5,6,7)
               PointLight(0xf8c0c8, 2.5, r=20) pos=(-4,3,3)
               PointLight(0xffe8e8, 1.2, r=15) pos=(3,-4,5)
Material:      MeshStandardMaterial color=0xeaaab2 roughness=.52 metalness=.04 opacity=.92
Core sphere:   SphereGeometry(0.55, 32, 32) color=0xf8d8dc
Tentacles:     120 total, Fibonacci distribution
               len = 0.7 + random*0.95
               rad = 0.045 + random*0.025
               CylinderGeometry(rad, rad*0.65, len, 8, 1) + SphereGeometry(rad*0.95, 8, 8) tip
Group position: (2.0, 0.3, 0) = center-right as in video
Mouse lerp:    0.038 (smooth but responsive)
Rotation:      y += 0.003, z += 0.001 per frame
Breathing:     scale *= (1 + sin(t*1.5)*0.042)
Tentacle wave: scale.y = 1 + 0.18*sin(t*1.8+phase)*cos(t*0.9+phase*0.7)
```

**Load sequence timeline:**
```
t=0.10s  Hero opacity: 0 → 1 (0.6s, power2.out)
t=0.32s  Organism scale: 0 → 1 via elastic.out(1, 0.46) over 1.6s
t=0.52s  Letters (.lc): translateY(115%) → 0, stagger 0.038s, 0.72s, power3.out
t=0.65s  Nav links (.n-a): translateY(-8px) opacity(0) → visible, stagger 0.08s
t=1.40s  #heroScroll: opacity 0 → 1 (0.5s)
```

**Scroll behaviour:**
```
trigger: #hero, start: top top, end: bottom top, scrub: true
progress 0 → 1 drives:
  background: rgb(212,196,188) → rgb(10,10,10)  [greige to dark]
  mScale: 1 → 5.2  [organism grows]
  canvas opacity: 1 → 0 (starts fading at progress ~0.83)
```

### 4.2 §2 PHOTO STRIP — exact specification

**Images used (7 cards):**
```
Card 1: photo-1586023492125-27b2c045efd7  label="BOOST"
Card 2: photo-1618221195710-dd6b41faaea6  label="COSMOS"
Card 3: photo-1555041469-a586c61ea9bc     label="SYNERGY"
Card 4: photo-1549497538-303791108f95     label="COSMOS"
Card 5: photo-1567767292278-a4f21aa2d36e  label="OOPARTS"
Card 6: photo-1616486338812-3dadae4b4ace  label="BOOST"
Card 7: photo-1583847268964-b28dc8f51f92  label="SYNERGY"
All: ?w=700&q=82, loading="lazy", width="700" height="1050"
```

**Card dimensions:** `width:300px; height:450px`
**Strip padding:** `0 56px`
**Card gap:** `18px`

**Animations:**
```javascript
// Cards fade up on entry
gsap.to('.ph-card', {
  opacity:1, y:0, stagger:.1, ease:'power2.out',
  scrollTrigger:{ trigger:'#photos', start:'top 76%', end:'center 50%', scrub:1 }
});
// Strip pans left while section is pinned
gsap.to('#phStrip', {
  x: () => -(stripEl.scrollWidth - window.innerWidth + 110),
  ease:'none',
  scrollTrigger:{ trigger:'#photos', start:'top top', end:'bottom top', pin:true, scrub:1.5, invalidateOnRefresh:true }
});
```

### 4.3 §3 SYNERGY — exact specification

**HTML structure:**
```html
<section id="synergy">
  <div class="sy-lbl">[ Design Trend ]</div>
  <div class="sy-sub">2025 LX Hausys Design Trend</div>
  <div class="sy-row">
    <span class="sy-l">SY</span>
    <span class="sy-e"><span class="sy-ebox">E</span></span>
    <span class="sy-r">NERGY</span>
  </div>
  <div class="sy-tag">New energy that flourishes when we come together</div>
</section>
```

**Crash animation — exact GSAP values:**
```
Trigger:  #synergy, start:'top 62%', toggleActions:'play none none reverse'
t=0.00s   .sy-l: translateX(-160px) → 0, opacity 0 → 1, 0.82s, power4.out
t=0.00s   .sy-r: translateX(+160px) → 0, opacity 0 → 1, 0.82s, power4.out
t=0.68s   .sy-e: scale(0) → scale(1.16), opacity 0 → 1, 0.22s, power2.out
t=0.90s   .sy-e: scale(1.16) → scale(1), 0.20s, back.out(3)
t=1.06s   .sy-tag: translateY(14px) → 0, opacity 0 → 1, 0.5s
```

**Copy:**
- `.sy-lbl`: `[ Design Trend ]`
- `.sy-sub`: `2025 LX Hausys Design Trend`
- `.sy-tag`: `New energy that flourishes when we come together`

### 4.4 §STATS — exact specification

```html
<section id="stats">
  <div class="stats-grid">
    <div class="stat-item">
      <span class="stat-num" data-target="52">0</span>
      <span class="stat-label">Years of Innovation</span>
    </div>
    <div class="stat-item">
      <span class="stat-num" data-target="30" data-suffix="+">0</span>
      <span class="stat-label">Countries</span>
    </div>
    <div class="stat-item">
      <span class="stat-num" data-target="4">0</span>
      <span class="stat-label">Design Themes 2025</span>
    </div>
    <div class="stat-item">
      <span class="stat-num" data-target="1200" data-suffix="+">0</span>
      <span class="stat-label">Material SKUs</span>
    </div>
  </div>
</section>
```

**CountUp trigger:** `start:'top 75%', once:true`. Runs once. Easing: `power2.out` over `2.2s`.

### 4.5 §4 THEMES — exact specification

**Slot machine logic:**
```javascript
const rows   = document.querySelectorAll('.th-row');
const listEl = document.getElementById('thList');
let aIdx = 0;
const slH  = () => document.querySelector('.th-slot').clientHeight;
const rH   = () => listEl.scrollHeight / rows.length;
const offY = i => slH()/2 - i*(rH()+2) - rH()/2;

// Pin for 1100px of extra scroll
ScrollTrigger.create({
  trigger: '#themes', start: 'top top', end: '+=1100',
  pin: true, scrub: 1,
  onUpdate: self => {
    const ni = Math.min(rows.length-1, Math.floor(self.progress * rows.length));
    if (ni !== aIdx) {
      rows[aIdx].classList.remove('on');
      rows[ni].classList.add('on');
      aIdx = ni;
    }
    gsap.to(listEl, { y: offY(aIdx), duration:.44, ease:'power2.out', overwrite:true });
  }
});
```

**Active row styling:** `.th-row.on .th-name { color: var(--rose-dust) }`
**CTA links to:** `themes/index.html`

**Theme rows (4 total):**
```
BOOST:   keywords COMFORT / ENERGY  images: photo-1586023492125 / photo-1618221195710
COSMOS:  keywords SPACE / DEPTH     images: photo-1556909114    / photo-1549497538
OOPARTS: keywords TIME / LINE       images: photo-1567767292278  / photo-1616486338812
SYNERGY: keywords FUSION / FLOW     images: photo-1583847268964  / photo-1555041469
```

### 4.6 §PHILOSOPHY — exact specification

```html
<section id="philosophy">
  <div class="phil-inner">
    <p class="phil-quote">"Design is not decoration.<br>It is the shape of how we live."</p>
    <span class="phil-attr">— LX Hausys Trendship 2025</span>
  </div>
</section>
```

Animation: `gsap.from('.phil-quote,.phil-attr', { opacity:0, y:24, stagger:.2 })`
Trigger: `start:'top 70%', toggleActions:'play none none reverse'`

### 4.7 §5 MAISON — exact specification

**Orb animation:**
- Starts: `transform:translateX(-50%) scale(.45); opacity:0`
- Trigger: `start:'top 66%', once:true`
- Animates from `p=0.45` to `p=1.0` over 1.5s, power2.out
- Opacity: `Math.max(0, (p - 0.45) * 1.82)`

**Temple image parallax:** `y: -60px` over full section scroll, `scrub:1.5`

**Images:**
- Temple: `photo-1528360983277-13d401cdc186?w=1600&q=80`
- Filter: `brightness(.65) saturate(1.1)`
- Object-position: `center 20%`
- Initial transform: `translateY(55px)` (GSAP animates to `-60px` on scroll)

**CTA:** `href="exhibition/index.html"` | text: `View Offline Exhibition →`

### 4.8 §6 BUILDING — exact specification

```html
<section id="building">
  <img class="b-img" id="bImg"
    src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1800&q=75"
    alt="Neoclassical building facade illuminated at night"
    loading="lazy" width="1800" height="1000">
  <div class="b-vig"></div>
  <div class="b-overlay">
    <div class="b-eyebrow">( Maison de Synergy — Offline Exhibition )</div>
    <div class="b-title">Where art and<br>design converge</div>
    <a href="exhibition/index.html" class="b-link">Enter Exhibition <span>→</span></a>
  </div>
</section>
```

**Parallax:** `scale: 1.06 → 1.0` from `top bottom` to `bottom top`, `scrub:1.2`

### 4.9 §MATERIALS — exact specification

4 cards in a CSS grid (`grid-template-columns:repeat(4,1fr)`). On `<800px`: 2 columns.

| Card | Theme | Name | Image ID | Colours (hex) |
|------|-------|------|----------|--------------|
| 1 | BOOST | Terracotta Linen | `photo-1558618666-fcd25c85cd64` | `#c8885a #b87848 #d8a880` |
| 2 | COSMOS | Midnight Slate | `photo-1519710164239-da123dc03ef4` | `#3a4870 #2a3860 #4a5880` |
| 3 | OOPARTS | Ancient Earth | `photo-1565538810643-b5bdb714032a` | `#9a7840 #7a5820 #c0a060` |
| 4 | SYNERGY | Bloom Rose | `photo-1616486338812-3dadae4b4ace` | `#d4857a #e8a8b0 #c47068` |

**All images:** `?w=600&q=80`, `loading="lazy"`, `width="600" height="800"`

Animation: `gsap.from('.mat-card', { opacity:0, y:40, stagger:.12, duration:.9, ease:'power3.out' })`
CTA: `href="collection/index.html"` | text: `View Full Collection →`

### 4.10 §7 ABOUT — exact specification

Images:
- `.ab-img1` (200×260px, top:18%, left:10%): `photo-1600585152220-90363fe7e115?w=600&q=75`
- `.ab-img2` (168×222px, bottom:14%, right:10%): `photo-1600210492493-0946911123ea?w=600&q=75`
Both: `filter:grayscale(1) contrast(1.08)`

Animations:
```javascript
gsap.to('#abTicker', { x:'-36%', scrollTrigger:{ scrub:1.2 } });
gsap.to('.ab-img1',  { y:-88, x:24,  scrollTrigger:{ scrub:1 } });
gsap.to('.ab-img2',  { y:-52, x:-20, scrollTrigger:{ scrub:1.4 } });
```

CTA: `href="about/index.html"` | text: `Learn About Us →`

---

## PART 5 — PAGES 02–09: BUILD SPECIFICATIONS

Use the HTML template from Part 3. Copy all global components unchanged.
Add `.dk.bl` to `#nav` immediately in JS (not on scroll trigger) since all other pages start dark.

```javascript
// Add this immediately for all pages except Home:
document.getElementById('nav').classList.add('dk', 'bl');
```

---

### PAGE 02: Design Trend (`design-trend/index.html`)

**Purpose:** Deep editorial dive into SY(E)NERGY. The why behind the 2025 trend.
**Background:** Dark throughout. Rose accent. Greige text highlight.
**Nav active:** Design Trend

**Sections in order:**

**S1 — Hero (100vh)**
- Background: `--dark`
- Eyebrow: `[ Design Trend ]` in rose-dust
- Large label: `2025 LX Hausys Design Trend` in EB Garamond, 18px, opacity .45
- The SY(E)NERGY wordmark crashes in on page load (same animation as home §3)
  — but triggered on load timeline instead of scroll
- Scroll indicator identical to home

**S2 — Philosophy Sentences (500vh pinned)**
- 5 sentences appear one at a time as user scrolls
- Each sentence: EB Garamond italic, `clamp(20px,2.5vw,34px)`, centred, max-width 700px
- Background shifts from dark → greige over the section
- Sentences:
  1. "Design is not decoration. It is the shape of how we live."
  2. "In 2025, the world does not need more things. It needs more meaning."
  3. "SY(E)NERGY begins where individual values end and collective energy begins."
  4. "When different forces meet — warmth and space, past and future — new beauty is born."
  5. "This is Trendship. This is the trend that changes how we feel at home."
- JS: divide scroll progress into 5 equal intervals; fade in at 0–30%, hold 30–70%, fade out 70–100%

**S3 — Three Trend Drivers (auto height)**
- 3 cards in a row, each: roman numeral `I / II / III`, short title, 2-line description
- Card 1: I — COMFORT — "Tactile warmth that anchors us"
- Card 2: II — SPACE — "Depth that holds the quiet of possibility"
- Card 3: III — FUSION — "When different values create something entirely new"
- Clip-reveal from bottom on scroll entry, stagger 0.14s

**S4 — Full-width editorial image**
- Image: `photo-1618221195710-dd6b41faaea6?w=1400&q=85`
- Full viewport width, 70vh height, object-fit:cover
- Parallax: scale `1.08 → 1.0` on scroll

**S5 — Two-column text + image (60vh)**
- Left 55%: large Unsplash interior image with clip-reveal
- Right 45%: body text about the trend
- Body copy: "SY(E)NERGY is not simply a style. It is a philosophy of coexistence — of materials that honour the past, spaces that invite the future, and surfaces that feel like they were made for the person standing in front of them."

**S6 — Pull Quote (60vh)**
- Single italic sentence: `"When different values meet, the result is never compromise. It is transcendence."`
- EB Garamond italic, `clamp(24px,3.5vw,48px)`, dark bg, centred

**S7 — CTA Banner**
- Dark bg, text: `"Explore the four design themes →"`
- Font: Bebas Neue, 3vw
- Links to `../themes/index.html`
- Hover: arrow slides right 6px

---

### PAGE 03: Themes (`themes/index.html`)

**Purpose:** Four full-viewport theme worlds — BOOST, COSMOS, OOPARTS, SYNERGY.
**Background:** Changes per world.
**Nav active:** Themes

**Sections:**

**S1 — Overview (100vh)**
- Dark bg
- Eyebrow: "Design Themes 2025"
- Four theme names listed vertically, large Bebas, hover → rose colour
- Click any name: smooth scroll to that world section

**S2 — BOOST World (100vh pinned 100vh)**
- Background: amber `#c8a060`
- "BOOST" at 18vw, CSS 3D rotation tied to scroll (rotateY: 0→20deg)
- Keyword tags float: COMFORT / WARMTH / VITALITY / BOLD / ORGANIC
- Right panel (appears at 50% scroll through): description + 3 material thumbnails + CTA → `../collection/index.html?filter=boost`
- Exit: background fades to COSMOS dark

**S3 — COSMOS World (100vh pinned 100vh)**
- Background: deep navy `#0a0e1e`
- Three.js particle starfield (2000 points, slow y-rotation)
- "COSMOS" assembles from particles on entry
- Keywords: SPACE / DEPTH / MYSTERY / INFINITE
- CTA → `../collection/index.html?filter=cosmos`

**S4 — OOPARTS World (100vh pinned 100vh)**
- Background: earth `#8a6840`
- CSS-only animated geometric shapes (rotating circles and lines)
- "OOPARTS" text fragments and reassembles on entry (GSAP SplitText-style)
- Keywords: TIME / ARTIFACT / MEMORY / LINE
- CTA → `../collection/index.html?filter=ooparts`

**S5 — SYNERGY World (100vh pinned 100vh)**
- Background: `--greige` (same as home hero — intentional callback)
- Three.js coral organism returns, centred, scale 0.85
- "SYNERGY" wordmark below in `--text-dark`
- Keywords: FUSION / ENERGY / TOGETHER / FLOW
- CTA → `../collection/index.html?filter=synergy`

**S6 — Theme Comparison Grid**
- 4 columns. Each: colour swatch 80px circle, theme name, 3 keywords, "View Materials →" link
- Background: `--dark`

---

### PAGE 04: Exhibition (`exhibition/index.html`)

**Purpose:** Maison de Synergy virtual gallery. Emotional, slow, reverent.
**Nav active:** Exhibition

**Sections:**

**S1 — Exhibition Entry (100vh)**
- Reuse exact Maison section from home: same radial gradient bg, same orb, same blossom, same temple image
- "Maison de Synergy" italic title
- Exhibition dates text: "2025 · Seoul · Ongoing"
- Scroll indicator

**S2 — Curatorial Statement (60vh)**
- Centred, max-width 600px
- Copy: "Maison de Synergy is an exhibition that emerges from the unique fusion of materials and fashion, as well as the reinvention of past styles infused with a modern sensibility."
- Exhibition info: dates, address, hours

**S3 — Room 01: Materials (100vh)**
- Full-width image left (clip-reveal), sticky text right
- Image: `photo-1618221195710-dd6b41faaea6?w=1200&q=80`
- Room label: `01 — Materials`
- Title: "Where warmth becomes surface"
- Body: 2 lines of copy
- CTA → `../collection/index.html`

**S4 — Room 02: Fashion (100vh)**
- Mirrored layout (text left, image right)
- Image: `photo-1555041469-a586c61ea9bc?w=1200&q=80`
- Room label: `02 — Fashion`
- Title: "The material of movement"

**S5 — Room 03: Architecture (100vh)**
- Full-bleed image, text overlay bottom-left
- Image: `photo-1486325212027-8081e485255e?w=1800&q=75` (same building night image)
- Room label: `03 — Architecture`

**S6 — Highlights Carousel (auto)**
- 8 images in a CSS-only scroll carousel (no Swiper dependency)
- `display:flex; overflow-x:auto; scroll-snap-type:x mandatory`
- Each slide: full-height 80vh, object-fit cover
- Custom navigation: prev/next arrows + "01/08" counter
- Images: mix of all 7 strip images + temple image

**S7 — Book a Visit CTA (60vh)**
- Rose gradient background
- "Visit the Exhibition" heading
- Contact email: `exhibition@trendship.com`
- Link → `../contact/index.html?interest=exhibition`

---

### PAGE 05: Collection (`collection/index.html`)

**Purpose:** Material showcase with filter, cards, and modal.
**Nav active:** Collection
**Background:** `--off-white` (light page — set `#nav` `.lt` mode immediately, not `.dk`)

**Note for nav on this page:**
```javascript
// Collection is a light page — nav text must be dark
const nav = document.getElementById('nav');
nav.classList.remove('dk', 'bl');
nav.style.color = 'var(--text-dark)';
// Add blur on scroll past hero
ScrollTrigger.create({ trigger:'#collection-hero', start:'bottom 72px',
  onEnter: () => nav.classList.add('bl'),
  onLeaveBack: () => nav.classList.remove('bl')
});
```

**Sections:**

**S1 — Hero (60vh)**
- `--off-white` background
- "COLLECTION" in Bebas Neue, `clamp(72px,12vw,160px)`, `--text-dark`
- Sub: "LX Hausys Materials 2025"
- Letter rise animation on load

**S2 — Filter Bar (sticky after hero)**
- 5 pills: ALL / BOOST / COSMOS / OOPARTS / SYNERGY
- Active state: `background:var(--rose); color:#fff`
- Inactive: `border:1px solid var(--border); color:var(--muted)`
- Click: filters grid with opacity/display transition (no Flip needed, simple CSS)
- URL: updates `?filter=` param via `history.replaceState`

**S3 — Material Grid (auto)**
- 12 cards, 3-column grid. Gap 16px. Each card: aspect-ratio 3/4
- 3 cards per theme (Boost, Cosmos, Ooparts, Synergy)
- Card hover: inner image scale 1.06, overlay fades in with "View Details"
- Card click: opens modal

**Material data — all 12:**
```
BOOST:
  B01 Terracotta Linen   photo-1558618666-fcd25c85cd64  #c8885a #b87848 #d8a880
  B02 Warm Oak           photo-1600566753190-17f0baa2a6c3 #c8a060 #a88040 #e0c080
  B03 Amber Matte        photo-1594736797933-d0501ba2fe65 #d09848 #b07828 #e8b868

COSMOS:
  C01 Midnight Slate     photo-1519710164239-da123dc03ef4 #3a4870 #2a3860 #4a5880
  C02 Void Navy          photo-1557804506-669a67965ba0    #1a2248 #0a1230 #2a3258
  C03 Steel Blue         photo-1550859492-d5da9d8e45f3    #607090 #485878 #788aa8

OOPARTS:
  O01 Ancient Earth      photo-1565538810643-b5bdb714032a #9a7840 #7a5820 #c0a060
  O02 Stone Ash          photo-1493666438817-866a91353ca9 #a8a090 #888070 #c8c0b0
  O03 Fossil Brown       photo-1598928506311-c55ded91a20c #8a6848 #6a4828 #aA8868

SYNERGY:
  S01 Bloom Rose         photo-1616486338812-3dadae4b4ace #d4857a #b46858 #e8a898
  S02 Petal Pink         photo-1617104678090-33e8e12c3dd2 #e8b0b0 #c89090 #f8d0d0
  S03 Silk Greige        photo-1600210492493-0946911123ea #c8b8b0 #a89890 #e0d8d0
```

**Modal:**
- Opens over collection page
- Left: large image
- Right: name, code, theme tag, surface type, colour dots, "Download Spec" button
- Close: ✕ button or Escape

---

### PAGE 06: Trend Report (`trend-report/index.html`)

**Purpose:** Annual design trend data, stats, expert voices. Download CTA.
**Nav active:** Report

**Sections:**

**S1 — Report Cover (100vh)**
- Dark bg. "2025 TRENDSHIP DESIGN TREND REPORT" in Bebas at 4.5vw
- Year "2025" at 28vw, near-transparent in background
- Rose rule line. "Annual Design Trend Report · LX Hausys" subtitle

**S2 — Executive Summary**
- White bg
- Intro at 22px EB Garamond
- 4 CountUp stats (same as home §stats — copy the JS)

**S3 — SVG World Map**
- Inline SVG, 800×420 viewBox
- Countries with LX Hausys presence filled in `var(--rose)` at 35% opacity
- Pulsing circle dots on: Seoul, London, New York, Singapore, Dubai, Sydney
- Tooltip on hover: city name + tagline

**S4 — Animated Line Chart**
- Custom SVG, 3 lines: COMFORT / SPACE / FUSION (2020–2025)
- Lines draw themselves via `stroke-dashoffset` animation on scroll entry
- Data points appear after lines finish (0.8s delay), with hover tooltips
- Legend below: coloured circles + labels

**S5 — Expert Pull Quotes (3)**
- Alternating dark/light backgrounds
- Quote, attribution, title
- Clip-reveal from bottom on scroll entry

**S6 — Featured Materials (3 cards)**
- Horizontal row, smaller than collection grid
- Links to `../collection/index.html`

**S7 — Download CTA (100vh)**
- Dark bg, glowing rose orb
- "Download the 2025 Trend Report"
- Email input + submit button
- On submit: `fetch('/api/report/download', { method:'POST', body:JSON.stringify({email}) })`
- Success: form swaps to "Check your inbox!" message

---

### PAGE 07: Lookbook (`lookbook/index.html`)

**Purpose:** Pure cinematic room photography. The most visual page.
**Nav active:** Lookbook

**Cursor special behaviour:** On this page, add cursor image trail:
```javascript
const trail = [];
for (let i = 0; i < 6; i++) {
  const el = document.createElement('div');
  el.className = 'cursor-trail';
  el.style.cssText = 'position:fixed;width:72px;height:72px;border-radius:50%;overflow:hidden;pointer-events:none;opacity:0;z-index:8000;';
  el.innerHTML = `<img src="${trailImages[i%trailImages.length]}" style="width:100%;height:100%;object-fit:cover">`;
  document.body.appendChild(el);
  trail.push(el);
}
let lastMove = 0, trailIdx = 0;
document.addEventListener('mousemove', e => {
  if (Date.now() - lastMove < 100) return;
  lastMove = Date.now();
  const el = trail[trailIdx++ % 6];
  gsap.set(el, { x: e.clientX-36, y: e.clientY-36, opacity:1 });
  gsap.to(el, { opacity:0, scale:.7, duration:.8, ease:'power2.out',
    onComplete: () => gsap.set(el, { scale:1 }) });
});
```

**Sections:**

**S1 — Cover (100vh)**
- "LOOKBOOK 2025" Bebas at 10vw, left-aligned
- Large hero image right half
- Image: `photo-1586023492125-27b2c045efd7?w=1600&q=85`

**S2 — BOOST Spread (100vh full-bleed)**
- Image: `photo-1586023492125-27b2c045efd7?w=1600&q=88`
- Ken Burns: scale 1.0 → 1.05 over full scroll distance (very subtle)
- Material tag bottom-right: "Terracotta Linen · BOOST → Collection"

**S3 — COSMOS Spread (100vh two-image)**
- Left: 68vh tall — `photo-1618221195710-dd6b41faaea6`
- Right: 44vh tall — `photo-1549497538-303791108f95` (top-aligned)
- Text between: "Quiet. Deep. Infinite." at 2vw

**S4 — OOPARTS Spread (100vh triptych)**
- Three columns, same height, 1px gap
- Images: photo-1567767292278 / photo-1616486338812 / photo-1583847268964
- Centre image at full opacity, flanking at 0.65

**S5 — SYNERGY Spread (100vh)**
- Full-bleed image with warm interior
- Text overlay centred italic: `"Where values meet, beauty emerges."`
- Image: `photo-1555041469-a586c61ea9bc?w=1600&q=88`

**Fixed dot nav (right edge):**
```html
<nav class="lb-dots" aria-label="Lookbook navigation">
  <button class="lb-dot active" data-target="lb-s1" aria-label="Cover"></button>
  <button class="lb-dot" data-target="lb-s2" aria-label="BOOST spread"></button>
  <button class="lb-dot" data-target="lb-s3" aria-label="COSMOS spread"></button>
  <button class="lb-dot" data-target="lb-s4" aria-label="OOPARTS spread"></button>
  <button class="lb-dot" data-target="lb-s5" aria-label="SYNERGY spread"></button>
</nav>
```

---

### PAGE 08: About (`about/index.html`)

**Purpose:** Brand story, history, team, partners. Warm and trustworthy.
**Nav active:** About

**Sections:**

**S1 — Hero (100vh)**
- Reuse exact `#about` section from home — ticker + 2 grayscale photos
- Same images, same parallax values
- Label: `( About Trendship )`

**S2 — Brand Statement (60vh)**
- `--off-white` bg
- Centre: EB Garamond italic, 32px
- Copy: "LX Hausys has been defining how Korea lives, works, and breathes beauty for over five decades."
- Character reveal on scroll (split into letters, stagger)

**S3 — Statistics (auto)**
- Reuse exact `#stats` from home — same 4 numbers, same CountUp animation

**S4 — Philosophy Pillars (auto)**
- Dark bg. 3 columns.
- Each: roman numeral (Bebas, 64px, rose), title (Bebas, 28px, off-white), body (Garamond, 15px, opacity .5)
- Pillar I: "MATERIAL TRUTH — We believe surfaces should be honest. Beautiful because of what they are, not despite it."
- Pillar II: "LIFE DESIGN — Design is not a finishing touch. It is the architecture of daily experience."
- Pillar III: "COLLECTIVE ENERGY — When different values meet, beauty multiplies. This is SY(E)NERGY."
- Clip-reveal bottom stagger on scroll entry

**S5 — History Timeline (horizontal scroll)**
- 6 milestones, pinned horizontal scroll section
- Timeline line draws itself via `stroke-dashoffset`
- Milestones: 1947 Foundation / 1995 LX Hausys / 2008 Global / 2019 Sustainability / 2023 Trendship Launch / 2025 SY(E)NERGY

**S6 — Team Section (auto)**
- CSS grid, 4 columns
- Each card: grayscale portrait, name, title
- Hover: desaturate removes → full colour, name slides up from bottom
- Images: generic professional portraits (Unsplash faces — pick 8)

**S7 — Partners Marquee (auto)**
- CSS `animation:marquee 30s linear infinite`
- Two sets of logos for seamless loop
- Pause on hover: `animation-play-state:paused`

---

### PAGE 09: Contact (`contact/index.html`)

**Purpose:** Contact form, newsletter, social, farewell organism.
**Nav active:** none (Contact not in top nav — only in overlay)

**Sections:**

**S1 — Hero with Organism (60vh)**
- Dark bg
- Three.js coral organism reused, centred, scale 0.75
- Mouse parallax lerp reduced to 0.025 (more peaceful)
- "Get in touch" in EB Garamond italic, `clamp(32px,5vw,60px)`, centred

**S2 — Contact Form (auto)**
- Floating label pattern (label moves up on focus/has-value)
- Fields: Name (text), Email (email), Company (text, optional), Interest (select), Message (textarea)
- Interest options: General / Exhibition / Collection / Press / Partnership
- Pre-fill interest from `?interest=` URL param
- Submit: POST to `/api/contact` (or show email fallback `hello@trendship.com` if no backend)
- Validation: required fields, email format
- Success: form fades out, replaced with "Thank you, [name]. We'll be in touch at [email]."

**S3 — Newsletter (40vh)**
- Dark bg with rose orb
- Heading: "Join 12,000 designers"
- Single email input + submit
- POST to `/api/newsletter`

**S4 — Office Locations (auto)**
- 3 columns: Seoul (HQ) / London / New York
- Each: city in Bebas 3vw, address in Garamond, phone

**S5 — Social Icons (40vh)**
- 3 large outlined circle buttons: IG / LI / YT
- Magnetic hover effect:
  ```javascript
  document.querySelectorAll('.social-icon').forEach(icon => {
    document.addEventListener('mousemove', e => {
      const rect = icon.getBoundingClientRect();
      const dx = e.clientX - (rect.left + rect.width/2);
      const dy = e.clientY - (rect.top  + rect.height/2);
      const dist = Math.sqrt(dx*dx + dy*dy);
      if (dist < 80) gsap.to(icon, { x:dx*.4, y:dy*.4, duration:.4 });
      else           gsap.to(icon, { x:0, y:0, duration:.6, ease:'elastic.out(1,.5)' });
    });
  });
  ```

---

## PART 6 — ANIMATION LIBRARY

Every named animation used in the site. Copy-paste ready.

### 6.1 Organism Bloom (load)
```javascript
// Triggered in page load timeline, delay 0.32s
gsap.to({ v: 0 }, {
  v: 1, duration: 1.6, ease: 'elastic.out(1, .46)',
  onUpdate: function() { mScale = this.targets()[0].v; }
});
```

### 6.2 Letter Rise
```javascript
// Split text manually: wrap each char in .lw > .lc
el.innerHTML = [...el.textContent].map(c =>
  `<span class="lw"><span class="lc">${c}</span></span>`).join('');
// Animate:
gsap.to('.lc', { y:0, opacity:1, duration:.72, ease:'power3.out', stagger:.038 });
```

### 6.3 Synergy Crash
```javascript
// SY + NERGY from sides, (E) pops
const tl = gsap.timeline({ scrollTrigger:{ trigger:'#synergy', start:'top 62%', toggleActions:'play none none reverse' }});
tl.to('.sy-l', { x:0, opacity:1, duration:.82, ease:'power4.out' }, 0)
  .to('.sy-r', { x:0, opacity:1, duration:.82, ease:'power4.out' }, 0)
  .to('.sy-e', { scale:1.16, opacity:1, duration:.22, ease:'power2.out' }, .68)
  .to('.sy-e', { scale:1, duration:.2, ease:'back.out(3)' }, .9)
  .to('.sy-tag', { opacity:1, y:0, duration:.5 }, 1.06);
```

### 6.4 CountUp
```javascript
document.querySelectorAll('.stat-num').forEach(el => {
  const target = +el.dataset.target;
  const suffix = el.dataset.suffix || '';
  const obj = { val: 0 };
  gsap.to(obj, {
    val: target, duration: 2.2, ease: 'power2.out',
    onUpdate: () => { el.textContent = Math.round(obj.val).toLocaleString() + suffix; }
  });
});
```

### 6.5 Circle Wipe
```javascript
// In: dot expands to fill screen
gsap.to('#tDot', { scale:2900, duration:1.0, ease:'power2.in' });
// Out: dot collapses
gsap.to('#tDot', { scale:0, duration:.85, ease:'power2.out', delay:.05 });
```

### 6.6 Clip Reveal (images)
```css
.clip-reveal { clip-path: inset(100% 0 0 0); }
```
```javascript
gsap.to(el, {
  clipPath:'inset(0% 0 0 0)', duration:1.1, ease:'power2.inOut',
  scrollTrigger:{ trigger:el, start:'top 72%', once:true }
});
// Pair with inner image scale:
gsap.from(el.querySelector('img'), { scale:1.12, duration:1.1, ease:'power2.inOut',
  scrollTrigger:{ trigger:el, start:'top 72%', once:true } });
```

### 6.7 Orb Expand (Maison)
```javascript
gsap.to({ p: .45 }, {
  p: 1, duration: 1.5, ease: 'power2.out',
  onUpdate: function() {
    const p = this.targets()[0].p;
    orb.style.transform = `translateX(-50%) scale(${p})`;
    orb.style.opacity   = Math.max(0, (p - .45) * 1.82).toString();
  }
});
```

### 6.8 SVG Line Draw
```javascript
document.querySelectorAll('.chart-line').forEach((line, i) => {
  const len = line.getTotalLength();
  gsap.set(line, { strokeDasharray:len, strokeDashoffset:len });
  gsap.to(line, {
    strokeDashoffset:0, duration:1.8, ease:'power2.inOut', delay:i*.3,
    scrollTrigger:{ trigger:'#chart-section', start:'top 65%', once:true }
  });
});
```

---

## PART 7 — IMAGE LIBRARY

All Unsplash images used across the site. Pattern: `https://images.unsplash.com/[ID]?w=[W]&q=[Q]`

### 7.1 Home Page Images

| Location | ID | Params | Alt text |
|----------|-----|--------|----------|
| Photo strip 1 | `photo-1586023492125-27b2c045efd7` | `w=700&q=82` | Warm terracotta living room interior |
| Photo strip 2 | `photo-1618221195710-dd6b41faaea6` | `w=700&q=82` | Modern minimal bedroom with soft lighting |
| Photo strip 3 | `photo-1555041469-a586c61ea9bc`   | `w=700&q=82` | Plush sofa in warm toned living room |
| Photo strip 4 | `photo-1549497538-303791108f95`   | `w=700&q=82` | Minimal bedroom with clean architectural lines |
| Photo strip 5 | `photo-1567767292278-a4f21aa2d36e` | `w=700&q=82` | Cosy living space with earth tones |
| Photo strip 6 | `photo-1616486338812-3dadae4b4ace` | `w=700&q=82` | Luxury bedroom with refined materials |
| Photo strip 7 | `photo-1583847268964-b28dc8f51f92` | `w=700&q=82` | Contemporary open plan living area |
| Theme thumb BOOST | `photo-1586023492125-27b2c045efd7` | `w=200&q=70` | BOOST theme interior |
| Theme thumb COSMOS | `photo-1556909114-f6e7ad7d3136` | `w=200&q=70` | COSMOS theme space |
| Theme thumb OOPARTS | `photo-1567767292278-a4f21aa2d36e` | `w=200&q=70` | OOPARTS theme time |
| Theme thumb SYNERGY | `photo-1583847268964-b28dc8f51f92` | `w=200&q=70` | SYNERGY theme fusion |
| Theme thumb BOOST r | `photo-1618221195710-dd6b41faaea6` | `w=200&q=70` | BOOST theme energy |
| Theme thumb COSMOS r | `photo-1549497538-303791108f95` | `w=200&q=70` | COSMOS theme depth |
| Theme thumb OOPARTS r | `photo-1616486338812-3dadae4b4ace` | `w=200&q=70` | OOPARTS theme line |
| Theme thumb SYNERGY r | `photo-1555041469-a586c61ea9bc` | `w=200&q=70` | SYNERGY theme flow |
| Materials card 1 | `photo-1558618666-fcd25c85cd64` | `w=600&q=80` | Terracotta linen surface material |
| Materials card 2 | `photo-1519710164239-da123dc03ef4` | `w=600&q=80` | Deep midnight slate stone material |
| Materials card 3 | `photo-1565538810643-b5bdb714032a` | `w=600&q=80` | Ancient earth warm brown texture |
| Materials card 4 | `photo-1616486338812-3dadae4b4ace` | `w=600&q=80` | Bloom rose soft pink surface |
| Temple / Maison | `photo-1528360983277-13d401cdc186` | `w=1600&q=80` | Korean traditional temple at dusk |
| Building night | `photo-1486325212027-8081e485255e` | `w=1800&q=75` | Neoclassical building illuminated at night |
| About img 1 | `photo-1600585152220-90363fe7e115` | `w=600&q=75` | Contemporary interior design space |
| About img 2 | `photo-1600210492493-0946911123ea` | `w=600&q=75` | Architectural interior with refined surfaces |

### 7.2 Image Rules

1. `loading="lazy"` on all images except those in the hero (above fold)
2. `decoding="async"` on all images
3. Always include `width` and `height` attributes to prevent CLS
4. All Unsplash images: append `&auto=format` if AVIF/WebP support is desired in future
5. Never link to Unsplash images wider than needed — use `?w=` to match display width

---

## PART 8 — NAVIGATION & ROUTING RULES

### 8.1 Path Calculation

| Page | File path | Link prefix for cross-links |
|------|-----------|---------------------------|
| Home | `index.html` | `./` or just filename |
| Design Trend | `design-trend/index.html` | `../` |
| Themes | `themes/index.html` | `../` |
| Exhibition | `exhibition/index.html` | `../` |
| Collection | `collection/index.html` | `../` |
| Trend Report | `trend-report/index.html` | `../` |
| Lookbook | `lookbook/index.html` | `../` |
| About | `about/index.html` | `../` |
| Contact | `contact/index.html` | `../` |

### 8.2 Active Nav Link

Add class `active` to the `.n-a` matching the current page.
The `.active` class triggers the rose underline via:
```css
.n-a.active { opacity: 1; }
.n-a.active::after { width: 100%; }
```

### 8.3 Nav Dark/Light Logic

| Page | On load | On scroll |
|------|---------|-----------|
| Home | dark text (no .dk) | .dk + .bl after hero |
| All others | `nav.classList.add('dk','bl')` immediately | stays .dk.bl always |
| Collection | no `.dk` (light page) | add `.bl` after hero |

### 8.4 URL Parameters

| Page | Parameter | Effect |
|------|-----------|--------|
| `collection/index.html` | `?filter=boost|cosmos|ooparts|synergy|all` | Pre-selects filter button |
| `themes/index.html` | `?theme=boost|cosmos|ooparts|synergy` | Scrolls to that world on load |
| `contact/index.html` | `?interest=general|exhibition|collection|press|partnership` | Pre-fills interest dropdown |

---

## PART 9 — PERFORMANCE & ACCESSIBILITY CHECKLIST

### Performance

| Rule | Check |
|------|-------|
| `loading="lazy"` on all below-fold images | ✓ |
| `width` + `height` on all images (prevents CLS) | ✓ |
| Three.js `setPixelRatio(Math.min(devicePixelRatio, 2))` | ✓ |
| GSAP ticker used for Lenis (no double RAF) | ✓ |
| `gsap.ticker.lagSmoothing(0)` | ✓ |
| Resize debounced 220ms | ✓ |
| `ScrollTrigger.refresh(true)` on resize | ✓ |
| `invalidateOnRefresh:true` on photo strip | ✓ |
| No `.filter` CSS on elements that animate transform | check per page |
| `will-change:transform` only on animated elements | ✓ |

### Accessibility

| Rule | Check |
|------|-------|
| All `<img>` have descriptive `alt` text | ✓ |
| `aria-hidden="true"` on decorative elements (cursor, grain, blossom) | ✓ |
| `role="navigation"` + `aria-label` on `<nav>` | ✓ |
| `role="dialog" aria-modal="true"` on nav overlay | ✓ |
| `aria-expanded` on hamburger button, toggled in JS | ✓ |
| Escape key closes overlay | ✓ |
| `role="contentinfo"` on `<footer>` | ✓ |
| Focus trapped in overlay when open | implement on all pages |
| Colour contrast: all text ≥ 4.5:1 | verify greige text on white |
| `cursor:none` removed for touch devices | `@media(hover:none)` ✓ |
| `prefers-reduced-motion`: animations skip | add to all pages |

**Add reduced motion support to every page:**
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.001ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.001ms !important;
  }
}
```
```javascript
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  // Skip all GSAP entrance animations
  // Still initialise Three.js but set mScale=1 immediately and stop rotation
}
```

---

## PART 10 — GITHUB-READY CHECKLIST

Run through every item before `git push`. Nothing pushed until all ticked.

### Structure
- [ ] `index.html` in root (home page — complete)
- [ ] `design-trend/index.html` exists
- [ ] `themes/index.html` exists
- [ ] `exhibition/index.html` exists
- [ ] `collection/index.html` exists
- [ ] `trend-report/index.html` exists
- [ ] `lookbook/index.html` exists
- [ ] `about/index.html` exists
- [ ] `contact/index.html` exists
- [ ] `README.md` in root
- [ ] `.gitignore` in root

### Code Quality
- [ ] Zero duplicate `:root {}` blocks across files
- [ ] CDN versions consistent: GSAP 3.12.2, Three.js r128, Lenis 1.0.19
- [ ] Every `<img>` has meaningful `alt` text
- [ ] Every below-fold `<img>` has `loading="lazy"`
- [ ] Every `<img>` has `width` and `height` attributes
- [ ] Every `<button>` has `aria-label` or visible text
- [ ] No console errors on any page (open DevTools on each)
- [ ] No 404 errors in Network tab (images, fonts, scripts)

### Navigation
- [ ] TRENDSHIP logo → home from every page ✓
- [ ] Every `.n-a` href resolves to correct page
- [ ] Every `.no-item` href in overlay resolves to correct page
- [ ] Every footer link resolves to correct page
- [ ] Active `.n-a` correctly identifies current page on each page
- [ ] Hamburger opens overlay on all 9 pages
- [ ] Escape closes overlay on all 9 pages
- [ ] Overlay closes when link is clicked
- [ ] Browser back button works

### Visual
- [ ] Coral organism visible on Home page
- [ ] Coral organism visible on Contact page
- [ ] Film grain visible on all pages
- [ ] Progress bar fills on all pages
- [ ] Footer on all 9 pages
- [ ] All Unsplash images load (no 404)
- [ ] No broken layout at 375px width (iPhone SE)
- [ ] No broken layout at 768px width (tablet)
- [ ] No horizontal scroll on any page

### Deployment
- [ ] `npx serve .` works locally (no broken relative paths)
- [ ] GitHub Pages: Settings → Pages → Source: main branch / root
- [ ] All links use relative paths (no absolute `https://yoursite.com/...`)

---

```
TRENDSHIP FINAL SPECIFICATION v1.0
LX Hausys 2025 Design Trend Platform
9 Pages · 19 Animations · 24 Images · 1 Organism
Last updated: March 2026
```

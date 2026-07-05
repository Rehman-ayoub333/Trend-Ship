# TRENDSHIP
### LX Hausys 2025 Design Trend Platform

<br>

> *"New energy that flourishes when we come together."*

<br>

![TRENDSHIP Hero](https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&q=80)

<br>

## Overview

**TRENDSHIP** is the official 2025 design trend platform for **LX Hausys**, South Korea's leading interior surface materials company. Built as a luxury editorial website, it presents the annual design trend — **SY(E)NERGY** — across four distinct theme worlds: BOOST, COSMOS, OOPARTS, and SYNERGY.

The website is designed to feel less like software and more like a gallery opening. Every scroll is a revelation. Every section is a room you want to stay in. It combines immersive 3D visuals, cinematic scroll animations, and a full PHP + MySQL backend into a single cohesive experience.

<br>

## Live Demo

```
http://localhost/trendship/          → Main website
http://localhost/trendship/admin/    → Admin panel
```

> Requires XAMPP running locally. See [Setup](#setup) below.

<br>

## Features

### Frontend
- **Three.js coral organism** — a living, breathing 3D anemone that responds to mouse movement, blooms on load, and grows as you scroll
- **GSAP + ScrollTrigger animations** — 19 named animation patterns including letter rises, synergy crashes, slot machine themes, parallax layers, and circle wipe transitions
- **Lenis smooth scroll** — cinema-quality momentum scrolling across all 9 pages
- **Full-screen navigation overlay** — all 9 pages accessible from a single animated menu
- **Custom cursor** — blend-mode difference cursor with magnetic effects on interactive elements
- **Film grain overlay** — subtle animated noise texture on every page
- **Scroll progress bar** — rose-coloured 2px line at the top of every page
- **Responsive design** — works on desktop, tablet, and mobile

### Pages (9 total)
| # | Page | Purpose |
|---|------|---------|
| 01 | Home | Hero organism, photo strip, SY(E)NERGY, themes slot machine, materials preview |
| 02 | Design Trend | Deep editorial dive into the 2025 trend philosophy |
| 03 | Themes | Four full-viewport theme worlds — BOOST, COSMOS, OOPARTS, SYNERGY |
| 04 | Exhibition | Maison de Synergy virtual gallery with room-by-room scroll |
| 05 | Collection | 12 surface materials with filter, detail modal, and moodboard builder |
| 06 | Trend Report | Annual data report with animated charts, world map, and PDF download |
| 07 | Lookbook | Cinematic full-bleed room photography with cursor image trail |
| 08 | About | Brand story, history timeline, team portraits, partners |
| 09 | Contact | Contact form, newsletter, office locations, social links |
| — | Login | Visitor account creation and sign-in |
| — | Admin | Full CRUD dashboard for all site content |

### Backend (PHP + MySQL)
- **CRUD for materials** — add, edit, delete surface materials from the admin panel; collection page loads live from the database
- **Exhibition bookings** — visitors book exhibition visits; admin confirms, cancels, or deletes bookings
- **Contact messages** — all form submissions stored in MySQL; admin reads and updates status
- **Newsletter subscribers** — email list with source tracking (newsletter / contact / report download); CSV export
- **Report downloads** — tracks who downloaded the trend report; auto-adds to subscriber list
- **Moodboard** — session-based, saves up to 6 materials per visitor
- **Visitor accounts** — register and log in to save moodboards across sessions
- **Admin panel** — protected dashboard with sidebar navigation, statistics, and full CRUD UI for every data type
- **Activity log** — every admin action is recorded with timestamp and IP

<br>

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| 3D Graphics | Three.js | r128 |
| Animations | GSAP + ScrollTrigger | 3.12.2 |
| Smooth Scroll | Lenis | 1.0.19 |
| Typography | EB Garamond + Bebas Neue | Google Fonts |
| Backend | PHP | 8.x |
| Database | MySQL (via XAMPP) | 8.0+ |
| Server | Apache (via XAMPP) | 2.4+ |
| Photography | Unsplash | — |

No build tools. No npm. No webpack. Open `index.html` and it works.

<br>

## Design System

### Colour Palette
| Token | Hex | Role |
|-------|-----|------|
| `--greige` | `#d4c4bc` | Hero background |
| `--dark` | `#0a0a0a` | Primary dark background |
| `--off-white` | `#f4efe9` | Light section backgrounds |
| `--rose` | `#d4857a` | Primary accent — buttons, CTAs, active states |
| `--rose-dust` | `#c4968e` | Muted accent — labels, nav numbers |
| `--rose-light` | `#e8a8b0` | Organism, soft highlights |
| `--wine` | `#8a4a42` | Deep rose — hover states |
| `--maison` | `#bf7268` | Exhibition terracotta |

### Typography
- **Display / Wordmarks** — Bebas Neue (`--ff-display`)
- **Body / Editorial** — EB Garamond (`--ff-body`)
- **Hero wordmark** — `clamp(88px, 15.5vw, 230px)`
- **SY(E)NERGY** — `clamp(58px, 13.5vw, 185px)`

<br>

## Database Schema

```
trendship_db
├── materials        12 seed records (3 per theme × 4 themes)
├── bookings         Exhibition visit bookings
├── subscribers      Newsletter email list
├── messages         Contact form submissions
├── report_downloads Trend report download log
├── moodboards       Visitor-saved material collections
├── visitors         Public user accounts
├── admins           Admin panel accounts
└── activity_log     Audit trail of all admin actions
```

<br>

## Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x)

### Step 1 — Start XAMPP
```
Open XAMPP Control Panel
Start Apache
Start MySQL
```

### Step 2 — Place files
```bash
# Windows
copy project to: C:\xampp\htdocs\trendship\

# Mac
copy project to: /Applications/XAMPP/xamppfiles/htdocs/trendship/

# Linux
copy project to: /opt/lampp/htdocs/trendship/
```

### Step 3 — Create the database
```
1. Open http://localhost/phpmyadmin
2. Create database: trendship_db
3. Collation: utf8mb4_unicode_ci
4. Go to SQL tab
5. Run the SQL from TRENDSHIP_CRUD_SPEC.md → Part 1
6. Run the visitors table SQL from TRENDSHIP_COMPLETE_STATUS.md → Part 5
```

### Step 4 — Create backend files
All PHP files are documented with complete code in `TRENDSHIP_CRUD_SPEC.md`.
Alternatively, use the CLI commands in `TRENDSHIP_CLI_INSTRUCTIONS.md`.

### Step 5 — Open the website
```
http://localhost/trendship/           → Home page
http://localhost/trendship/admin/     → Admin panel
http://localhost/trendship/login/     → Visitor login
```

### Default Admin Credentials
```
Username: admin
Password: admin123
```
> **Change the password immediately after first login.**

<br>

## Project Structure

```
trendship/
├── index.html                   # Home page (complete)
├── login/index.html             # Visitor login & register
├── design-trend/index.html      # Design Trend editorial
├── themes/index.html            # 4 theme worlds
├── exhibition/index.html        # Maison de Synergy gallery
├── collection/index.html        # Material collection + filter
├── trend-report/index.html      # Annual trend report
├── lookbook/index.html          # Styled space photography
├── about/index.html             # Brand story + team
├── contact/index.html           # Contact form + newsletter
│
├── api/
│   ├── config.php               # DB connection + constants
│   ├── helpers.php              # Shared functions
│   ├── materials/               # CRUD: surface materials
│   ├── bookings/                # CRUD: exhibition bookings
│   ├── subscribers/             # CRUD: newsletter list
│   ├── messages/                # CRUD: contact messages
│   ├── downloads/               # Log: report downloads
│   ├── moodboards/              # CRUD: visitor moodboards
│   ├── visitors/                # Auth: register / login
│   └── auth/                    # Auth: admin sessions
│
└── admin/
    ├── login.php                # Admin sign in
    ├── index.php                # Dashboard
    ├── materials.php            # Manage materials
    ├── bookings.php             # Manage bookings
    ├── messages.php             # View messages
    ├── subscribers.php          # Manage email list
    └── downloads.php            # Download log
```

<br>

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| `GET` | `/api/materials/index.php` | Public | List materials (`?theme=`, `?featured=1`) |
| `POST` | `/api/materials/index.php` | Admin | Create material |
| `GET` | `/api/materials/read.php?id=N` | Public | Get single material |
| `PUT` | `/api/materials/update.php` | Admin | Update material |
| `DELETE` | `/api/materials/delete.php` | Admin | Delete material |
| `POST` | `/api/bookings/index.php` | Public | Create exhibition booking |
| `POST` | `/api/messages/index.php` | Public | Submit contact message |
| `POST` | `/api/subscribers/index.php` | Public | Subscribe to newsletter |
| `POST` | `/api/downloads/index.php` | Public | Record report download |
| `POST` | `/api/visitors/register.php` | Public | Create visitor account |
| `POST` | `/api/visitors/login.php` | Public | Visitor sign in |

All endpoints return JSON. All public write endpoints include rate limiting.

<br>

## Screenshots

| Section | Description |
|---------|-------------|
| Hero | Three.js coral organism on warm greige, TRENDSHIP wordmark at 15.5vw |
| SY(E)NERGY | Letters crash from both sides, (E) pops with rose box |
| Themes | Slot machine scroll through BOOST → COSMOS → OOPARTS → SYNERGY |
| Maison de Synergy | Terracotta radial gradient, botanical blossom, Korean temple image |
| Building Night | Neoclassical facade with warm window glow at dusk |
| Materials | 4-column grid of surface materials with colour swatches |
| About | Giant TRENDSHIP ticker with grayscale portrait photos |

<br>

## Browser Support

| Browser | Version |
|---------|---------|
| Chrome | 90+ |
| Firefox | 88+ |
| Safari | 14+ |
| Edge | 90+ |

Three.js r128 does not include `CapsuleGeometry` — all geometry uses `CylinderGeometry` + `SphereGeometry` for compatibility.

<br>

## Security Notes

- All database queries use **PDO prepared statements** — SQL injection is not possible
- All user output uses `htmlspecialchars()` — XSS is not possible
- Admin passwords stored as **bcrypt hashes** — never plain text
- Rate limiting on contact form (3 per email per 24 hours)
- `api/.htaccess` disables directory browsing
- `api/config.php` is listed in `.gitignore` — never commit DB credentials

<br>

## Documentation

All specification documents are included in the repository:

| Document | Contents |
|----------|---------|
| `TRENDSHIP_FINAL_SPEC.md` | Design system, all 9 pages, animation library, image library |
| `TRENDSHIP_CRUD_SPEC.md` | Complete PHP backend code for all 32 API + admin files |
| `TRENDSHIP_COMPLETE_STATUS.md` | Login page, contact page, visitors API — full implementation |
| `TRENDSHIP_CLI_INSTRUCTIONS.md` | Step-by-step terminal commands for Windows, Mac, and Linux |
| `TRENDSHIP_FUNCTIONAL_SPEC.md` | Every button, every interaction, every state machine |
| `TRENDSHIP_INTEGRATION_SPEC.md` | How all 9 pages connect as one website |

<br>

## Credits

| Role | Credit |
|------|--------|
| Design Concept | LX Hausys — Trendship 2025 |
| Original Website | [@wearebrand.io](https://www.instagram.com/wearebrand.io) |
| Development | Rehman ([@websby_umair](https://www.fiverr.com/websby_umair)) |
| Photography | [Unsplash](https://unsplash.com) contributors |
| 3D Library | [Three.js](https://threejs.org) |
| Animation | [GSAP](https://greensock.com/gsap/) by GreenSock |
| Smooth Scroll | [Lenis](https://github.com/studio-freight/lenis) by Studio Freight |

<br>

## License

This project is built for portfolio and educational purposes, inspired by the original Trendship website by LX Hausys. All LX Hausys branding, product names, and design concepts belong to LX Hausys Co., Ltd.

Photography from Unsplash is used under the [Unsplash License](https://unsplash.com/license).

<br>

---

<p align="center">
  <strong>TRENDSHIP</strong> — Designed with love in Seoul, 2025
  <br>
  <em>Where values meet, beauty emerges.</em>
</p>

# Design System: Bruce Class
**Project ID:** `5404652156695515193` (Stitch project "Bruce Class", private)
**Stitch Home screen:** "Bruce Class — Home", `projects/5404652156695515193/screens/914626fc0e2c4714bdf7986090a1e0d8` (DESKTOP, 2560 × 21318 px canvas)
**Stitch design system:** `assets/9364784798711867201` "Bruce Class Editorial"
**Local reference draft:** `.stitch/designs/index.html` (hand-built v0), screenshots `index.png` and `index-mobile.png`
**Status:** v1.1. Sections 1–11 are the intended system. Section 0 records what Stitch actually generated, as far as it can be verified through Stitch MCP metadata. **The visual analysis of the real screen is still pending**, because this environment's network policy blocks the screen's HTML and screenshot downloads (see §0.4).

---

## 0. Stitch Source of Truth (verified through Stitch MCP)

### 0.1 Verified project and screen record
| Item | Value (from `get_project` / `list_screens` / `get_screen`) |
|---|---|
| Project | `projects/5404652156695515193`, title "Bruce Class", `PROJECT_DESIGN`, origin `STITCH`, visibility `PRIVATE`, role `OWNER` |
| Created | 2026-09-26T15:11:53Z |
| Screen | "Bruce Class — Home", id `914626fc0e2c4714bdf7986090a1e0d8`, device `DESKTOP`, width 2560, height 21318 |
| HTML file | `projects/5404652156695515193/files/b6705099e1f54a0d8f057d3dd15824f6` (text/html) |
| Screenshot file | `projects/5404652156695515193/files/074a215235c2487c99c9ca36c70f7b2c` |
| Screens in project | Exactly one |

### 0.2 Theme Stitch applied to the project (verified)
| Setting | Stitch value | Intended (Sections 2–3) | Status |
|---|---|---|---|
| Color mode | `LIGHT`, variant `NEUTRAL` | Light | Matches |
| Seed / override primary | `#1C3B5A` | Harbor Navy `#1C3B5A` | Matches |
| Override secondary | `#A87A2E` | Heritage Brass `#A87A2E` | Matches |
| Override neutral | `#F6F4EF` | Warm Paper `#F6F4EF` | Matches |
| Headline font | **Newsreader** | Fraunces | **Differs.** Stitch's font list has no Fraunces, so Newsreader is the theme font. The generation prompt asked for Fraunces via Google Fonts. Whether the screen HTML uses Fraunces or Newsreader is unverified. |
| Body font | Geist | Geist | Matches |
| Label font | Geist | Geist Mono | **Differs.** Stitch's font list has no Geist Mono, so labels fall back to Geist unless the HTML loads Geist Mono. Unverified. |
| Roundness | `ROUND_EIGHT` (8px) | 8px buttons and images | Matches |
| Spacing scale | `2` | Section 5 scale | Stitch-internal value; mapping unverified |

### 0.3 Palette Stitch generated from the seed colours (verified `namedColors`)
Stitch expands the seed colours into Material-style roles. These are the values its screens use by default:

| Stitch role | Hex | Closest intended token | Note |
|---|---|---|---|
| `background` / `surface` / `surface_bright` | `#FBF9F4` | Warm Paper `#F6F4EF` | Lighter and less warm than intended |
| `surface_container_lowest` | `#FFFFFF` | Clean Surface White `#FFFFFF` | Matches |
| `surface_container_low` | `#F5F4ED` | Warm Paper `#F6F4EF` | Near match |
| `surface_container` | `#EFEEE6` | Soft Linen `#EDEAE2` | Near match |
| `surface_container_high` / `highest` | `#E8E9E0` / `#E2E3D9` | — | Additional tonal steps |
| `on_background` / `on_surface` | `#31332C` | Midnight Ink `#151B28` | **Lighter, olive-grey rather than ink navy** |
| `on_surface_variant` | `#5E6058` | Slate Muted `#5A6272` | Near match, warmer |
| `outline` / `outline_variant` | `#797C73` / `#B1B3A9` | Firm Stone `#C9C2B3` / Hairline Stone `#DCD7CB` | Darker than intended |
| `primary` | `#436081` | Harbor Navy `#1C3B5A` | **Noticeably lighter.** `primary_dim` is `#375475` and `on_primary_fixed` is `#234160` |
| `primary_container` / `primary_fixed` | `#D1E4FF` | — | Light blue tint; not in the intended system |
| `on_primary` | `#F5F8FF` | White | Near match |
| `secondary` | `#80570A` | Burnished Brass `#86601F` | Near match |
| `secondary_container` | `#FFDDB0` | Featured badge `#F2E8D5` | More saturated |
| `tertiary` | `#595E78` | — | Muted indigo; not in the intended system |
| `error` | `#9F403D` | Brick Red `#A2382C` | Near match |
| `inverse_surface` | `#0E0E0C` | Night Ink `#0F1522` | Warmer near-black |

Contrast of the Stitch defaults (WCAG): `#31332C` on `#FBF9F4` is about 12.2:1; `#436081` on `#FBF9F4` is about 6.2:1; white on `#436081` is about 6.5:1. All pass AA.

**Rule until the visual check is done:** Sections 2–11 remain the source of truth for new prompts. Every baton prompt must keep naming the exact hex values (Harbor Navy `#1C3B5A`, Midnight Ink `#151B28`, Warm Paper `#F6F4EF`), because Stitch's automatic roles drift lighter (`primary #436081`, text `#31332C`).

### 0.4 Pending: visual analysis of the real Home screen
The `design-md` skill needs the screen's HTML (Tailwind config, classes, layout) and screenshot. Both downloads were refused by this cloud environment's egress policy:

- `contribution.usercontent.google.com` (screen HTML)
- `lh3.googleusercontent.com` (screenshots)

Once both hosts are allowed in the environment's network settings:
1. Download the HTML to `.stitch/designs/index.html` and the screenshot (with `=w2560`) to `.stitch/designs/index.png`, keeping the local draft under a new name.
2. Re-run `design-md` and replace or confirm Sections 1–7 and 11 with what the screen actually uses: fonts actually loaded, the Tailwind colour config, component classes, section order and layout.
3. Resolve the differences in §0.2 and §0.3.
4. Record the screen in `.stitch/metadata.json` under `screens.index`.

> The `design-md` skill normally reads a finished Stitch screen through the Stitch MCP server. That server was not connected during setup. This file follows the `design-md` output format (Sections 1–5), adds the Stitch prompt block the `stitch-loop` skill needs (Section 6), and adds the rules from `taste-design` (Sections 7–11). Its values come from the local reference home screen. Once the home screen exists in Stitch, run `design-md` again against it and reconcile any differences here.

---

## 1. Visual Theme & Atmosphere

Bruce Class should feel like a **premium editorial institution**: part university press and part modern product studio. The mood is **calm, assured and quietly expensive**. The quality comes from typography, spacing, composition and restraint, not from decoration.

- **Density:** Gallery-airy on public pages (3/10) and balanced in portals (6/10). Sections breathe, and portals stay information-first.
- **Variance:** Offset-asymmetric (6/10). Split layouts are left-weighted, and editorial grids mix large and small compositions. Hero sections are never centered.
- **Motion:** Fluid and restrained (4/10). Content rises gently into view, links draw an underline, images zoom slowly and tabs cross-fade. Motion never bounces or loops for attention. The one exception is a slow pulse on the "Enrollment open" status dot.
- **Character references:** A modern university website, a digital newsroom and a Swiss grid layout. Warm paper tones meet deep ink navy, with a thin brass line as the signature detail.

**Key characteristics**
- A large serif display type (Fraunces) sets an editorial voice. A precise grotesque (Geist) handles everything functional.
- Small monospaced "kicker" labels in brass, preceded by a short hairline rule, introduce every section.
- Hairline dividers and ruled lists are used more often than boxed cards. Cards appear only where elevation shows hierarchy.
- Section rhythm alternates between paper, white surface, a deep-ink dark band and a navy CTA panel, so no two consecutive sections look alike.
- Imagery is photography-first, with subtly rounded frames and slow hover zooms. Photos are never tinted with random gradients.

---

## 2. Color Palette & Roles

The palette is small on purpose: warm neutrals, one navy primary, one brass accent and three status colours. Do not add colours.

### Foundation
| Token | Name | Hex | Role |
|---|---|---|---|
| `--paper` | **Warm Paper** | `#F6F4EF` | Default page background. Warmer and calmer than pure white. |
| `--surface` | **Clean Surface White** | `#FFFFFF` | Alternating section bands, cards, inputs, notes and modals. |
| `--sunken` | **Soft Linen** | `#EDEAE2` | Hover fills on list rows, table row stripes, disabled fills. |
| `--border` | **Hairline Stone** | `#DCD7CB` | Dividers, card borders, table rules. |
| `--border-strong` | **Firm Stone** | `#C9C2B3` | Ghost-button and chip outlines, scrollbars. Decorative only, so pair it with text. |

### Text
| Token | Name | Hex | Role |
|---|---|---|---|
| `--ink` | **Midnight Ink** | `#151B28` | Headlines, body text, primary list rules (15.7:1 on Paper). |
| `--muted` | **Slate Muted** | `#5A6272` | Supporting copy, metadata, captions, form input borders (5.6:1 on Paper). |

### Brand
| Token | Name | Hex | Role |
|---|---|---|---|
| `--primary` | **Harbor Navy** | `#1C3B5A` | The single action colour: primary buttons, links, active states, focus rings, the admissions CTA panel. |
| `--primary-hover` | **Deep Harbor** | `#142C45` | Hover and pressed state of primary. |
| `--accent` | **Heritage Brass** | `#A87A2E` | Decorative only: kicker rules, fine lines, emblem details. Never used for body text (3.5:1). |
| `--accent-text` | **Burnished Brass** | `#86601F` | Kicker labels and small brass text on light backgrounds (5.2:1). |
| `--accent-on-dark` | **Lamplight Brass** | `#D2AE6A` | Kicker labels, active tab metadata and footer headings on dark bands (8.7:1). |

### Dark band (portal preview, footer, dark sections)
| Token | Name | Hex | Role |
|---|---|---|---|
| `--ink-deep` | **Night Ink** | `#0F1522` | Dark section background. Never pure black. |
| `--on-dark` | **Bone White** | `#F3F1EC` | Primary text on dark (16.2:1). |
| `--on-dark-muted` | **Mist Gray** | `#A9B0BD` | Secondary text on dark (8.4:1). |
| `--line-dark` | **Night Rule** | `#27304A` | Dividers and borders on dark. |

### Derived shades (use only in these specific places, never as new brand colours)
- **Portal mock surfaces on dark:** Night Panel `#151D2E` (window), `#1A2336` (tiles), `#1F2940` (active nav item and skeleton base), `#2A3552` (skeleton highlight), `#2B3650` (window dots).
- **Muted text variants:** `#8891A0` for placeholder text and the copyright line on Night Ink (5.7:1), and `#C6D0DC` for lede text on the Harbor Navy CTA panel (7.4:1).
- **Image-placeholder duotones (stand-ins for photography only):** cool slate `#9FB0C2` to `#5E7690` to `#2F4660`; warm sand `#D8CBB0` to `#B59A6B` to `#8A7250`; deep ink `#3A4A63` to `#1E2A40` to `#141C2D`; neutral stone `#C9C2B3` to `#A9AEB5` to `#7E8896`.
- `--focus` is an alias of Harbor Navy `#1C3B5A`.

### Status (system feedback only)
| Token | Name | Hex | Role |
|---|---|---|---|
| `--success` | **Evergreen** | `#2E6A4E` | Paid, enrolled, verified, "open" (5.8:1). |
| `--warning` | **Amber Ochre** | `#8F6410` | Pending, due soon, incomplete requirements (4.8:1). |
| `--error` | **Brick Red** | `#A2382C` | Errors, overdue, rejected, destructive actions (6.1:1). |

For status backgrounds, use the status colour at 10–12% opacity over Surface with the full-strength colour for text and icon. Never communicate status with colour alone: always add a label or icon.

**Color rules**
- Harbor Navy is the only colour for interactive elements. Brass is never a button fill.
- Put only one saturated element in view at a time. The navy CTA panel is the loudest block on any page.
- No gradients on text, buttons or backgrounds. The only allowed gradient is on image placeholders, as a stand-in for photography.
- No neon, no glow and no purple. Never use `#000000`.

---

## 3. Typography Rules

| Role | Family | Why |
|---|---|---|
| **Display / Headings** | **Fraunces** (variable, optical size 9–144, weights 300–600) | A modern serif with editorial authority, far from the generic Georgia and Times look. It is used at light weights (300–420) for an expensive, confident tone. |
| **Body / UI** | **Geist** (400, 500, 600) | A precise, neutral grotesque that reads cleanly at small sizes. It is used for body copy, navigation, buttons, forms and tables. |
| **Labels / Data** | **Geist Mono** (400, 500) | Used for kickers, dates, codes (BSIT), receipt numbers, times and tabular figures. |

Load all three with `https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..600&family=Geist:wght@400;500;600&family=Geist+Mono:wght@400;500&display=swap`.

**Portal rule:** In dashboards, tables and forms inside the portals, use Geist and Geist Mono only. Fraunces may appear only in the page title of a portal screen, or not at all.

### Type scale
| Style | Font | Size (fluid) | Weight | Line height | Tracking | Use |
|---|---|---|---|---|---|---|
| Display XL | Fraunces, opsz 144 | `clamp(2.75rem, 7.2vw, 6.25rem)` | 350 (emphasis: 300 italic, Harbor Navy) | 0.98 | −0.025em | Home hero only |
| H1 | Fraunces, opsz 96 | `clamp(2.5rem, 5vw, 4.5rem)` | 360 | 1.02 | −0.02em | Interior page titles |
| H2 | Fraunces, opsz 96 | `clamp(2rem, 4.2vw, 3.5rem)` | 380 | 1.05 | −0.02em | Section titles |
| H3 | Fraunces | `clamp(1.375rem, 2vw, 1.75rem)` | 400–420 | 1.2 | −0.01em | Card, story and program titles |
| Statement | Fraunces | `clamp(1.625rem, 3.2vw, 2.625rem)` | 340 | 1.22 | −0.015em | Intro statements, pull quotes |
| Lede | Geist | `clamp(1.0625rem, 1.4vw, 1.25rem)` | 400 | 1.6 | 0 | Section intros (max 56ch) |
| Body | Geist | 1.0625rem (17px) desktop, 1rem mobile | 400 | 1.65 | 0 | Paragraphs (max 65ch) |
| UI / Button | Geist | 0.875–0.9375rem | 500 | 1 | 0 | Navigation, buttons, tabs |
| Small | Geist | 0.875rem | 400 | 1.5 | 0 | Helper text, table cells |
| Kicker | Geist Mono | 0.75rem | 500 | 1 | 0.14em, uppercase | Section eyebrow with a 28px brass rule before it |
| Meta | Geist Mono | 0.8125rem | 400 | 1.4 | 0.02em | Dates, categories, codes, times |
| Stat | Fraunces | `clamp(2.5rem, 4.5vw, 4rem)` | 340 | 1 | −0.02em | Verified figures only |

**Rules**
- Build hierarchy through size and weight contrast, not colour. Headlines are Midnight Ink, and only the hero's italic emphasis word turns Harbor Navy.
- Use sentence case for headings. Uppercase is reserved for monospaced kickers and badges.
- Never bold a serif above 600. Never set body copy in the serif.
- Use `font-variant-numeric: tabular-nums` for all tables, grades and amounts.

---

## 4. Component Stylings

### Buttons (minimum height 48px public, 40px dense portal tables)
- **Primary:** Harbor Navy fill, white Geist 500 text, subtly rounded corners (8px). On hover the fill deepens to Deep Harbor and a soft navy-tinted shadow appears beneath (`0 6px 18px −8px rgba(20,44,69,.55)`). On press it moves down 1px. A trailing arrow glides 4px right on hover.
- **Secondary (ghost):** Transparent with a 1px Firm Stone outline and Midnight Ink text. On hover the outline darkens to Ink and the fill becomes Surface.
- **Inverse:** A Bone White fill with Night Ink text, used on dark bands and the navy CTA panel.
- **Text link:** Harbor Navy Geist 500 with an underline that draws left-to-right on hover (260ms) and a trailing arrow nudge.
- **Icon button:** A 48px circle with a Firm Stone outline. On hover it fills with Ink and the icon turns Bone White. Always give it an `aria-label`.
- **Disabled:** Soft Linen fill, Slate Muted text, no shadow, `cursor: not-allowed`.
- **Loading:** Keep the button width fixed and replace the label with a 3-dot mono ellipsis. Add `aria-busy="true"`.
- **Limit:** One primary button per view region. The hero pairs a primary button with a text link, never two filled buttons.

### Cards & containers
- Use cards only when elevation carries meaning (the hero status note, the portal preview, modals, dashboard tiles). Everywhere else, use hairline-ruled lists and whitespace.
- **Corners:** Subtly rounded, 8px for images and small cards and 14px for large panels (CTA panel, portal window). Pill shapes (999px) are only for chips and badges.
- **Surface:** Clean Surface White with a 1px Hairline Stone border. There is no shadow at rest. Floating panels get a long soft shadow (`0 40px 80px −40px rgba(0,0,0,.6)` on dark, `0 24px 48px −28px rgba(21,27,40,.25)` on light).
- **Image frames:** Full-bleed inside an 8px-rounded clip. On hover the image scales to 1.04 over 900ms.

### Navigation
- **Desktop (1100px and up):** A fixed 76px bar. The brand is on the left (circular monogram "B" in Fraunces italic, with "Bruce Class" and a "College & Academy" mono subline). Seven links sit in the centre (Geist 500, 14px). A ghost "Apply" button and a primary "Student Portal" button sit on the right.
- **Over the hero** the bar is transparent. After 24px of scroll it becomes translucent Warm Paper (86%) with a 14px background blur and a Hairline Stone bottom border.
- **Link hover and current page:** A 1px underline scales in from the left. The current page also gets `aria-current="page"`.
- **Mobile and tablet (below 1100px):** A 44px outlined menu button whose two lines rotate into an X. It opens a full-screen Warm Paper drawer with numbered links (01–07) in 30px Fraunces, hairline separators and a full-width "Sign in to Student Portal" button at the bottom. Escape closes the drawer, focus moves into it on open and returns to the button on close, and page scroll is locked while it is open.

### Kicker + section header
- A brass mono kicker (with its 28px rule) sits above an H2. An optional text link ("All news →") is right-aligned on the same baseline and stacks below the heading on mobile.

### Editorial lists (announcements, programs, events)
- A 1px Midnight Ink top rule starts the list, and 1px Hairline Stone rules separate rows.
- **Announcement row:** A mono date block (large Fraunces day, then an uppercase mono month), then title and summary. On hover the row shifts right by 8px and an arrow fades in.
- **Program row:** A mono code (BSIT), a Fraunces title with a Geist degree and duration line, and a 40px circular arrow. On hover the row gets a Soft Linen fill and the arrow button fills navy and rotates −45°. On desktop a sticky preview image beside the list changes to match the hovered program.
- **Event row:** A large Fraunces date, then title and description, then a mono location and time, then an arrow.

### Forms & inputs
- The label sits above the field (Geist 500, 14px, Ink). Helper text goes below in Slate Muted 14px, and the error message goes below in Brick Red with an icon.
- **Field:** Surface White fill, a 1px Slate Muted border (3:1 or better against the background), 8px corners, minimum height 48px and 16px horizontal padding.
- **Focus:** The border becomes Harbor Navy and a 3px ring at 20% navy appears. Never remove the focus indicator.
- **Error:** A Brick Red border and message with `aria-invalid="true"`. The message is linked with `aria-describedby`.
- Multi-step forms (admissions) show a numbered step rail. Each step holds one topic (8 fields at most), and a sticky footer holds Back and Continue.
- There are no floating labels and no placeholder-as-label.

### Tables (portals)
- The header row uses mono uppercase 12px Slate Muted text on Surface, and each row has a Hairline Stone bottom rule. Row height is 52px (44px in compact mode). Hovering a row fills it with Soft Linen.
- Numbers and amounts are right-aligned with tabular figures. Each row ends with an actions column holding a ghost icon button that opens a dropdown.
- On mobile, rows turn into stacked label/value cards.

### Badges & chips
- **Badge:** A 24px-tall pill with a mono 11px uppercase label and a 1px outline. The "Featured" variant uses Burnished Brass text on `#F2E8D5`. Status badges use the status colour at 10% opacity with the full-strength colour for text.
- **Filter chip:** A 40px pill with a Firm Stone outline. When selected it becomes an Ink fill with Bone White text, carries `aria-pressed="true"`, and uses a 160ms transition.

### Alerts
- An inline banner with a 3px left rule in the status colour, a 10% status tint, an icon, a title and body, and an optional action link. It is dismissible where appropriate. Use `role="status"` for information and `role="alert"` for errors.

### Tabs
- **Public:** An underline style, where the active tab gets a 2px Harbor Navy rule.
- **Role switcher (portal preview):** A vertical ruled list where the active label turns Bone White and its metadata turns Lamplight Brass.
- Tabs use roving tabindex with arrow-key navigation, `role="tablist"`/`tab`/`tabpanel`, and a 200ms cross-fade between panels.

### Dropdowns & menus
- A Surface panel with a 1px Hairline Stone border, 8px corners and a soft light shadow. Items are 40px tall and highlight with Soft Linen on hover. The panel fades in and slides 4px down over 160ms. Arrow keys, Enter and Escape are supported.

### Modals
- A centred Surface panel, max 560px wide with 14px corners, over a Night Ink overlay at 55%. It enters with a fade and scales from 0.98 to 1 over 260ms. Focus is trapped inside, Escape closes it, and focus returns to the element that opened it.

### Pagination
- Mono page numbers in 40px square targets. The current page gets an Ink fill with Bone White text. Previous and Next are text links with arrows. For news, "Load more" (a ghost button) is preferred over numbered pages.

### Loading & empty states
- **Loading:** Skeleton bars that match the final layout, with a slow shimmer (2.2s) and no circular spinners.
- **Empty:** A short Fraunces sentence, one line of Slate Muted guidance and a single action (for example "No grades posted yet. Grades appear here once your teachers submit them.").

### Footer
- A Night Ink band. Its first column holds the tagline in large Fraunces ("Where ambition becomes achievement.") and an underline-only email signup. Three link columns (School, Admissions, Portals) have Lamplight Brass mono headings. A bottom bar holds the copyright and the address, phone and email in mono.

### Image placeholders
- When real photography is missing, use a rounded frame with a muted duotone gradient (cool slate, warm sand or deep ink) and a fine grain overlay. A mono uppercase caption states the intended shot (for example "Photo — Students in the main library, natural light"). Placeholders are always labelled and are never random stock photos.

---

## 5. Layout Principles

### Grid & containers
- **Max content width:** 1320px, centred.
- **Page gutter:** `clamp(20px, 4vw, 48px)`.
- **Grid:** 12 columns with 24px gutters (40px at 1440px and above). Common splits are 7/5, 5/7, 3/9 (kicker column plus content) and 1.15fr/1fr (hero).
- Full-bleed bands (dark sections, the highlights carousel) extend to the viewport edge while their content stays aligned to the container.

### Breakpoints
| Name | Range | Behavior |
|---|---|---|
| Mobile | < 768px | Single column, drawer navigation, stacked CTAs at full width, carousels at 82% card width |
| Tablet | 768–1099px | Two-column content, drawer navigation, sticky program preview hidden |
| Laptop | 1100–1439px | Full navigation, all split layouts |
| Desktop | 1440–1919px | Full layout, larger gutters |
| Large | ≥ 1920px | Content capped at 1320px, with bands and imagery extending to the edges |

### Spacing
- **Base unit:** 4px. The scale is 4, 8, 12, 16, 20, 24, 32, 40, 48, 64, 80, 96, 120 and 144.
- **Section padding (vertical):** `clamp(72px, 10vw, 144px)`. Compact bands (stats) use `clamp(64px, 8vw, 112px)`.
- **Section header to content:** `clamp(40px, 5vw, 64px)`.
- **Card and panel padding:** 18–24px for small cards, and `clamp(40px, 6vw, 88px)` for feature panels.
- **Component gaps:** 8px within a control group, 12–16px between related items and 24–32px between groups.
- **Text blocks:** Kicker to heading 18px, heading to lede 20–28px, lede to actions 40px.

### Rhythm
- Alternate backgrounds in this order: Paper, then Surface (with hairline top and bottom borders), then Night Ink, then Paper. Never place two identical layouts back to back.
- Mix compositions across a page: a split hero, an offset statement, a lead story beside a list, a sticky image beside a list, a zig-zag, a dark product preview, a horizontal carousel, a four-up stat rule, a 7/5 magazine grid, a ruled agenda, and a navy panel.
- Avoid three equal cards in a row. Use a lead item plus a list, a zig-zag or a horizontal scroll.

### Responsive intent (mobile is designed, not compressed)
- The hero stacks as kicker, headline, lede, full-width primary button, text link and then an image mosaic (wide photo on top, with the photo and status note side by side below).
- Program discovery keeps the filter chips (which wrap) and hides the sticky preview image.
- The portal preview turns its sidebar into a horizontally scrolling tab strip and its tiles into a single column.
- Stats become a 2×2 grid, events drop their location column, and the footer becomes two columns with the tagline spanning both.
- All touch targets are at least 44×44px, and nothing may overflow horizontally.

---

## 6. Design System Notes for Stitch Generation

**Copy this block into every baton prompt (`.stitch/next-prompt.md`).**

```
**DESIGN SYSTEM (REQUIRED):**
- Project: Bruce Class — premium modern college & academy website with student/staff portals
- Platform: Web, responsive, designed mobile-first; generate DESKTOP first unless told otherwise
- Theme: Light, premium editorial + modern education technology; calm, assured, restrained, Swiss-structured, asymmetric but controlled
- Background: Warm Paper (#F6F4EF); alternate bands of Clean Surface White (#FFFFFF) with hairline borders; one Night Ink (#0F1522) dark band per page at most, plus the Night Ink footer
- Primary action color: Harbor Navy (#1C3B5A), hover Deep Harbor (#142C45); the ONLY color for buttons, links, active and focus states
- Accent: Heritage Brass (#A87A2E) for hairline rules only; Burnished Brass (#86601F) for small uppercase mono kicker labels; Lamplight Brass (#D2AE6A) on dark
- Text: Midnight Ink (#151B28) headings/body; Slate Muted (#5A6272) supporting text; Bone White (#F3F1EC) and Mist Gray (#A9B0BD) on dark
- Borders: Hairline Stone (#DCD7CB) 1px dividers; editorial lists start with a 1px Midnight Ink top rule
- Status: Evergreen (#2E6A4E) success, Amber Ochre (#8F6410) warning, Brick Red (#A2382C) error — always with a text label
- Display font: Fraunces (modern variable serif), light weights 300–420, tight tracking, large editorial scale; hero may italicize one emphasis word in Harbor Navy
- UI/body font: Geist; labels, dates, codes and numbers in Geist Mono; portals use Geist/Geist Mono only
- Section eyebrow: small uppercase Geist Mono kicker in brass, preceded by a short 28px hairline rule
- Buttons: subtly rounded corners (8px), 48px tall, Harbor Navy fill with white text; ghost variant with a thin stone outline; text links with a draw-in underline and a small arrow
- Cards: used sparingly; white surface, 1px hairline border, subtly rounded (8px, 14px for large panels), no shadow at rest; prefer hairline-ruled lists and whitespace over card grids
- Imagery: authentic school photography (students, classrooms, library, labs, faculty, campus) in subtly rounded frames with slow hover zoom; if unavailable, labeled duotone placeholders stating the intended shot
- Layout: 1320px max width, 12-column grid, generous section spacing (72–144px), asymmetric splits (7/5, 3/9), never a centered hero, never three equal cards in a row
- Motion: subtle and fast — content rises 24px and fades in on scroll, underline draw-ins, 1.04 image zoom, 200ms tab cross-fades; no bounce, no parallax, no glow; respect reduced motion
- Accessibility: WCAG 2.2 AA contrast, visible 2px Harbor Navy focus rings, 44px touch targets, semantic landmarks, labels above inputs
- Avoid: generic Bootstrap/AdminLTE look, gradients on text or buttons, glassmorphism panels, neon, emojis, stock-photo clichés, invented statistics (use [metric] placeholders), AI copy clichés ("elevate", "seamless", "unleash")
```

### Language to use with Stitch
- **Atmosphere:** "Premium editorial institution with calm, generous whitespace and Swiss grid structure"
- **Corners:** "Subtly rounded corners" for buttons and images, and "pill-shaped" only for chips and badges
- **Depth:** "Flat at rest with hairline borders; long, soft shadows only on floating panels"
- **Lists:** "Hairline-ruled editorial list, starting with a thin dark rule"
- **Hero:** "Left-aligned oversized light serif headline, asymmetric image mosaic on the right"

### Incremental iteration
1. Change one component at a time (for example "Refine the program list rows").
2. Be specific (for example "Increase row padding from 22px to 28px").
3. Always use the colour names and hex codes from Section 2.

---

## 7. Motion & Interaction

| Token | Value | Use |
|---|---|---|
| `--ease` | `cubic-bezier(.22,.61,.36,1)` | Colour, background and border transitions |
| `--ease-out` | `cubic-bezier(.16,1,.3,1)` | Movement: reveals, arrows, zooms |
| `--t-fast` | 160ms | Chips, icon buttons, dropdowns |
| `--t-base` | 260ms | Buttons, underlines, nav elevation, modals |
| `--t-slow` | 600–900ms | Scroll reveals (800ms), image zoom (900ms) |

- **Scroll reveal:** Elements rise 24px and fade in once, when 8% of them is in view. Siblings stagger by 80ms each, for no more than 5 items.
- **Navigation:** The bar elevates on scroll, underlines draw in, and the drawer fades and slides 8px.
- **Cards and images:** Images zoom to 1.04 and arrows shift 4px. Nothing lifts more than 2px.
- **Tabs and panels:** 200ms cross-fade.
- **Page transitions (future):** A 200ms fade on route change using the View Transitions API where supported.
- **Only animate** `transform` and `opacity`, never layout properties.
- **Reduced motion:** Under `prefers-reduced-motion: reduce` all reveals show immediately, transitions become instant, smooth scrolling is off and the status pulse stops.
- **Banned:** Bounce or elastic easing, parallax backgrounds, auto-playing carousels, scroll-jacking, custom cursors, "scroll to explore" prompts and bouncing chevrons.

---

## 8. Accessibility Requirements

- **Contrast:** All text meets WCAG 2.2 AA (see the ratios in Section 2). Heritage Brass `#A87A2E` is decorative only.
- **Keyboard:** Every interactive element can be reached and operated. The page starts with a "Skip to content" link. Tabs use roving tabindex with arrow keys, and the drawer and modals trap focus, close on Escape and return focus.
- **Focus:** A visible 2px Harbor Navy outline with a 3px offset (Lamplight Brass on dark). Never write `outline: none` without a replacement.
- **Semantics:** Use `header`, `nav[aria-label]`, `main#main`, `section[aria-labelledby]`, `article`, `footer` and one `h1` per page, and never skip heading levels.
- **Buttons vs links:** A link navigates and a button acts. Icon-only controls always have an `aria-label`.
- **Forms:** Visible labels, `autocomplete` attributes, errors tied to fields with `aria-describedby`, and an error summary on submit for long forms.
- **Media:** Meaningful images get descriptive `alt` text and decorative ones get `alt=""`. Videos have captions and never autoplay with sound.
- **Responsive text:** Use fluid `clamp()` sizes with a minimum of 16px for body text. The layout must survive 200% zoom with no horizontal scroll.
- **Status:** Never rely on colour alone. Always pair it with a text label or icon.
- **Motion:** Honour `prefers-reduced-motion`, as described in Section 7.

---

## 9. Voice & Content

- **Tone:** Confident, warm and precise. Use short declarative sentences. Write "you" to the student and "we" for the school.
- **Headlines:** Benefit-led and concrete ("Find the program that fits your future", "One portal for your whole school life").
- **Never invent facts.** Enrollment counts, passing rates, founding year, addresses, fees and deadlines must come from the school. Until then, use visible placeholders: `[metric]`, `[Date]`, `[Time]`, `[Campus address]`.
- **Banned words:** elevate, seamless, unleash, next-gen, cutting-edge, world-class (unless verified), revolutionize.

---

## 10. Anti-Patterns (never do)

- Bootstrap or AdminLTE defaults: blue `btn-primary`, boxy info-box tiles, default navbars.
- Rows of three equal cards, or card grids for everything.
- A centred hero with a stock photo behind it and white text on top.
- Gradients on text, buttons or section backgrounds. Glassmorphism panels. Neon or glow shadows.
- More than one accent colour. Pure black. Children's-site colours.
- Emojis. Generic names ("John Doe"). Fake round statistics.
- Animations on every element, long (>1s) transitions, bouncing, parallax, autoplaying sliders.
- Placeholder text used as a label. Removing focus outlines.
- Hard-coded dates or figures presented as real without verification.

---

## 11. Implementation Tokens (CSS)

```css
:root{
  --paper:#F6F4EF; --surface:#FFFFFF; --sunken:#EDEAE2;
  --border:#DCD7CB; --border-strong:#C9C2B3;
  --ink:#151B28; --ink-deep:#0F1522; --muted:#5A6272;
  --primary:#1C3B5A; --primary-hover:#142C45;
  --accent:#A87A2E; --accent-text:#86601F; --accent-on-dark:#D2AE6A;
  --on-dark:#F3F1EC; --on-dark-muted:#A9B0BD; --line-dark:#27304A;
  --success:#2E6A4E; --warning:#8F6410; --error:#A2382C;
  --f-display:"Fraunces",ui-serif,serif;
  --f-sans:"Geist",ui-sans-serif,system-ui,sans-serif;
  --f-mono:"Geist Mono",ui-monospace,monospace;
  --max:1320px; --gutter:clamp(20px,4vw,48px); --section:clamp(72px,10vw,144px);
  --r-sm:4px; --r-md:8px; --r-lg:14px;
  --ease:cubic-bezier(.22,.61,.36,1); --ease-out:cubic-bezier(.16,1,.3,1);
  --t-fast:160ms; --t-base:260ms; --t-slow:600ms;
}
```

The PHP application will eventually load these tokens from a new stylesheet (planned: `assets/bruce/tokens.css`) so that the existing `public.css` and `custom.css` are not overwritten.

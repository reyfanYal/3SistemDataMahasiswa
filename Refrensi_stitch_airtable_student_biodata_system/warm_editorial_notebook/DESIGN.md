---
name: Warm Editorial Notebook
colors:
  surface: '#f8f9ff'
  surface-dim: '#d6dae4'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4fe'
  surface-container: '#eaeef8'
  surface-container-high: '#e4e8f3'
  surface-container-highest: '#dee2ed'
  on-surface: '#171c23'
  on-surface-variant: '#45474b'
  inverse-surface: '#2c3139'
  inverse-on-surface: '#edf1fb'
  outline: '#76777c'
  outline-variant: '#c6c6cc'
  surface-tint: '#5a5e69'
  primary: '#000208'
  on-primary: '#ffffff'
  primary-container: '#181d26'
  on-primary-container: '#808590'
  inverse-primary: '#c2c6d3'
  secondary: '#af3003'
  on-secondary: '#ffffff'
  secondary-container: '#ff693c'
  on-secondary-container: '#601500'
  tertiary: '#00020e'
  on-tertiary: '#ffffff'
  tertiary-container: '#00194c'
  on-tertiary-container: '#5c81e1'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dee2ef'
  primary-fixed-dim: '#c2c6d3'
  on-primary-fixed: '#171c25'
  on-primary-fixed-variant: '#424751'
  secondary-fixed: '#ffdbd1'
  secondary-fixed-dim: '#ffb5a0'
  on-secondary-fixed: '#3b0900'
  on-secondary-fixed-variant: '#862200'
  tertiary-fixed: '#dae1ff'
  tertiary-fixed-dim: '#b3c5ff'
  on-tertiary-fixed: '#001849'
  on-tertiary-fixed-variant: '#0e409e'
  background: '#f8f9ff'
  on-background: '#171c23'
  surface-variant: '#dee2ed'
typography:
  display-hero:
    fontFamily: Space Grotesk
    fontSize: 48px
    fontWeight: '900'
    lineHeight: 54px
    letterSpacing: -0.03em
  display-hero-mobile:
    fontFamily: Space Grotesk
    fontSize: 34px
    fontWeight: '900'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Space Grotesk
    fontSize: 26px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 30px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Space Grotesk
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: '0'
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 22px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-field:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  code-nim:
    fontFamily: Space Mono
    fontSize: 13px
    fontWeight: '700'
    lineHeight: 18px
    letterSpacing: 0.05em
  badge-label:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.04em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

This design system establishes an editorial academic studio environment tailored for student records, biodata dossiers, and academic registries. It moves away from cold, mechanical spreadsheets, fusing the structured clarity of modern relational data views (Airtable) with the tactility, warmth, and deliberate craft of a physical creative studio notebook. 

The aesthetic is built upon:
- **Warm Tactility:** A rich parchment foundation that reduces screen fatigue and creates an immediate sense of archival permanence.
- **Editorial Precision:** Typographic tension pairing an expressive, geometric headline display with a clean, functional workhorse sans for data density and tabular readability.
- **Structured Hierarchy:** Sharp hairline borders, high-contrast focal points in solid Onyx, and distinct saturated accent blocks used deliberately to classify disciplines, cohorts, and academic statuses.

The UI should evoke intellectual focus, craft, and administrative calm—elevating routine data entry into an intentional, portfolio-grade archival experience.

## Colors

The palette balances warm neutral backgrounds with intense ink tones and selective academic accent markers:

- **Canvas & Surfaces:**
  - Base Page Canvas: Parchment Cream (`#faf5e8`)
  - Elevated Cards & Data Sheets: Pure White (`#ffffff`)
  - Structural Hairlines: Raw Canvas Border (`#e8e2d2`) or Ink Tint (`rgba(24, 29, 38, 0.08)`)

- **Typographic Tones:**
  - Midnight Indigo (`#040e20`): High-authority display headings, hero sections, and modal headers.
  - Onyx (`#181d26`): High-emphasis UI elements, primary buttons, and critical metadata keys.
  - Charcoal (`#333840`): Primary body copy, student profiles, and table data values.
  - Graphite (`#525965`): Captions, secondary labels, metadata tags, and disabled states.

- **Categorical Accent Blocks:**
  - Terracotta (`#aa2d00`): Faculty marks, warning calls, urgent flags.
  - Sapphire (`#254fad`): Academic tracks, major specializations, interactive links.
  - Forest Ink (`#0a2e0e`): Research clusters, environmental disciplines, high-achievement markers.
  - Petal Pink (`#fa91e0`): Student organizations, creative arts, dynamic highlight chips.
  - Warm Marigold (`#d97706`): Advisory notices, pending approvals, scholarship highlights.

- **Academic Status Identifiers:**
  - **Aktif (Active):** Soft Spring Green background (`#ecfdf5`), border (`#a7f3d0`), text (`#065f46`).
  - **Cuti (Leave of Absence):** Warm Amber background (`#fffbeb`), border (`#fde68a`), text (`#92400e`).
  - **Lulus (Graduated):** Slate Neutral background (`#f1f5f9`), border (`#cbd5e1`), text (`#334155`).
  - **Drop Out / Non-Aktif:** Rose background (`#fff1f2`), border (`#fecdd3`), text (`#9f1239`).

## Typography

The typographic hierarchy anchors the editorial studio feel:

1. **Display & Headings (Space Grotesk):** Features geometric, slightly off-kilter technical letterforms that give student biodata dossiers identity and character. Display Hero uses weight 900 with tight tracking (`-0.03em`) for section cover titles and dashboard mastheads. Section headers use weights 600 and 700.
2. **Body & Form Data (Inter):** Clean, objective, high-legibility sans-serif optimized for tabular displays, personal records, narrative bios, and compact form controls.
3. **Identifiers & Technical Data (Space Mono):** Used strictly for Student ID numbers (NIM / Nomor Induk Mahasiswa), academic credit codes (SKS), timestamps, and curriculum IDs. The monospace treatment grounds the record with administrative authenticity.
4. **All-Caps Eyebrows:** Small Section Eyebrows (`badge-label` scale) should be transformed to uppercase with `0.05em` letter-spacing to establish clear hierarchy above large Space Grotesk section titles.

## Layout & Spacing

The layout operates on an intentional notebook grid system:

- **Desktop (12 Columns):** Fluid layout constrained to an editorial max-width of `1440px`. Gutters are fixed at `1.5rem` (24px) with outer page margins of `2rem` (32px).
- **Tablet (8 Columns):** Breakpoint at `1024px` down to `768px`. Outer margins adjust to `1.5rem` (24px).
- **Mobile (4 Columns):** Breakpoint below `768px`. Margins compress to `1rem` (16px), and gutters drop to `1rem` (16px).

### Spacing Rhythm
- **Micro Spacing (`space-xs` to `space-sm`):** Internal component padding, gap between NIM labels and values, icon-to-text gaps.
- **Component Spacing (`space-md`):** Form field gaps, chip stacks, internal card row separators.
- **Section Spacing (`space-lg` to `space-xl`):** Card-to-card gaps, internal card padding (desktop defaults to `1.5rem` or `2rem`), division between student dossier sections (e.g., Akademik, Biodata Pribadi, Prestasi).

## Elevation & Depth

This system avoids heavy, artificial drop shadows in favor of a clean, tactile notebook layout driven by structural lines and ambient paper tiers:

- **Surface Level 0 (Parchment Foundation):** `#faf5e8`. The broad editorial canvas.
- **Surface Level 1 (Studio Record Cards):** `#ffffff`. All interactive data modules, biodata summaries, and form groups sit as pure white sheets laid across the parchment canvas.
- **Border Architecture:** Cards and field groups rely on sharp, understated hairlines: `1px solid #e8e2d2` or `1px solid rgba(24, 29, 38, 0.08)`. This creates a crisp, bookbound look.
- **Subtle Elevation / Floating Elements:** For contextual dropdown menus, floating action bars, and modal sheets, use an ultra-diffused paper lift:
  - `box-shadow: 0 4px 20px -2px rgba(24, 29, 38, 0.06), 0 1px 4px 0 rgba(24, 29, 38, 0.04);`
- **Interactive State Tiers:** On hover, clickable student cards do not lift high off the page; instead, their borders shift to `rgba(24, 29, 38, 0.25)` and a micro-offset shadow appears (`0 2px 8px -1px rgba(24, 29, 38, 0.08)`), replicating a card being touched or chosen in a physical index drawer.

## Shapes

The design system incorporates geometric contrast: rounded, comfortable card containers paired with disciplined control elements.

- **Primary Container & Card Radii:** Standard cards and sheets employ `16px` to `24px` radius (`rounded-lg` to `rounded-xl`). This creates a contemporary, soft binder aesthetic that offsets the high-contrast typography.
- **Interactive Controls (CTAs & Inputs):** Buttons and form input fields strictly use `12px` border-radius (`0.75rem`), balancing precision with touchability.
- **Status Tags & Badges:** Rounded pill shapes (`9999px`) or compact `6px` soft lozenges for discrete monospace data chips.
- **Image Avatars & Document Previews:** Student photos use `14px` border radius with an internal `1px` inner border tint (`rgba(24, 29, 38, 0.1)`) to anchor images against white or cream backgrounds.

## Components

### 1. Buttons
- **Primary CTA:** Background Onyx (`#181d26`), text `#ffffff`, border-radius `12px`, font `Space Grotesk` weight 600. Padding: `10px 20px` (body-md). Hover state subtly shifts background to `#2a3240`. Active state applies `transform: scale(0.98)`.
- **Secondary / Ghost Button:** Pure White background (`#ffffff`), `1px solid #181d26`, text Onyx (`#181d26`), border-radius `12px`, weight 600. Hover state: background `#faf5e8`.
- **Tertiary / Utility Button:** Transparent background, text Graphite (`#525965`), hover text Midnight Indigo (`#040e20`), no border. Used for inline table actions and pagination controls.

### 2. Form Inputs & Selectors
- **Text Inputs & Dropdowns:** Pure White background, `1px solid #e8e2d2`, `12px` border radius, padding `12px 16px`. Text Charcoal (`#333840`), placeholder Graphite (`#525965` at 50% opacity).
- **Focus State:** `1.5px solid #181d26` outline with zero fuzzy glow to maintain the crisp, printed notebook character.
- **Field Labels:** `Inter` weight 600, `12px`, Graphite (`#525965`), positioned above the field with `6px` spacing.

### 3. Cards & Record Containers
- **Biodata Section Cards:** Pure White fill, `16px` or `24px` radius, `1px solid #e8e2d2`. Internal padding `24px` or `32px`. 
- **Accent Ribbon Cards:** Dossier cards can feature a top or left saturated highlight line (`4px` solid in Terracotta, Sapphire, or Forest Ink) to visually designate faculties or study programs.

### 4. Status Badges & Academic Chips
- **Structure:** `6px` to `9999px` radius, padding `4px 10px`, font `Inter` weight 600, size `11px`.
- **Aktif:** Green tint background (`#ecfdf5`), border `#a7f3d0`, text `#065f46`. Includes a 6px solid green indicator dot.
- **Cuti:** Amber tint background (`#fffbeb`), border `#fde68a`, text `#92400e`.
- **Lulus:** Slate tint background (`#f1f5f9`), border `#cbd5e1`, text `#334155`.
- **NIM Monospace Pill:** Background `#f4ede0`, border `1px solid #e2dac9`, text Onyx (`#181d26`), font `Space Mono`, size `12px`, weight 700.

### 5. Checkboxes & Selection Controls
- **Checkboxes:** `20px` square, `6px` radius, `1.5px solid #525965` on white. Checked state: Onyx background (`#181d26`) with crisp white SVG check icon.
- **Radio Buttons:** Circular `20px`, `1.5px solid #525965`. Selected state: Pure white fill with an inner Onyx circle (`8px` diameter).

### 6. Relational Table & Grid Views (Airtable-style)
- **Header Row:** Crisp background `#f5efe1`, borders bottom and right `1px solid #e8e2d2`. Column title text in `Space Grotesk` weight 600, size `12px`, uppercase with `0.04em` tracking.
- **Data Cells:** Height `44px`, background `#ffffff`, border bottom `1px solid #f2ede2`, text `Inter` weight 400, size `13px`.
- **Sticky Column (NIM & Student Name):** Pinned left edge with a subtle vertical separator rule (`1px solid #e2dac9`).
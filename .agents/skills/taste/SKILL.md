---
name: taste
description: Anti-slop frontend & UI/UX design taste skill. Guides AI agents to produce high-agency, polished, non-templated user interfaces with calibrated typography, intentional color palettes, layout diversity, micro-interactions, responsive mechanics, and strict pre-flight visual quality checks.
---

# Taste: Anti-Slop Frontend & UI/UX Design System

> Elevate UI/UX engineering beyond generic AI-generated templates. This skill provides explicit design judgment, layout variance, motion tuning, and strict anti-slop constraints.

---

## 0. BRIEF INFERENCE (Read the Room Before Anything Else)

Before touching code or tweaking styles, **infer the intentional design direction**:

### 0.A Signals to Read
1. **Page Context:** Landing page, management dashboard, data table, scan kiosk, public portal, or settings screen.
2. **Vibe & Aesthetic Family:** Minimalist, calm, Linear-style, glassmorphic, enterprise B2B, modern consumer, dark tech, editorial.
3. **Audience:** School staff & students, B2B stakeholders, general public, or mobile kiosk users.
4. **Existing Brand Assets:** Logo, color accents, typography, icons. Redesigns build upon existing foundation, not discard it.
5. **Accessibility & Contrast:** WCAG AA min contrast (4.5:1 for body, 3:1 for large text). Never light gray text on white.

### 0.B Anti-Default Discipline
- **Avoid:** Generic AI purple gradients, centered heroes over dark mesh, 3 identical white box cards, Inter+slate-900 cliché, and infinite loop jittery animations.
- **Reach for:** Purposeful typography hierarchy, tactile interactive states, cohesive color palettes, intentional asymmetry, and smooth micro-interactions.

---

## 1. THE THREE DIALS (Calibration Matrix)

* **`DESIGN_VARIANCE` (1 - 10):** 1 = Rigid Symmetry, 5 = Balanced Modern, 10 = Artsy Asymmetry.
* **`MOTION_INTENSITY` (1 - 10):** 1 = Instant Static, 5 = Smooth Transitions (200-300ms), 10 = Cinematic Physics.
* **`VISUAL_DENSITY` (1 - 10):** 1 = Airy Gallery, 5 = Modern Product UI, 10 = Dense Mission Control.

### Dial Presets
- **Dashboard / Admin Panel:** `VARIANCE: 5` | `MOTION: 4` | `DENSITY: 7`
- **Kiosk / Fullscreen Scanner:** `VARIANCE: 6` | `MOTION: 6` | `DENSITY: 4`
- **Landing / Auth Page:** `VARIANCE: 7` | `MOTION: 5` | `DENSITY: 3`

---

## 2. TYPOGRAPHY & VISUAL HIERARCHY

1. **Font Pairings:**
   - Modern UI: `Outfit` / `Plus Jakarta Sans` / `Geist` (Headings) + `Inter` (Body).
   - Monospace & Data: `JetBrains Mono` / `Fira Code` / `Geist Mono` for timecodes, metrics, and barcodes.
2. **Hierarchy Rules:**
   - Display / Page Titles: Bold, tight letter-spacing (`tracking-tight`), crisp contrast.
   - Section Titles: Clear weight distinction (font-weight 700+), generous breathing room.
   - Body & Helpers: Readable line-height (`1.5 - 1.6`), max width `65ch` for long text paragraphs.
3. **Descender Clearance:** Always maintain line-height `1.1+` and padding on display text containing `g, j, p, q, y` so letters are never clipped.

---

## 3. COLOR PALETTES & ACCENTS

1. **Singular Accent Rule:**
   - Pick 1 primary brand accent (e.g. Electric Sky Blue `#0284c7`, Emerald `#10b981`, Indigo `#6366f1`).
   - Saturation < 85% for eye comfort in daily usage.
2. **Neutral Foundations:**
   - Light Theme: Crisp Slate/Zinc (`#f8fafc` background, `#ffffff` cards, `#e2e8f0` borders, `#0f172a` text).
   - Dark Theme: Deep Slate (`#0f172a` background, `#1e293b` surface, `#334155` borders).
3. **Status Colors (Consistent Meaning):**
   - Success / On-time / Present: `#10b981` (Emerald)
   - Warning / Late: `#f59e0b` (Amber)
   - Danger / Absent / Error: `#ef4444` (Rose / Crimson)
   - Info / Checkout / System: `#0284c7` (Sky Blue)

---

## 4. MATERIALITY, CARDS & ELEVATION

1. **Card Architecture:**
   - Subtle borders (`1px solid rgba(226, 232, 240, 0.8)`) paired with soft multi-layer shadow (`0 10px 25px -5px rgba(15, 23, 42, 0.04)`).
   - Consistent corner radiuses: `12px` for inputs/buttons, `20px - 24px` for main cards and modals.
2. **Tactile Feedback & Interactive States:**
   - `:hover`: Subtle lift (`translateY(-2px)` or `translateY(-3px)`) and soft shadow expansion.
   - `:active`: Subtle press (`scale(0.98)` or `translateY(0px)`).
   - `:focus-visible`: High-contrast 3px outline or glow ring with brand accent.
3. **Empty States & Skeletons:**
   - Never show blank white rectangles. Provide styled empty state illustrations/icons with actionable instructions.

---

## 5. FORMS, TABLES & DATA DENSITY

1. **Form Ergonomics:**
   - Labels positioned ABOVE inputs with clear font-weight.
   - Inputs formatted with clean padding (`10px 14px`), subtle border, and instant visual focus state.
   - Inline feedback for validation with accessible contrast.
2. **Table Excellence:**
   - Sticky header with subtle background.
   - Generous row padding (`12px - 16px`), vertical alignment centered.
   - Meaningful badges (`HADIR`, `TERLAMBAT`, `ALPA`) formatted as rounded pills with tinted backgrounds.
   - Clear pagination pills with active state indicators.

---

## 6. PRE-FLIGHT UI/UX CHECKLIST

Before completing any frontend or UI task, verify:
- [ ] **Contrast Check:** All text and button labels pass WCAG AA contrast against their backgrounds.
- [ ] **Viewport Stability:** No unintended horizontal overflow (`overflow-x: hidden`).
- [ ] **Responsive Test:** Layout collapses cleanly on mobile (`< 768px`) and scales on desktop (`>= 1200px`).
- [ ] **No Redundant Clutter:** Navigation and action buttons are streamlined without duplicate intents.
- [ ] **Micro-Interactions:** Hover, active, focus, loading, and error states are fully styled.
- [ ] **Design Harmony:** Fonts, corner radii, and color accents are unified across all screens.

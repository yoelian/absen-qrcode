---
name: taste
description: >-
  Apply world-class design taste, aesthetic craftsmanship, and micro-interaction polish to frontend UI/UX development. Enforces modern design standards (Linear, Vercel, Apple, Stripe aesthetics) covering typography, spacing, color harmony, subtle motion, bento layouts, and tactile feedback. Use whenever designing, styling, or refining UI components, layouts, pages, or user experiences.
---

# 🎨 Taste: Aesthetic Craftsmanship & UI/UX Standards

This skill instills high-caliber **design taste**, visual refinement, and tactile polish into all software interfaces. It moves beyond "functional but bland" design into world-class digital craftsmanship inspired by the design languages of Linear, Apple, Stripe, and Vercel.

---

## 👁️ 1. Core Taste Principles

*   **Subtlety Over Loudness:** Never use raw, aggressive primary colors, heavy black borders, or gaudy drop shadows. Use soft ambient shadows, delicate semi-transparent borders, and harmonious pastel accents.
*   **Intentional Hierarchy:** Every screen must have a singular clear focal point. Secondary and tertiary elements should recede naturally without disappearing.
*   **The 4pt / 8pt Spatial Rhythm:** Spacing is never arbitrary. Padding and margins adhere strictly to multiples of 4 or 8 (8px, 12px, 16px, 24px, 32px) to achieve consistent visual breathing room.
*   **Tactile & Responsive:** Every interactive surface (buttons, cards, inputs) must communicate its state clearly through crisp hover, active-press, focus, and disabled transitions.

---

## 🔤 2. Typography & Numbers

1.  **Tabular Numbers for Data:**
    *   Always enable `tabular-nums` (or use monospace fonts) for counters, monetary values, timestamps, and statistics tables so changing numbers do not jitter horizontally.
2.  **Soft Contrast & Legibility:**
    *   Avoid pure `#000000` text on pure `#ffffff`. Use refined deep slate tones:
        *   Primary Text: `rgb(15, 23, 42)` / `#0f172a` (Slate 900)
        *   Secondary Text: `rgb(71, 85, 105)` / `#475569` (Slate 600)
        *   Muted / Metadata: `rgb(148, 163, 184)` / `#94a3b8` (Slate 400)
3.  **Optical Kerning & Line Heights:**
    *   Tighten letter-spacing slightly for large headings (`tracking-tight` or `-0.02em`).
    *   Maintain comfortable line heights (`leading-relaxed` / `1.5 - 1.6`) for long-form reading.

---

## 🎨 3. Color Harmony & Surface Depth

1.  **Layered Surface Elevation:**
    *   Background: Soft canvas (`#f8fafc` or `#f1f5f9`).
    *   Cards / Containers: Pure white (`#ffffff`) with subtle hairline border: `1px solid rgba(0, 0, 0, 0.06)` or `border-slate-200/60`.
    *   Ambient Shadows: Multi-layered soft ambient diffusion:
        ```css
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04), 0 8px 16px -4px rgba(0, 0, 0, 0.04);
        ```
2.  **Muted Pastel Status Badges:**
    *   ❌ **Avoid:** Saturated neon green `#00ff00` or aggressive red `#ff0000`.
    *   ✅ **Success:** Muted emerald bg `#ecfdf5`, text `#065f46`, border `#a7f3d0`.
    *   ✅ **Warning:** Warm amber bg `#fffbeb`, text `#92400e`, border `#fde68a`.
    *   ✅ **Error:** Gentle rose bg `#fff1f2`, text `#9f1239`, border `#fecdd3`.
    *   ✅ **Neutral:** Clean slate bg `#f1f5f9`, text `#334155`, border `#e2e8f0`.

---

## 🍱 4. Layout Architecture (Bento Grid)

1.  **Asymmetric Bento Cards:**
    *   Group related features into modular bento cards with consistent rounded radii (`rounded-2xl` / 16px - 20px).
    *   Anchor important metrics with large readable digits and micro-trend indicators (+3% this week).
2.  **Refined Data Tables:**
    *   Never use heavy grid borders between every cell.
    *   Use soft subtle dividers (`border-b border-slate-100`).
    *   Include subtle row hover highlighting (`hover:bg-slate-50/70 transition-colors`).
    *   Align numbers right, text left, and status badges center.

---

## ⚡ 5. Micro-Interactions & Fluid Motion

1.  **Tactile Button Feedback:**
    *   Include active-press micro-compression: `active:scale-[0.98] transition-all duration-150`.
    *   Smooth elevation rise on card hover: `hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 ease-out`.
2.  **Zero Layout Thrashing (Layout Shift):**
    *   Use skeleton placeholders during asynchronous loading that match the exact aspect ratio and dimensions of the incoming data.
    *   Smooth progress indicators with custom easing (`cubic-bezier(0.16, 1, 0.3, 1)`).

---

## 🌸 6. Thoughtful Empty & Error States

*   Never show a barren blank table or generic "No data found".
*   Display a composed empty state:
    *   A calm, minimalist illustration or badge icon.
    *   A friendly, descriptive title explaining why it's empty.
    *   A clear, prominent call-to-action button to create the first record.

---

## 📚 Supporting References
*   [Taste UI Recipes & CSS Snippets](./references/recipes.md)

# Project Guidelines & Anti-Slop Directives

All AI agents working on this project must adhere to Senior Developer standards and Anti-Slop protocols.

---

## 🚫 Anti-Slop Rules (Always Active)

1.  **Zero Fluff Comments:**
    *   Do not write comments that merely repeat what the code does (e.g. `// fetch user`, `// return view`).
    *   Only document the non-obvious *why*, domain logic constraints, or edge-case handling.

2.  **No Speculative Over-Engineering (YAGNI):**
    *   Solve the immediate problem with minimal, elegant, and idiomatic code.
    *   Do not introduce unnecessary design patterns, interfaces, or factories for single-use logic.

3.  **No Lazy Placeholders:**
    *   Never emit `// ... existing code ...`, `// TODO`, or incomplete snippets in code edits.
    *   Produce complete, compilable, production-ready code.

4.  **Surgical Edits:**
    *   Touch only the specific lines and files necessary to fulfill the request.
    *   Preserve existing conventions, formatting, and codebase patterns.

5.  **Concise & High-Signal Communication:**
    *   Skip conversational filler ("Certainly! I'd be glad to help...").
    *   Deliver direct, clear, and actionable results.

---

## 🎨 Taste & UI/UX Craftsmanship Directives

1.  **Subtlety & Color Harmony:**
    *   Never use raw saturated primaries or thick black borders. Use soft slate typography (`#0f172a`, `#475569`), hairline borders (`border-slate-200/60`), and gentle pastel status badges.
2.  **Typography & Numbers:**
    *   Always use tabular numerals (`tabular-nums` / font-mono) for counters, clock times, monetary amounts, and table metrics to prevent horizontal jitter.
3.  **Tactile Micro-Interactions:**
    *   Interactive elements must have crisp hover, active-press (`active:scale-[0.98]`), and smooth transition states (`duration-150` / `duration-200`).
4.  **Bento Layout & Spatial Rhythm:**
    *   Use modern rounded cards (`rounded-2xl`), ambient shadows, and consistent 4/8pt spacing rhythm. Avoid cramped or cluttered layouts.


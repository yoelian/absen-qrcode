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

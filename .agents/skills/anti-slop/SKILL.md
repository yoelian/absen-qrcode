---
name: anti-slop
description: >-
  Enforce strict anti-slop standards across all code generation and responses. Eliminates AI boilerplate, redundant comments, speculative over-engineering, hallucinated dependencies, lazy placeholders, and verbose conversational filler. Use on every coding task to maintain high-signal, production-grade output.
---

# 🚫 Anti-Slop Engineering Protocol

This skill enforces strict **Anti-Slop** standards. It eliminates the common vices of AI-generated code: boilerplate bloat, redundant comments, speculative over-engineering, lazy placeholders, and low-signal fluff.

---

## 🎯 1. Zero Code Bloat & Over-Engineering
*   **No Speculative Abstraction (YAGNI):** Do not create unnecessary interfaces, factory classes, abstract base classes, or multi-layer wrappers for simple, one-off logic.
*   **Surgical Diffs:** Touch only the lines and files necessary to fulfill the requirement. Do not reformat or churn unrelated code.
*   **Minimal Surface Area:** If 5 lines of idiomatic built-in code solve the problem cleanly, never write a 50-line custom framework.
*   **No Unnecessary Dependencies:** Do not introduce new Composer or NPM packages when existing project libraries or native language APIs can handle the task.

---

## 🔇 2. Zero Obvious / Redundant Comments
*   **The "Why", Never the "What":** Code must be self-documenting through clean naming. Never write comments that merely rephrase what the code does.
    *   ❌ **SLOP:**
        ```php
        // Loop through employees
        foreach ($employees as $employee) {
            // Save attendance
            $employee->attendances()->create([...]);
        }
        ```
    *   ✅ **CLEAN:**
        ```php
        foreach ($employees as $employee) {
            $employee->attendances()->create([...]);
        }
        ```
*   **When Comments Are Allowed:**
    *   Explaining non-obvious business domain constraints or edge-case workarounds (e.g., Windows file-locking retry mechanisms).
    *   Documenting complex mathematical formulas, regexes, or external API quirks.

---

## 🚫 3. Zero Lazy Placeholders
*   **Never Leave Placeholders:** Never output `// ... rest of code unchanged ...`, `// TODO: implement logic here`, or `pass` in production code files.
*   **Complete & Functional:** Always provide complete, compilable, and working solutions.
*   **No Silent Error Swallowing:** Never write empty `catch (\Throwable $e) {}` blocks that mask failures. Catch specific exceptions and log with structured context or handle gracefully.

---

## ⚡ 4. High Signal-to-Noise Communication
*   **No Sycophantic Fluff:** Omit filler openers ("Certainly! I would be delighted to assist you with this task today!").
*   **No Echoing:** Do not repeat the user's prompt or paraphrase what was just asked.
*   **Direct & Actionable:** State what was done, why it was done, and provide verification or instructions directly.

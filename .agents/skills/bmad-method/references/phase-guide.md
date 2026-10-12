# BMAD Phase-by-Phase Execution Guide

Detailed instructions for executing each phase of the BMAD Method.

---

## Phase 1: Analysis & Discovery (Analyst Persona)
*   **Trigger:** Complex domain problem, new business model, or unclear technical boundaries.
*   **Activities:**
    1. Research existing codebase capabilities and limitations.
    2. Identify target user archetypes and operational bottlenecks.
    3. Produce executive domain brief (`docs/briefs/FEATURE-brief.md`).

---

## Phase 2: Product Requirements (PM Persona)
*   **Trigger:** Feature definition.
*   **Activities:**
    1. Define core problem statement, Goals, and explicit Non-Goals.
    2. Write user stories with Given-When-Then acceptance criteria.
    3. Produce PRD (`docs/prd/FEATURE-prd.md`).
    4. **Human Gate:** Solicit user feedback on scope.

---

## Phase 3: System Architecture (Architect Persona)
*   **Trigger:** Multi-table migrations, new APIs, or performance constraints.
*   **Activities:**
    1. Design database ERD and migration specifications.
    2. Draft API contracts (HTTP endpoints, request/response JSON schemas).
    3. Document trade-offs via Architecture Decision Records (`docs/adr/`).
    4. **Human Gate:** Confirm data models and architecture before code generation.

---

## Phase 4: Sprint Decomposition (Scrum Master Persona)
*   **Trigger:** Ready to implement.
*   **Activities:**
    1. Break down architecture and PRD into atomic tasks.
    2. Sequence tasks: Migration ➔ Model ➔ Service/Action ➔ Controller/API ➔ UI/View ➔ Tests.

---

## Phase 5: Implementation (Developer Persona)
*   **Trigger:** Tasks decomposed.
*   **Activities:**
    1. Write clean, surgical, production-ready code.
    2. Apply Senior Developer standards (`senior-developer` skill) and zero bloat (`anti-slop` skill).
    3. Ensure UI meets modern aesthetic standards (`taste` skill).

---

## Phase 6: QA & Verification (QA Persona)
*   **Trigger:** Code written.
*   **Activities:**
    1. Execute test suite (PHPUnit, Jest, Cypress).
    2. Validate edge cases (null values, network failures, concurrency).
    3. Perform self-diff audit before final release.

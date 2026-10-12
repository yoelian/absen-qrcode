# Architecture Decision Record (ADR) Template

> **ADR ID:** ADR-[0001]  
> **Title:** [Short title of decision]  
> **Status:** Proposed / Accepted / Deprecated / Superseded by [ADR-XXXX]  
> **Date:** [YYYY-MM-DD]  

---

## 1. Context & Problem Statement
*   What is the architectural dilemma or engineering requirement?
*   What constraints (performance, memory, offline, cross-platform) exist?

---

## 2. Considered Options
1.  **Option A:** [e.g., Using Redis for caching]
2.  **Option B:** [e.g., Using SQLite file cache]
3.  **Option C:** [e.g., In-memory PHP array cache]

---

## 3. Decision Outcome
*   **Chosen Option:** [Option B: SQLite file cache]
*   **Justification:** [Why this option is optimal given our offline desktop architecture and zero-external-dependency requirement.]

---

## 4. Consequences & Trade-Offs

### Positive Consequences
*   No external server installation required on Windows client machines.
*   Zero network latency.
*   Persistent across app restarts.

### Negative Consequences / Mitigations
*   Concurrent write locks on Windows filesystem require retry mechanisms (`usleep` + retry loop).
*   Mitigation: Implemented in `Filesystem::replace()` fallback.

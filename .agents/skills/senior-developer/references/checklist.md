# Senior Developer Code Quality & Security Checklist

Use this checklist during development and self-code reviews before shipping code to production.

---

## 1. Correctness & Business Logic
- [ ] Does the solution correctly fulfill the intended requirement without unintended side effects?
- [ ] Are edge cases handled? (e.g. empty lists, null values, negative numbers, extreme dates, unicode characters)
- [ ] Are state transitions strictly validated (e.g. cannot transition from `canceled` to `approved`)?
- [ ] Are concurrent operations race-condition proof?

## 2. Architecture & Code Cleanliness
- [ ] Is there clear separation between controller, service/business logic, and persistence layers?
- [ ] Are functions small, focused, and doing one thing well (Single Responsibility)?
- [ ] Are variable, function, and class names self-documenting and descriptive?
- [ ] Is duplicated code avoided without premature, overly complex abstractions?
- [ ] Are hardcoded values replaced with named constants or configuration settings?

## 3. Database & Performance
- [ ] Are all relationships eager-loaded where necessary to eliminate N+1 queries?
- [ ] Are all foreign keys and frequently queried filter columns indexed?
- [ ] Are database mutations wrapped in a `DB::transaction()` where atomicity is required?
- [ ] Are large datasets processed via streaming/chunking rather than loading everything into RAM?
- [ ] Are cache keys properly versioned and invalidated upon data mutation?

## 4. Security Hardening (OWASP)
- [ ] Are all inputs validated against strict whitelist rules (types, formats, allowed ranges)?
- [ ] Are queries parameterized to prevent SQL Injection?
- [ ] Are views escaping dynamic variables to prevent Cross-Site Scripting (XSS)?
- [ ] Are all mutations protected by CSRF verification?
- [ ] Is user authorization explicitly enforced per record (preventing IDOR)?
- [ ] Are sensitive fields (passwords, tokens) masked from logs and API serialization?

## 5. Resilience & Error Handling
- [ ] Are try-catch blocks scoped to the vulnerable operations rather than wrapping huge blocks?
- [ ] Are caught exceptions logged with sufficient structured context (entity IDs, timestamp, error trace)?
- [ ] Does the application fail gracefully with user-friendly messages rather than exposing stack traces?
- [ ] Are external API calls and I/O operations equipped with explicit timeouts and retry limits?

## 6. Cleanliness & Final Polish
- [ ] Are all temporary debug lines (`dump()`, `dd()`, `print_r()`, `console.log()`) removed?
- [ ] Is the `.gitignore` up to date with no build artifacts or secrets staged?
- [ ] Do automated tests or manual test scripts pass cleanly?

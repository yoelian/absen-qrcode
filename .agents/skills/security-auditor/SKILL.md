---
name: security-auditor
description: >-
  Conduct comprehensive security audits, vulnerability scanning, OWASP Top 10 hardening, and data protection reviews. Enforces parameterized queries, XSS sanitization, CSRF defenses, IDOR authorization gates, database hardening, and dependency auditing. Use whenever conducting security reviews, implementing authentication/authorization, or handling sensitive user records.
---

# 🔒 Security Auditor: OWASP Defense & Data Protection

This skill instills offensive security awareness and defensive hardening across all layers of the application stack, adhering strictly to **OWASP Top 10** standards.

---

## 🛡️ 1. Core Security Tenets

*   **Principle of Least Privilege:** Users, database handles, and background processes must operate with the minimum necessary permissions.
*   **Defense-in-Depth:** Never rely on a single defensive layer (e.g., hiding a button in the UI). Always enforce validation, authentication, and authorization on the server.
*   **Fail Securely:** When an authentication, signature, or decryption check fails, reject immediately and securely. Never default to an open state.

---

## 🚨 2. The OWASP Checklist & Remediation

### 1. Insecure Direct Object References (IDOR)
*   ❌ **Vulnerable:** Updating an employee by raw ID from URL parameter without checking organization/ownership.
*   ✅ **Hardened:** Use Laravel Policies / Gates:
    ```php
    $this->authorize('update', $employee);
    ```

### 2. Injection (SQL / Command / Eval)
*   ❌ **Vulnerable:** `DB::raw("SELECT * FROM employees WHERE name LIKE '%" . $name . "%'")`
*   ✅ **Hardened:** Always use bindings:
    ```php
    DB::select('SELECT * FROM employees WHERE name LIKE ?', ['%' . $name . '%']);
    ```

### 3. Cross-Site Scripting (XSS)
*   Never render user-controlled HTML with unescaped Blade `{!! $data !!}`. Always use standard `{{ $data }}`.
*   Sanitize any Rich Text using an HTML purifier before storage or display.

### 4. Cross-Site Request Forgery (CSRF)
*   Enforce CSRF verification for all state-changing endpoints (`POST`, `PUT`, `DELETE`).
*   Only exempt strictly necessary routes (e.g. headless scan endpoints) when accompanied by alternative verification (local IP restriction, API keys).

---

## 🗃️ 3. SQLite & Local Database Hardening

For desktop / offline applications storing SQLite databases locally:
*   **Directory Permissions:** Place SQLite files in restricted user directories (`%APPDATA%`), not world-writable temporary paths.
*   **Password Hashing:** Always use Argon2id or Bcrypt with appropriate work factor (`Hash::make($password)`). Never store or log raw plaintext passwords.
*   **Dependency Audits:** Routinely run `composer audit` and `npm audit` to identify known CVEs in upstream vendor libraries.

---

## 📚 Supporting References
*   [OWASP Hardening & Vulnerability Matrix](./references/owasp-hardening.md)

# OWASP Hardening & Vulnerability Matrix

Reference guide for auditing common web application vulnerabilities.

---

## 1. Mass Assignment Prevention
Always explicitly declare `$fillable` or use Form Requests with `$request->validated()`:

```php
// Good: Only allow specified attributes
$employee->update($request->validated());

// Bad: Allows malicious input like isAdmin=true
$employee->update($request->all());
```

---

## 2. Timing-Attack Safe Comparisons
When comparing tokens, passwords, or HMAC hashes, use timing-safe comparison to prevent side-channel attacks:

```php
hash_equals($knownHash, $userProvidedHash);
```

---

## 3. Sensitive Data Masking in Logs
Ensure sensitive fields are excluded from logs:

```php
// config/logging.php or exception handler
'masked_fields' => ['password', 'password_confirmation', 'token', 'secret', 'pin'],
```

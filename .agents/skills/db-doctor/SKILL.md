---
name: db-doctor
description: >-
  Diagnose, optimize, and maintain database performance, schema integrity, and disaster recovery. Enforces proper indexing, N+1 query elimination, EXPLAIN query plan analysis, SQLite WAL mode, database locks, and automated backup strategies. Use whenever optimizing slow queries, designing database schemas, writing complex reporting queries, or managing data migrations.
---

# 🗄️ DB Doctor: Database Health & Query Performance

This skill diagnoses and optimizes database performance, schema architecture, and disaster recovery pipelines, ensuring sub-100ms queries even with tens of thousands of records.

---

## ⚡ 1. The 3 Golden Performance Rules

1.  **Index Every Filter & Join Column:**
    *   Any column appearing in `WHERE`, `ORDER BY`, `JOIN ON`, or `GROUP BY` must have an index.
    *   Composite indexes must follow the *Left-Prefix Rule* (e.g. `(employee_id, scanned_at)`).
2.  **Eliminate N+1 Queries with Eager Loading:**
    *   ❌ **N+1:** Iterating through 500 attendances and accessing `$attendance->employee->name` triggers 501 SQL queries.
    *   ✅ **Eager Loaded:** `Attendance::with('employee')->get()` triggers only 2 queries.
3.  **Audit Queries with EXPLAIN Plan:**
    *   Inspect `EXPLAIN QUERY PLAN SELECT ...` to verify queries utilize `SEARCH TABLE USING INDEX` instead of `SCAN TABLE` (full table scan).

---

## 🗃️ 2. SQLite Optimization for Desktop (WAL Mode)

In SQLite applications (like NativePHP on desktop):
*   **Enable WAL Mode (Write-Ahead Logging):**
    *   By default, SQLite locks the entire database on writes. WAL mode allows concurrent readers while a write is occurring.
    *   Execute on boot: `PRAGMA journal_mode = WAL;` and `PRAGMA synchronous = NORMAL;`.
*   **Vacuum & Integrity Check:**
    *   Periodically run `PRAGMA integrity_check;` and `VACUUM;` during maintenance or automated backup routines to reclaim unused disk space and optimize page cache.

---

## 🔄 3. Disaster Recovery & Automated Backups

1.  **Non-Blocking Automated Snapshots:**
    *   Generate daily JSON or SQLite snapshots without disrupting user operations.
2.  **Backup Retention Policy:**
    *   Implement rolling retention (keep last 7 days of daily backups, 4 weeks of weekly backups).
3.  **Idempotent Migrations:**
    *   Always verify columns and tables exist before altering (`Schema::hasColumn(...)`) to ensure migrations can be safely re-run without throwing fatal duplicate column exceptions.

---

## 📚 Supporting References
*   [Query Tuning & Indexing Recipes](./references/query-tuning.md)

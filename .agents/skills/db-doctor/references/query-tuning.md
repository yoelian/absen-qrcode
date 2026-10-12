# Query Tuning & Indexing Recipes

Reference examples for optimizing Eloquent and raw SQL queries.

---

## 1. Keyset Pagination (Fast Infinite Scroll / Large Tables)
For tables with over 50,000 rows, standard `OFFSET` pagination becomes slow (`O(N)`). Use Keyset pagination:

```php
// Good: Fast O(1) query utilizing index on id
Attendance::where('id', '>', $lastSeenId)
    ->orderBy('id', 'asc')
    ->limit(50)
    ->get();
```

---

## 2. Preventing Memory Spikes with `cursor()`
When generating large monthly report exports (e.g. 10,000 attendance records):

```php
// Uses PHP Generators to load 1 record at a time into memory
foreach (Attendance::with('employee')->cursor() as $attendance) {
    fputcsv($fileHandle, [$attendance->id, $attendance->employee->name, $attendance->scanned_at]);
}
```

# Windows Resilience & NativePHP Best Practices

Tactical guidelines for overcoming Windows-specific runtime hurdles in NativePHP applications.

---

## 1. Defeating Windows File-Locking (`Access is denied - code: 5`)
When PHP overwrites files (e.g. Blade view compilation or storage writes), Windows Defender or file indexers often lock the file momentarily.

```php
public function safeReplace(string $path, string $content): void
{
    $tempPath = tempnam(dirname($path), basename($path));
    file_put_contents($tempPath, $content);

    if (@rename($tempPath, $path)) {
        return;
    }

    // Windows retry backoff loop
    for ($i = 0; $i < 5; $i++) {
        usleep(25000); // 25ms delay
        if (@rename($tempPath, $path)) {
            return;
        }
        if (@copy($tempPath, $path)) {
            @unlink($tempPath);
            return;
        }
    }

    // Direct write fallback
    if (@file_put_contents($path, $content) !== false) {
        @unlink($tempPath);
        return;
    }

    rename($tempPath, $path);
}
```

---

## 2. Windows Port Conflicts & Process Management
*   Electron runs internal IPC on ports (e.g., 4001).
*   Ensure service providers gracefully handle connection timeouts to localhost without breaking the core user interface.
*   Prevent orphan PHP processes by registering shutdown handlers on app termination.

---

## 3. High-DPI Scaling & Fonts
*   Always include locally cached WOFF2 fonts for offline execution.
*   Ensure font rendering remains crisp on 100%, 125%, and 150% Windows display scaling settings.

---
name: desktop-native
description: >-
  Specialized engineering protocols for NativePHP and Electron desktop applications on Windows. Covers hardware barcode scanner integration, window state management, kiosk modes, low-memory footprint optimization, file-locking resilience, and offline-first persistence. Use whenever developing, debugging, building, or packaging desktop features in NativePHP.
---

# 🖥️ Desktop Native: NativePHP & Electron Engineering

This skill provides specialized protocols for building, debugging, and packaging robust **NativePHP & Electron** desktop applications on Microsoft Windows.

---

## 🏗️ 1. Core Desktop Principles

*   **Offline-First & Local Sovereignty:** Desktop applications must function flawlessly without an active internet connection. All assets (fonts, icons, stylesheets, JS libraries) must be bundled locally in `public/assets/`, never loaded via external CDNs.
*   **Hardware Peripherals Emulation:** Barcode scanners, QR readers, and RFID guns behave as physical human keyboard strokes (HID). The UI must listen globally or maintain autofocus on scanner inputs with debounced input clearing.
*   **Windows File System Quirks:** Windows NTFS enforces strict file-locking semantics (e.g. Antivirus / Windows Defender inspecting newly written files). Always employ retries, copy fallbacks, or streaming writes rather than assuming atomic file renames.

---

## 🪟 2. Window Lifecycle & Kiosk Mode

1.  **Window State Persistence:**
    *   Window coordinates (`x`, `y`, `width`, `height`, maximized state) are tracked in AppData (`window-state-main.json`).
    *   If corrupted, provide automated recovery by resetting the config rather than crashing on launch.
2.  **Kiosk / Fullscreen Mode:**
    *   When in attendance scanning mode, support toggling Kiosk Mode (F11 / Esc).
    *   Hide navigation elements, minimize distractors, and focus strictly on barcode capture.

---

## ⚡ 3. Memory & Resource Optimization (Low-Spec PCs)

Schools and local offices frequently run on low-spec hardware (2GB - 4GB RAM, Celeron/i3):
*   **Disable Unused Queue Workers:** If the desktop application processes jobs synchronously, set `'queue_workers' => []` in `config/nativephp.php` to avoid spawning redundant background PHP processes and cURL timeouts.
*   **Clean Temp & Log Bloat:** Avoid unbounded log files. Rotate logs daily and restrict in-memory reads to the last 2MB / 500 lines.
*   **Avoid Build Cache Bloat:** Before running `php artisan native:build win`, always prune `dist/` and nested `vendor/nativephp/electron/resources/js/resources/app` to avoid recursive copy loops.

---

## ⌨️ 4. Hardware Barcode / QR Scanner Handling

```javascript
// Reliable Barcode Scanner Listener Pattern
let scanBuffer = '';
let scanTimeout = null;

window.addEventListener('keypress', (e) => {
    // If Enter is pressed, dispatch the accumulated buffer
    if (e.key === 'Enter') {
        if (scanBuffer.length >= 3) {
            processBarcode(scanBuffer.trim());
        }
        scanBuffer = '';
        return;
    }
    
    // Rapid input accumulation from barcode scanner (typically < 30ms between keys)
    scanBuffer += e.key;
    clearTimeout(scanTimeout);
    scanTimeout = setTimeout(() => { scanBuffer = ''; }, 100);
});
```

---

## 📚 Supporting References
*   [Windows Resilience & NativePHP Best Practices](./references/windows-resilience.md)

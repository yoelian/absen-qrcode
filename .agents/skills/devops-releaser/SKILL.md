---
name: devops-releaser
description: >-
  Automate build, packaging, release distribution, and CI/CD pipelines using GitHub Actions, electron-builder, and semantic versioning. Covers automated installer generation, changelog drafting, Git tagging, and GitHub Releases asset uploading. Use whenever preparing release packages, configuring GitHub Actions workflows, managing version bumps, or distributing desktop builds.
---

# 🚀 DevOps Releaser: CI/CD & Build Distribution

This skill standardizes and automates build packaging, version tagging, and release distribution for software applications.

---

## 🏷️ 1. Semantic Versioning (SemVer) Protocol

All project releases must follow standard SemVer: `MAJOR.MINOR.PATCH` (e.g. `v1.3.2`):
*   **PATCH (`1.3.1` ➔ `1.3.2`):** Backward-compatible bug fixes and stability improvements.
*   **MINOR (`1.3.0` ➔ `1.4.0`):** New backward-compatible features (e.g. adding new export formats, new search filters).
*   **MAJOR (`1.0.0` ➔ `2.0.0`):** Incompatible architectural overhauls or breaking database restructuring.

---

## 📦 2. Pre-Build Hygiene for Desktop Packaging

Before initiating an installer build (e.g. `php artisan native:build win`):
1.  **Sync Version across configs:**
    *   Update `NATIVEPHP_APP_VERSION=X.Y.Z` in `.env`.
    *   Ensure version matches in `package.json` and application metadata.
2.  **Prune Build Bloat:**
    *   Clear temporary views (`storage/framework/views/*.php`).
    *   Purge local logs (`storage/logs/*.log`).
    *   Delete previous `dist/` and internal cache folders (`vendor/nativephp/electron/resources/js/resources/app`) to prevent recursive bloat.
3.  **Validate PHP & JS Syntax:**
    *   Run `php -l` and ensure no syntax regressions break the electron packaging step.

---

## 🤖 3. GitHub Actions Release Automation

Automate the build and asset release workflow in GitHub Actions:
*   Triggered when a Git tag `v*` is pushed.
*   Builds the Windows NSIS installer `.exe` in an isolated runner.
*   Publishes the release draft with release notes and attaches the `.exe` binary.

---

## 📚 Supporting References
*   [GitHub Actions Desktop Build Workflow](./references/github-actions-workflow.md)

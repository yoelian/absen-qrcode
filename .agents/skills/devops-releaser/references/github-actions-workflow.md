# GitHub Actions Desktop Build Workflow

Example workflow file (`.github/workflows/build-release.yml`) for automated packaging and releasing NativePHP desktop installers on GitHub:

```yaml
name: Build and Release Desktop App

on:
  push:
    tags:
      - 'v*'

jobs:
  build-windows:
    runs-on: windows-latest

    steps:
      - name: Checkout Code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: sqlite3, curl, mbstring, openssl, gd, zip

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: 22

      - name: Install Composer Dependencies
        run: composer install --prefer-dist --no-interaction --optimize-autoloader

      - name: Install NPM Dependencies
        run: npm ci

      - name: Build Native App Installer
        run: php artisan native:build win
        env:
          CI: true

      - name: Create GitHub Release
        uses: softprops/action-gh-release@v1
        with:
          files: dist/*.exe
          generate_release_notes: true
```

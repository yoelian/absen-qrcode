# Graph Report - absen-qrcode  (2026-09-28)

## Corpus Check
- 172 files · ~76,936 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2916 nodes · 6903 edges · 204 communities (141 shown, 63 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 162 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- tn
- chart.min.js
- a
- n
- xt
- f
- rt
- Controller
- l
- Illuminate\Http\Request
- cs
- .append
- ho
- AttendanceExport.php
- Illuminate\Database\Migrations\Migration
- updateElements
- .toString
- _
- nt
- Jn
- .getContext
- Category
- .getHeight
- r
- .get
- devDependencies
- .hide
- p
- bootstrap.bundle.min.js
- ht
- .parseInformation
- ke
- AttendanceQualityTest
- gr
- .substring
- b
- s
- xt
- ze
- qi
- me
- cr
- xe
- .encode
- w
- sn
- remove
- ie
- sr
- LoginRequest
- Ks
- .getSize
- Bt
- et
- .getX
- N
- je
- Q
- ae
- ve
- e
- User
- EmployeeImport
- scripts
- Es
- m
- .decode
- .decode
- ee
- pe
- .decodeRow
- T
- composer.json
- qn
- st
- ce
- Qe
- .decode
- i
- j
- .decodeRow
- tt
- .runEuclideanAlgorithm
- User.php
- README.md
- we
- require
- bootstrap/app.php
- it
- dev
- O
- ar
- AutoBackupMiddleware.php
- require-dev
- hs
- Nr
- setup
- Q
- ot
- AppServiceProvider
- config
- extra
- x
- Template_Import_Siswa_Staff_23baaeb7.md
- NativeAppServiceProvider
- rules/graphify.md
- oe
- workflows/graphify.md
- psr-4
- logging.php
- bt
- se
- .getNotFoundInstance
- gt
- ExampleTest
- profile/edit.blade.php
- excel.php
- console.php

## God Nodes (most connected - your core abstractions)
1. `tn` - 124 edges
2. `_` - 110 edges
3. `f` - 60 edges
4. `n()` - 59 edges
5. `User` - 49 edges
6. `s()` - 45 edges
7. `Employee` - 43 edges
8. `cs` - 38 edges
9. `a()` - 36 edges
10. `sr` - 36 edges

## Surprising Connections (you probably didn't know these)
- `AttendanceQualityTest` --references--> `Category`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/Category.php
- `AttendanceQualityTest` --references--> `Employee`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/Employee.php
- `AttendanceQualityTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/User.php
- `updateElements()` --indirect_call--> `k()`  [INFERRED]
  public/assets/js/chart.min.js → public/assets/js/bootstrap.bundle.min.js
- `ii()` --indirect_call--> `qt()`  [INFERRED]
  public/assets/js/bootstrap.bundle.min.js → public/assets/js/chart.min.js

## Import Cycles
- None detected.

## Communities (204 total, 63 thin omitted)

### Community 0 - "tn"
Cohesion: 0.04
Nodes (17): aa(), addBox(), addElements(), afterDatasetsUpdate(), configure(), d(), Di(), generateLabels() (+9 more)

### Community 1 - "chart.min.js"
Cohesion: 0.03
Nodes (51): ai(), at(), b(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw(), beforeUpdate(), buildTicks() (+43 more)

### Community 2 - "a"
Cohesion: 0.07
Nodes (37): a(), average(), beforeLayout(), dataset(), draw(), eo(), et(), f() (+29 more)

### Community 3 - "n"
Cohesion: 0.06
Nodes (17): e(), ei(), en, fn(), gi(), gn(), je(), mi() (+9 more)

### Community 4 - "xt"
Cohesion: 0.07
Nodes (8): cn, an(), as(), ln(), on, rs(), ts(), xt

### Community 7 - "Controller"
Cohesion: 0.07
Nodes (26): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+18 more)

### Community 8 - "l"
Cohesion: 0.07
Nodes (21): afterDraw(), afterEvent(), afterUpdate(), Ba(), l(), ki(), lo(), Oi() (+13 more)

### Community 9 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (20): AttendanceController, DashboardController, EmployeeController, LeaveController, ReportController, StatisticController, Attendance, Employee (+12 more)

### Community 10 - "cs"
Cohesion: 0.08
Nodes (5): cs, us, es(), is(), ns()

### Community 12 - ".append"
Cohesion: 0.18
Nodes (3): he, te, ue

### Community 13 - "ho"
Cohesion: 0.10
Nodes (10): buildLookupTable(), _generate(), getDecimalForValue(), _getTimestampsForTable(), getValueForPixel(), ho(), initOffsets(), jo() (+2 more)

### Community 14 - "AttendanceExport.php"
Cohesion: 0.11
Nodes (18): AttendanceExport, DetailAttendanceExport, GridAttendanceExport, MonthlyAttendanceExport, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromCollection, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize (+10 more)

### Community 15 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.08
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 16 - "updateElements"
Cohesion: 0.07
Nodes (25): Ae(), Bn(), _calculateBarIndexPixels(), _calculateBarValuePixels(), da(), _getAxis(), _getAxisCount(), getBasePixel() (+17 more)

### Community 17 - ".toString"
Cohesion: 0.10
Nodes (3): er, ir(), or

### Community 18 - "_"
Cohesion: 0.08
Nodes (10): _, a, c, g, K, s, st, tr (+2 more)

### Community 20 - "Jn"
Cohesion: 0.08
Nodes (3): H, Jn, W

### Community 21 - ".getContext"
Cohesion: 0.11
Nodes (12): ao(), Bi(), Ci(), co(), cs, Do(), Fi(), inXRange() (+4 more)

### Community 22 - "Category"
Cohesion: 0.06
Nodes (16): CategoryController, PositionController, Category, Leave, Position, DatabaseSeeder, DummyAttendanceSeeder, DummyEmployeeSeeder (+8 more)

### Community 26 - "devDependencies"
Cohesion: 0.06
Nodes (31): alpinejs, autoprefixer, chart.js, concurrently, html5-qrcode, laravel-vite-plugin, dependencies, chart.js (+23 more)

### Community 27 - ".hide"
Cohesion: 0.12
Nodes (6): ao, io(), no(), oo, Us(), Ys()

### Community 29 - "bootstrap.bundle.min.js"
Cohesion: 0.06
Nodes (50): Ae(), be(), Ce(), D(), De(), di(), $e(), Ee() (+42 more)

### Community 30 - "ht"
Cohesion: 0.12
Nodes (3): ht, jt, kt

### Community 31 - ".parseInformation"
Cohesion: 0.11
Nodes (5): Qt, vt, xt, yt, zt

### Community 33 - "AttendanceQualityTest"
Cohesion: 0.08
Nodes (3): SettingController, Illuminate\Support\Facades\Storage, AttendanceQualityTest

### Community 34 - "gr"
Cohesion: 0.15
Nodes (4): br(), gr, mr, Vr

### Community 37 - "s"
Cohesion: 0.05
Nodes (28): ri(), Be(), bo, Bt(), color(), Ee(), Ft(), getRange() (+20 more)

### Community 47 - "remove"
Cohesion: 0.12
Nodes (8): d(), on(), remove(), cn(), hn(), Nn(), un(), wn()

### Community 50 - "LoginRequest"
Cohesion: 0.17
Nodes (7): LoginRequest, ProfileUpdateRequest, Illuminate\Auth\Events\Lockout, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Support\Facades\RateLimiter, Illuminate\Validation\Rule

### Community 51 - "Ks"
Cohesion: 0.21
Nodes (3): getElementFromSelector(), Ks, Fs()

### Community 53 - "Bt"
Cohesion: 0.15
Nodes (3): Bt, getSelectorFromElement(), Y

### Community 55 - ".getX"
Cohesion: 0.05
Nodes (6): be, de, fe, ft, lt, re

### Community 56 - "N"
Cohesion: 0.07
Nodes (3): ge, le, N

### Community 62 - "User"
Cohesion: 0.06
Nodes (21): UserController, User, Illuminate\Auth\Events\Verified, Illuminate\Auth\Notifications\ResetPassword, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Support\Facades\Event (+13 more)

### Community 63 - "EmployeeImport"
Cohesion: 0.21
Nodes (10): EmployeeImport, Maatwebsite\Excel\Concerns\Importable, Maatwebsite\Excel\Concerns\SkipsErrors, Maatwebsite\Excel\Concerns\SkipsFailures, Maatwebsite\Excel\Concerns\SkipsOnError, Maatwebsite\Excel\Concerns\SkipsOnFailure, Maatwebsite\Excel\Concerns\ToModel, Maatwebsite\Excel\Concerns\WithHeadingRow (+2 more)

### Community 64 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall (+7 more)

### Community 68 - ".decode"
Cohesion: 0.19
Nodes (3): constructor(), I, lr

### Community 71 - ".decodeRow"
Cohesion: 0.12
Nodes (3): ct, mt, pt

### Community 73 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 80 - "i"
Cohesion: 0.08
Nodes (18): bs(), ce(), ct(), de, dt(), ge(), he(), ks() (+10 more)

### Community 85 - "User.php"
Cohesion: 0.14
Nodes (9): UserFactory, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Notifications\Notifiable, Illuminate\Support\Str, Pdo\Mysql, Spatie\Permission\Traits\HasRoles (+1 more)

### Community 86 - "README.md"
Cohesion: 0.25
Nodes (7): About Laravel, Agentic Development, Code of Conduct, Contributing, Learning Laravel, License, Security Vulnerabilities

### Community 89 - "require"
Cohesion: 0.20
Nodes (10): require, barryvdh/laravel-dompdf, doctrine/dbal, laravel/framework, laravel/tinker, maatwebsite/excel, milon/barcode, nativephp/electron (+2 more)

### Community 90 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 92 - "dev"
Cohesion: 0.40
Nodes (5): dev, native:dev, Composer\\Config::disableProcessTimeout, npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite --kill-others, npx concurrently --kill-others-on-fail -c \"#93c5fd,#c4b5fd\" \"php artisan native:serve --no-interaction --no-dependencies\" \"npm run dev\" --names=app,vite

### Community 96 - "AutoBackupMiddleware.php"
Cohesion: 0.36
Nodes (4): AutoBackupMiddleware, Closure, Illuminate\Support\Facades\File, Symfony\Component\HttpFoundation\Response

### Community 97 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/breeze, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 100 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 104 - "AppServiceProvider"
Cohesion: 0.33
Nodes (3): AppServiceProvider, Illuminate\Pagination\Paginator, Illuminate\Support\ServiceProvider

### Community 105 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 106 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 109 - "NativeAppServiceProvider"
Cohesion: 0.40
Nodes (3): NativeAppServiceProvider, Native\Laravel\Contracts\ProvidesPhpIni, Native\Laravel\Facades\Window

### Community 113 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 114 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 120 - "profile/edit.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

## Knowledge Gaps
- **84 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+79 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **63 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_` connect `_` to `f`, `rt`, `.append`, `.toString`, `nt`, `.getHeight`, `r`, `.get`, `p`, `ht`, `.parseInformation`, `ke`, `gr`, `.substring`, `b`, `ze`, `me`, `cr`, `xe`, `.encode`, `w`, `ie`, `sr`, `.getSize`, `et`, `.getX`, `N`, `je`, `Q`, `ae`, `ve`, `e`, `m`, `.decode`, `.decode`, `ee`, `pe`, `.decodeRow`, `T`, `ce`, `Qe`, `.decode`, `j`, `.decodeRow`, `tt`, `.runEuclideanAlgorithm`, `we`, `it`, `O`, `ar`, `Nr`, `ot`, `x`, `oe`, `bt`, `se`, `.getNotFoundInstance`, `gt`?**
  _High betweenness centrality (0.315) - this node is a cross-community bridge._
- **Why does `e()` connect `e` to `.decode`, `me`, `.encode`, `w`, `i`, `.toString`, `_`, `r`?**
  _High betweenness centrality (0.214) - this node is a cross-community bridge._
- **Why does `i()` connect `i` to `tn`, `chart.min.js`, `a`, `n`, `s`, `l`, `bt`, `.getContext`, `e`?**
  _High betweenness centrality (0.211) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _84 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `tn` be split into smaller, more focused modules?**
  _Cohesion score 0.037416550225120325 - nodes in this community are weakly interconnected._
- **Should `chart.min.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02779963283503803 - nodes in this community are weakly interconnected._
- **Should `a` be split into smaller, more focused modules?**
  _Cohesion score 0.07184325108853411 - nodes in this community are weakly interconnected._
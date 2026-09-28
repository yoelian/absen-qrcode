# Graph Report - absen-qrcode  (2026-09-28)

## Corpus Check
- 174 files · ~77,917 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2930 nodes · 6915 edges · 215 communities (145 shown, 70 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 162 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- tn
- chart.min.js
- a
- n
- cn
- f
- rt
- Illuminate\Http\Request
- .isHorizontal
- Employee
- cs
- j
- .append
- ho
- AttendanceExport.php
- Illuminate\Database\Migrations\Migration
- updateElements
- .toString
- _
- .decodeRow
- W
- .getContext
- Category
- .getHeight
- b
- pr
- devDependencies
- .hide
- p
- bootstrap.bundle.min.js
- ht
- .parseInformation
- ke
- Carbon
- gr
- .substring
- ii
- jt
- xt
- ze
- qi
- me
- .encode
- .arraycopy
- wr
- .decodeRow
- sn
- remove
- ie
- sr
- LoginRequest
- Ks
- .get
- Bt
- et
- .getX
- le
- je
- Q
- ae
- .decode
- e
- User
- EmployeeImport
- scripts
- Es
- m
- be
- .decode
- ee
- .getCount
- N
- T
- composer.json
- qn
- Illuminate\Database\Eloquent\Model
- st
- ce
- Qe
- .runEuclideanAlgorithm
- s
- or
- So
- TestCase
- .runEuclideanAlgorithm
- User.php
- README.md
- Jn
- require
- bootstrap/app.php
- it
- dev
- fe
- O
- ar
- AutoBackupMiddleware.php
- require-dev
- hs
- Nr
- setup
- Taste: Anti-Slop Frontend & UI/UX Design System
- ui
- ne
- AppServiceProvider
- config
- extra
- lt
- Template_Import_Siswa_Staff_23baaeb7.md
- NativeAppServiceProvider
- rules/graphify.md
- r
- workflows/graphify.md
- psr-4
- logging.php
- bt
- SettingController
- sn
- EmailVerificationTest.php
- ExampleTest
- profile/edit.blade.php
- PasswordResetTest
- Illuminate\View\Component
- dr
- excel.php
- console.php
- y
- Illuminate\Support\Facades\Hash
- AuthenticationTest
- st
- .encode
- PasswordConfirmationTest
- taste.md

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

## Communities (215 total, 70 thin omitted)

### Community 0 - "tn"
Cohesion: 0.04
Nodes (17): addBox(), addElements(), afterDatasetsUpdate(), configure(), d(), Di(), generateLabels(), Ie() (+9 more)

### Community 1 - "chart.min.js"
Cohesion: 0.03
Nodes (49): ai(), at(), b(), Be(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw(), beforeUpdate() (+41 more)

### Community 2 - "a"
Cohesion: 0.07
Nodes (44): a(), buildTicks(), da(), determineDataLimits(), draw(), eo(), f(), fo() (+36 more)

### Community 3 - "n"
Cohesion: 0.05
Nodes (15): e(), ei(), en, fn(), gn(), je(), n(), pi() (+7 more)

### Community 4 - "cn"
Cohesion: 0.10
Nodes (7): cn, an(), as(), ln(), on, rs(), ts()

### Community 7 - "Illuminate\Http\Request"
Cohesion: 0.10
Nodes (23): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+15 more)

### Community 8 - ".isHorizontal"
Cohesion: 0.07
Nodes (17): afterDraw(), afterEvent(), afterUpdate(), Ba(), ki(), lo(), Oi(), po() (+9 more)

### Community 9 - "Employee"
Cohesion: 0.09
Nodes (18): DashboardController, EmployeeController, ReportController, StatisticController, Attendance, Employee, Setting, AttendanceService (+10 more)

### Community 10 - "cs"
Cohesion: 0.08
Nodes (5): cs, us, es(), is(), ns()

### Community 11 - "j"
Cohesion: 0.11
Nodes (3): d, gt, j

### Community 12 - ".append"
Cohesion: 0.16
Nodes (3): he, te, ue

### Community 13 - "ho"
Cohesion: 0.08
Nodes (13): beforeLayout(), buildLookupTable(), _generate(), getDecimalForValue(), _getTimestampsForTable(), getValueForPixel(), Go(), ho() (+5 more)

### Community 14 - "AttendanceExport.php"
Cohesion: 0.11
Nodes (18): AttendanceExport, DetailAttendanceExport, GridAttendanceExport, MonthlyAttendanceExport, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromCollection, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize (+10 more)

### Community 15 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.08
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 16 - "updateElements"
Cohesion: 0.13
Nodes (14): aa(), Ae(), Bn(), _calculateBarIndexPixels(), _calculateBarValuePixels(), _getAxis(), _getAxisCount(), getFirstScaleIdForIndexAxis() (+6 more)

### Community 18 - "_"
Cohesion: 0.10
Nodes (8): _, a, c, g, K, oe, s, tr

### Community 19 - ".decodeRow"
Cohesion: 0.09
Nodes (3): dt, nt, x

### Community 21 - ".getContext"
Cohesion: 0.07
Nodes (22): ao(), average(), Bi(), Ci(), co(), cs, dataset(), Do() (+14 more)

### Community 22 - "Category"
Cohesion: 0.08
Nodes (12): CategoryController, PositionController, Category, Position, DatabaseSeeder, DummyAttendanceSeeder, DummyEmployeeSeeder, Illuminate\Database\Eloquent\Relations\HasMany (+4 more)

### Community 26 - "devDependencies"
Cohesion: 0.06
Nodes (31): alpinejs, autoprefixer, chart.js, concurrently, html5-qrcode, laravel-vite-plugin, dependencies, chart.js (+23 more)

### Community 27 - ".hide"
Cohesion: 0.08
Nodes (7): ao, Q, io(), no(), oo, Us(), Ys()

### Community 29 - "bootstrap.bundle.min.js"
Cohesion: 0.11
Nodes (21): be(), D(), ei(), getDataAttributes(), I(), Ie(), j(), k() (+13 more)

### Community 31 - ".parseInformation"
Cohesion: 0.11
Nodes (5): Qt, vt, xt, yt, zt

### Community 33 - "Carbon"
Cohesion: 0.13
Nodes (3): AttendanceController, Carbon, AttendanceQualityTest

### Community 34 - "gr"
Cohesion: 0.14
Nodes (4): br(), gr, mr, Vr

### Community 36 - "ii"
Cohesion: 0.23
Nodes (25): Ae(), Ce(), De(), di(), $e(), Ee(), fe(), ge() (+17 more)

### Community 37 - "jt"
Cohesion: 0.06
Nodes (18): ri(), Bt(), color(), Ee(), Ft(), Gt(), It(), jt() (+10 more)

### Community 45 - ".decodeRow"
Cohesion: 0.07
Nodes (3): ct, mt, w

### Community 47 - "remove"
Cohesion: 0.14
Nodes (6): d(), on(), remove(), Nn(), Pn(), wn()

### Community 50 - "LoginRequest"
Cohesion: 0.13
Nodes (9): LoginRequest, ProfileUpdateRequest, Illuminate\Auth\Events\Lockout, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Str, Illuminate\Validation\Rule (+1 more)

### Community 51 - "Ks"
Cohesion: 0.21
Nodes (3): getElementFromSelector(), Ks, Fs()

### Community 53 - "Bt"
Cohesion: 0.15
Nodes (3): Bt, getSelectorFromElement(), Y

### Community 62 - "User"
Cohesion: 0.19
Nodes (4): UserController, User, Illuminate\Foundation\Auth\User, ProfileTest

### Community 63 - "EmployeeImport"
Cohesion: 0.21
Nodes (10): EmployeeImport, Maatwebsite\Excel\Concerns\Importable, Maatwebsite\Excel\Concerns\SkipsErrors, Maatwebsite\Excel\Concerns\SkipsFailures, Maatwebsite\Excel\Concerns\SkipsOnError, Maatwebsite\Excel\Concerns\SkipsOnFailure, Maatwebsite\Excel\Concerns\ToModel, Maatwebsite\Excel\Concerns\WithHeadingRow (+2 more)

### Community 64 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall (+7 more)

### Community 68 - ".decode"
Cohesion: 0.21
Nodes (3): constructor(), I, lr

### Community 73 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 75 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.15
Nodes (5): LeaveController, Leave, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 80 - "s"
Cohesion: 0.07
Nodes (25): bs(), ce(), ct(), de, dt(), et(), ge(), getRange() (+17 more)

### Community 81 - "or"
Cohesion: 0.14
Nodes (3): ir(), or, rr()

### Community 82 - "So"
Cohesion: 0.12
Nodes (5): bo, H(), j(), So, xo()

### Community 83 - "TestCase"
Cohesion: 0.21
Nodes (6): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, RegistrationTest, EmployeeTemplateDownloadTest, ExampleTest, TestCase

### Community 85 - "User.php"
Cohesion: 0.22
Nodes (7): UserFactory, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Notifications\Notifiable, Spatie\Permission\Traits\HasRoles, static

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

### Community 101 - "Taste: Anti-Slop Frontend & UI/UX Design System"
Cohesion: 0.17
Nodes (11): 0.A Signals to Read, 0.B Anti-Default Discipline, 0. BRIEF INFERENCE (Read the Room Before Anything Else), 1. THE THREE DIALS (Calibration Matrix), 2. TYPOGRAPHY & VISUAL HIERARCHY, 3. COLOR PALETTES & ACCENTS, 4. MATERIALITY, CARDS & ELEVATION, 5. FORMS, TABLES & DATA DENSITY (+3 more)

### Community 103 - "ne"
Cohesion: 0.08
Nodes (3): ne, ot, re

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

### Community 118 - "EmailVerificationTest.php"
Cohesion: 0.25
Nodes (4): Illuminate\Auth\Events\Verified, Illuminate\Support\Facades\Event, Illuminate\Support\Facades\URL, EmailVerificationTest

### Community 120 - "profile/edit.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 121 - "PasswordResetTest"
Cohesion: 0.25
Nodes (3): Illuminate\Auth\Notifications\ResetPassword, Illuminate\Support\Facades\Notification, PasswordResetTest

### Community 122 - "Illuminate\View\Component"
Cohesion: 0.38
Nodes (3): AppLayout, GuestLayout, Illuminate\View\Component

## Knowledge Gaps
- **93 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+88 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **70 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_` connect `_` to `f`, `rt`, `j`, `.append`, `.toString`, `.decodeRow`, `.getHeight`, `b`, `pr`, `p`, `ht`, `.parseInformation`, `ke`, `gr`, `.substring`, `ze`, `me`, `.encode`, `.arraycopy`, `wr`, `.decodeRow`, `ie`, `sr`, `.get`, `et`, `.getX`, `le`, `je`, `Q`, `ae`, `.decode`, `e`, `m`, `be`, `.decode`, `ee`, `.getCount`, `N`, `T`, `ce`, `Qe`, `.runEuclideanAlgorithm`, `y`, `or`, `st`, `.encode`, `.runEuclideanAlgorithm`, `it`, `fe`, `O`, `ar`, `Nr`, `ne`, `lt`, `r`, `bt`, `dr`?**
  _High betweenness centrality (0.314) - this node is a cross-community bridge._
- **Why does `e()` connect `e` to `.decode`, `f`, `.encode`, `.decodeRow`, `r`, `s`, `.toString`, `_`?**
  _High betweenness centrality (0.213) - this node is a cross-community bridge._
- **Why does `i()` connect `s` to `tn`, `chart.min.js`, `a`, `n`, `jt`, `.isHorizontal`, `bt`, `.getContext`, `e`?**
  _High betweenness centrality (0.209) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _93 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `tn` be split into smaller, more focused modules?**
  _Cohesion score 0.036650731565985806 - nodes in this community are weakly interconnected._
- **Should `chart.min.js` be split into smaller, more focused modules?**
  _Cohesion score 0.029653780468871294 - nodes in this community are weakly interconnected._
- **Should `a` be split into smaller, more focused modules?**
  _Cohesion score 0.06526806526806526 - nodes in this community are weakly interconnected._
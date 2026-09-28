# Graph Report - absen-qrcode  (2026-09-28)

## Corpus Check
- 172 files · ~76,933 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2915 nodes · 6902 edges · 202 communities (143 shown, 59 thin omitted)
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
- .isHorizontal
- Illuminate\Http\Request
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
- Jn
- .getContext
- Category
- .get
- r
- pr
- devDependencies
- .hide
- p
- bootstrap.bundle.min.js
- .getX
- ht
- ke
- Setting
- gr
- or
- update
- jt
- xt
- .getValue
- qi
- me
- cr
- be
- .encode
- .arraycopy
- sn
- remove
- ie
- sr
- LoginRequest
- Ks
- .encode
- Bt
- et
- .getY
- N
- je
- Q
- .getProps
- .decode
- e
- User
- EmployeeImport
- scripts
- Es
- de
- ye
- .decode
- ee
- ae
- .charAt
- T
- composer.json
- qn
- .buildOrUpdateControllers
- st
- ce
- Qe
- .runEuclideanAlgorithm
- i
- .getSize
- lt
- tt
- xe
- User.php
- README.md
- require
- bootstrap/app.php
- it
- dev
- ne
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
- dr
- Template_Import_Siswa_Staff_23baaeb7.md
- NativeAppServiceProvider
- rules/graphify.md
- oe
- workflows/graphify.md
- psr-4
- logging.php
- se
- ExampleTest
- profile/edit.blade.php
- wt
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

## Communities (202 total, 59 thin omitted)

### Community 0 - "tn"
Cohesion: 0.04
Nodes (13): addBox(), Ae(), configure(), d(), Di(), getPixelForTick(), Gs(), Ie() (+5 more)

### Community 1 - "chart.min.js"
Cohesion: 0.03
Nodes (62): ai(), at(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw(), beforeUpdate(), Bt(), ca (+54 more)

### Community 2 - "a"
Cohesion: 0.08
Nodes (20): a(), bo, determineDataLimits(), draw(), fo(), gi(), H(), l() (+12 more)

### Community 3 - "n"
Cohesion: 0.04
Nodes (20): Be(), ce(), de, dt(), ei(), en, fn(), gn() (+12 more)

### Community 4 - "xt"
Cohesion: 0.07
Nodes (8): cn, an(), as(), ln(), on, rs(), ts(), xt

### Community 7 - "Controller"
Cohesion: 0.07
Nodes (26): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+18 more)

### Community 8 - ".isHorizontal"
Cohesion: 0.07
Nodes (20): afterDraw(), afterEvent(), afterUpdate(), Ba(), Ee(), f(), ki(), Le() (+12 more)

### Community 9 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (20): AttendanceController, DashboardController, EmployeeController, LeaveController, ReportController, StatisticController, Attendance, Employee (+12 more)

### Community 10 - "cs"
Cohesion: 0.08
Nodes (5): cs, us, es(), is(), ns()

### Community 12 - ".append"
Cohesion: 0.12
Nodes (3): te, ue, y

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
Nodes (20): aa(), afterDatasetsUpdate(), Bn(), _calculateBarValuePixels(), da(), generateLabels(), getBasePixel(), getLabelAndValue() (+12 more)

### Community 18 - "_"
Cohesion: 0.06
Nodes (12): _, a, bt, c, g, gt, jt, K (+4 more)

### Community 19 - ".decodeRow"
Cohesion: 0.09
Nodes (3): dt, nt, x

### Community 20 - "Jn"
Cohesion: 0.08
Nodes (3): H, Jn, W

### Community 21 - ".getContext"
Cohesion: 0.10
Nodes (12): ao(), Bi(), Ci(), co(), cs, Do(), Fi(), inXRange() (+4 more)

### Community 22 - "Category"
Cohesion: 0.07
Nodes (15): CategoryController, PositionController, Category, Leave, Position, DatabaseSeeder, DummyEmployeeSeeder, Illuminate\Database\Eloquent\Factories\HasFactory (+7 more)

### Community 23 - ".get"
Cohesion: 0.07
Nodes (4): fr, ge, le, Xr

### Community 24 - "r"
Cohesion: 0.07
Nodes (3): b, l, r()

### Community 26 - "devDependencies"
Cohesion: 0.06
Nodes (31): alpinejs, autoprefixer, chart.js, concurrently, html5-qrcode, laravel-vite-plugin, dependencies, chart.js (+23 more)

### Community 27 - ".hide"
Cohesion: 0.12
Nodes (6): ao, io(), no(), oo, Us(), Ys()

### Community 29 - "bootstrap.bundle.min.js"
Cohesion: 0.06
Nodes (50): Ae(), be(), Ce(), D(), De(), di(), $e(), Ee() (+42 more)

### Community 31 - "ht"
Cohesion: 0.08
Nodes (6): ht, kt, Qt, vt, xt, zt

### Community 33 - "Setting"
Cohesion: 0.08
Nodes (4): SettingController, Setting, Illuminate\Support\Facades\Storage, AttendanceQualityTest

### Community 34 - "gr"
Cohesion: 0.15
Nodes (4): br(), gr, mr, Vr

### Community 35 - "or"
Cohesion: 0.19
Nodes (3): ir(), or, rr()

### Community 36 - "update"
Cohesion: 0.14
Nodes (12): b(), beforeLayout(), buildTicks(), eo(), g(), Go(), init(), g() (+4 more)

### Community 37 - "jt"
Cohesion: 0.10
Nodes (10): ri(), color(), It(), jt(), kt(), mt(), qt(), _t() (+2 more)

### Community 45 - ".arraycopy"
Cohesion: 0.07
Nodes (3): st, w, yt

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

### Community 59 - ".getProps"
Cohesion: 0.21
Nodes (13): average(), dataset(), getCenterPoint(), index(), nearest(), Re(), s(), to() (+5 more)

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

### Community 71 - ".charAt"
Cohesion: 0.13
Nodes (3): ct, pt, ut

### Community 73 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 75 - ".buildOrUpdateControllers"
Cohesion: 0.21
Nodes (3): addElements(), removeBox(), stop()

### Community 80 - "i"
Cohesion: 0.10
Nodes (21): bs(), ct(), dn(), e(), fe(), ge(), K(), ks() (+13 more)

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
- **59 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_` connect `_` to `f`, `rt`, `j`, `.append`, `.toString`, `.decodeRow`, `.get`, `r`, `pr`, `p`, `.getX`, `ht`, `ke`, `gr`, `or`, `.getValue`, `me`, `cr`, `be`, `.encode`, `.arraycopy`, `ie`, `sr`, `.encode`, `et`, `.getY`, `N`, `je`, `Q`, `.decode`, `e`, `de`, `ye`, `.decode`, `ee`, `ae`, `.charAt`, `T`, `ce`, `Qe`, `.runEuclideanAlgorithm`, `.getSize`, `lt`, `tt`, `xe`, `it`, `ne`, `O`, `ar`, `Nr`, `ot`, `dr`, `oe`, `se`, `wt`?**
  _High betweenness centrality (0.316) - this node is a cross-community bridge._
- **Why does `e()` connect `e` to `.decode`, `f`, `.arraycopy`, `i`, `.toString`, `_`, `.getVersionForNumber`, `r`?**
  _High betweenness centrality (0.214) - this node is a cross-community bridge._
- **Why does `i()` connect `i` to `tn`, `chart.min.js`, `a`, `n`, `update`, `_`, `.getContext`, `e`?**
  _High betweenness centrality (0.211) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _84 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `tn` be split into smaller, more focused modules?**
  _Cohesion score 0.03997715591090805 - nodes in this community are weakly interconnected._
- **Should `chart.min.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02937132858392701 - nodes in this community are weakly interconnected._
- **Should `a` be split into smaller, more focused modules?**
  _Cohesion score 0.08181818181818182 - nodes in this community are weakly interconnected._
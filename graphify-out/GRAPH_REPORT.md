# Graph Report - absen-qrcode  (2026-09-28)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 2900 nodes · 6891 edges · 208 communities (143 shown, 65 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 162 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- tn
- chart.min.js
- s
- n
- xt
- f
- rt
- Controller
- l
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
- Jn
- .getContext
- Category
- .getHeight
- r
- pr
- devDependencies
- .hide
- ht
- bootstrap.bundle.min.js
- fe
- .parseInformation
- ke
- Illuminate\Http\Request
- gr
- or
- parse
- jt
- xt
- ze
- qi
- .get
- cr
- be
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
- .getY
- p
- je
- Q
- inRange
- .decode
- e
- User
- EmployeeImport
- scripts
- Es
- .getX
- ye
- TestCase
- ee
- .getCount
- .charAt
- T
- composer.json
- qn
- .substring
- st
- ce
- Illuminate\Support\Str
- he
- i
- at
- lt
- .runEuclideanAlgorithm
- xe
- User.php
- Carbon
- ae
- require
- us
- it
- ge
- ne
- O
- ar
- AutoBackupMiddleware.php
- require-dev
- hs
- Nr
- setup
- EmailVerificationTest.php
- PasswordResetTest
- ot
- AppServiceProvider
- config
- bt
- dr
- Illuminate\Support\Facades\Hash
- NativeAppServiceProvider
- le
- oe
- AuthenticationTest
- psr-4
- logging.php
- se
- PasswordConfirmationTest
- post-create-project-cmd
- ExampleTest
- profile/edit.blade.php
- permission.php
- jt
- wt
- excel.php
- console.php
- autoload-dev

## God Nodes (most connected - your core abstractions)
1. `tn` - 124 edges
2. `_` - 110 edges
3. `f` - 60 edges
4. `n()` - 59 edges
5. `User` - 49 edges
6. `s()` - 45 edges
7. `Employee` - 43 edges
8. `cs` - 38 edges
9. `sr` - 36 edges
10. `a()` - 36 edges

## Surprising Connections (you probably didn't know these)
- `AttendanceQualityTest` --references--> `Category`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/Category.php
- `AttendanceQualityTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/User.php
- `AttendanceQualityTest` --references--> `Employee`  [EXTRACTED]
  tests/Feature/AttendanceQualityTest.php → app/Models/Employee.php
- `e()` --indirect_call--> `i()`  [INFERRED]
  public/assets/js/html5-qrcode.min.js → public/assets/js/chart.min.js
- `updateElements()` --indirect_call--> `k()`  [INFERRED]
  public/assets/js/chart.min.js → public/assets/js/bootstrap.bundle.min.js

## Import Cycles
- None detected.

## Communities (208 total, 65 thin omitted)

### Community 0 - "tn"
Cohesion: 0.04
Nodes (16): addBox(), afterDatasetsUpdate(), d(), generateLabels(), Ie(), ke(), kn(), onClick() (+8 more)

### Community 1 - "chart.min.js"
Cohesion: 0.03
Nodes (59): ai(), at(), b(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw(), beforeUpdate(), Bt() (+51 more)

### Community 2 - "s"
Cohesion: 0.05
Nodes (37): a(), Be(), beforeLayout(), bo, determineDataLimits(), Di(), eo(), g() (+29 more)

### Community 3 - "n"
Cohesion: 0.06
Nodes (14): e(), ei(), en, fn(), gn(), je(), n(), qe() (+6 more)

### Community 4 - "xt"
Cohesion: 0.08
Nodes (7): cn, an(), as(), ln(), on, ts(), xt

### Community 7 - "Controller"
Cohesion: 0.07
Nodes (26): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+18 more)

### Community 8 - "l"
Cohesion: 0.06
Nodes (25): Ae(), afterDraw(), afterEvent(), afterUpdate(), Ba(), configure(), f(), gi() (+17 more)

### Community 9 - "Employee"
Cohesion: 0.10
Nodes (17): DashboardController, EmployeeController, ReportController, StatisticController, Attendance, Employee, Setting, AttendanceService (+9 more)

### Community 10 - "cs"
Cohesion: 0.10
Nodes (4): cs, getSelectorFromElement(), es(), is()

### Community 11 - "j"
Cohesion: 0.12
Nodes (3): d, gt, j

### Community 12 - ".append"
Cohesion: 0.11
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
Cohesion: 0.11
Nodes (19): aa(), Bn(), _calculateBarIndexPixels(), _calculateBarValuePixels(), _getAxis(), _getAxisCount(), getBasePixel(), getFirstScaleIdForIndexAxis() (+11 more)

### Community 18 - "_"
Cohesion: 0.08
Nodes (11): _, a, c, g, h, I, K, s (+3 more)

### Community 19 - ".decodeRow"
Cohesion: 0.08
Nodes (3): mt, st, x

### Community 20 - "Jn"
Cohesion: 0.08
Nodes (3): H, Jn, W

### Community 21 - ".getContext"
Cohesion: 0.11
Nodes (13): ao(), Bi(), Ci(), co(), cs, da(), Do(), Fi() (+5 more)

### Community 22 - "Category"
Cohesion: 0.09
Nodes (8): CategoryController, PositionController, Category, Position, DatabaseSeeder, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\HasMany

### Community 24 - "r"
Cohesion: 0.06
Nodes (4): b, l, Qe, r()

### Community 26 - "devDependencies"
Cohesion: 0.06
Nodes (31): alpinejs, autoprefixer, chart.js, concurrently, html5-qrcode, laravel-vite-plugin, dependencies, chart.js (+23 more)

### Community 27 - ".hide"
Cohesion: 0.08
Nodes (7): ao, Q, io(), no(), oo, Us(), Ys()

### Community 29 - "bootstrap.bundle.min.js"
Cohesion: 0.06
Nodes (50): Ae(), be(), Ce(), D(), De(), di(), $e(), Ee() (+42 more)

### Community 31 - ".parseInformation"
Cohesion: 0.13
Nodes (4): Qt, vt, xt, zt

### Community 33 - "Illuminate\Http\Request"
Cohesion: 0.11
Nodes (6): AttendanceController, SettingController, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 34 - "gr"
Cohesion: 0.15
Nodes (4): br(), gr, mr, Vr

### Community 35 - "or"
Cohesion: 0.12
Nodes (3): ir(), or, rr()

### Community 36 - "parse"
Cohesion: 0.14
Nodes (10): buildTicks(), ii(), init(), mo(), parse(), parseArrayData(), parseObjectData(), parsePrimitiveData() (+2 more)

### Community 37 - "jt"
Cohesion: 0.11
Nodes (8): ri(), color(), jt(), kt(), qt(), _t(), te(), wt()

### Community 47 - "remove"
Cohesion: 0.14
Nodes (5): d(), on(), remove(), Nn(), wn()

### Community 50 - "LoginRequest"
Cohesion: 0.16
Nodes (7): LoginRequest, ProfileUpdateRequest, Illuminate\Auth\Events\Lockout, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Support\Facades\RateLimiter, Illuminate\Validation\Rule

### Community 51 - "Ks"
Cohesion: 0.21
Nodes (3): getElementFromSelector(), Ks, Fs()

### Community 59 - "inRange"
Cohesion: 0.17
Nodes (14): average(), dataset(), getCenterPoint(), index(), inRange(), nearest(), qi(), s() (+6 more)

### Community 61 - "e"
Cohesion: 0.17
Nodes (3): constructor(), e(), lr

### Community 62 - "User"
Cohesion: 0.19
Nodes (4): UserController, User, Illuminate\Foundation\Auth\User, ProfileTest

### Community 63 - "EmployeeImport"
Cohesion: 0.21
Nodes (10): EmployeeImport, Maatwebsite\Excel\Concerns\Importable, Maatwebsite\Excel\Concerns\SkipsErrors, Maatwebsite\Excel\Concerns\SkipsFailures, Maatwebsite\Excel\Concerns\SkipsOnError, Maatwebsite\Excel\Concerns\SkipsOnFailure, Maatwebsite\Excel\Concerns\ToModel, Maatwebsite\Excel\Concerns\WithHeadingRow (+2 more)

### Community 64 - "scripts"
Cohesion: 0.13
Nodes (16): scripts, dev, native:dev, post-autoload-dump, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout (+8 more)

### Community 66 - ".getX"
Cohesion: 0.14
Nodes (3): de, dt, re

### Community 68 - "TestCase"
Cohesion: 0.21
Nodes (6): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, RegistrationTest, EmployeeTemplateDownloadTest, ExampleTest, TestCase

### Community 73 - "composer.json"
Cohesion: 0.14
Nodes (13): description, extra, laravel, keywords, dont-discover, license, minimum-stability, name (+5 more)

### Community 78 - "Illuminate\Support\Str"
Cohesion: 0.20
Nodes (5): DummyAttendanceSeeder, DummyEmployeeSeeder, Illuminate\Database\Seeder, Illuminate\Support\Str, Pdo\Mysql

### Community 80 - "i"
Cohesion: 0.09
Nodes (17): bs(), ce(), ct(), de, dt(), ge(), he(), ks() (+9 more)

### Community 85 - "User.php"
Cohesion: 0.22
Nodes (7): UserFactory, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Notifications\Notifiable, Spatie\Permission\Traits\HasRoles, static

### Community 86 - "Carbon"
Cohesion: 0.13
Nodes (5): LeaveController, Leave, Carbon, Illuminate\Database\Eloquent\Factories\HasFactory, AttendanceQualityTest

### Community 89 - "require"
Cohesion: 0.20
Nodes (10): require, barryvdh/laravel-dompdf, doctrine/dbal, laravel/framework, laravel/tinker, maatwebsite/excel, milon/barcode, nativephp/electron (+2 more)

### Community 96 - "AutoBackupMiddleware.php"
Cohesion: 0.36
Nodes (4): AutoBackupMiddleware, Closure, Illuminate\Support\Facades\File, Symfony\Component\HttpFoundation\Response

### Community 97 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/breeze, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 100 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 101 - "EmailVerificationTest.php"
Cohesion: 0.25
Nodes (4): Illuminate\Auth\Events\Verified, Illuminate\Support\Facades\Event, Illuminate\Support\Facades\URL, EmailVerificationTest

### Community 102 - "PasswordResetTest"
Cohesion: 0.25
Nodes (3): Illuminate\Auth\Notifications\ResetPassword, Illuminate\Support\Facades\Notification, PasswordResetTest

### Community 104 - "AppServiceProvider"
Cohesion: 0.33
Nodes (3): AppServiceProvider, Illuminate\Pagination\Paginator, Illuminate\Support\ServiceProvider

### Community 105 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 109 - "NativeAppServiceProvider"
Cohesion: 0.40
Nodes (3): NativeAppServiceProvider, Native\Laravel\Contracts\ProvidesPhpIni, Native\Laravel\Facades\Window

### Community 113 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 114 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 118 - "post-create-project-cmd"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 120 - "profile/edit.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 121 - "permission.php"
Cohesion: 0.50
Nodes (3): Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission, Spatie\Permission\Models\Role

### Community 207 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

## Knowledge Gaps
- **73 isolated node(s):** `K`, `composer install`, `npm install --ignore-scripts`, `npm run build`, `@php artisan key:generate` (+68 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **65 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_` connect `_` to `f`, `rt`, `j`, `.append`, `.toString`, `.decodeRow`, `.getHeight`, `r`, `pr`, `ht`, `fe`, `.parseInformation`, `ke`, `gr`, `or`, `ze`, `.get`, `cr`, `be`, `.encode`, `w`, `ie`, `sr`, `.getSize`, `et`, `.getY`, `p`, `je`, `Q`, `.decode`, `e`, `.getX`, `ye`, `ee`, `.getCount`, `.charAt`, `T`, `.substring`, `ce`, `he`, `at`, `lt`, `.runEuclideanAlgorithm`, `xe`, `ae`, `it`, `ge`, `ne`, `O`, `ar`, `Nr`, `ot`, `bt`, `dr`, `le`, `oe`, `se`, `jt`, `wt`?**
  _High betweenness centrality (0.321) - this node is a cross-community bridge._
- **Why does `e()` connect `e` to `f`, `.get`, `.encode`, `w`, `i`, `.toString`, `_`, `.decodeRow`, `r`?**
  _High betweenness centrality (0.229) - this node is a cross-community bridge._
- **Why does `i()` connect `i` to `tn`, `chart.min.js`, `s`, `n`, `l`, `bt`, `.getContext`, `inRange`, `e`?**
  _High betweenness centrality (0.225) - this node is a cross-community bridge._
- **What connects `K`, `composer install`, `npm install --ignore-scripts` to the rest of the system?**
  _73 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `tn` be split into smaller, more focused modules?**
  _Cohesion score 0.03557422969187675 - nodes in this community are weakly interconnected._
- **Should `chart.min.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02780952380952381 - nodes in this community are weakly interconnected._
- **Should `s` be split into smaller, more focused modules?**
  _Cohesion score 0.05257936507936508 - nodes in this community are weakly interconnected._
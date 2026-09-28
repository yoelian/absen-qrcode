<x-guest-layout>
    <div class="text-center">
        <div class="auth-brand-logo mx-auto">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        <h2 class="auth-title">Masuk ke Portal</h2>
        <p class="auth-subtitle">Sistem Informasi & Presensi Digital</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success border-0 rounded-4 mb-4 py-2 px-3 small text-white bg-success bg-opacity-75">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 mb-4 py-2 px-3 small text-white bg-danger bg-opacity-75">
            <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-group-modern">
            <label class="form-label" for="email">
                <i class="bi bi-envelope me-1 text-info"></i> Alamat Email
            </label>
            <div class="position-relative">
                <i class="bi bi-person input-icon"></i>
                <input id="email" class="form-control-modern" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@instansi.sch.id">
            </div>
        </div>

        <!-- Password -->
        <div class="input-group-modern">
            <label class="form-label d-flex justify-content-between align-items-center" for="password">
                <span><i class="bi bi-key me-1 text-warning"></i> Kata Sandi</span>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted hover-light" style="font-size: 0.8rem;">Lupa sandi?</a>
                @endif
            </label>
            <div class="position-relative">
                <i class="bi bi-lock input-icon"></i>
                <input id="password" class="form-control-modern" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y text-muted pe-3 border-0 bg-transparent" onclick="togglePasswordVisibility()" style="z-index: 10;">
                    <i id="togglePasswordIcon" class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me & Submit -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted" for="remember_me">
                    Ingat Saya
                </label>
            </div>
        </div>

        <button type="submit" class="btn-modern-primary">
            <span>Masuk Sekarang</span>
            <i class="bi bi-arrow-right-short fs-4"></i>
        </button>
    </form>

    <!-- Quick Demo Accounts -->
    <div class="demo-badge">
        <span><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Akun Cepat:</span>
        <div class="d-flex gap-2">
            <span class="demo-pill" onclick="fillDemo('admin@absenpro.local', 'password')" title="Klik untuk isi Admin">Admin</span>
            <span class="demo-pill" onclick="fillDemo('operator@absenpro.local', 'password')" title="Klik untuk isi Operator">Operator</span>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('scan') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Layar Pindai Presensi
        </a>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                passInput.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>

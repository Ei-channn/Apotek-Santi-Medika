<!DOCTYPE html>
<html>

<head>
    <title>Apotek Santi Medika</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-apotek.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <!-- Background Decorations -->
    <div class="bg-decoration"></div>
    <div class="bg-decoration"></div>
    <div class="bg-decoration"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-header">
            <div class="logo-circle">💊</div>
            <h1>Apotek Santi Medika</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        <!-- Error Alert (Hidden by default, show when needed) -->
        <!-- <div class="alert alert-error">
            <span>Email atau password salah!</span>
        </div> -->

        <form action="/login" method="POST" class="login-form" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    {{-- <span class="input-icon">📧</span> --}}
                    <input type="email" name="email" id="email" placeholder="Email" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    {{-- <span class="input-icon">🔒</span> --}}
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">
                    <button type="button" class="password-toggle" id="togglePassword">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                Masuk
            </button>
        </form>

        <div class="divider">
            <span>atau</span>
        </div>

        <div class="register-link">
            Belum punya akun? <a href="#">pulang aja sana</a>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        // Form Submission
        // const loginForm = document.getElementById('loginForm');
        // const loginBtn = document.getElementById('loginBtn');

        // loginForm.addEventListener('submit', function(e) {
        //     // For demo - remove in production
        //     e.preventDefault();

        //     // Add loading state
        //     loginBtn.classList.add('loading');
        //     loginBtn.disabled = true;

        //     // Simulate API call (remove in production)
        //     setTimeout(() => {
        //         loginBtn.classList.remove('loading');
        //         loginBtn.disabled = false;

        //         // Show success (for demo)
        //         const alert = document.createElement('div');
        //         alert.className = 'alert alert-success';
        //         alert.innerHTML = '<span>Login berhasil! Mengalihkan...</span>';
        //         loginForm.insertBefore(alert, loginForm.firstChild);

        //         // Redirect (uncomment in production)
        //         // window.location.href = '/dashboard';
        //     }, 1500);
        // });

        // Input Animation on Focus
        // const inputs = document.querySelectorAll('.form-group input');
        // inputs.forEach(input => {
        //     input.addEventListener('focus', function() {
        //         this.parentElement.style.transform = 'translateY(-2px)';
        //     });

        //     input.addEventListener('blur', function() {
        //         this.parentElement.style.transform = 'translateY(0)';
        //     });
        // });

        // Prevent multiple submissions
        // loginForm.addEventListener('submit', function() {
        //     loginBtn.disabled = true;
        //     setTimeout(() => {
        //         loginBtn.disabled = false;
        //     }, 2000);
        // });
    </script>

</body>

</html>

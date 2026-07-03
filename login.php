<?php
// login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';

// Redirect jika sudah login
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->login($_POST['username'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Setoran Hafalan Al-Qur'an</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ═══════════════════════════════════════
           MAIN WRAPPER — split screen
        ═══════════════════════════════════════ */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════════════════════════
           LEFT PANEL — hero / branding
        ═══════════════════════════════ */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(155deg, #0A3D22 0%, #1B8A4E 45%, #4CAF82 80%, #A8E6C3 100%);
        }

        /* Geometric ornament shapes – left panel */
        .left-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .ls-1 {
            width: 500px; height: 500px;
            top: -160px; left: -160px;
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 65%);
            animation: lsFloat1 20s infinite alternate ease-in-out;
        }
        .ls-2 {
            width: 400px; height: 400px;
            bottom: -130px; right: -120px;
            background: radial-gradient(circle, rgba(15,92,51,0.50) 0%, transparent 65%);
            animation: lsFloat2 26s infinite alternate ease-in-out;
        }
        .ls-3 {
            width: 260px; height: 260px;
            top: 55%; left: 65%;
            background: radial-gradient(circle, rgba(168,230,195,0.22) 0%, transparent 65%);
            animation: lsFloat3 17s infinite alternate ease-in-out;
        }
        /* Islamic arch decoration */
        .arch-ornament {
            position: absolute;
            width: 300px; height: 300px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
            z-index: 0;
        }
        .arch-ornament::before {
            content: '';
            position: absolute;
            inset: 18px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .arch-ornament::after {
            content: '';
            position: absolute;
            inset: 36px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.05);
        }

        @keyframes lsFloat1 { 0% { transform:translate(0,0); } 100% { transform:translate(-60px,50px); } }
        @keyframes lsFloat2 { 0% { transform:translate(0,0); } 100% { transform:translate(60px,-50px); } }
        @keyframes lsFloat3 { 0% { transform:scale(1); } 100% { transform:scale(1.25) translate(-20px,15px); } }

        /* Left panel content */
        .left-content {
            position: relative;
            z-index: 5;
            text-align: center;
            max-width: 380px;
        }

        .hero-logo {
            width: 130px; height: 130px;
            object-fit: contain;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            padding: 10px;
            margin-bottom: 28px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.15),
                0 20px 50px rgba(0,0,0,0.20),
                0 0 60px rgba(168,230,195,0.20);
            animation: heroPop 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
        }
        @keyframes heroPop {
            0%   { opacity:0; transform: scale(0.75) translateY(20px); }
            100% { opacity:1; transform: scale(1) translateY(0); }
        }

        .hero-logo-fallback {
            width: 130px; height: 130px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.20);
            display: flex; align-items:center; justify-content:center;
            margin: 0 auto 28px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.20);
            animation: heroPop 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
        }

        .hero-title {
            color: #fff;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
            animation: fadeSlide 0.7s 0.2s ease forwards;
            opacity: 0;
        }
        .hero-subtitle {
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 36px;
            animation: fadeSlide 0.7s 0.35s ease forwards;
            opacity: 0;
        }
        @keyframes fadeSlide {
            0%   { opacity:0; transform: translateY(16px); }
            100% { opacity:1; transform: translateY(0); }
        }

        /* Feature pills */
        .feature-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            animation: fadeSlide 0.7s 0.5s ease forwards;
            opacity: 0;
        }
        .feature-pill {
            display: flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 30px;
            padding: 7px 16px;
            color: rgba(255,255,255,0.90);
            font-size: 0.82rem;
            font-weight: 500;
            backdrop-filter: blur(8px);
        }
        .feature-pill i { font-size: 0.95rem; color: #A8E6C3; }

        /* Quote strip */
        .hero-quote {
            position: absolute;
            bottom: 28px;
            left: 0; right: 0;
            text-align: center;
            color: rgba(255,255,255,0.50);
            font-size: 0.75rem;
            font-style: italic;
            letter-spacing: 0.02em;
            z-index: 5;
        }

        /* ═══════════════════════════════
           RIGHT PANEL — form
        ═══════════════════════════════ */
        .login-right {
            width: 460px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #f7fbf9;
            position: relative;
            overflow-y: auto;
        }

        /* Subtle green tint top-right corner */
        .login-right::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(76,175,130,0.10) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-right::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(27,138,78,0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .form-panel {
            width: 100%;
            max-width: 360px;
            position: relative;
            z-index: 2;
            animation: slideUp 0.7s 0.1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(28px);
        }
        @keyframes slideUp { to { opacity:1; transform:translateY(0); } }

        /* Mini logo + welcome text top of form */
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-logo-mini {
            width: 56px; height: 56px;
            object-fit: contain;
            border-radius: 50%;
            background: #e8f5ee;
            padding: 6px;
            margin-bottom: 14px;
            box-shadow: 0 4px 14px rgba(27,138,78,0.18);
        }
        .form-logo-mini-fallback {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1B8A4E, #4CAF82);
            display: flex; align-items:center; justify-content:center;
            margin: 0 auto 14px;
            box-shadow: 0 4px 14px rgba(27,138,78,0.30);
        }
        .form-welcome { color: #0D3320; font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
        .form-tagline { color: #4A7A60; font-size: 0.85rem; min-height: 24px; }

        /* Divider */
        .form-divider {
            display: flex; align-items: center; gap: 10px;
            margin: 20px 0;
            color: #A8C5B5;
            font-size: 0.75rem;
        }
        .form-divider::before, .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #D4EDE1;
        }

        /* Form controls */
        .form-floating > .form-control {
            background: #fff;
            border: 1.5px solid #C3E0CC;
            border-radius: 12px;
            color: #0D3320;
            font-size: 0.93rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.25s ease;
            height: 58px;
        }
        .form-floating > .form-control:focus {
            background: #fff;
            border-color: #1B8A4E;
            box-shadow: 0 0 0 3px rgba(27,138,78,0.15);
            outline: none;
        }
        .form-floating > label {
            color: #6B9F85;
            font-size: 0.88rem;
            font-family: 'Outfit', sans-serif;
        }
        .form-floating > .form-control::placeholder { color: transparent; }

        /* Login button */
        .btn-login {
            background: linear-gradient(135deg, #0F5C33 0%, #1B8A4E 50%, #4CAF82 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            padding: 14px 20px;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(27,138,78,0.35);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            cursor: pointer;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(27,138,78,0.50);
            color: #fff;
        }
        .btn-login:active { transform: translateY(0); }

        /* Ripple effect */
        .ripple {
            position: absolute;
            background: rgba(255,255,255,0.35);
            border-radius: 50%;
            transform: scale(0);
            animation: rippleAnim 0.6s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* Error alert */
        .alert-login-error {
            background: rgba(220,53,69,0.07);
            border: 1px solid rgba(220,53,69,0.22);
            border-radius: 12px;
            color: #9B1C2A;
            font-size: 0.86rem;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Credential hint */
        .cred-box {
            margin-top: 18px;
            padding: 11px 16px;
            background: #EEF8F3;
            border: 1px solid #C3E0CC;
            border-radius: 12px;
            font-size: 0.76rem;
            color: #2B6040;
        }
        .cred-box strong { color: #0F5C33; }

        /* Typing cursor */
        .cursor {
            display: inline-block;
            width: 2px;
            background: #4CAF82;
            margin-left: 1px;
            animation: blink 0.8s infinite;
        }
        @keyframes blink { 50% { opacity: 0; } }

        /* ── Responsive: stack on small screens ── */
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- ══════════════════════════════════
         LEFT PANEL — Hero Branding
    ══════════════════════════════════ -->
    <div class="login-left">
        <!-- Floating shapes -->
        <div class="left-shape ls-1"></div>
        <div class="left-shape ls-2"></div>
        <div class="left-shape ls-3"></div>
        <div class="arch-ornament"></div>

        <!-- Main content -->
        <div class="left-content">
            <!-- Logo -->
            <img src="assets/images/logo.png"
                 alt="Logo Setoran Hafalan"
                 class="hero-logo"
                 onerror="this.style.display='none'; document.getElementById('hero-logo-fb').style.display='flex';">
            <div class="hero-logo-fallback" id="hero-logo-fb" style="display:none;">
                <i class="bi bi-journal-bookmark-fill text-white" style="font-size:3.2rem;"></i>
            </div>

            <h1 class="hero-title">Sistem Setoran<br>Hafalan Al-Qur'an</h1>
            <p class="hero-subtitle">
                Platform digital untuk mencatat, memantau,<br>dan membina hafalan santri secara modern.
            </p>

            <!-- Feature pills -->
            <div class="feature-pills">
                <div class="feature-pill"><i class="bi bi-journal-check"></i> Catat Setoran</div>
                <div class="feature-pill"><i class="bi bi-graph-up-arrow"></i> Pantau Progress</div>
                <div class="feature-pill"><i class="bi bi-people-fill"></i> Kelola Santri</div>
                <div class="feature-pill"><i class="bi bi-shield-check-fill"></i> Role Based Access</div>
            </div>
        </div>

        <!-- Quote at bottom -->
        <p class="hero-quote">
            "Sebaik-baik kalian adalah yang belajar Al-Qur'an dan mengajarkannya." — HR. Bukhari
        </p>
    </div>

    <!-- ══════════════════════════════════
         RIGHT PANEL — Form Login
    ══════════════════════════════════ -->
    <div class="login-right">
        <div class="form-panel">

            <!-- Form Header -->
            <div class="form-header">
                <img src="assets/images/logo.png"
                     alt="Logo"
                     class="form-logo-mini"
                     onerror="this.style.display='none'; document.getElementById('mini-logo-fb').style.display='flex';">
                <div class="form-logo-mini-fallback" id="mini-logo-fb" style="display:none;">
                    <i class="bi bi-journal-bookmark-fill text-white" style="font-size:1.5rem;"></i>
                </div>
                <h4 class="form-welcome">Selamat Datang!</h4>
                <p id="typing-text" class="form-tagline font-monospace"
                   data-texts='["Sistem Catatan Hafalan", "Kemudahan Monitor Santri", "Desain Modern & Islami"]'></p>
            </div>

            <!-- Divider -->
            <div class="form-divider">masuk dengan akun Anda</div>

            <!-- Error Alert -->
            <?php if (isset($_SESSION['toast']) && $_SESSION['toast']['type'] === 'error'): ?>
                <div class="alert-login-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><?= htmlspecialchars($_SESSION['toast']['message']) ?></span>
                </div>
                <?php unset($_SESSION['toast']); ?>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="login.php" method="POST" id="loginForm" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" name="username" class="form-control"
                           id="floatingUsername" placeholder="Username"
                           required autocomplete="username">
                    <label for="floatingUsername">
                        <i class="bi bi-person me-1"></i> Username
                    </label>
                    <div class="invalid-feedback">Username tidak boleh kosong.</div>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control"
                           id="floatingPassword" placeholder="Password"
                           required autocomplete="current-password">
                    <label for="floatingPassword">
                        <i class="bi bi-lock me-1"></i> Password
                    </label>
                    <div class="invalid-feedback">Password tidak boleh kosong.</div>
                </div>

                <button type="submit" class="btn-login d-flex align-items-center justify-content-center gap-2" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <!-- Credential hint -->
            <div class="cred-box mt-3">
                <div class="mb-1 fw-semibold" style="color:#0F5C33; font-size:0.77rem;">
                    <i class="bi bi-info-circle me-1"></i> Akun Demo
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>Ustad :</strong> ustad / ustad123</span>
                    <span><strong>Mhs :</strong> najib / mhs123</span>
                </div>
            </div>

            <!-- Footer text -->
            <p class="text-center mt-4 mb-0" style="color:#A8C5B5; font-size:0.73rem;">
                © <?= date('Y') ?> Sistem Setoran Hafalan Al-Qur'an
            </p>
        </div>
    </div>

</div><!-- /.login-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    // Ripple on login button
    document.getElementById('loginBtn').addEventListener('click', function(e) {
        let rect = this.getBoundingClientRect();
        let ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.cssText = `left:${e.clientX-rect.left}px;top:${e.clientY-rect.top}px;width:20px;height:20px;margin-left:-10px;margin-top:-10px;`;
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });

    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
</script>
</body>
</html>

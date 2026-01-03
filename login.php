<?php
session_start();
include "inc/koneksi.php";

if (isset($_SESSION['ses_username'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($koneksi, "
        SELECT anggota.*, level_pengguna.nama_level
        FROM anggota
        JOIN level_pengguna ON anggota.id_level = level_pengguna.id_level
        WHERE username='$username' AND password='$password'
    ");

    if (mysqli_num_rows($q) == 1) {
        $d = mysqli_fetch_assoc($q);

        $_SESSION['ses_id']       = $d['id_anggota'];
        $_SESSION['ses_username'] = $d['username'];
        $_SESSION['ses_nama']     = $d['nama'];
        $_SESSION['ses_level']    = $d['nama_level'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Books */
        .book-bg {
            position: absolute;
            font-size: 60px;
            color: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite;
        }

        .book-bg:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .book-bg:nth-child(2) { top: 20%; right: 15%; animation-delay: 2s; }
        .book-bg:nth-child(3) { bottom: 15%; left: 20%; animation-delay: 4s; }
        .book-bg:nth-child(4) { bottom: 25%; right: 10%; animation-delay: 6s; }
        .book-bg:nth-child(5) { top: 50%; left: 5%; animation-delay: 8s; }
        .book-bg:nth-child(6) { top: 60%; right: 5%; animation-delay: 10s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
            position: relative;
            z-index: 10;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            animation: bounce 2s infinite;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-body {
            padding: 40px 30px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s;
            box-shadow: 0 4px 15px rgba(238, 90, 111, 0.3);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .error-message i {
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #667eea;
            font-size: 18px;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control:focus + .input-icon {
            color: #667eea;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            color: #95a5a6;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s;
            z-index: 1;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #7f8c8d;
            font-size: 13px;
        }

        .login-footer i {
            color: #e74c3c;
            margin: 0 3px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .welcome-text h2 {
                font-size: 20px;
            }
        }

        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Animated Background Books -->
    <i class="fas fa-book book-bg"></i>
    <i class="fas fa-book-open book-bg"></i>
    <i class="fas fa-bookmark book-bg"></i>
    <i class="fas fa-book-reader book-bg"></i>
    <i class="fas fa-glasses book-bg"></i>
    <i class="fas fa-graduation-cap book-bg"></i>

    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h1>Sistem Informasi Perpustakaan</h1>
            <p>Selamat Datang di Portal Perpustakaan</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <div class="welcome-text">
                <h2>Masuk ke Akun Anda</h2>
                <p>Silakan masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <?php if ($error != "") { ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $error ?></span>
            </div>
            <?php } ?>

            <form method="post" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            class="form-control" 
                            placeholder="Masukkan username Anda"
                            required
                            autocomplete="username"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Masukkan password Anda"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>Dibuat dengan <i class="fas fa-heart"></i> untuk Perpustakaan</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add loading animation on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.classList.add('loading');
            btn.querySelector('span').textContent = 'Memproses...';
        });

        // Auto focus on username field
        window.addEventListener('load', function() {
            document.getElementById('username').focus();
        });
    </script>

</body>
</html>
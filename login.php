<?php
session_start();
require 'connect.php';

if (isset($_POST['submit'])) {
    unset($_SESSION['error'], $_SESSION['error-no-login']);

   
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password']; 

    // Query ambil user (lengkap: user_id, password, role, nama)
    $query = "SELECT user_id, password, role, nama FROM pengguna WHERE nama = '$username' ";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password dengan hash dari database
        if (password_verify($password, $user['password'])) {
            // Login sukses
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];

            if ($user['role'] == 'Admin') {
                header('Location: admin.php');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['error'] = "Login gagal! Username atau password salah.";
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Login gagal! Username atau password salah.";
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        h1 {
            color: #343a40;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-login {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        .btn-login:hover {
            background-color: #0056b3;
        }
        
        .btn-login:active {
            background-color: #004085;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 12px 15px;
            margin-bottom: 20px;
            text-align: left;
            font-weight: 500;
        }
        
        .system-name {
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 25px;
                margin: 10px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Sistem Inventaris Barang</h1>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo '<div class="alert-error">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['error-no-login'])) {
            echo '<div class="alert-error">'.$_SESSION['error-no-login'].'</div>';
            unset($_SESSION['error-no-login']);
        }
        ?>
        
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" name="submit" class="btn-login">Login</button>
        </form>
        
        <div class="system-name">
            RAKA INVENTARIS BARANG &copy; <?php echo date('Y'); ?>
        </div>
    </div>
</body>
</html>
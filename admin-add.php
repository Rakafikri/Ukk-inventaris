<?php
session_start();
require('connect.php');

// Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: index.php');
    exit;
}

// Proses Tambah Data
if(isset($_POST['submit'])) {
    $nama     = $_POST['nama'];
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if(!empty($nama) && !empty($password) && !empty($role)) {
        $query = "INSERT INTO pengguna (nama, password, role) VALUES ('$nama', '$hashed_password', '$role')";
        $result = mysqli_query($connect, $query);

        if($result) {
            header('Location: admin.php');
        } else {
            echo "Gagal menambah user: " . mysqli_error($connect);
        }
    } else {
        echo "Semua data harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah User Baru</title>
</head>
<body>
    <h2>Tambah User Baru</h2>
    
    <form action="" method="POST">
        <label>Nama User:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Password:</label><br>
        <input type="text" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select><br><br>

        <button type="submit" name="submit">Simpan User</button>
        <a href="admin.php">Batal</a>
    </form>
</body>
</html>
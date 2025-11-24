<?php
session_start();
require('connect.php');

if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: index.php');
    exit;
}

if(!isset($_GET['user_id'])) {
    header('Location: admin.php');
    exit;
}

$id = $_GET['user_id'];

if(isset($_POST['update'])) {
    $nama     = $_POST['nama'];
    $role     = $_POST['role'];
    $password = $_POST['password'];

   if(!empty($password)) {
        $queryUpdate = "UPDATE pengguna SET nama='$nama', role='$role', password='$password' WHERE user_id='$id'";
    } else {
        // Kalo password kosong, update nama & role saja (Password lama tetap aman)
        $queryUpdate = "UPDATE pengguna SET nama='$nama', role='$role' WHERE user_id='$id'";
    }

    $result = mysqli_query($connect, $queryUpdate);

    if($result) {
        // Berhasil, balik ke admin
        header('Location: admin.php');
    } else {
        echo "Gagal update: " . mysqli_error($connect);
    }
}

// 4. Ambil Data Lama
$queryData = mysqli_query($connect, "SELECT * FROM pengguna WHERE user_id = '$id'");
$data = mysqli_fetch_assoc($queryData);

if(!$data) {
    echo "Data user tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit Data Pengguna</h2>
    
    <form action="" method="POST">
        <!-- NAMA -->
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required><br><br>

        <!-- PASSWORD -->
        <label>Password:</label><br>
        <input type="text" name="password" placeholder="Isi jika ingin ganti password"><br>
        <small><i>*Kosongkan jika tidak ingin mengubah password</i></small><br><br>

        <!-- ROLE -->
        <label>Role:</label><br>
        <select name="role">
            <option value="User" <?php if($data['role'] == 'User') echo 'selected'; ?>>User</option>
            <option value="Admin" <?php if($data['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
        </select><br><br>

        <!-- TOMBOL -->
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="admin.php">Batal</a>
    </form>
</body>
</html>
<?php
session_start();
require 'connect.php';

if(!isset($_SESSION['role'])) {
    $_SESSION['error-no-login'] = "Kamu harus login terlebih dahulu!";
    session_write_close(); 
    header('Location: login.php');
    exit;
}

if($_SESSION['role'] != "Admin") {
    $_SESSION['error-no-login'] = "Akses ditolak! Halaman untuk Admin saja.";
    session_write_close(); 
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; }
        h2 { margin-top: 30px; }
        
        /* Style Tombol Tambah */
        .btn-add {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .btn-add:hover { background-color: #0056b3; }

        /* Style Tombol Logout (Merah) */
        .btn-logout {
            float: right; /* Supaya ada di kanan */
            padding: 8px 15px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-logout:hover { background-color: #c82333; }

        /* Style Tombol Transaksi */
        .btn-transaksi {
            display: inline-block;
            padding: 8px 15px;
            background-color: #ffc107;
            color: #212529;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .btn-transaksi:hover { background-color: #e0a800; }

        /* Helper untuk judul biar sejajar */
        .header-container {
            overflow: hidden; /* Clear float */
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        /* Menu navigation */
        .menu-nav {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }
        .menu-nav a {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
        }
        .menu-nav a:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1 style="float: left; margin: 0;">Dashboard Admin</h1>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
    </div>
    
    <div class="menu-nav">
        <a href="admin-transaksi.php" class="btn-transaksi">Riwayat Transaksi</a>
        <a href="admin-add.php" class="btn-add">+ Tambah User</a>
        <a href="admin-add-barang.php" class="btn-add">+ Tambah Barang</a>
    </div>
    
    <h2>Data Pengguna</h2>
    
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Hapus</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql_users = mysqli_query($connect, "SELECT user_id, nama, `role` FROM pengguna ORDER BY user_id ASC");
                if(mysqli_num_rows($sql_users) > 0) {
                    while($user_data = mysqli_fetch_assoc($sql_users)) {
                        echo "<tr>";
                            echo "<td>".$user_data['user_id']."</td>";
                            echo "<td>".$user_data['nama']."</td>";
                            echo "<td>".$user_data['role']."</td>";
                            echo "<td class='delete-button'>";
                            echo "<a href='admin-delete.php?delete_user_id=".$user_data['user_id']."' class='btn'>HAPUS</a>" ;
                            echo "</td>";
                            echo "<td class='edit-button'>";
                            echo "<a href='admin-edit.php?user_id=".$user_data['user_id']."' class='btn'>Edit</a>" ;
                            echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Belum ada user terdaftar.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    <hr>
    <h2>Data Barang</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Hapus</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql_barang = mysqli_query($connect, "SELECT * FROM barang ORDER BY id_barang ASC");
                if(mysqli_num_rows($sql_barang) > 0) {
                    while($barang = mysqli_fetch_assoc($sql_barang)) {
                        echo "<tr>";
                            echo "<td>".$barang['id_barang']."</td>";
                            echo "<td>".$barang['nama_barang']."</td>";
                            echo "<td>".$barang['satuan_barang']."</td>";
                            echo "<td>".$barang['jumlah']."</td>";
                            echo "<td>".$barang['keterangan']."</td>";
                            
                            echo "<td class='delete-button'>";
                            echo "<a href='admin-delete-barang.php?delete_barang_id=".$barang['id_barang']."' class='btn'>HAPUS</a>";
                            echo "</td>";
                            
                            echo "<td class='edit-button'>";
                            echo "<a href='admin-edit-barang.php?edit_barang_id=".$barang['id_barang']."' class='btn'>Edit</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Belum ada barang terdaftar.</td></tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>
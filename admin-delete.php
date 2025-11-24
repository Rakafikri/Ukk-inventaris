<?php
session_start();
require('connect.php');

// Cek Login & Admin (Sama seperti admin.php)
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: index.php');
    exit;
}

// Proses Hapus
if (isset($_GET['delete_user_id'])) {
    $id = $_GET['delete_user_id'];
    
    // Query Hapus
    $delete = mysqli_query($connect, "DELETE FROM pengguna WHERE user_id = '$id'");

    if($delete) {
        // Jika berhasil, kembalikan ke halaman admin
        header('Location: admin.php'); 
    } else {
        echo "Gagal menghapus data: " . mysqli_error($connect);
    }
} else {
    header('Location: admin.php');
}
?>
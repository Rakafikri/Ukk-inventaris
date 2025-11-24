<?php
session_start();
require('connect.php');

// Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: index.php');
    exit;
}

// Proses Hapus Barang
if (isset($_GET['delete_barang_id'])) {
    $id = $_GET['delete_barang_id'];
    
    // Query Hapus
    $delete = mysqli_query($connect, "DELETE FROM barang WHERE id_barang = '$id'");

    if($delete) {
        header('Location: admin.php'); 
    } else {
        echo "Gagal menghapus data: " . mysqli_error($connect);
    }
} else {
    header('Location: admin.php');
}
?>
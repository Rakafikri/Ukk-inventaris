<?php
session_start();
require('connect.php');

// Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: login.php');
    exit;
}

// Proses pengembalian barang
if(isset($_GET['id_transaksi'])) {
    $id_transaksi = mysqli_real_escape_string($connect, $_GET['id_transaksi']);
    
    // Update status transaksi menjadi 'Kembali' dan set tanggal_kembali
    $tanggal_kembali = date('Y-m-d');
    $query_update = "UPDATE transaksi SET status = 'Kembali', tanggal_kembali = '$tanggal_kembali' WHERE id_transaksi = '$id_transaksi'";
    
    $result = mysqli_query($connect, $query_update);
    
    if($result) {
        // Berhasil mengembalikan
        $_SESSION['pesan'] = "Barang berhasil dikembalikan!";
    } else {
        // Gagal
        $_SESSION['error'] = "Gagal mengembalikan barang: " . mysqli_error($connect);
    }
    
    header('Location: admin-transaksi.php');
    exit;
} else {
    header('Location: admin-transaksi.php');
    exit;
}
?>
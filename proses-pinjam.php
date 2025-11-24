<?php
session_start();
require('connect.php');



if(!isset($_SESSION['role'])) {
    header('Location: index.php');
    exit; 
}


if(isset($_GET['id_barang'])) {
    
    $id_barang = mysqli_real_escape_string($connect, $_GET['id_barang']);
    

    if(!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Error: ID User tidak ditemukan. Coba login ulang.";
        header('Location: barang.php');
        exit; 
    }

    $id_user        = $_SESSION['user_id'];
    $tanggal_pinjam = date('Y-m-d');


    $sql_cek_stok_realtime = "
        SELECT 
            b.id_barang, 
            b.nama_barang, 
            (b.jumlah - COALESCE(SUM(CASE WHEN t.status = 'Dipinjam' THEN t.jumlah_pinjam ELSE 0 END), 0)) AS stok_saat_ini
        FROM barang b 
        LEFT JOIN transaksi t ON b.id_barang = t.id_barang
        WHERE b.id_barang = '$id_barang'
        GROUP BY b.id_barang, b.nama_barang, b.jumlah
    ";

    $cek_stok = mysqli_query($connect, $sql_cek_stok_realtime);
    
    if(mysqli_num_rows($cek_stok) === 0) {
        $_SESSION['error'] = "Barang tidak ditemukan.";
        header('Location: barang.php');
        exit; 
    }
    
    $data_barang = mysqli_fetch_assoc($cek_stok);

    if($data_barang['stok_saat_ini'] > 0) {

        $insert_transaksi = "INSERT INTO transaksi (tanggal_pinjam, id_barang, nama_peminjam, jumlah_pinjam, status) 
                             VALUES ('$tanggal_pinjam', '$id_barang', '$id_user', 1, 'Dipinjam')";
        
        $proses_transaksi = mysqli_query($connect, $insert_transaksi);
        
        if($proses_transaksi) {
            $_SESSION['pesan'] = "Berhasil meminjam " . $data_barang['nama_barang'] . " (1 unit).";
        } else {
            $_SESSION['error'] = "Gagal memproses transaksi: " . mysqli_error($connect);
        }

    } else {
        $_SESSION['error'] = "Stok barang sudah habis!";
    }


    header('Location: barang.php');
    exit; 

} else {
    
    header('Location: barang.php');
    exit; 
}
?>
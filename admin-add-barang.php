<?php
session_start();
require('connect.php');

// Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: login.php');
    exit;
}

// Proses Tambah Data Barang
if(isset($_POST['submit_barang'])) {
    $nama_barang   = $_POST['nama_barang'];
    $satuan_barang = $_POST['satuan_barang'];
    $jumlah        = $_POST['jumlah'];
    $keterangan    = $_POST['keterangan'];

    // Insert Query
    $query = "INSERT INTO barang (nama_barang, satuan_barang, jumlah, keterangan) VALUES ('$nama_barang', '$satuan_barang', '$jumlah', '$keterangan')";
    
    $result = mysqli_query($connect, $query);

    if($result) {
        header('Location: admin.php');
    } else {
        echo "Gagal menambah barang: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang Baru</title>
</head>
<body>
    <h2>Tambah Barang Baru</h2>
    
    <form action="" method="POST">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama_barang" required><br><br>

        <label>Satuan:</label><br>
        <select name="satuan_barang">
            <option value="Pcs">Pcs</option>
            <option value="Kg">Kg</option>
            <option value="Unit">Unit</option>
            <option value="Litre">Litre</option>
        </select><br><br>

        <label>Jumlah:</label><br>
        <input type="number" name="jumlah" required><br><br>

        <label>Keterangan:</label><br>
        <select name="keterangan">
            <option value="Tersedia">Tersedia</option>
            <option value="Dipinjam">Dipinjam</option>
        </select><br><br>

        <button type="submit" name="submit_barang">Simpan Barang</button>
        <a href="admin.php">Batal</a>
    </form>
</body>
</html>
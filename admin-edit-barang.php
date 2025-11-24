<?php
session_start();
require('connect.php');

// 1. Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: index.php');
    exit;
}

// 2. Cek ID di URL
if(!isset($_GET['edit_barang_id'])) {
    header('Location: admin.php');
    exit;
}

$id = $_GET['edit_barang_id'];

// 3. Proses Update Data
if(isset($_POST['update_barang'])) {
    $nama_barang   = $_POST['nama_barang'];
    $satuan_barang = $_POST['satuan_barang'];
    $jumlah        = $_POST['jumlah'];
    $keterangan    = $_POST['keterangan'];

    $queryUpdate = "UPDATE barang SET 
                    nama_barang='$nama_barang', 
                    satuan_barang='$satuan_barang', 
                    jumlah='$jumlah', 
                    keterangan='$keterangan' 
                    WHERE id_barang='$id'";

    $result = mysqli_query($connect, $queryUpdate);

    if($result) {
        header('Location: admin.php');
    } else {
        echo "Gagal update: " . mysqli_error($connect);
    }
}

// 4. Ambil Data Lama
$queryData = mysqli_query($connect, "SELECT * FROM barang WHERE id_barang = '$id'");
$data = mysqli_fetch_assoc($queryData);

if(!$data) {
    echo "Data barang tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
</head>
<body>
    <h2>Edit Data Barang</h2>
    
    <form action="" method="POST">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama_barang" value="<?php echo $data['nama_barang']; ?>" required><br><br>

        <label>Satuan:</label><br>
        <select name="satuan_barang">
            <option value="Kg" <?php if($data['satuan_barang'] == 'Kg') echo 'selected'; ?>>Kg</option>
            <option value="Pcs" <?php if($data['satuan_barang'] == 'Pcs') echo 'selected'; ?>>Pcs</option>
            <option value="Unit" <?php if($data['satuan_barang'] == 'Unit') echo 'selected'; ?>>Unit</option>
            <option value="Litre" <?php if($data['satuan_barang'] == 'Litre') echo 'selected'; ?>>Litre</option>
        </select><br><br>

        <label>Jumlah:</label><br>
        <input type="number" name="jumlah" value="<?php echo $data['jumlah']; ?>" required><br><br>

        <label>Keterangan:</label><br>
        <select name="keterangan">
            <option value="Tersedia" <?php if($data['keterangan'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
            <option value="Dipinjam" <?php if($data['keterangan'] == 'Dipinjam') echo 'selected'; ?>>Dipinjam</option>
        </select><br><br>

        <button type="submit" name="update_barang">Simpan Perubahan</button>
        <a href="admin.php">Batal</a>
    </form>
</body>
</html>
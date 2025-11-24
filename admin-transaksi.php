<?php
session_start();
require('connect.php');

// Cek Login & Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        h2 { margin-top: 30px; }
        
        .btn-back {
            display: inline-block;
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .btn-back:hover { background-color: #5a6268; }
        
        .btn-kembali {
            background-color: #ffc107;
            color: #212529;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn-kembali:hover { background-color: #e0a800; }
        
        .status-dipinjam {
            background-color: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .status-kembali {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .header-container {
            overflow: hidden;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1 style="float: left; margin: 0;">Riwayat Transaksi Barang</h1>
        <a href="admin.php" class="btn-back" style="float: right;">Kembali ke Dashboard</a>
    </div>
    
    <h2>Barang yang Sedang Dipinjam</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk transaksi yang masih dipinjam
            $sql_transaksi = "
                SELECT 
                    t.id_transaksi,
                    b.nama_barang,
                    p.nama AS nama_peminjam,
                    t.jumlah_pinjam,
                    t.tanggal_pinjam,
                    t.status
                FROM transaksi t
                JOIN barang b ON t.id_barang = b.id_barang
                JOIN pengguna p ON t.nama_peminjam = p.user_id
                WHERE t.status = 'Dipinjam'
                ORDER BY t.tanggal_pinjam DESC
            ";
            
            $query = mysqli_query($connect, $sql_transaksi);
            
            if(mysqli_num_rows($query) > 0) {
                while($transaksi = mysqli_fetch_assoc($query)) {
                    echo "<tr>";
                        echo "<td>".$transaksi['id_transaksi']."</td>";
                        echo "<td>".$transaksi['nama_barang']."</td>";
                        echo "<td>".$transaksi['nama_peminjam']."</td>";
                        echo "<td>".$transaksi['jumlah_pinjam']."</td>";
                        echo "<td>".date('d-m-Y', strtotime($transaksi['tanggal_pinjam']))."</td>";
                        echo "<td><span class='status-dipinjam'>".$transaksi['status']."</span></td>";
                        echo "<td>";
                        echo "<a href='admin-proses-kembali.php?id_transaksi=".$transaksi['id_transaksi']."' class='btn-kembali' onclick='return confirm(\"Yakin ingin mengembalikan barang ini?\")'>KEMBALIKAN</a>";
                        echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' align='center'>Tidak ada barang yang sedang dipinjam.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <h2>Riwayat Barang yang Sudah Dikembalikan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk transaksi yang sudah dikembalikan
            $sql_riwayat = "
                SELECT 
                    t.id_transaksi,
                    b.nama_barang,
                    p.nama AS nama_peminjam,
                    t.jumlah_pinjam,
                    t.tanggal_pinjam,
                    t.tanggal_kembali,
                    t.status
                FROM transaksi t
                JOIN barang b ON t.id_barang = b.id_barang
                JOIN pengguna p ON t.nama_peminjam = p.user_id
                WHERE t.status = 'Kembali'
                ORDER BY t.tanggal_kembali DESC
            ";
            
            $query_riwayat = mysqli_query($connect, $sql_riwayat);
            
            if(mysqli_num_rows($query_riwayat) > 0) {
                while($transaksi = mysqli_fetch_assoc($query_riwayat)) {
                    echo "<tr>";
                        echo "<td>".$transaksi['id_transaksi']."</td>";
                        echo "<td>".$transaksi['nama_barang']."</td>";
                        echo "<td>".$transaksi['nama_peminjam']."</td>";
                        echo "<td>".$transaksi['jumlah_pinjam']."</td>";
                        echo "<td>".date('d-m-Y', strtotime($transaksi['tanggal_pinjam']))."</td>";
                        echo "<td>".date('d-m-Y', strtotime($transaksi['tanggal_kembali']))."</td>";
                        echo "<td><span class='status-kembali'>".$transaksi['status']."</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' align='center'>Belum ada barang yang dikembalikan.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
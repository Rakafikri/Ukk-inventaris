<?php
session_start();
require('connect.php');

// Cek Login
// Mengalihkan ke halaman login.php jika belum login
if(!isset($_SESSION['role'])) {
    $_SESSION['error-no-login'] = "Silakan login terlebih dahulu!";
    header('Location: login.php'); 
    exit; // Wajib
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang</title>
    <style>
        /* Gaya tetap sama */
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #000; }
        .btn-pinjam { 
            background-color: #28a745; color: white; padding: 6px 12px; 
            text-decoration: none; border-radius: 4px; font-weight: bold;
            border: none; cursor: pointer;
        }
        .btn-habis {
            background-color: #ccc; color: #555; padding: 6px 12px; 
            text-decoration: none; border-radius: 4px; cursor: not-allowed;
            display: inline-block;
        }
        .alert-sukses { color: green; margin-bottom: 10px; font-weight: bold;}
        .alert-gagal { color: red; margin-bottom: 10px; font-weight: bold;}
        .btn-logout {
            float: right; padding: 8px 15px; background-color: #dc3545;
            color: white; text-decoration: none; border-radius: 4px;
            font-weight: bold;
        }
        .btn-logout:hover { background-color: #c82333; }
        .header-container {
            overflow: hidden; border-bottom: 2px solid #eee;
            padding-bottom: 10px; margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h2 style="float: left; margin: 0;">Daftar Barang Tersedia</h2>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>

    <p>Halo, <b><?php echo $_SESSION['nama']; ?></b> (<?php echo $_SESSION['role']; ?>).</p>

    <?php 
    if(isset($_SESSION['pesan'])) {
        echo "<div class='alert-sukses'>".$_SESSION['pesan']."</div>";
        unset($_SESSION['pesan']);
    }
    if(isset($_SESSION['error'])) {
        echo "<div class='alert-gagal'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
    }
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Stok Saat Ini (Realtime)</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            
            $sql_query = "
                SELECT 
                    b.id_barang, 
                    b.nama_barang, 
                    b.satuan_barang, 
                    (b.jumlah - COALESCE(SUM(CASE WHEN t.status = 'Dipinjam' THEN t.jumlah_pinjam ELSE 0 END), 0)) AS stok_saat_ini,
                    b.keterangan
                FROM 
                    barang b 
                LEFT JOIN 
                    transaksi t ON b.id_barang = t.id_barang
                GROUP BY
                    b.id_barang, b.nama_barang, b.satuan_barang, b.jumlah, b.keterangan
                ORDER BY 
                    b.nama_barang ASC
            ";

            $query = mysqli_query($connect, $sql_query);
            
            if(mysqli_num_rows($query) > 0) {
                while($data = mysqli_fetch_assoc($query)) {
                    
                    $keterangan_tampil = ($data['stok_saat_ini'] > 0) ? "Tersedia" : "Habis";

                    echo "<tr>";
                        echo "<td>".$no++."</td>";
                        echo "<td>".$data['nama_barang']."</td>";
                        echo "<td>".$data['satuan_barang']."</td>";
                        echo "<td>".$data['stok_saat_ini']."</td>"; 
                        echo "<td>".$keterangan_tampil."</td>";
                        
                        echo "<td>";
                        if($data['stok_saat_ini'] > 0) {
                            // Cukup gunakan konfirmasi dasar yang sudah ada (tanpa logic this.onclick)
                            echo "<a href='proses-pinjam.php?id_barang=".$data['id_barang']."' class='btn-pinjam' onclick='return confirm(\"Yakin pinjam barang ini?\")'>PINJAM</a>";
                        } else {
                            echo "<span class='btn-habis'>HABIS</span>";
                        }
                        echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' align='center'>Tidak ada barang.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
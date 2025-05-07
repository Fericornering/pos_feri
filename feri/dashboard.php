<?php
include 'koneksi.php';

$produk = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $produk);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <!-- Sidebar / Navbar Kiri -->
    <div class="sidebar">
        <div class="brand">Toko</div>
        <div class="links">
            <a href="produk.php">Barang</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
            <a href="profil.php">Profil</a>
            <a href="login.php">Logout</a>
        </div>
    </div>

    <!-- Konten Utama (Daftar Produk) -->
    <div class="main">
        <h2>Daftar Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $r['id_produk']; ?></td>
                        <td><?php echo $r['nama_produk']; ?></td>
                        <td>Rp<?php echo number_format($r['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $r['stok']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

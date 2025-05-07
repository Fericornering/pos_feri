<?php
include 'koneksi.php';

$tanggal = $_POST['tanggal'] ?? '';
$bulan = $_POST['bulan'] ?? '';

$query = "SELECT p.tanggal_penjualan, p.id_penjualan, pr.nama_produk, dp.jumlah_produk, dp.subtotal
          FROM detail_penjualan dp
          JOIN penjualan p ON dp.id_penjualan = p.id_penjualan
          JOIN produk pr ON dp.id_produk = pr.id_produk";

$filters = [];

if ($tanggal) {
    $filters[] = "DATE(p.tanggal_penjualan) = '" . mysqli_real_escape_string($koneksi, $tanggal) . "'";
}
if ($bulan) {
    $filters[] = "MONTH(p.tanggal_penjualan) = " . (int)$bulan;
}

if ($filters) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="laporan.css">
</head>
<body>

    <h2>Laporan Penjualan</h2>

    <form method="POST" class="form-filter">
        <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
        <select name="bulan">
            <option value="">Pilih Bulan</option>
            <?php 
                $bulan_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                foreach ($bulan_arr as $i => $nama): 
            ?>
                <option value="<?= $i+1 ?>" <?= ($bulan == $i+1) ? 'selected' : '' ?>><?= $nama ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Terapkan</button>
    </form>

    <div class="actions">
        <button onclick="window.print()">🖨️ Print</button>
        <button onclick="exportToExcel()">📊 Excel</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>ID Penjualan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($r = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $r['tanggal_penjualan'] ?></td>
                        <td><?= $r['id_penjualan'] ?></td>
                        <td><?= $r['nama_produk'] ?></td>
                        <td><?= $r['jumlah_produk'] ?></td>
                        <td>Rp <?= number_format($r['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Data Tidak Ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tombol kembali di bawah tabel -->
    <div class="footer-button">
        <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <script>
        function exportToExcel() {
            const table = document.querySelector("table").outerHTML;
            const blob = new Blob(
                [`<html><head><meta charset="UTF-8"></head><body>${table}</body></html>`],
                { type: "application/vnd.ms-excel" }
            );
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "Laporan_Penjualan.xls";
            link.click();
        }
    </script>
</body>
</html>

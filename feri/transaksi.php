<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_POST) {
    $id = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : 0;
    $uang_dibayar = isset($_POST['uang_dibayar']) ? $_POST['uang_dibayar'] : 0;

    $produk = $koneksi->query("SELECT * FROM produk WHERE id_produk=$id")->fetch_assoc();

    if ($jumlah > $produk['stok']) {
        echo "<script>alert('Stoknya Habis')</script>";
    } else {
        $subtotal = $produk['harga'] * $jumlah;
        $diskon_total = ($diskon / 100) * $subtotal;
        $total_bayar = $subtotal - $diskon_total;

        if ($uang_dibayar < $total_bayar) {
            echo "<script>alert('Uangnya Kurang')</script>";
        } else {
            $kembalian = $uang_dibayar - $total_bayar;
            $koneksi->query("INSERT INTO penjualan (tanggal_penjualan, total_harga) VALUES (NOW(), $total_bayar)");
            $koneksi->query("INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah_produk, subtotal) 
                VALUES (LAST_INSERT_ID(), $id, $jumlah, $total_bayar)");
            $koneksi->query("UPDATE produk SET stok = stok - $jumlah WHERE id_produk=$id");

            $struk = [
                'produk' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'uang_dibayar' => $uang_dibayar,
                'total_bayar' => $total_bayar,
                'kembalian' => $kembalian,
                'tanggal' => date('Y-m-d H:i:s'),
            ];
        }
    }
}

$produk = $koneksi->query("SELECT * FROM produk");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Toko</title>
    <link rel="stylesheet" href="transaksi.css">
</head>
<body>
    <h2>Transaksi Toko</h2>
    <div class="input">
        <form method="POST">
            <label for="id_produk">Pilih Produk:</label>
            <select name="id_produk" id="id_produk" onchange="updateDetails(this)" required>
                <option value="">-- Pilih Produk --</option>
                <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
                    <option value="<?= $p['id_produk'] ?>"
                        data-nama="<?= $p['nama_produk'] ?>"
                        data-harga="<?= $p['harga'] ?>"
                        data-stok="<?= $p['stok'] ?>">
                        <?= $p['nama_produk'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <table>
                <tr><td>Nama Produk</td><td><span id="nama_produk">-</span></td></tr>
                <tr><td>Harga</td><td>Rp <span id="harga">-</span></td></tr>
                <tr><td>Stok</td><td><span id="stok">-</span></td></tr>
                <tr><td>Jumlah Beli</td><td><input type="number" name="jumlah" id="jumlah" oninput="updateSubtotal()" min="1" required></td></tr>
                <tr><td>Subtotal</td><td><span id="subtotal">-</span></td></tr>
                <tr><td>Diskon (%)</td><td><input type="number" name="diskon" id="diskon" oninput="updateSubtotal()" value="0" min="0" required></td></tr>
                <tr><td>Uang Dibayar</td><td><input type="number" name="uang_dibayar" min="0" required></td></tr>
            </table>

            <button type="submit">Transaksi</button>
            <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
        </form>
    </div>

    <?php if (isset($struk)) : ?>
        <h2>Struk Pembelian</h2>
        <table class="struk">
            <tr><td>Produk</td><td><?= $struk['produk'] ?></td></tr>
            <tr><td>Harga</td><td>Rp <?= number_format($struk['harga'], 0, ',', '.') ?></td></tr>
            <tr><td>Jumlah</td><td><?= $struk['jumlah'] ?></td></tr>
            <tr><td>Subtotal</td><td>Rp <?= number_format($struk['subtotal'], 0, ',', '.') ?></td></tr>
            <tr><td>Diskon</td><td><?= $struk['diskon'] ?>%</td></tr>
            <tr><td>Total Bayar</td><td>Rp <?= number_format($struk['total_bayar'], 0, ',', '.') ?></td></tr>
            <tr><td>Uang Dibayar</td><td>Rp <?= number_format($struk['uang_dibayar'], 0, ',', '.') ?></td></tr>
            <tr><td>Kembalian</td><td>Rp <?= number_format($struk['kembalian'], 0, ',', '.') ?></td></tr>
            <tr><td>Tanggal</td><td><?= $struk['tanggal'] ?></td></tr>
        </table>
        <button onclick="printStruk()">Cetak Struk</button>
    <?php endif; ?>

    <script>
        let harga = 0;
        function updateDetails(select) {
            const opt = select.selectedOptions[0];
            document.getElementById('nama_produk').textContent = opt.getAttribute('data-nama') || '-';
            harga = parseInt(opt.getAttribute('data-harga')) || 0;
            document.getElementById('harga').textContent = harga.toLocaleString('id-ID');
            document.getElementById('stok').textContent = opt.getAttribute('data-stok') || '-';
            updateSubtotal();
        }

        function updateSubtotal() {
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            const diskon = parseFloat(document.getElementById('diskon').value) || 0;
            const subtotal = harga * jumlah;
            const diskonValue = (diskon / 100) * subtotal;
            const total = subtotal - diskonValue;
            document.getElementById('subtotal').textContent = total > 0 ? `Rp${total.toLocaleString('id-ID')}` : '-';
        }

        function printStruk() {
            const printContent = document.querySelector(".struk").outerHTML;
            const newWin = window.open('', '', 'width=600,height=400');
            newWin.document.write(`<html><head><title>Struk</title></head><body>${printContent}</body></html>`);
            newWin.document.close();
            newWin.print();
        }
    </script>
</body>
</html>

<!-- profil.php -->
<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_user'];
    $nama = $_POST['nama_kasir'];
    $email = $_POST['email'];
    $tanggal = $_POST['tgl_lahir'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE akun SET nama_kasir='$nama', email='$email', tgl_lahir='$tanggal', alamat='$alamat' WHERE id_user=$id";
    $koneksi->query($query);
    header('Location: profil.php');
    exit;
}

$edit = isset($_GET['edit']) ? $koneksi->query("SELECT * FROM akun WHERE id_user=" . $_GET['edit'])->fetch_assoc() : null;
$akun = $koneksi->query("SELECT * FROM akun");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="profil.css">
</head>
<body>

    <?php while ($n = mysqli_fetch_assoc($akun)): ?>
        <div class="card">
            <h2>Profil</h2>
            <img src="../src/img/user-icon.png" alt="Foto Profil">
            <p><strong>Nama Kasir:</strong> <?= $n['nama_kasir'] ?></p>
            <p><strong>Email:</strong> <?= $n['email'] ?></p>
            <p><strong>Tanggal Lahir:</strong> <?= date('d-m-Y', strtotime($n['tgl_lahir'])) ?></p>
            <p><strong>Alamat:</strong> <?= $n['alamat'] ?></p>
            <a href="?edit=<?= $n['id_user'] ?>">Edit</a><br><br>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
    <?php endwhile; ?>

    <?php if ($edit): ?>
    <form method="POST">
        <h2>Edit Profil</h2>
        <input type="hidden" name="id_user" value="<?= $edit['id_user'] ?>">
        <input name="nama_kasir" value="<?= $edit['nama_kasir'] ?>" placeholder="Nama kasir" required>
        <input name="email" value="<?= $edit['email'] ?>" placeholder="Email" required>
        <input type="date" name="tgl_lahir" value="<?= $edit['tgl_lahir'] ?>" required>
        <input name="alamat" value="<?= $edit['alamat'] ?>" placeholder="Alamat" required>
        <button type="submit">Simpan</button>
        <a href="profil.php">Batal</a>
    </form>
    <?php endif; ?>

</body>
</html>

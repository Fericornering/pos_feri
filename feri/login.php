<?php
//Menghubungkan database
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $q = $koneksi->query("SELECT * FROM akun WHERE email='$email' and password='$pass'");

    if ($q->num_rows > 0) {
        header('Location: dashboard.php');
        exit;
    } else {
        //Menampilkan peringatan jika data salah
        echo "<script>alert('Email atau password salah!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<!-- Untuk menginput email dan password-->
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>
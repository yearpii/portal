<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass'");
    $user = mysqli_fetch_assoc($q);

    if ($user) {
        $_SESSION['user'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['id'] = $user['id'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: ukm/dashboard.php");
        }
    } else {
        echo "Login gagal!";
    }
}
?>

<!-- HTML Form Login -->
<form method="POST">
    <input name="email" type="text" placeholder="Username" />
    <input name="password" type="password" placeholder="Password" />
    <button type="submit">LOGIN</button>
</form>
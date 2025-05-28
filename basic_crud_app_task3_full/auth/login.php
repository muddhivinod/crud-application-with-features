<?php
include("../includes/db.php");
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user["username"];
            header("Location: ../posts/read.php");
            exit();
        }
    }
    $message = "Invalid login.";
}
?>
<!DOCTYPE html>
<html><head><title>Login</title><?= include('../assets/css/style.css'); ?></head><body class='container mt-5'>
<h2>Login</h2>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
  <button class="btn btn-success">Login</button>
</form>
<p><?= $message ?></p>
</body></html>

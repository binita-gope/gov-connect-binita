<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>GovConnect - Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #1e3c72, #2a5298);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.box {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    width: 380px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}
h2 {
    text-align: center;
    color: #1e3c72;
    margin-bottom: 25px;
}
input, select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}
button {
    width: 100%;
    padding: 12px;
    background: #1e3c72;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}
button:hover {
    background: #2a5298;
}
.error {
    color: #b00020;
    text-align: center;
    margin-bottom: 10px;
}
a {
    color: #1e3c72;
    text-decoration: none;
    font-size: 14px;
    display: block;
    text-align: center;
    margin-top: 10px;
}
</style>
</head>
<body>
<div class="box">
    <h2><i class="fas fa-shield-alt"></i> GovConnect Login</h2>
    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" action="login_process.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="response">Response</option>
        </select>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Don't have an account? Register</a>
</div>
</body>
</html>

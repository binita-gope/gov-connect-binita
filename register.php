<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>GovConnect - Register</title>
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
    width: 400px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}
h2 {
    text-align: center;
    color: #1e3c72;
    margin-bottom: 25px;
}
input {
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
    <h2><i class="fas fa-user-plus"></i> Create Account</h2>
    <form method="post" action="register_process.php">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="location" placeholder="Your Location (City/Area)" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>

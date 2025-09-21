<?php
session_start();
require_once "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $password = trim($_POST['password']);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role_id = 2; // default role (user)

    // Check if email exists
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Email already registered!'); window.location='register.php';</script>";
        exit;
    }

    // Get location_id
    $stmt = $pdo->prepare("SELECT location_id FROM locations WHERE location_name = ?");
    $stmt->execute([$location]);
    $loc = $stmt->fetch();
    $location_id = $loc ? $loc['location_id'] : 1; // default to first location if not found

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role_id, location_id) VALUES (?,?,?,?,?,?)");
    if ($stmt->execute([$name, $email, $phone, $hashed_password, $role_id, $location_id])) {
        echo "<script>alert('✅ Registration successful! Please login.'); window.location='login.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Registration failed.'); window.location='register.php';</script>";
        exit;
    }
}
?>

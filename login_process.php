<?php
session_start();
require_once "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $selected_role = trim($_POST['role']);

    // Fetch user by email and join with roles table
    $stmt = $pdo->prepare("SELECT u.user_id, u.name, u.email, u.password, r.role_name as role, u.role_id, u.location_id 
                          FROM users u 
                          JOIN roles r ON u.role_id = r.role_id 
                          WHERE u.email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Debug logging
    echo "<script>console.log('Email:', " . json_encode($email) . ");</script>";
    echo "<script>console.log('User found:', " . json_encode($user ? 'yes' : 'no') . ");</script>";
    if ($user) {
        echo "<script>console.log('Role match:', " . json_encode($user['role'] === $selected_role) . ");</script>";
        echo "<script>console.log('Password verify:', " . json_encode(password_verify($password, $user['password'])) . ");</script>";
    }

    if ($user && password_verify($password, $user['password'])) {
        // Check role
        if ($user['role'] !== $selected_role) {
            header("Location: login.php?error=Role+does+not+match");
            exit;
        }

        // Set session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=Invalid+credentials");
        exit;
    }
}
?>

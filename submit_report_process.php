<?php
session_start();
require_once "db_connect.php"; // Database connection

// Only logged-in users with role 'user' can submit
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $suggestion = trim($_POST['suggestion'] ?? '');
    $priority = trim($_POST['priority']);
    $location = trim($_POST['location']); // expects "lat,lng"

    // Validate location
    if (empty($location)) {
        echo "<script>alert('❌ Please select a location on the map'); window.history.back();</script>";
        exit;
    }

    // Get category_id from category name
    $stmt = $pdo->prepare("SELECT category_id FROM problem_categories WHERE category_name = ?");
    $stmt->execute([$category]);
    $cat = $stmt->fetch();
    $category_id = $cat ? $cat['category_id'] : 1; // default to first category if not found

    // Get location_id from locations table or create new if needed
    $stmt = $pdo->prepare("SELECT location_id FROM locations WHERE location_name = ?");
    $stmt->execute([$location]);
    $loc = $stmt->fetch();
    $location_id = $loc ? $loc['location_id'] : 1; // default to first location if not found

    // Get default status and priority
    $status_id = 1; // Pending

    // Convert priority string to priority_id
    $priority_map = [
        'low' => 1,
        'medium' => 2,
        'high' => 3
    ];
    $priority_id = $priority_map[$priority] ?? 2; // Default to medium (2) if priority not found

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO problems (user_id, category_id, description, location_id, status_id, priority_id) 
                          VALUES (:user_id, :category_id, :description, :location_id, :status_id, :priority_id)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':location_id', $location_id);
    $stmt->bindParam(':status_id', $status_id);
    $stmt->bindParam(':priority_id', $priority_id);

    try {
        $stmt->execute();
        echo "<script>alert('✅ Report submitted successfully!'); window.location='dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('❌ Error submitting report: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>

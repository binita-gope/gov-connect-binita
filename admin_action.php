<?php
session_start();
require_once "db_connect.php";

// Check if user is logged in and is an admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Check if required parameters are provided
if(!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: admin_panel.php?error=invalid_request");
    exit;
}

$problem_id = intval($_GET['id']);
$action = $_GET['action'];

// Get the corresponding status IDs from the database
$status_query = $mysqli->prepare("SELECT status_id, status_name FROM problem_statuses WHERE status_name IN ('Verified', 'Rejected')");
$status_query->execute();
$status_result = $status_query->get_result();
$status_ids = [];
while($row = $status_result->fetch_assoc()) {
    $status_ids[$row['status_name']] = $row['status_id'];
}

// Validate problem exists and is in Pending state
$check_stmt = $mysqli->prepare("SELECT status_id FROM problems WHERE problem_id = ?");
$check_stmt->bind_param("i", $problem_id);
$check_stmt->execute();
$current_status = $check_stmt->get_result()->fetch_assoc();

if(!$current_status) {
    header("Location: admin_panel.php?error=problem_not_found");
    exit;
}

// Process the action
switch($action) {
    case 'verify':
        $new_status_id = $status_ids['Verified'];
        $update_stmt = $mysqli->prepare("UPDATE problems SET status_id = ? WHERE problem_id = ?");
        $update_stmt->bind_param("ii", $new_status_id, $problem_id);
        
        if($update_stmt->execute()) {
            header("Location: admin_panel.php?success=problem_verified");
        } else {
            header("Location: admin_panel.php?error=update_failed");
        }
        break;

    case 'reject':
        $new_status_id = $status_ids['Rejected'];
        $update_stmt = $mysqli->prepare("UPDATE problems SET status_id = ? WHERE problem_id = ?");
        $update_stmt->bind_param("ii", $new_status_id, $problem_id);
        
        if($update_stmt->execute()) {
            header("Location: admin_panel.php?success=problem_rejected");
        } else {
            header("Location: admin_panel.php?error=update_failed");
        }
        break;

    default:
        header("Location: admin_panel.php?error=invalid_action");
        break;
}

$mysqli->close();
?>
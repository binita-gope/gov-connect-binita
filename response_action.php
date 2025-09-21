<?php
session_start();
require_once "db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='response'){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: response_dashboard.php");
    exit;
}

$response_id = $_GET['id'];
$status = $_GET['status'];
$responder_id = $_SESSION['user_id'];

// Start transaction
$mysqli->begin_transaction();

try {
    // First, get the problem_id from the response
    $stmt = $mysqli->prepare("SELECT problem_id FROM responses WHERE response_id = ?");
    $stmt->bind_param("i", $response_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        throw new Exception("Response not found");
    }
    
    $problem_id = $result->fetch_assoc()['problem_id'];
    
    // Update response action based on status
    $action_message = "";
    $problem_status_id = 0;
    
    switch($status) {
        case 'on_route':
            $action_message = "Response team is on route to address the issue";
            $problem_status_id = 2; // In Progress
            break;
        case 'resolved':
            $action_message = "Issue has been resolved by the response team";
            $problem_status_id = 3; // Resolved
            break;
        case 'rejected':
            $action_message = "Issue has been reviewed and cannot be addressed at this time";
            $problem_status_id = 5; // Requires Follow-up
            break;
        default:
            throw new Exception("Invalid status");
    }
    
    // Update the response
    $stmt = $mysqli->prepare("UPDATE responses SET response_action = ? WHERE response_id = ?");
    $stmt->bind_param("si", $action_message, $response_id);
    $stmt->execute();
    
    // Update the problem status
    $stmt = $mysqli->prepare("UPDATE problems SET status_id = ? WHERE problem_id = ?");
    $stmt->bind_param("ii", $problem_status_id, $problem_id);
    $stmt->execute();
    
    // Add log entry
    $stmt = $mysqli->prepare("INSERT INTO logs (problem_id, type_id, message) VALUES (?, 2, ?)");
    $stmt->bind_param("is", $problem_id, $action_message);
    $stmt->execute();
    
    // Commit transaction
    $mysqli->commit();
    
    // Redirect back to dashboard with success message
    header("Location: response_dashboard.php?success=1&message=" . urlencode("Status updated successfully"));
    exit;
    
} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    
    // Redirect back to dashboard with error message
    header("Location: response_dashboard.php?error=1&message=" . urlencode("Error: " . $e->getMessage()));
    exit;
}
?>
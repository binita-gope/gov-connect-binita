<?php
session_start();
require_once "db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='response'){
    header("Location: login.php");
    exit;
}

// Fetch all responses with their related problem information
$stmt = $mysqli->prepare("SELECT 
    r.response_id, 
    p.problem_id, 
    u.name as user_name, 
    pc.category_name as category, 
    p.description, 
    l.location_name as location, 
    pp.priority_name as priority, 
    ps.status_name as status, 
    r.response_action as status_update,
    r.created_at
FROM responses r
JOIN problems p ON r.problem_id = p.problem_id
JOIN users u ON p.user_id = u.user_id
JOIN problem_categories pc ON p.category_id = pc.category_id
JOIN locations l ON p.location_id = l.location_id
JOIN problem_priorities pp ON p.priority_id = pp.priority_id
JOIN problem_statuses ps ON p.status_id = ps.status_id
ORDER BY r.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Response Dashboard - GovConnect</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 15px;
            background: #1e3c72;
            color: #fff;
            text-align: center;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #1e3c72;
            color: #fff;
        }
        a.button {
            padding: 5px 10px;
            background: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 2px;
        }
        a.button:hover {
            background: #2ecc71;
        }
        a.button.resolved {
            background: #2c3e50;
        }
        a.button.rejected {
            background: #e74c3c;
        }
        .action-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .action-text:hover {
            white-space: normal;
            overflow: visible;
        }
        .alert {
            padding: 15px;
            margin: 20px auto;
            width: 90%;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .button.on-route {
            background: #27ae60;
        }
        .button.resolved {
            background: #2c3e50;
        }
        .button.rejected {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Response Team Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION['name']; ?> | <a href="logout.php" style="color:#fff;">Logout</a></p>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message'] ?? 'Action completed successfully'); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['message'] ?? 'An error occurred'); ?>
        </div>
    <?php endif; ?>

    <?php if($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Category</th>
            <th>Description</th>
            <th>Location</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Action Update</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['problem_id']); ?></td>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
            <td><div class="action-text"><?php echo htmlspecialchars($row['description']); ?></div></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['priority']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><div class="action-text"><?php echo htmlspecialchars($row['status_update']); ?></div></td>
            <td>
                <a href="response_action.php?id=<?php echo $row['response_id']; ?>&status=on_route" class="button">On Route</a>
                <a href="response_action.php?id=<?php echo $row['response_id']; ?>&status=resolved" class="button resolved">Resolved</a>
                <a href="response_action.php?id=<?php echo $row['response_id']; ?>&status=rejected" class="button rejected">Reject</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
    <div style="text-align: center; margin: 20px; padding: 20px; background: #fff;">
        <p>No responses found in the system.</p>
    </div>
    <?php endif; ?>
    </table>
    </div>
</body>
</html>
<?php
session_start();
require_once "db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='response'){
    header("Location: login.php");
    exit;
}

// Fetch all problems that need response team attention
$stmt = $mysqli->prepare("SELECT 
    p.problem_id, 
    u.name as user_name,
    pc.category_name,
    p.description,
    p.suggestion,
    l.location_name,
    pp.priority_name,
    ps.status_name,
    p.created_at
FROM problems p
JOIN users u ON p.user_id = u.user_id
JOIN problem_categories pc ON p.category_id = pc.category_id
JOIN locations l ON p.location_id = l.location_id
JOIN problem_priorities pp ON p.priority_id = pp.priority_id
JOIN problem_statuses ps ON p.status_id = ps.status_id
WHERE ps.status_name = 'Pending' OR ps.status_name = 'In Progress'
ORDER BY pp.priority_id DESC, p.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Response Panel - GovConnect</title>
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
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
    vertical-align: middle;
}
th {
    background: #1e3c72;
    color: #fff;
    font-weight: 600;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
    align-items: center;
}
a.button {
    padding: 6px 12px;
    background: #27ae60;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}
a.button:hover {
    background: #2ecc71;
}
a.button.reject {
    background: #e74c3c;
}
a.button.reject:hover {
    background: #c0392b;
}
a.button.resolved {
    background: #2c3e50;
}
a.button.resolved:hover {
    background: #2c3e50;
}
.priority-high {
    color: #e74c3c;
    font-weight: bold;
}
.priority-medium {
    color: #f39c12;
    font-weight: bold;
}
.priority-low {
    color: #27ae60;
    font-weight: bold;
}
.status-pending {
    color: #e74c3c;
}
.status-progress {
    color: #f39c12;
}
.status-resolved {
    color: #27ae60;
}
</style>
</head>
<body>

<div class="header">
  <h2>Response Team Dashboard</h2>
  <p>Welcome, <?php echo $_SESSION['name']; ?> | <a href="logout.php" style="color:#fff;">Logout</a></p>
</div>

<table>
<tr>
<th>ID</th>
<th>User</th>
<th>Category</th>
<th>Description</th>
<th>Location</th>
<th>Priority</th>
<th>Status</th>
<th>Suggestion</th>
<th>Action Update</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['problem_id']; ?></td>
<td><?php echo htmlspecialchars($row['user_name']); ?></td>
<td><?php echo htmlspecialchars($row['category_name']); ?></td>
<td><?php echo htmlspecialchars($row['description']); ?></td>
<td><?php echo htmlspecialchars($row['location_name']); ?></td>
<td class="priority-<?php echo strtolower($row['priority_name']); ?>"><?php echo htmlspecialchars($row['priority_name']); ?></td>
<td class="status-<?php echo strtolower(str_replace(' ', '', $row['status_name'])); ?>"><?php echo htmlspecialchars($row['status_name']); ?></td>
<td><?php echo htmlspecialchars($row['suggestion'] ?? ''); ?></td>
<td>
    <div class="action-buttons">
        <a href="response_action.php?id=<?php echo $row['problem_id']; ?>&action=on_route" class="button">On Route</a>
        <a href="response_action.php?id=<?php echo $row['problem_id']; ?>&action=resolved" class="button resolved">Resolved</a>
        <a href="response_action.php?id=<?php echo $row['problem_id']; ?>&action=reject" class="button reject">Reject</a>
    </div>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>

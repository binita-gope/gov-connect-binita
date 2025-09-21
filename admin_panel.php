<?php
session_start();
require_once "db_connect.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin'){
    header("Location: login.php");
    exit;
}

// Fetch all problems with related information
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
ORDER BY p.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Panel - GovConnect</title>
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
    min-width: 70px;
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
a.button.verify {
    background: #27ae60;
}
a.button.verify:hover {
    background: #219a52;
}
a.button.assign {
    background: #f39c12;
}
a.button.assign:hover {
    background: #d68910;
}
</style>
</head>
<body>

<div class="header">
  <h2>Admin Dashboard</h2>
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
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['problem_id']; ?></td>
<td><?php echo $row['user_name']; ?></td>
<td><?php echo ucfirst($row['category_name']); ?></td>
<td><?php echo $row['description']; ?></td>
<td><?php echo $row['location_name']; ?></td>
<td><?php echo ucfirst($row['priority_name']); ?></td>
<td><?php echo ucfirst($row['status_name']); ?></td>
<td><?php echo htmlspecialchars($row['suggestion'] ?? ''); ?></td>
<td>
    <div class="action-buttons">
    <?php if($row['status_name']=='Pending'): ?>
        <a href="admin_action.php?id=<?php echo $row['problem_id']; ?>&action=verify" class="button verify">Verify</a>
        <a href="admin_action.php?id=<?php echo $row['problem_id']; ?>&action=reject" class="button reject">Reject</a>
    <?php elseif($row['status_name']=='Verified'): ?>
        <a href="admin_action.php?id=<?php echo $row['problem_id']; ?>&action=assign" class="button assign">Assign</a>
    <?php endif; ?>
    </div>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>

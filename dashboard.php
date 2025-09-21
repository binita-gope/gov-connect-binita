<?php
session_start();
require_once "db_connect.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];
$name = $_SESSION["name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - GovConnect</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 0;
    background: #f4f6f9;
}
.header {
    padding: 20px;
    background: #1e3c72;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header h2 { margin: 0; font-size: 24px; }
.header a { color: #fff; text-decoration: none; font-weight: bold; }
.nav-buttons {
    padding: 20px;
    text-align: center;
}
.nav-buttons a {
    display: inline-block;
    margin: 5px 10px;
    text-decoration: none;
    color: #fff;
    background: #1e3c72;
    padding: 12px 18px;
    border-radius: 8px;
    font-weight: bold;
}
.nav-buttons a:hover { background: #2a5298; }
#map { height: 600px; width: 95%; margin: 20px auto; border-radius: 12px; border: 1px solid #ccc; }
</style>
</head>
<body>

<div class="header">
    <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="nav-buttons">
    <?php if ($role === "user"): ?>
        <a href="submit_report.php"><i class="fas fa-bullhorn"></i> Submit Report</a>
    <?php elseif ($role === "admin"): ?>
        <a href="admin_panel.php"><i class="fas fa-cogs"></i> Manage Reports</a>
    <?php elseif ($role === "response"): ?>
        <a href="response_panel.php"><i class="fas fa-car-side"></i> Response Team Panel</a>
    <?php endif; ?>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Initialize map
var map = L.map('map').setView([23.8103, 90.4125], 12); // Dhaka default

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Problem markers
<?php
$stmt = $pdo->query("SELECT category, description, location, priority, suggestion FROM problems");
$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($problems as $problem) {
    $latlng = explode(",", $problem['location']);
    $lat = isset($latlng[0]) ? $latlng[0] : 23.8103;
    $lng = isset($latlng[1]) ? $latlng[1] : 90.4125;

    $color = "blue";
    if ($problem['priority'] === 'high') $color = 'red';
    elseif ($problem['priority'] === 'medium') $color = 'orange';
    elseif ($problem['priority'] === 'low') $color = 'green';

    $desc = addslashes($problem['description']);
    $category = addslashes($problem['category']);
    $suggestion = addslashes($problem['suggestion']);

    echo "L.circle([$lat,$lng], {radius: 200, color:'$color', fillOpacity:0.6})
        .addTo(map)
        .bindPopup('<b>Category:</b> $category<br><b>Description:</b> $desc<br><b>Priority:</b> {$problem['priority']}'";
    if($suggestion) echo " + '<br><b>Suggestion:</b> $suggestion'";
    echo ");\n";
}
?>
</script>

</body>
</html>

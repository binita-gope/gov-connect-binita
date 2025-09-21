<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Report - GovConnect</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
body { margin:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f6f9; }
.header { background:#1e3c72; color:#fff; padding:15px; text-align:center; }
.header h2 { margin:0; }
.box { max-width:600px; background:#fff; margin:20px auto; padding:25px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.box h2 { text-align:center; color:#1e3c72; }
label { display:block; margin-top:10px; font-weight:bold; }
input, select, textarea { width:100%; padding:10px; margin-top:5px; border-radius:6px; border:1px solid #ccc; }
button { margin-top:15px; padding:12px; background:#1e3c72; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:16px; width:100%; transition:0.3s; }
button:hover { background:#2a5298; }
#map { height: 400px; width: 100%; margin-top:10px; border-radius:10px; }
</style>
</head>
<body>

<div class="header">
    <h2>Submit New Report</h2>
    <p>Welcome, <?= htmlspecialchars($name) ?>!</p>
</div>

<div class="box">
<form method="post" action="submit_report_process.php">
    <label>Category</label>
    <select name="category" required>
        <option value="fire">Fire</option>
        <option value="robbery">Robbery</option>
        <option value="road">Road</option>
        <option value="medical">Medical</option>
        <option value="other">Other</option>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4" placeholder="Describe the problem" required></textarea>

    <label>Suggestion (optional)</label>
    <textarea name="suggestion" rows="2" placeholder="E.g., avoid road, traffic jam"></textarea>

    <label>Priority</label>
    <select name="priority" required>
        <option value="low">Low</option>
        <option value="medium" selected>Medium</option>
        <option value="high">High</option>
    </select>

    <label>Location (click on map to select)</label>
    <div id="map"></div>
    <input type="hidden" name="location" id="location" required />

    <button type="submit">Submit Report</button>
</form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Initialize map
var map = L.map('map').setView([23.8103, 90.4125], 12); // Dhaka default
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var marker;

// Set marker on click
map.on('click', function(e) {
    var latlng = e.latlng;
    if(marker) map.removeLayer(marker);
    marker = L.marker(latlng, {draggable:true}).addTo(map);
    document.getElementById('location').value = latlng.lat + ',' + latlng.lng;

    // Update hidden input when marker is dragged
    marker.on('dragend', function(evt){
        var pos = evt.target.getLatLng();
        document.getElementById('location').value = pos.lat + ',' + pos.lng;
    });
});
</script>

</body>
</html>

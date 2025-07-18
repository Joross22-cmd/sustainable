<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// ✅ Correct query
$result = $conn->query("SELECT cell_id, weight_value, weighed_at FROM weight ORDER BY weighed_at DESC LIMIT 10");

if (!$result) {
    die("❌ Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weight Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f0f0f0;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>
    <script>
        // Refresh the page every 10 seconds
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</head>
<body>

<h2>Weight Monitoring</h2>

<table>
    <thead>
        <tr>
            <th>Cell ID</th>
            <th>Weight (g)</th>
            <th>Weighed At</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['cell_id']) ?></td>
                <td><?= htmlspecialchars($row['weight_value']) ?></td>
                <td><?= htmlspecialchars($row['weighed_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br><a href="index.php">← Back to Dashboard</a>

</body>
</html>

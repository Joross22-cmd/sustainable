<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Fetch recent behavior data
$result = $conn->query("SELECT cell_id, behavior_status, observed_at FROM behavior ORDER BY observed_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Behavior Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        h2 {
            color: #333;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .back {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
    <script>
        // Auto-refresh every 10 seconds
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</head>
<body>

<h2>Behavior Monitoring</h2>

<table>
    <thead>
        <tr>
            <th>Cell ID</th>
            <th>Behavior Status</th>
            <th>Observed At</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['cell_id']) ?></td>
                <td><?= htmlspecialchars($row['behavior_status']) ?></td>
                <td><?= htmlspecialchars($row['observed_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br><a class="back" href="index.php">← Back to Dashboard</a>

</body>
</html>

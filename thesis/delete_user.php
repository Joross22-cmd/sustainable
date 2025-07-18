<?php
include 'db.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM user WHERE user_id = $id");
    header("Location: admin_dashboard.php");
}

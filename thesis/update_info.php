<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $cpn_number = $_POST['cpn_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE user SET full_name = ?, birthday = ?, address = ?, cpn_number = ?, email = ?, gender = ? WHERE username = ?");
    $stmt->bind_param("sssssss", $full_name, $birthday, $address, $cpn_number, $email, $gender, $username);

    if ($stmt->execute()) {
        header("Location: index.php?updated=1");
    } else {
        echo "Update failed: " . $stmt->error;
    }
}

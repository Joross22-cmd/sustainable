<?php
$conn = new mysqli("localhost", "root", "", "thesis");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

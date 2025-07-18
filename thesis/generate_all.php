<?php
include 'db.php';

// Enable error reporting for debugging (optional)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Step 1: Get valid cell IDs from the `cell` table
$cell_ids = [];
$result = $conn->query("SELECT cell_id FROM cell");

while ($row = $result->fetch_assoc()) {
    $cell_ids[] = $row['cell_id'];
}

if (empty($cell_ids)) {
    die("❌ No cells found in the database.");
}

// Step 2: Pick a random cell
$random_cell = $cell_ids[array_rand($cell_ids)];

// Step 3: Insert into behavior table
$behaviors = ['normal', 'aggressive', 'lethargic'];
$random_behavior = $behaviors[array_rand($behaviors)];

$stmt1 = $conn->prepare("INSERT INTO behavior (cell_id, behavior_status, observed_at) VALUES (?, ?, NOW())");
$stmt1->bind_param("is", $random_cell, $random_behavior);
$stmt1->execute();

// Step 4: Insert into body_temperature table
$random_temp = rand(390, 430) / 10; // 39.0 to 43.0
$stmt2 = $conn->prepare("INSERT INTO body_temperature (cell_id, temperature, measured_at) VALUES (?, ?, NOW())");
$stmt2->bind_param("id", $random_cell, $random_temp);
$stmt2->execute();

// ✅ Insert into weight
$random_weight = rand(1200, 3000) / 100.0; // 12.00 to 30.00 kg

$stmt3 = $conn->prepare("INSERT INTO weight (cell_id, weight_value, weighed_at) VALUES (?, ?, NOW())");
if (!$stmt3) {
    die("❌ Weight insert prepare failed: " . $conn->error);
}

$stmt3->bind_param("id", $random_cell, $random_weight);
if (!$stmt3->execute()) {
    die("❌ Weight insert failed: " . $stmt3->error);
}


echo "✅ Data generated for Cell ID: $random_cell";
?>

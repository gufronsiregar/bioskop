<?php
require_once 'db.php';
header('Content-Type: application/json');

$result = $mysqli->query("SELECT COUNT(*) AS total FROM tickets_new WHERE status='waiting'");
if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['total' => 0]);
}
?>

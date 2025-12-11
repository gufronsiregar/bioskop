<?php
require_once 'db.php';
header('Content-Type: application/json');

$current = $mysqli->query("SELECT code FROM tickets_new WHERE status='serving' ORDER BY id DESC LIMIT 1");
if ($current && $current->num_rows > 0) {
    $code = $current->fetch_assoc()['code'];
    echo json_encode([
        'code' => $code,
        'status_text' => 'Nomor antrian yang sedang dilayani'
    ]);
} else {
    echo json_encode([
        'code' => 'B-XXX',
        'status_text' => 'Belum ada antrian berlangsung'
    ]);
}
?>

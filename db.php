<?php
$mysqli = new mysqli("localhost", "root", "", "antrian_bioskop");
if ($mysqli->connect_errno) {
    die("❌ Koneksi database gagal: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// ✅ Cek apakah tabel 'tickets_new' tersedia
$check = $mysqli->query("SHOW TABLES LIKE 'tickets_new'");
if ($check === false) {
    die("❌ Query pengecekan tabel gagal: " . $mysqli->error);
}
if ($check->num_rows === 0) {
    die("❌ Error: Tabel 'tickets_new' belum ada di database.");
}
?>

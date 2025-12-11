<?php
require_once 'db.php';

$msg = null;

// Tombol panggil berikutnya
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['call_next'])) {
    $res = $mysqli->query("SELECT * FROM tickets_new WHERE status='waiting' ORDER BY id ASC LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $ticket = $res->fetch_assoc();
        $stmt = $mysqli->prepare("UPDATE tickets_new SET status='serving', served_at=NOW() WHERE id=?");
        $stmt->bind_param('i', $ticket['id']);
        $stmt->execute();
        header('Location: loket.php');
        exit;
    } else {
        $msg = 'Tidak ada antrean menunggu.';
    }
}

// Tombol selesai
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $stmt = $mysqli->prepare("UPDATE tickets_new SET status='done' WHERE id=?");
    $stmt->bind_param('i', $ticket_id);
    $stmt->execute();
    header('Location: loket.php');
    exit;
}

// Ambil antrean yang sedang dilayani
$current_res = $mysqli->query("SELECT * FROM tickets_new WHERE status='serving' ORDER BY served_at DESC LIMIT 1");
$current_ticket = ($current_res && $current_res->num_rows > 0) ? $current_res->fetch_assoc() : null;

// Hitung antrean menunggu
$waiting_res = $mysqli->query("SELECT COUNT(*) AS total FROM tickets_new WHERE status='waiting'");
$waiting = ($waiting_res && $waiting_res->num_rows > 0) ? $waiting_res->fetch_assoc()['total'] : 0;

include 'inc/header.php';
?>

<div class="card-ghost p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-white mb-0">Panel Petugas Loket</h3>
    <div class="text-muted-ghost">Menunggu: <strong id="waitingCount"><?php echo $waiting; ?></strong></div>
  </div>

  <?php if($msg): ?>
    <div class="alert alert-warning"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <div class="p-3 card-ghost text-center">
    <?php if ($current_ticket): ?>
      <div class="h1 text-danger mb-1" id="currentCode"><?php echo htmlspecialchars($current_ticket['code']); ?></div>
      <div class="text-white mb-3"><?php echo htmlspecialchars($current_ticket['name']); ?></div>
      <form method="post">
        <input type="hidden" name="ticket_id" value="<?php echo $current_ticket['id']; ?>">
        <button name="finish" class="btn btn-light">Selesai</button>
      </form>
    <?php else: ?>
      <form method="post">
        <button name="call_next" class="btn ticket-btn">Panggil Berikutnya</button>
      </form>
    <?php endif; ?>
  </div>

  <div class="mt-4 text-center">
    <a href="index.php" class="btn btn-outline-light btn-sm">Kembali</a>
  </div>
</div>

<!-- 🔔 Audio Notifikasi -->
<audio id="notifSound" src="notif.mp3" preload="auto"></audio>

<!-- 🔔 Pop-up Notifikasi Visual -->
<div id="notifPopup" 
     style="display:none; position:fixed; top:20px; right:20px; background:#28a745; color:white; 
     padding:15px 20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.4); z-index:9999;">
  🔔 Antrean baru telah masuk!
</div>

<script>
// Simpan jumlah antrean terakhir
let lastWaiting = <?php echo $waiting; ?>;

async function checkNewAntrean() {
  try {
    const response = await fetch('check_waiting.php');
    const data = await response.json();

    const countElem = document.getElementById('waitingCount');
    if (data.total !== undefined) {
      countElem.textContent = data.total;

      // Jika antrean baru masuk
      if (data.total > lastWaiting) {
        const sound = document.getElementById('notifSound');
        sound.currentTime = 0;
        sound.play();

        showPopup();
      }

      lastWaiting = data.total;
    }
  } catch (err) {
    console.error("Gagal mengambil data antrean:", err);
  }
}

// Fungsi untuk menampilkan pop-up notifikasi
function showPopup() {
  const popup = document.getElementById('notifPopup');
  popup.style.display = 'block';
  setTimeout(() => {
    popup.style.display = 'none';
  }, 3000);
}

// Jalankan setiap 5 detik
setInterval(checkNewAntrean, 5000);
</script>

<?php include 'inc/footer.php'; ?>

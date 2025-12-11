<?php
session_start();
require_once '../db.php';

// Proteksi: hanya admin yang bisa masuk
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// --- CEK KONEKSI & AKTIFKAN LAPORAN ERROR ---
mysqli_report(MYSQLI_REPORT_OFF);

// Fungsi bantu agar aman fetch_assoc()
function safe_count($mysqli, $query) {
    $res = $mysqli->query($query);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row['cnt'] ?? 0;
    }
    return 0;
}

// Statistik tiket
$waiting = safe_count($mysqli, "SELECT COUNT(*) as cnt FROM tickets WHERE status='waiting'");
$serving = safe_count($mysqli, "SELECT COUNT(*) as cnt FROM tickets WHERE status='serving'");
$done    = safe_count($mysqli, "SELECT COUNT(*) as cnt FROM tickets WHERE status='done'");

// --- Ambil daftar film ---
$films = $mysqli->query("SELECT * FROM films ORDER BY id DESC");
if ($films === false) {
    die("<div style='color:red'>Query film gagal: " . htmlspecialchars($mysqli->error) . "</div>");
}

// --- Tambah film ---
if (isset($_POST['add_film'])) {
    $title = trim($_POST['title']);
    $duration = intval($_POST['duration']);
    if ($title !== '') {
        $stmt = $mysqli->prepare("INSERT INTO films (title, duration) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("si", $title, $duration);
            $stmt->execute();
            header("Location: dashboard.php");
            exit;
        } else {
            die("<div style='color:red'>Gagal menambah film: " . htmlspecialchars($mysqli->error) . "</div>");
        }
    }
}

// --- Hapus film ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($mysqli->query("DELETE FROM films WHERE id=$id") === false) {
        die("<div style='color:red'>Gagal menghapus film: " . htmlspecialchars($mysqli->error) . "</div>");
    }
    header("Location: dashboard.php");
    exit;
}

include '../inc/header.php';
?>

<div class="row">
  <!-- Sidebar -->
  <div class="col-md-3">
    <div class="card-ghost p-3 mb-3">
      <h5 class="text-white">Admin</h5>
      <p class="text-muted-ghost mb-1"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>

    <div class="card-ghost p-3">
      <h6 class="text-white">Statistik</h6>
      <div class="mt-2">
        <div class="mb-2 text-muted-ghost">Menunggu: <strong><?php echo $waiting;?></strong></div>
        <div class="mb-2 text-muted-ghost">Sedang: <strong><?php echo $serving;?></strong></div>
        <div class="mb-2 text-muted-ghost">Selesai: <strong><?php echo $done;?></strong></div>
      </div>
      <a href="export_csv.php" class="btn btn-light btn-sm mt-2">Ekspor CSV</a>
    </div>
  </div>

  <!-- Konten utama -->
  <div class="col-md-9">
    <!-- Kelola Film -->
    <div class="card-ghost p-3 mb-4">
      <h5 class="text-white mb-3">🎬 Kelola Film</h5>
      <form method="post" class="row g-2 align-items-end">
        <div class="col-md-6">
          <label class="form-label text-muted-ghost">Judul Film</label>
          <input type="text" name="title" class="form-control" placeholder="Masukkan judul film" required>
        </div>
        <div class="col-md-3">
          <label class="form-label text-muted-ghost">Durasi (menit)</label>
          <input type="number" name="duration" class="form-control" min="1" placeholder="120">
        </div>
        <div class="col-md-3">
          <button type="submit" name="add_film" class="btn btn-warning w-100">Tambah Film</button>
        </div>
      </form>

      <div class="table-responsive mt-3">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr class="text-muted-ghost">
              <th>ID</th>
              <th>Judul</th>
              <th>Durasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($films && $films->num_rows > 0): ?>
              <?php while($film = $films->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $film['id']; ?></td>
                  <td><?php echo htmlspecialchars($film['title']); ?></td>
                  <td><?php echo htmlspecialchars($film['duration']); ?> menit</td>
                  <td>
                    <a href="?delete=<?php echo $film['id']; ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus film ini?')">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center text-muted-ghost">Belum ada data film.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Riwayat Antrian -->
    <div class="card-ghost p-3">
      <h5 class="text-white">📋 Riwayat Terbaru</h5>
      <div class="table-responsive mt-3">
        <table class="table table-borderless text-white">
          <thead>
            <tr class="text-muted-ghost">
              <th>Kode</th>
              <th>Nama</th>
              <th>Film</th>
              <th>Waktu Layani</th>
              <th>Loket</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $hist = $mysqli->query("SELECT t.*, f.title AS film_title, c.name AS counter_name 
                                    FROM tickets t 
                                    LEFT JOIN films f ON t.film_id = f.id 
                                    LEFT JOIN counters c ON t.counter_id = c.id 
                                    WHERE t.status IN ('done','serving') 
                                    ORDER BY t.served_at DESC LIMIT 200");
            if ($hist && $hist->num_rows > 0):
              while($r = $hist->fetch_assoc()):
            ?>
            <tr>
              <td><?php echo htmlspecialchars($r['code']); ?></td>
              <td><?php echo htmlspecialchars($r['name']); ?></td>
              <td><?php echo htmlspecialchars($r['film_title']); ?></td>
              <td><?php echo htmlspecialchars($r['served_at']); ?></td>
              <td><?php echo htmlspecialchars($r['counter_name']); ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" class="text-center text-muted-ghost">Belum ada data riwayat.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>

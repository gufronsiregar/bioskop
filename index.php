<?php
require_once 'db.php';
session_start();

// --- LOGIN PETUGAS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_petugas'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $valid_user = 'petugas';
    $valid_pass = '12345';

    if ($username === $valid_user && $password === $valid_pass) {
        $_SESSION['petugas'] = $username;
        header('Location: loket.php');
        exit;
    } else {
        $error_login = "❌ Username atau password salah!";
    }
}

// --- CEK KONEKSI & CEK TABEL FILMS ---
$check_films = $mysqli->query("SHOW TABLES LIKE 'films'");
if ($check_films && $check_films->num_rows > 0) {
    $films = $mysqli->query("SELECT * FROM films ORDER BY id");
} else {
    $films = false;
}

// --- PROSES AMBIL TIKET BARU ---
$ticket_info = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ambil'])) {
    $name = trim($_POST['name']) ?: 'Pengunjung';
    $film_id = intval($_POST['film_id']);
    $payment_method = $_POST['payment_method'] ?? 'Tunai';
    $card_number = isset($_POST['card_number']) ? preg_replace('/\s+/', '', trim($_POST['card_number'])) : null;

    $check = $mysqli->query("SHOW TABLES LIKE 'tickets_new'");
    if ($check->num_rows == 0) {
        die("<b>❌ Error:</b> Tabel <code>tickets_new</code> belum ada di database.");
    }

    // Ambil ID terakhir untuk kode
    $res = $mysqli->query("SELECT MAX(id) as mx FROM tickets_new");
    $row = $res ? $res->fetch_assoc() : ['mx' => 0];
    $next = intval($row['mx']) + 1;
    $code = 'B-' . str_pad($next, 3, '0', STR_PAD_LEFT);

    // Simpan tiket baru
    $stmt = $mysqli->prepare("INSERT INTO tickets_new (code, name, film_id, payment_method, card_number, status) VALUES (?, ?, ?, ?, ?, 'waiting')");
    $stmt->bind_param('ssiss', $code, $name, $film_id, $payment_method, $card_number);
    $stmt->execute();
    $ticket_id = $stmt->insert_id;

    // Ambil data tiket untuk ditampilkan
    $ticket_info = $mysqli->query("SELECT * FROM tickets_new WHERE id=$ticket_id")->fetch_assoc();
}

// --- ANTRIAN SAAT INI ---
$current = $mysqli->query("SELECT code FROM tickets_new WHERE status='serving' ORDER BY id DESC LIMIT 1");
$current_code = ($current && $current->num_rows > 0) ? $current->fetch_assoc()['code'] : 'B-XXX';

// --- JUMLAH MENUNGGU ---
$waiting = 0;
$wait_query = $mysqli->query("SELECT COUNT(*) as cnt FROM tickets_new WHERE status='waiting'");
if ($wait_query && $wait_query->num_rows > 0) {
    $waiting = $wait_query->fetch_assoc()['cnt'];
}

include 'inc/header.php';
?>

<div class="card-ghost p-4 mb-4">
  <div class="row g-4">
    <div class="col-md-7">
      <?php if ($ticket_info): ?>
        <div class="p-4 ticket-card text-center">
          <h3 class="text-white mb-3">Tiket Berhasil Diambil!</h3>
          <p class="text-muted-ghost">Kode Tiket: <b><?= htmlspecialchars($ticket_info['code']); ?></b></p>
          <p>Nama: <?= htmlspecialchars($ticket_info['name']); ?></p>
          <p>Metode Pembayaran: <b><?= htmlspecialchars($ticket_info['payment_method']); ?></b></p>

          <?php if ($ticket_info['payment_method'] === 'E-Wallet'): ?>
            <div class="my-3">
              <img src="https://api.qrserver.com/v1/create-qr-code/?data=QRIS-<?= urlencode($ticket_info['code']); ?>&size=200x200" alt="QRIS" class="rounded shadow">
            </div>
            <p class="text-muted-ghost small">Scan QR di atas untuk membayar via e-wallet (QRIS).</p>

          <?php elseif ($ticket_info['payment_method'] === 'Kartu'): ?>
            <?php
              $card = $ticket_info['card_number'] ?? '';
              $last4 = $card ? substr($card, -4) : '';
              $masked = $last4 ? '**** **** **** ' . $last4 : '(nomor kartu tidak diisi)';
            ?>
            <p>Nomor Kartu: <?= htmlspecialchars($masked); ?></p>
            <p class="small text-muted-ghost">Gunakan mesin EDC untuk verifikasi pembayaran kartu Anda.</p>
          <?php endif; ?>

          <div class="mt-3">
            <a href="print_ticket.php?id=<?= $ticket_info['id']; ?>" class="btn btn-success">🧾 Cetak Tiket</a>
            <a href="index.php" class="btn btn-outline-light ms-2">Kembali</a>
          </div>
        </div>
      <?php else: ?>
        <!-- FORM AMBIL TIKET -->
        <div class="p-4 ticket-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h2 class="mb-0 text-white">Ambil Tiket Antrean</h2>
              <small class="text-muted-ghost">Pilih film, metode pembayaran, dan ambil tiket Anda</small>
            </div>
            <div class="text-end text-muted-ghost">
              <div>Menunggu</div>
              <div class="h3 mb-0"><?= $waiting; ?></div>
            </div>
          </div>

          <form method="post" class="row g-2 align-items-end" id="ambilForm">
            <div class="col-12">
              <label class="form-label text-muted-ghost">Pilih Film</label>
              <select name="film_id" class="form-select form-control" required>
                <option value="">-- Pilih Film --</option>
                <?php if ($films && $films->num_rows > 0): ?>
                  <?php while($f = $films->fetch_assoc()): ?>
                    <option value="<?= $f['id']; ?>"><?= htmlspecialchars($f['title']); ?></option>
                  <?php endwhile; ?>
                <?php else: ?>
                  <option value="">❌ Belum ada data film</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label text-muted-ghost">Metode Pembayaran</label>
              <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="Tunai">Tunai</option>
                <option value="E-Wallet">E-Wallet (QRIS)</option>
                <option value="Kartu">Kartu Debit/Kredit</option>
              </select>
            </div>

            <div class="col-12" id="card-field" style="display:none;">
              <label class="form-label text-muted-ghost">Nomor Kartu</label>
              <input type="text" name="card_number" id="card_number" class="form-control" maxlength="19" placeholder="1234 5678 9012 3456">
            </div>

            <div class="col-12 col-sm-8">
              <label class="form-label text-muted-ghost">Nama (opsional)</label>
              <input type="text" name="name" class="form-control" placeholder="Nama Anda">
            </div>

            <div class="col-12 col-sm-4 text-sm-end">
              <button name="ambil" class="ticket-btn w-100">Ambil Tiket <i class="fa-solid fa-ticket ms-2"></i></button>
            </div>
          </form>
        </div>

        <div class="mt-4 text-muted-ghost">
          <h6 class="text-white">Cara kerja</h6>
          <ol>
            <li>Ambil tiket untuk film yang diinginkan.</li>
            <li>Pilih metode pembayaran (Tunai / E-Wallet / Kartu).</li>
            <li>Jika E-Wallet akan muncul QRIS, jika Kartu masukkan nomor kartu Anda.</li>
          </ol>
        </div>
      <?php endif; ?>
    </div>

    <!-- ANTRIAN SAAT INI -->
    <div class="col-md-5">
      <div class="p-4 card-ghost text-center">
        <h5 class="text-white">Antrian Saat Ini</h5>
        <div class="mt-3 ticket-card text-center">
          <div class="ticket-code text-danger fw-bold" style="font-size:2.5rem;">
            <?= htmlspecialchars($current_code); ?>
          </div>
          <div class="ticket-meta mt-2">
            <?php if($current_code !== 'B-XXX'): ?>
              Nomor antrian yang sedang dilayani
            <?php else: ?>
              Belum ada antrian berlangsung
            <?php endif; ?>
          </div>
        </div>
        <div class="mt-3 text-center">
          <button class="btn btn-outline-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginPetugasModal">Panel Petugas</button>
          <a href="admin/login.php" class="btn btn-light btn-sm">Login Admin</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Login Petugas -->
<div class="modal fade" id="loginPetugasModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">🔐 Login Petugas</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <?php if(!empty($error_login)): ?>
            <div class="alert alert-danger py-2"><?= $error_login; ?></div>
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer border-secondary">
          <button type="submit" name="login_petugas" class="btn btn-primary w-100">Masuk</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function() {
  const cardField = document.getElementById('card-field');
  cardField.style.display = (this.value === 'Kartu') ? 'block' : 'none';
});
document.getElementById('card_number').addEventListener('input', function() {
  let val = this.value.replace(/\D/g, '').substring(0, 19);
  this.value = val.replace(/(.{4})/g, '$1 ').trim();
});
</script>

<?php include 'inc/footer.php'; ?>

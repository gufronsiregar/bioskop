<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Abditama - Antrian Bioskop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS - Inline untuk memastikan ter-load -->
    <style>
        :root {
            --red: #e50914;
            --dark: #0b0b0b;
            --muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
        }

        body.bg-dark-theme {
            background: radial-gradient(circle at top left, #1a0000, #000);
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            min-height: 100vh;
        }

        .logo-circle {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--red);
        }

        .card-ghost {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(0, 0, 0, 0.06));
            border: 1px solid rgba(255, 255, 255, 0.04);
            color: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
        }

        .ticket-btn {
            background: linear-gradient(90deg, var(--red), #6f1010);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(233, 9, 20, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ticket-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(233, 9, 20, 0.3);
        }

        .ticket-card {
            background: linear-gradient(180deg, #2a0b0b, #120606);
            padding: 18px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .ticket-code {
            font-size: 48px;
            font-weight: 800;
            color: var(--red);
            letter-spacing: 2px;
        }

        .text-muted-ghost {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0.65rem 0.75rem;
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--red);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(229, 9, 20, 0.25);
            outline: 0;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-select option {
            background: #1a1a1a;
            color: #fff;
        }

        .table td, .table th {
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            color: #fff;
            background: transparent;
        }

        .table {
            color: #fff;
        }

        a.btn-light {
            background: #fff;
            color: #000;
        }

        .btn-outline-light:hover {
            background: #fff;
            color: #000;
        }

        .small-muted {
            color: var(--muted);
        }

        .modal-content.bg-dark {
            background: linear-gradient(180deg, #1a1a1a, #0d0d0d) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .border-secondary {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border-color: rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.15);
            border-color: rgba(25, 135, 84, 0.3);
            color: #6dd194;
        }

        .alert-info {
            background: rgba(13, 202, 240, 0.15);
            border-color: rgba(13, 202, 240, 0.3);
            color: #6edff6;
        }

        header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .ticket-meta {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        /* Center Screen untuk Login */
        .center-screen {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Form styling khusus */
        .form-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            transition: all 0.3s ease;
        }

        .form-control:hover, .form-select:hover {
            border-color: rgba(229, 9, 20, 0.3);
        }

        code {
            background: rgba(255, 255, 255, 0.05);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        hr {
            opacity: 0.1;
        }
    </style>
</head>
<body class="bg-dark-theme">
    <header class="py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="logo-circle">
                    <i class="fa-solid fa-film fa-lg"></i>
                </div>
                <div>
                    <div class="h5 mb-0 text-white fw-bold">Cinema Abditama</div>
                    <small class="text-muted-ghost">Sistem Antrian Bioskop</small>
                </div>
            </div>
            <nav>
                <?php 
                // Deteksi apakah di folder admin atau root
                $is_admin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
                $base = $is_admin ? '../' : '';
                ?>
                <a href="<?= $base ?>index.php" class="btn btn-outline-light btn-sm me-2">Beranda</a>
                <a href="<?= $base ?>loket.php" class="btn btn-outline-light btn-sm me-2">Petugas</a>
                <a href="<?= $base ?>admin/login.php" class="btn btn-light btn-sm">Admin</a>
            </nav>
        </div>
    </header>
    <main class="py-4">
        <div class="container">

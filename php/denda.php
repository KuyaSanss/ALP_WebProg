<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // 1. Mengambil data untuk Summary Cards di bagian atas
    // Menghitung berdasarkan enum 'Lunas' dan 'Belum Bayar' yang ada di database Anda
    $summary_sql = "SELECT 
                        SUM(NominalBayar) as total_fines,
                        SUM(CASE WHEN StatusPembayaran = 'Lunas' THEN NominalBayar ELSE 0 END) as total_paid,
                        SUM(CASE WHEN StatusPembayaran = 'Belum Bayar' THEN NominalBayar ELSE 0 END) as total_unpaid
                    FROM denda";
    $summary_result = mysqli_query($conn, $summary_sql);
    $summary = mysqli_fetch_assoc($summary_result);

    $total_fines = $summary['total_fines'] ? $summary['total_fines'] : 0;
    $total_paid = $summary['total_paid'] ? $summary['total_paid'] : 0;
    $total_unpaid = $summary['total_unpaid'] ? $summary['total_unpaid'] : 0;

    // 2. Mengambil parameter filter dari URL
    $current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

    // 3. Menyiapkan query untuk tabel utama
    $sql = "SELECT 
                d.DendaID, 
                a.Nama AS member_name, 
                d.NominalBayar AS amount, 
                d.HariKeterlambatan AS late_days, 
                d.StatusPembayaran AS status, 
                d.MetodeBayar AS payment_method
            FROM denda d
            JOIN peminjaman p ON d.PeminjamanID = p.PeminjamanID
            JOIN anggota a ON p.AnggotaID = a.AnggotaID";

    // Filter berdasarkan status
    if ($current_filter == 'Unpaid') {
        $sql .= " WHERE d.StatusPembayaran = 'Belum Bayar'";
    } elseif ($current_filter == 'Paid') {
        $sql .= " WHERE d.StatusPembayaran = 'Lunas'";
    }
    
    $sql .= " ORDER BY d.DendaID DESC";

    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Management - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
            <span class="logo-text">Knowledge Journey</span>
        </div>
        
        <nav class="nav-links">
            <a href="adminRegister.php"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="member.php"><i class="fa-solid fa-users"></i> Members</a>
            <a href="book.php"><i class="fa-solid fa-book"></i> Books</a>
            <a href="staff.php"><i class="fa-solid fa-user-tie"></i> Staff</a>
            <a href="peminjaman.php"><i class="fa-solid fa-clipboard-list"></i> Borrowing</a>
            <a href="pengembalian.php"><i class="fa-solid fa-rotate-left"></i> Returns</a>
            <a href="denda.php" class="active"><i class="fa-solid fa-dollar-sign"></i> Fines</a>
        </nav>

        <div class="nav-actions">
            <button class="icon-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            <button class="icon-btn position-relative">
                <i class="fa-regular fa-bell"></i>
                <span class="badge"></span>
            </button>
            <div class="avatar">A</div>
        </div>
    </header>

    <main class="main-container">
        
        <div class="page-header" style="margin-bottom: 25px;">
            <div>
                <h1 class="page-title">Fines Management</h1>
                <p class="page-subtitle">Track and manage all library fines</p>
            </div>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="card-icon icon-red">
                    <i class="fa-solid fa-rupiah-sign"></i>
                </div>
                <div class="card-info">
                    <p class="card-title">Total Unpaid</p>
                    <h3 class="card-amount">Rp <?= number_format($total_unpaid, 0, ',', '.') ?></h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon icon-green">
                    <i class="fa-solid fa-rupiah-sign"></i>
                </div>
                <div class="card-info">
                    <p class="card-title">Total Paid</p>
                    <h3 class="card-amount">Rp <?= number_format($total_paid, 0, ',', '.') ?></h3>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-icon icon-blue">
                    <i class="fa-solid fa-rupiah-sign"></i>
                </div>
                <div class="card-info">
                    <p class="card-title">Total Fines</p>
                    <h3 class="card-amount">Rp <?= number_format($total_fines, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <div class="filter-section">
            <span class="filter-label">Filter:</span>
            <a href="fines.php?filter=All" class="btn-filter <?= ($current_filter == 'All') ? 'active' : '' ?>">All</a>
            <a href="fines.php?filter=Unpaid" class="btn-filter <?= ($current_filter == 'Unpaid') ? 'active' : '' ?>">Unpaid</a>
            <a href="fines.php?filter=Paid" class="btn-filter <?= ($current_filter == 'Paid') ? 'active' : '' ?>">Paid</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fine ID</th>
                        <th>Member Name</th>
                        <th>Amount</th>
                        <th>Late Days</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium text-gray"><?= sprintf("FIN%03d", $row['DendaID']) ?></td>
                                <td>
                                    <i class="fa-regular fa-user table-icon"></i> 
                                    <span class="font-medium"><?= htmlspecialchars($row['member_name']) ?></span>
                                </td>
                                <td class="font-medium">
                                    <span class="text-gray" style="margin-right:4px;">Rp</span><?= number_format($row['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="text-gray">
                                    <i class="fa-regular fa-calendar table-icon"></i> 
                                    <?= htmlspecialchars($row['late_days']) ?> <?= ($row['late_days'] > 1) ? 'days' : 'day' ?>
                                </td>
                                
                                <td>
                                    <?php if($row['status'] == 'Lunas'): ?>
                                        <span class="pill pill-success">Paid</span>
                                    <?php else: ?>
                                        <span class="pill pill-danger">Unpaid</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-gray">
                                    <?php 
                                        $method = $row['payment_method'];
                                        if (empty($method) || $row['status'] == 'Belum Bayar') {
                                            echo '-';
                                        } else {
                                            // Menyesuaikan ikon dengan enum (Cash, Transfer, QRIS)
                                            if ($method == 'Cash') {
                                                $icon = 'fa-money-bill';
                                            } elseif ($method == 'QRIS') {
                                                $icon = 'fa-qrcode';
                                            } else {
                                                $icon = 'fa-building-columns'; // Untuk Transfer
                                            }
                                            echo "<i class='fa-solid {$icon} table-icon'></i> " . htmlspecialchars($method);
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6B7280;">Tidak ada data denda saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
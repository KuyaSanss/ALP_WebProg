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

    // 1. Total Books
    $q_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"));
    $total_books = $q_books['total'];

    // 2. Active Borrowings
    $q_borrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM detailpeminjaman WHERE StatusPeminjaman = 'Dipinjam'"));
    $active_borrowings = $q_borrow['total'];

    // 3. Late Returns
    $q_late = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM detailpeminjaman dp 
        JOIN peminjaman p ON dp.PeminjamanID = p.PeminjamanID 
        WHERE dp.StatusPeminjaman = 'Dipinjam' AND CURDATE() > p.TanggalTenggatPengembalian
    "));
    $late_returns = $q_late['total'];

    // 4. Total Members
    $q_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"));
    $total_members = $q_members['total'];

    // 5. Staff Members
    $q_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM staff"));
    $total_staff = $q_staff['total'];

    // 6. Unpaid Fines
    $q_fines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(NominalBayar) as total FROM denda WHERE StatusPembayaran = 'Belum Bayar'"));
    $unpaid_fines = $q_fines['total'] ? $q_fines['total'] : 0;

    // 7. Recent Activities (Menarik 5 peminjaman terbaru)
    $q_recent = mysqli_query($conn, "
        SELECT a.Nama, b.JudulBuku, p.TanggalPeminjaman 
        FROM detailpeminjaman dp
        JOIN peminjaman p ON dp.PeminjamanID = p.PeminjamanID
        JOIN anggota a ON p.AnggotaID = a.AnggotaID
        JOIN buku b ON dp.BukuID = b.BukuID
        ORDER BY p.PeminjamanID DESC LIMIT 4
    ");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Knowledge Journey</title>
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
            <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="member.php"><i class="fa-solid fa-users"></i> Members</a>
            <a href="book.php"><i class="fa-solid fa-book"></i> Books</a>
            <a href="staff.php"><i class="fa-solid fa-user-tie"></i> Staff</a>
            <a href="peminjaman.php"><i class="fa-solid fa-clipboard-list"></i> Borrowing</a>
            <a href="pengembalian.php"><i class="fa-solid fa-rotate-left"></i> Returns</a>
            <a href="denda.php"><i class="fa-solid fa-dollar-sign"></i> Fines</a>
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
                <h1 class="page-title">Admin Dashboard</h1>
                <p class="page-subtitle">Manage your library operations efficiently</p>
            </div>
        </div>

        <div class="dashboard-metrics">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-green-light"><i class="fa-solid fa-book text-green-dark"></i></div>
                </div>
                <h2 class="metric-value"><?= number_format($total_books) ?></h2>
                <p class="metric-label">Total Books</p>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-green-light"><i class="fa-regular fa-clock text-green-dark"></i></div>
                </div>
                <h2 class="metric-value"><?= number_format($active_borrowings) ?></h2>
                <p class="metric-label">Active Borrowings</p>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-red-light"><i class="fa-solid fa-circle-exclamation text-red-dark"></i></div>
                </div>
                <h2 class="metric-value"><?= number_format($late_returns) ?></h2>
                <p class="metric-label">Late Returns</p>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-green-light"><i class="fa-solid fa-user-group text-green-dark"></i></div>
                </div>
                <h2 class="metric-value"><?= number_format($total_members) ?></h2>
                <p class="metric-label">Total Members</p>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-blue-light"><i class="fa-solid fa-user-tie text-blue-dark"></i></div>
                </div>
                <h2 class="metric-value"><?= number_format($total_staff) ?></h2>
                <p class="metric-label">Staff Members</p>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon bg-orange-light"><i class="fa-solid fa-dollar-sign text-orange-dark"></i></div>
                </div>
                <h2 class="metric-value">Rp <?= number_format($unpaid_fines, 0, ',', '.') ?></h2>
                <p class="metric-label">Unpaid Fines</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="recent-activities">
                <h3 class="section-title">Recent Activities</h3>
                <div class="activity-list">
                    <?php while($activity = mysqli_fetch_assoc($q_recent)): ?>
                    <div class="activity-item">
                        <div class="activity-icon bg-green-light text-green-dark"><i class="fa-solid fa-book-open"></i></div>
                        <div class="activity-details">
                            <h4><?= htmlspecialchars($activity['Nama']) ?></h4>
                            <p>Borrowed: <?= htmlspecialchars($activity['JudulBuku']) ?></p>
                        </div>
                        <div class="activity-time"><?= date("d M Y", strtotime($activity['TanggalPeminjaman'])) ?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="dashboard-sidebar">
                <div class="quick-actions">
                    <h3 class="section-title">Quick Actions</h3>
                    <a href="book.php" class="action-btn btn-dark-green"><i class="fa-solid fa-book"></i> Add New Book</a>
                    <a href="member.php" class="action-btn btn-mid-green"><i class="fa-solid fa-user-group"></i> Register Member</a>
                    <a href="pengembalian.php" class="action-btn btn-light-green"><i class="fa-regular fa-clock"></i> Process Return</a>
                    <a href="#" class="action-btn btn-blue"><i class="fa-solid fa-chart-line"></i> View Reports</a>
                </div>

                <div class="system-health">
                    <h3 class="section-title text-white">System Health</h3>
                    <div class="health-item">
                        <span>Database</span>
                        <span class="status-pill online">Online</span>
                    </div>
                    <div class="health-item">
                        <span>Backup</span>
                        <span class="status-pill active">Active</span>
                    </div>
                    <div class="health-item">
                        <span>Last Sync</span>
                        <span class="sync-time">Just now</span>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>
</html>
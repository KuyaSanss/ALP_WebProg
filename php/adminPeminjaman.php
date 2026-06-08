<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    // Nama database yang baru
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Mengambil parameter filter dari URL
    $current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

    // Query untuk mengambil data yang sedang "Dipinjam"
    // Logika CASE digunakan untuk menentukan apakah statusnya Active atau Late berdasarkan tanggal hari ini
    $sql = "SELECT 
                p.PeminjamanID, 
                a.Nama AS member_name, 
                b.JudulBuku AS book_title, 
                p.TanggalPeminjaman AS borrow_date, 
                p.TanggalTenggatPengembalian AS due_date, 
                CASE 
                    WHEN CURDATE() > p.TanggalTenggatPengembalian THEN 'Late'
                    ELSE 'Active'
                END AS status
            FROM detailpeminjaman dp
            JOIN peminjaman p ON dp.PeminjamanID = p.PeminjamanID
            JOIN anggota a ON p.AnggotaID = a.AnggotaID
            JOIN buku b ON dp.BukuID = b.BukuID
            WHERE dp.StatusPeminjaman = 'Dipinjam'";

    // Menambahkan kondisi filter berdasarkan hasil alias 'status' dari logika CASE di atas
    if ($current_filter == 'Active') {
        $sql .= " AND CURDATE() <= p.TanggalTenggatPengembalian";
    } elseif ($current_filter == 'Late') {
        $sql .= " AND CURDATE() > p.TanggalTenggatPengembalian";
    }
    
    // Urutkan dari yang terbaru
    $sql .= " ORDER BY p.TanggalPeminjaman DESC";

    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Management - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <main class="main-container">
        <div class="page-header" style="margin-bottom: 20px;">
            <div>
                <h1 class="page-title">Borrowing Management</h1>
                <p class="page-subtitle">Track and manage all book borrowings</p>
            </div>
        </div>

        <div class="filter-section">
            <span class="filter-label">Filter:</span>
            <a href="borrowing.php?filter=All" class="btn-filter <?= ($current_filter == 'All') ? 'active' : '' ?>">All</a>
            <a href="borrowing.php?filter=Active" class="btn-filter <?= ($current_filter == 'Active') ? 'active' : '' ?>">Active</a>
            <a href="borrowing.php?filter=Late" class="btn-filter <?= ($current_filter == 'Late') ? 'active' : '' ?>">Late</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Borrowing ID</th>
                        <th>Member Name</th>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium text-gray">
                                    <?= sprintf("BRW%03d", $row['PeminjamanID']) ?>
                                </td>
                                <td>
                                    <i class="fa-regular fa-user table-icon"></i> 
                                    <span class="font-medium"><?= htmlspecialchars($row['member_name']) ?></span>
                                </td>
                                <td>
                                    <i class="fa-solid fa-book table-icon" style="opacity: 0.5;"></i> 
                                    <span class="text-gray"><?= htmlspecialchars($row['book_title']) ?></span>
                                </td>
                                <td class="text-gray">
                                    <i class="fa-regular fa-calendar table-icon"></i> 
                                    <?= htmlspecialchars($row['borrow_date']) ?>
                                </td>
                                
                                <?php $date_class = ($row['status'] == 'Late') ? 'text-red-date font-medium' : 'text-gray'; ?>
                                <td class="<?= $date_class ?>">
                                    <i class="fa-regular fa-calendar table-icon <?= ($row['status'] == 'Late') ? 'text-red-date' : '' ?>"></i> 
                                    <?= htmlspecialchars($row['due_date']) ?>
                                </td>

                                <td>
                                    <?php if($row['status'] == 'Active'): ?>
                                        <span class="pill pill-success">Active</span>
                                    <?php else: ?>
                                        <span class="pill pill-danger">Late</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6B7280;">Tidak ada data peminjaman yang sedang berlangsung.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
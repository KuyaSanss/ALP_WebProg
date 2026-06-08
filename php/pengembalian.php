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

    /* Query ini memulai pencarian dari tabel detailpeminjaman (dp) 
       karena status 'Dikembalikan' dan data spesifik buku ada di sana.
       Kemudian di-JOIN ke peminjaman (p), anggota (a), buku (b), dan denda (d).
    */
    $sql = "SELECT 
                dp.DetailPeminjamanID, 
                a.Nama AS member_name, 
                b.JudulBuku AS book_title, 
                COALESCE(d.TanggalBayar, p.TanggalTenggatPengembalian) AS return_date,
                COALESCE(d.NominalBayar, 0) AS fine,
                CASE 
                    WHEN d.DendaID IS NOT NULL AND d.NominalBayar > 0 THEN 'Late Return'
                    ELSE 'On Time'
                END AS return_status
            FROM detailpeminjaman dp
            JOIN peminjaman p ON dp.PeminjamanID = p.PeminjamanID
            JOIN anggota a ON p.AnggotaID = a.AnggotaID
            JOIN buku b ON dp.BukuID = b.BukuID
            LEFT JOIN denda d ON p.PeminjamanID = d.PeminjamanID
            WHERE dp.StatusPeminjaman = 'Dikembalikan'
            ORDER BY return_date DESC";

    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns Management - Knowledge Journey</title>
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
            <a href="pengembalian.php" class="active"><i class="fa-solid fa-rotate-left"></i> Returns</a>
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
        
        <div class="page-header" style="margin-bottom: 30px;">
            <div>
                <h1 class="page-title">Returns Management</h1>
                <p class="page-subtitle">Track all returned books and late returns</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Return ID</th>
                        <th>Member Name</th>
                        <th>Book Title</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium text-gray">
                                    <?= sprintf("RET%03d", $row['DetailPeminjamanID']) ?>
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
                                    <?= htmlspecialchars($row['return_date']) ?>
                                </td>
                                
                                <td>
                                    <?php if($row['return_status'] == 'On Time'): ?>
                                        <span class="pill pill-success">On Time</span>
                                    <?php else: ?>
                                        <span class="pill pill-danger">Late Return</span>
                                    <?php endif; ?>
                                </td>

                                <?php 
                                    $fine_amount = $row['fine'];
                                    $fine_class = ($fine_amount > 0) ? 'text-red-fine' : 'text-green-fine';
                                    $fine_text = ($fine_amount > 0) ? "Rp " . number_format($fine_amount, 2) : "Rp0.00";
                                ?>
                                <td class="font-medium <?= $fine_class ?>">
                                    <?= $fine_text ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6B7280;">Tidak ada data buku yang dikembalikan saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
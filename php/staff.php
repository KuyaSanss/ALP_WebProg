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

    // Mengambil semua data dari tabel staff
    $sql = "SELECT StaffID, NamaStaff, Jabatan, NomerHandphone FROM staff ORDER BY StaffID ASC";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Knowledge Journey</title>
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
            <a href="staff.php" class="active"><i class="fa-solid fa-user-tie"></i> Staff</a>
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
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Staff Management</h1>
                <p class="page-subtitle">Manage library staff members and their roles</p>
            </div>
            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add New Staff
            </button>
        </div>

        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" placeholder="Search staff...">
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="text-gray"><?= sprintf("STF%03d", $row['StaffID']) ?></td>
                                <td class="font-medium"><?= htmlspecialchars($row['NamaStaff']) ?></td>
                                <td>
                                    <span class="pill pill-blue"><?= htmlspecialchars($row['Jabatan']) ?></span>
                                </td>
                                <td class="text-gray"><?= htmlspecialchars($row['NomerHandphone']) ?></td>
                                <td>
                                    <div class="action-icons">
                                        <i class="fa-regular fa-eye text-blue" title="View"></i>
                                        <i class="fa-regular fa-pen-to-square text-green" title="Edit"></i>
                                        <i class="fa-regular fa-trash-can text-red" title="Delete"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px; color: #6B7280;">Tidak ada data staff.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
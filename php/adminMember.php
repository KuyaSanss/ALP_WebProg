<?php
    require_once "adminSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $sql = "SELECT AnggotaID, Nama, NIM, Alamat, NomerHandphone FROM Anggota ORDER BY AnggotaID DESC";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management - Knowledge Journey</title>
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
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Member Management</h1>
                <p class="page-subtitle">Manage library members and their information</p>
            </div>
            <a href="adminMemberAdd.php" style="text-decoration: none;">
                <button class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Member
                </button>
            </a>
        </div>

        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" placeholder="Search members...">
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium text-gray"><?= sprintf("MEM%03d", $row['AnggotaID']) ?></td>
                                <td class="font-medium"><?= htmlspecialchars($row['Nama']) ?></td>
                                <td class="text-gray"><?= htmlspecialchars($row['NIM']) ?></td>
                                <td class="text-gray"><?= htmlspecialchars($row['Alamat']) ?></td>
                                <td class="text-gray"><?= htmlspecialchars($row['NomerHandphone']) ?></td>
                                <td>
                                    <div class="action-icons">
                                        <a href="member_view.php?id=<?= $row['AnggotaID'] ?>" title="View Details" style="text-decoration: none;">
                                            <i class="fa-regular fa-eye text-blue"></i>
                                        </a>
                                        
                                        <a href="member_edit.php?id=<?= $row['AnggotaID'] ?>" title="Edit" style="text-decoration: none;">
                                            <i class="fa-regular fa-pen-to-square text-green"></i>
                                        </a>
                                        
                                        <a href="member_delete.php?id=<?= $row['AnggotaID'] ?>" title="Delete" style="text-decoration: none;" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                            <i class="fa-regular fa-trash-can text-red"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6B7280;">Tidak ada data anggota.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
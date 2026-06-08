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

    $sql = "SELECT StaffID, NamaStaff, Jabatan, NomerHandphone FROM staff ORDER BY StaffID DESC";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Knowledge Journey</title>
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
                <h1 class="page-title">Staff Management</h1>
                <p class="page-subtitle">Manage library staff members and their roles</p>
            </div>
            <a href="adminStaffAdd.php" style="text-decoration: none;">
                <button class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Staff
                </button>
            </a>
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
                            <tr id="row-<?= $row['StaffID'] ?>">
                                <td class="text-gray"><?= sprintf("STF%03d", $row['StaffID']) ?></td>
                                <td class="font-medium"><?= htmlspecialchars($row['NamaStaff']) ?></td>
                                <td>
                                    <span class="pill pill-blue"><?= htmlspecialchars($row['Jabatan']) ?></span>
                                </td>
                                <td class="text-gray"><?= htmlspecialchars($row['NomerHandphone']) ?></td>
                                <td>
                                    <div class="action-icons">
                                        <a href="adminStaffEdit.php?id=<?= $row['StaffID'] ?>" title="Edit" style="text-decoration: none;">
                                            <i class="fa-regular fa-pen-to-square text-green"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="delete-btn" data-id="<?= $row['StaffID'] ?>" title="Delete" style="text-decoration: none;">
                                            <i class="fa-regular fa-trash-can text-red"></i>
                                        </a>
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

    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var staffId = $(this).data('id');
                var rowElement = $('#row-' + staffId);

                if (confirm('Apakah Anda yakin ingin menghapus staff ini?')) {
                    $.ajax({
                        url: 'adminStaffDelete.php',
                        type: 'POST',
                        data: { id: staffId },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                rowElement.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            } else {
                                alert('Gagal menghapus data. ' + response);
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan komunikasi dengan server.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
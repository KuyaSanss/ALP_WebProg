<?php
    require_once "adminSessionChecker.php";
    
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/adminJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <main class="main-container">
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Book Management</h1>
                <p class="page-subtitle">Manage library books and inventory</p>
            </div>
            <a href="adminBookAdd.php" style="text-decoration: none;">
                <button class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Add New Book
                </button>
            </a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>ISBN</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * FROM buku ORDER BY BukuID DESC";
                        $result = mysqli_query($conn, $query);

                        while($buku = mysqli_fetch_assoc($result)){
                    ?>
                        <tr id="row-<?= $buku['BukuID'] ?>">
                            <td>
                                <img
                                    src="<?php echo !empty($buku['CoverBuku']) ? $buku['CoverBuku'] : 'https://via.placeholder.com/60x80?text=No+Cover'; ?>"
                                    alt="cover buku '<?php echo htmlspecialchars($buku['JudulBuku']); ?>'"
                                    class="w-[60px] h-[80px] object-cover rounded"
                                >
                            </td>
                            <td class="font-medium"><?php echo htmlspecialchars($buku['JudulBuku']); ?></td>
                            <td class="text-gray"><?php echo htmlspecialchars($buku['ISBN']); ?></td>
                            <td class="text-gray"><?php echo htmlspecialchars($buku['NamaPengarang']); ?></td>
                            <td class="text-gray"><?php echo htmlspecialchars($buku['Penerbit']); ?></td>
                            <td class="text-gray"><?php echo htmlspecialchars($buku['TahunTerbit']); ?></td>
                            <td>
                                <span class="pill pill-category"><?php echo htmlspecialchars($buku['KategoriBuku']); ?></span>
                            </td>
                            <td>
                                <?php if($buku['StatusKetersediaan'] == 'Tersedia'): ?>
                                    <span class="pill pill-success">Available</span>
                                <?php else: ?>
                                    <span class="pill pill-danger">Unavailable</span>
                                <?php endif; ?>
                            </td>

                            <td class="font-medium text-center">
                                <div class="action-icons">
                                    <a href="adminBookEdit.php?id=<?php echo $buku['BukuID']; ?>">
                                        <i class="fa-regular fa-pen-to-square text-green"></i>
                                    </a>

                                    <a href="javascript:void(0);" class="delete-btn" data-id="<?php echo $buku['BukuID']; ?>">
                                        <i class="fa-regular fa-trash-can text-red"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var bookId = $(this).data('id');
                var rowElement = $('#row-' + bookId);

                if (confirm('Apakah Anda yakin ingin menghapus buku ini dari database?')) {
                    $.ajax({
                        url: 'adminBookDelete.php',
                        type: 'POST',
                        data: { id: bookId },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                rowElement.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            } else {
                                alert('Gagal menghapus buku. ' + response);
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
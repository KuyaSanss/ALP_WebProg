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
    <script src="../js/globalJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <main class="main-container">
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Book Management</h1>
                <p class="page-subtitle">Manage library books and inventory</p>
            </div>
            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add New Book
            </button>
        </div>

        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" placeholder="Search books...">
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
                        $query = "SELECT * FROM buku";
                        $result = mysqli_query($conn, $query);

                        while($buku = mysqli_fetch_assoc($result)){
                    ?>
                        <tr>
                            <td>
                                <img
                                    src="<?php echo $buku['CoverBuku']; ?>"
                                    alt="cover buku '<?php echo $buku['JudulBuku']; ?>'"
                                    class="w-[60px] h-[80px] object-cover rounded"
                                >
                            </td>

                            <td class="font-medium">
                                <?php echo $buku['JudulBuku']; ?>
                            </td>

                            <td class="text-gray">
                                <?php echo $buku['ISBN']; ?>
                            </td>

                            <td class="text-gray">
                                <?php echo $buku['NamaPengarang']; ?>
                            </td>

                            <td class="text-gray">
                                <?php echo $buku['Penerbit']; ?>
                            </td>

                            <td class="text-gray">
                                <?php echo $buku['TahunTerbit']; ?>
                            </td>

                            <td>
                                <span class="pill pill-category">
                                    <?php echo $buku['KategoriBuku']; ?>
                                </span>
                            </td>

                            <td>
                                <?php
                                    if($buku['StatusKetersediaan'] == 'Tersedia'){
                                        echo '<span class="pill pill-success">Available</span>';
                                    }
                                    else{
                                        echo '<span class="pill pill-danger">Unavailable</span>';
                                    }
                                ?>
                            </td>

                            <td class="font-medium text-center">
                                -
                            </td>

                            <td>
                                <div class="action-icons">
                                    <a href="detailBook.php?id=<?php echo $buku['BukuID']; ?>">
                                        <i class="fa-regular fa-eye text-blue"></i>
                                    </a>

                                    <a href="editBook.php?id=<?php echo $buku['BukuID']; ?>">
                                        <i class="fa-regular fa-pen-to-square text-green"></i>
                                    </a>

                                    <a href="deleteBook.php?id=<?php echo $buku['BukuID']; ?>">
                                        <i class="fa-regular fa-trash-can text-red"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
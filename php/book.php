<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management - Knowledge Journey</title>
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
            <a href="book.php" class="active"><i class="fa-solid fa-book"></i> Books</a>
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
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="book-cover cover-1"></div></td>
                        <td class="font-medium">Introduction to<br>Algorithms</td>
                        <td class="text-gray">978-0-262-<br>04630-5</td>
                        <td class="text-gray">Thomas H.<br>Cormen</td>
                        <td class="text-gray">MIT Press</td>
                        <td class="text-gray">2022</td>
                        <td><span class="pill pill-category">Computer Science</span></td>
                        <td><span class="pill pill-success">Available</span></td>
                        <td class="font-medium text-center">3</td>
                        <td>
                            <div class="action-icons">
                                <i class="fa-regular fa-eye text-blue"></i>
                                <i class="fa-regular fa-pen-to-square text-green"></i>
                                <i class="fa-regular fa-trash-can text-red"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="book-cover cover-2"></div></td>
                        <td class="font-medium">Clean Code</td>
                        <td class="text-gray">978-0-132-<br>35088-4</td>
                        <td class="text-gray">Robert C. Martin</td>
                        <td class="text-gray">Prentice Hall</td>
                        <td class="text-gray">2008</td>
                        <td><span class="pill pill-category">Software<br>Engineering</span></td>
                        <td><span class="pill pill-success">Available</span></td>
                        <td class="font-medium text-center">5</td>
                        <td>
                            <div class="action-icons">
                                <i class="fa-regular fa-eye text-blue"></i>
                                <i class="fa-regular fa-pen-to-square text-green"></i>
                                <i class="fa-regular fa-trash-can text-red"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="book-cover cover-3"></div></td>
                        <td class="font-medium">Sapiens</td>
                        <td class="text-gray">978-0-062-<br>31609-7</td>
                        <td class="text-gray">Yuval Noah<br>Harari</td>
                        <td class="text-gray">Harper</td>
                        <td class="text-gray">2015</td>
                        <td><span class="pill pill-category">History</span></td>
                        <td><span class="pill pill-danger">Out of<br>Stock</span></td>
                        <td class="font-medium text-center">0</td>
                        <td>
                            <div class="action-icons">
                                <i class="fa-regular fa-eye text-blue"></i>
                                <i class="fa-regular fa-pen-to-square text-green"></i>
                                <i class="fa-regular fa-trash-can text-red"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>
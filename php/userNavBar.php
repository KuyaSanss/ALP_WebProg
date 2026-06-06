<?php
    session_start();

    $firstLetter = "";

    if(isset($_SESSION['Nama'])){
        $firstLetter = strtoupper(substr($_SESSION['Nama'], 0, 1));
    }
?>

<nav class="w-screen bg-white fixed top-0 left-0 shadow-md p-[15px] pl-[30px] pr-[30px] flex justify-between z-[9999]">
    <a href="userDashboard.php">
        <div class="logoDiv gap-[7px] text-[#3e5f44] text-[20px]">
            <img src="https://img.freepik.com/premium-vector/lorem-ipsum-logo-design-colorful-gradient_779267-46.jpg?w=2000" alt="logo" class="logo">
            <b><p>Knowlede Journey</p></b>
        </div>
    </a>
    
    <div>

    </div>
    <div class="logoDiv gap-5 md:gap-10">
        <button id="hamburgerButton" class="md:hidden text-[25px]">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="hidden md:flex gap-5 items-center">
            <a href="userDashboard.php" class="navLink">
                <i class="fa-regular fa-house"></i>
                Home
            </a>

            <a href="userBooks.php" class="navLink">
                <i class="fa-solid fa-book"></i>
                Books
            </a>

            <a href="userBorrowings.php" class="navLink">
                <i class="fa-solid fa-inbox"></i>
                Borrowings
            </a>
        </div>

        <div class="relative">
            <div id="profileButton" class="rounded-[50%] bg-[#487051] text-white w-[45px] h-[45px] flex items-center justify-center text-[20px] cursor-pointer">
                <b><?php echo $firstLetter; ?></b>
            </div>

            <div id="profileMenu" class="hidden absolute right-0 top-[60px] bg-white rounded-[15px] shadow-lg min-w-[180px] overflow-hidden z-[10000]">
                <a href="logout.php" class="flex items-center gap-[10px] p-[15px] hover:bg-gray-100">
                    <i class="fa-solid fa-right-from-bracket text-red-500"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-md p-[20px]">
    <div class="flex flex-col gap-[15px]">
            <a href="userDashboard.php" class="navLink">
                <i class="fa-regular fa-house"></i>
                Home
            </a>

            <a href="userBooks.php" class="navLink">
                <i class="fa-solid fa-book"></i>
                Books
            </a>

            <a href="userBorrowings.php" class="navLink">
                <i class="fa-solid fa-inbox"></i>
                Borrowings
            </a>
        </div>
    </div>
</nav>
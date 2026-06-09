<?php
    session_start();

    $firstLetter = "";

    if(isset($_SESSION['NamaStaff'])){
        $firstLetter = strtoupper(substr($_SESSION['NamaStaff'], 0, 1));
    }
?>

<nav class="w-screen bg-white fixed top-0 left-0 shadow-md p-[15px] pl-[30px] pr-[30px] flex justify-between z-[9999]">
    <div id="mobileMenu" class="hidden md:hidden fixed top-[75px] left-0 w-full bg-white shadow-lg z-[9998]">
        <div class="flex flex-col">

            <a href="adminDashboard.php" class="mobileNavLink">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="adminMember.php" class="mobileNavLink">
                <i class="fa-solid fa-users"></i>
                Members
            </a>

            <a href="adminBook.php" class="mobileNavLink">
                <i class="fa-solid fa-book"></i>
                Books
            </a>

            <a href="adminStaff.php" class="mobileNavLink">
                <i class="fa-solid fa-user-tie"></i>
                Staff
            </a>

            <a href="adminPeminjaman.php" class="mobileNavLink">
                <i class="fa-solid fa-clipboard-list"></i>
                Borrowing
            </a>

            <a href="adminPengembalian.php" class="mobileNavLink">
                <i class="fa-solid fa-rotate-left"></i>
                Returns
            </a>

            <a href="adminDenda.php" class="mobileNavLink">
                <i class="fa-solid fa-dollar-sign"></i>
                Fines
            </a>

        </div>
    </div>

    <a href="adminDashboard.php">
        <div class="logoDiv gap-[7px] text-[#3e5f44] text-[20px]">
            <img src="Logo.png" alt="logo" class="logo">
            <b><p>Knowlede Journey</p></b>
        </div>
    </a>
    
    <div class="logoDiv gap-5 md:gap-10">
        <button id="hamburgerButton" class="md:hidden text-[25px]">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="hidden md:flex gap-5 items-center">
            <a href="adminDashboard.php" class="navLink"> 
                <i class="fa-solid fa-house"></i> 
                Dashboard
            </a>

            <a href="adminMember.php" class="navLink"> 
                <i class="fa-solid fa-users"></i> 
                Members
            </a>

            <a href="adminBook.php" class="navLink"> 
                <i class="fa-solid fa-book"></i> 
                Books
            </a>

            <a href="adminStaff.php" class="navLink"> 
                <i class="fa-solid fa-user-tie"></i> 
                Staff
            </a>

            <a href="adminPeminjaman.php" class="navLink"> 
                <i class="fa-solid fa-clipboard-list"></i> 
                Borrowing
            </a>
            <a href="adminPengembalian.php" class="navLink"> 
                <i class="fa-solid fa-rotate-left"></i> 
                Returns
            </a>

            <a href="adminDenda.php" class="navLink"> 
                <i class="fa-solid fa-dollar-sign"></i> 
                Fines
            </a>
        </div>

        <div class="relative">
            <div id="profileButton" class="rounded-[50%] bg-[#487051] text-white w-[45px] h-[45px] flex items-center justify-center text-[20px] cursor-pointer">
                <b><?php echo $firstLetter ?></b>
            </div>

            <div id="profileMenu" class="hidden absolute right-0 top-[60px] bg-white rounded-[15px] shadow-lg min-w-[180px] overflow-hidden z-[10000]">
                <a href="logoutAdmin.php" class="flex items-center gap-[10px] p-[15px] hover:bg-gray-100">
                    <i class="fa-solid fa-right-from-bracket text-red-500"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>
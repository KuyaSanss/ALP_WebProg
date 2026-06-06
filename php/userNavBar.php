<?php
    session_start();

    $firstLetter = "";

    if(isset($_SESSION['Nama'])){
        $firstLetter = strtoupper(substr($_SESSION['Nama'], 0, 1));
    }
?>

<nav class="navbar w-screen bg-white sticky top-0 shadow-md p-[15px] pl-[30px] pr-[30px] flex justify-between">
    <div class="logoDiv gap-[7px] text-[#3e5f44] text-[20px]">
        <img src="https://img.freepik.com/premium-vector/lorem-ipsum-logo-design-colorful-gradient_779267-46.jpg?w=2000" alt="logo" class="logo">
        <b><p>Knowlede Journey</p></b>
    </div>
    <div>

    </div>
    <div class="logoDiv gap-20">
        <div class="flex gap-5">
            <a href="userDashboard.php" class="navLink">
                Home
            </a>
            <a href="userBooks.html" class="navLink">
                Books
            </a>
            <a href="userBorrowings.html" class="navLink">
                Borrowings
            </a>
        </div>
        
        <div class="rounded-[50%] bg-[#487051] text-white w-[45px] h-[45px] flex text-center items-center justify-center text-[20px]">
            <b>
                <?php
                    echo $firstLetter;
                ?>
            </b>
        </div>
    </div>
</nav>
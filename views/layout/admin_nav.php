<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="admin-sidebar"> <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    
    <?php if (!empty($_SESSION['uloga_id'])): ?>
        <div class="user-info">
            <a href="/mvc-course-app/controllers/log_out.php">LOG OUT</a>
            <span class="span">
                <?= $_SESSION['ime'] . " " . $_SESSION['prezime']; ?>
            </span>
        </div>
    <?php endif; ?>

    <a href="/mvc-course-app/views/admin/admin_home.php">HOME</a>

    <a href="/mvc-course-app/views/admin/admin_course.php"
        <?= $currentPage == 'course.php' ? 'class="active"' : '' ?>>
        Kursevi
    </a>

    <a href="/mvc-course-app/views/admin/admin_user.php">Korisnici</a>
    <a href="#">Statistika</a>
    <a href="#">Uvoz/Izvoz</a>
</nav>
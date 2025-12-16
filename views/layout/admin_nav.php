<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="admin-sidebar">
    <div class="header">
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

    <!-- STATISTIKA (dropdown / submenu) -->
    <div class="submenu">
        <a href="/mvc-course-app/views/admin/admin_home.php"
           class="<?= $currentPage == 'admin_home.php' ? 'active' : '' ?>">
            Statistika
        </a>
        <div class="submenu-items">
            <a href="/mvc-course-app/views/admin/admin_home.php"
               class="<?= $currentPage == 'stat_overview.php' ? 'active' : '' ?>">
                Overview
            </a>
            <a href="/mvc-course-app/views/admin/admin_finance.php"
               class="<?= $currentPage == 'admin_finance.php' ? 'active' : '' ?>">
                finansije
            </a>
            
        </div>
    </div>

    <!-- Ostale sekcije -->
    <a href="/mvc-course-app/views/admin/admin_course.php"
        <?= $currentPage == 'admin_course.php' ? 'class="active"' : '' ?>>
        Kursevi
    </a>

    <a href="/mvc-course-app/views/admin/admin_user.php"
        <?= $currentPage == 'admin_user.php' ? 'class="active"' : '' ?>>
        Korisnici
    </a>

    
</nav>


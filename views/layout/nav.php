<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/nav.css">
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>


    <nav class="user-nav">
        <a href="/mvc-course-app/index.php"><img class="logo" src="/mvc-course-app/assets/img/logo.png" alt="MK Sales Logo"></a>

        <div class="nav-links">
            <a href="/mvc-course-app/views/course/course.php">Kursevi</a>
            <?php if (!empty($_SESSION['uloga_id']) && $_SESSION['uloga_id'] == 1): ?>
                <a href="/mvc-course-app/views/user/shoping_cart.php"><i class="fa-solid fa-cart-shopping"></i> Korpa</a>
                <a href="/user/login">Moji kursevi</a>
            <?php endif; ?>

            <?php if (!empty($_SESSION['uloga_id'])): ?>
                <a href="/mvc-course-app/controllers/log_out.php">LOG OUT</a>
                <span id="name-of-the-user">
                    <?= $_SESSION['ime'] . " " . $_SESSION['prezime']; ?>
                </span>
            <?php else: ?>
                <?php if ($currentPage != 'login.php' && $currentPage != 'signup.php'): ?>
                    <a href="/mvc-course-app/views/auth/login.php">LOG IN</a>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </nav>



</body>

</html>
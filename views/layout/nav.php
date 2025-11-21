<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?= URLROOT ?>/assets/css/nav.css">
</head>
<body>
  <nav class="user-nav">
    <a href="<?= URLROOT ?>"><img class="logo" src="<?= URLROOT ?>/assets/img/logo.png" alt="MK Sales Logo"></a>
    
    <div class="nav-links">
        <a href="<?= URLROOT ?>/courses/list">Kursevi</a>
        <a href="#"><i class="fa-solid fa-cart-shopping"></i> Korpa</a>
        <a href="<?= URLROOT ?>/user/login">Kontakt</a>
        <a href="<?= URLROOT ?>/auth/login.php">Login</a>

        <a href="<?= URLROOT ?>/user/login">Moji kursevi</a> <! --pojavljuje se samo ako je user logovan -- >
    </div>
</nav>


</body>
</html>

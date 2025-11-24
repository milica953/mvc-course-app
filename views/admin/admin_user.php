<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_home.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_user.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">

    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    // Učitaj env i bazu
    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include __DIR__ . '../../../config/database.php';
    // Dohvati sve korisnike iz baze
    $sql = "
            SELECT 
                korisnik.korisnik_id, 
                korisnik.ime, 
                korisnik.prezime, 
                korisnik.email, 
                uloga.naziv,
                korisnik.username,
                korisnik.is_aktivan
            FROM korisnik 
            INNER JOIN uloga ON korisnik.uloga_id = uloga.uloga_id
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $korisnici = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <body>
        <div class="layout">
            <?php include __DIR__ . '/../layout/admin_nav.php'; ?>

            <div class="content">
                <div class="info">
                    <h2 style="text-align: center;">Menadžment korisnika</h2>
                    <?php foreach ($korisnici as $key => $korisnik) {

                        if ($korisnik['korisnik_id'] == $_SESSION['korisnik_id']) {
                            continue;
                        }
                        $key++; ?>
                        <div id="second-div">
                            
                            <span class="user-info">
                                <?php echo $key . ". " . $korisnik['ime'] . " " . $korisnik['prezime']
                                    . " - " .  $korisnik['naziv']
                                    . " (@" . $korisnik['username'] . ")"; ?>
                            </span>

                            <?php if ($korisnik['is_aktivan'] == 1) { ?>
                                <a href="/mvc-course-app/controllers/admin_user_panel_logic.php?status=0&korisnik_id=<?php echo $korisnik['korisnik_id'] ?>">
                                    <p>Deaktiviraj nalog</p>
                                </a>
                            <?php } else { ?>
                                <a href="/mvc-course-app/controllers/admin_user_panel_logic.php?status=1&korisnik_id=<?php echo $korisnik['korisnik_id'] ?>">
                                    <p class="aktivno">Aktiviraj nalog</p>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>


</html>
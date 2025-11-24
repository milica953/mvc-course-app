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
    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include __DIR__ . '../../../config/database.php';

    // Broj korisnika po stranici
    $po_stranici = 5;

    // Trenutna stranica iz GET parametra
    $stranica = isset($_GET['stranica']) ? (int)$_GET['stranica'] : 1;
    if ($stranica < 1) $stranica = 1;

    // Prebroj ukupno korisnike (osim sebe)
    $stmt = $pdo->query("SELECT COUNT(*) FROM korisnik WHERE korisnik_id != " . $_SESSION['korisnik_id']);
    $ukupno_korisnika = $stmt->fetchColumn();

    // Ukupno stranica
    $ukupno_stranica = ceil($ukupno_korisnika / $po_stranici);

    // Offset za SQL
    $offset = ($stranica - 1) * $po_stranici;

    // Dohvati korisnike za trenutnu stranicu
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
    WHERE korisnik.korisnik_id != :sami
    LIMIT :limit OFFSET :offset
";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':sami', $_SESSION['korisnik_id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $po_stranici, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
                <div class="paginacija">
    <?php for ($i = 1; $i <= $ukupno_stranica; $i++): ?>
        <?php if ($i == $stranica): ?>
            <div><?= $i ?></div>
        <?php else: ?>
            <a href="/mvc-course-app/views/admin/admin_user.php?stranica=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
            </div>
        </div>
    </body>


</html>
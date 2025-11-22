<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/shoping_cart.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/nav.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/footer.css">

</head>

<body>
    <?php

    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include '../../Config/database.php';
    include_once __DIR__ . '../../layout/nav.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    $korisnik_id = $_SESSION['korisnik_id'];

    $upit = "SELECT 
        stavka_korpe.stavka_korpe_id AS id,
                kurs.naziv,
                kurs.cena,
                korpa.korisnik_id
            FROM stavka_korpe 
            INNER JOIN kurs  ON stavka_korpe.kurs_id = kurs.kurs_id
            INNER JOIN korpa  ON stavka_korpe.korpa_id = korpa.korpa_id
        WHERE korpa.korisnik_id = :korisnik_id";

    $stmt = $pdo->prepare($upit);
    $stmt->bindParam(':korisnik_id', $korisnik_id, PDO::PARAM_INT);
    $stmt->execute();

    $stavka_korpe = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <main>
        <h1>Porudžbina</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Kurs</th>
                    <th>Cena</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $suma = 0;
                foreach ($stavka_korpe as $stavka) {
                    $suma += $$stavka['cena'];
                ?>
                    <tr>
                        <form action="/mvc-course-app/controllers/update_shoping_cart.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $stavka['id'] ?>">
                            <td>
                                <a href="/mvc-course-app/controllers/delete_shoping_cart.php?id=<?php echo $stavka['id'] ?>">Obriši kurs iz korpe</a>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"><strong>Ukupno za plaćanje:</strong></td>
                    <td colspan="2"><?php echo $suma ?> RSD</td>
                </tr>
            </tbody>
        </table>
    </main>
    <?php include_once __DIR__ . '../../layout/footer.php'; ?>

</body>

</html>
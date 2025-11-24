<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursevi</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/course.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/nav.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/footer.css">
    <script defer src="/mvc-course-app/assets/js/search.js"></script>
</head>

<body>

    <?php
    session_start();

    // Učitaj env i bazu
    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include __DIR__ . '../../../config/database.php';
    include_once __DIR__ . '../../layout/nav.php';
    echo "ENV HOST: " . $_ENV['DB_HOST'];

    // Dohvati kurseve iz kategorije 'web'
    $sql = "
        SELECT 
            kurs.kurs_id, 
            kurs.naziv AS kurs_naziv, 
            kurs.opis, 
            kurs.cena, 
            kategorija.naziv AS kategorija_naziv
        FROM kurs 
        INNER JOIN kategorija ON kurs.kategorija_id = kategorija.kategorija_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $kursevi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dohvati stavke korisnika u korpi
    $upit = "
        SELECT 
            stavka_korpe.stavka_korpe_id, 
            stavka_korpe.kurs_id,
            korpa.korisnik_id
        FROM stavka_korpe 
        INNER JOIN kurs ON stavka_korpe.kurs_id = kurs.kurs_id 
        INNER JOIN korpa ON stavka_korpe.korpa_id = korpa.korpa_id 
        WHERE korpa.korisnik_id = :korisnik_id
    ";
    $stmt = $pdo->prepare($upit);
    $stmt->bindParam(':korisnik_id', $_SESSION['korisnik_id']);
    $stmt->execute();
    $stavke_korpe = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="filters">
        <input
            type="text"
            id="searchInput"
            placeholder="Pretraga kurseva...">

        <select id="categoryFilter">
            <option value="all">Sve kategorije</option>
            <?php foreach ($kursevi as $kurs): ?>
                <option value="<?php echo htmlspecialchars($kurs['kategorija_naziv']); ?>">
                    <?php echo htmlspecialchars($kurs['kategorija_naziv']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="card-holder">
        <?php foreach ($kursevi as $kurs): ?>
            <div class="card">
                <h1><?php echo htmlspecialchars($kurs['kurs_naziv']); ?></h1>
                <p class="category"><?php echo htmlspecialchars($kurs['kategorija_naziv']); ?></p>
                <p class="price"><?php echo number_format($kurs['cena'], 2); ?> RSD</p>
                <p><?php echo htmlspecialchars($kurs['opis']); ?></p>

                <?php
                $kurs_je_u_korpi = false;
                foreach ($stavke_korpe as $stavka) {
                    if ($stavka['kurs_id'] == $kurs['kurs_id']) {
                        $kurs_je_u_korpi = true;
                        break;
                    }
                }
                ?>

                <div class="card-action">
                    <?php if ($kurs_je_u_korpi): ?>
                        <button class="button disabled" disabled>Kurs je u korpi</button>
                    <?php else: ?>
                        <form action="/mvc-course-app/controllers/add_to_shopping_chart.php" method="post">
                            <input type="hidden" name="korisnik_id" value="<?php echo $_SESSION['korisnik_id'] ?>">
                            <input type="hidden" name="kurs_id" value="<?php echo $kurs['kurs_id'] ?>">
                            <input type="submit" value="KUPI" class="button">
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
    <?php include_once __DIR__ . '../../layout/footer.php'; ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/css/my_courses.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/nav.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/footer.css">

    <title>Document</title>
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

            //dohvati kurseve iz moji_kursevi baze
            $sql = "
                SELECT 
                    moji_kursevi.korisnik_id,
                    moji_kursevi.kurs_id,
                    kurs.naziv,
                    moji_kursevi.datum_kupovine_kursa,
                    moji_kursevi.status_kursa
                FROM moji_kursevi
                    INNER JOIN korisnik ON moji_kursevi.korisnik_id =  korisnik.korisnik_id
                    INNER JOIN kurs ON moji_kursevi.kurs_id =  kurs.kurs_id	
                WHERE
                    korisnik.korisnik_id = :korisnik_id
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':korisnik_id', $_SESSION['korisnik_id']);
            $stmt->execute();
            $moji_kursevi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <h2 class="header">Moji Kursevi</h2>
        <div class="course-list">
            <?php foreach ($moji_kursevi as $kurs): ?>
                <div class="course-item">
                    <h3><?php echo htmlspecialchars($kurs['naziv']); ?></h3>
                    <p>Datum kupovine: <?php echo htmlspecialchars($kurs['datum_kupovine_kursa']); ?></p>
                    <p>Status: <?php echo htmlspecialchars($kurs['status_kursa']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
         <?php include_once __DIR__ . '../../layout/footer.php'; ?>
</body>
</html>
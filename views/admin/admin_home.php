<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_home.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">
    <script src="/mvc-course-app/assets/js/admin_nav.js" defer></script>
    <script src="/mvc-course-app/assets/js/admin_overwiew.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <title>Document</title>
</head>

<body>
    <div class="layout">
        <?php
        session_start();
        include __DIR__ . '/../layout/admin_nav.php';
        require_once __DIR__ . '../../../core/env.php';
        loadEnv(__DIR__ . '../../../.env');
        include __DIR__ . '../../../config/database.php';

        //Podaci o broju aktivnih korisnika u sistemu.
        $sql = "
            SELECT 
                COUNT(korisnik.korisnik_id) AS ukupan_broj_aktivnih_korisnika_u_sistemu
            FROM korisnik
            WHERE korisnik.uloga_id = :tip_uloge;
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':tip_uloge', 1, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $activeUsers = (int)$result['ukupan_broj_aktivnih_korisnika_u_sistemu'];


        // podaci iz baze o deaktiviranim korisnicima
        $sql = "
            SELECT 
                COUNT(korisnik.korisnik_id) AS ukupan_broj_neaktivnih_korisnika_u_sistemu
            FROM
                korisnik
            WHERE 
                korisnik.uloga_id = :tip_uloge
                AND korisnik.is_aktivan = :vrednost
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':tip_uloge', 1, PDO::PARAM_INT);
        $stmt->bindValue(':vrednost', '0', PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $deactivatedUsers = (int)$result['ukupan_broj_neaktivnih_korisnika_u_sistemu'];

        // Podaci o broju prodatih kurseva u zadnje 3 god
        $sql = "
            SELECT 
                YEAR(datum_kupovine) AS godina,
                COUNT(*) AS broj_prodatih
            FROM kupovina
            WHERE status = :status
            AND YEAR(datum_kupovine) >= YEAR(CURDATE()) - 2
            GROUP BY YEAR(datum_kupovine)
            ORDER BY godina ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno', PDO::PARAM_STR);
        $stmt->execute();
        $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Priprema za JS Chart
        $years = [];
        $sales = [];
        foreach ($salesData as $row) {
            $years[] = $row['godina'];
            $sales[] = $row['broj_prodatih'];
        }

        // Podaci o ukupnom prihodu do sada u trenutnoj godini

        $sql = "
            SELECT 
                YEAR(datum_kupovine) AS godina,
                COALESCE(SUM(ukupna_cena), 0) AS ukupni_prihod
        FROM kupovina
        WHERE status = :status
        AND YEAR(datum_kupovine) = YEAR(CURDATE());
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno', PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalRevenue = number_format($result['ukupni_prihod'], 0, ',', '.');
        // prihodi po mesecima trenutne god    
        $sql = "
            SELECT 
                MONTH(datum_kupovine) AS mesec,
                COALESCE(SUM(ukupna_cena), 0) AS prihod
            FROM kupovina
            WHERE status = :status
            AND YEAR(datum_kupovine) = YEAR(CURDATE())
            GROUP BY MONTH(datum_kupovine)
            ORDER BY mesec ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno', PDO::PARAM_STR);
        $stmt->execute();

        $monthlyRevenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $months = [];
        $revenues = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($monthlyRevenueData as $row) {
            $months[] = $monthNames[$row['mesec'] - 1];
            $revenues[] = (float)$row['prihod'];
        }

        $stmt = $pdo->prepare("
            SELECT 
                kurs.naziv AS kurs_naziv,
                kurs.broj_prodatih,
                kurs.cena,
                kategorija.naziv AS kategorija
            FROM kurs
            INNER JOIN kategorija ON kurs.kategorija_id = kategorija.kategorija_id
            ORDER BY kurs.broj_prodatih DESC
            LIMIT 1
        ");
        $stmt->execute();
        $topCourse = $stmt->fetch(PDO::FETCH_ASSOC);


        ?>

        <div class="content">
            <h1>Brzi pregled statistike u celom sistemu</h1>

            <div class="overview-grid">

                <!-- Stat card: Ukupan prihod -->
                <div class="stat-card">
                    <h4>Ukupan prihod (ova godina)</h4>
                    <p class="stat-number"><?= $totalRevenue ?> RSD</p>
                </div>

                <div class="bar">
                    <canvas id="monthlyRevenueChart"
                        data-months='<?= json_encode($months) ?>'
                        data-revenues='<?= json_encode($revenues) ?>'></canvas>
                </div>

                <!-- Bar chart -->
                <div class="bar">
                    <canvas id="salesBarChart"
                        data-years='<?= json_encode($years) ?>'
                        data-sales='<?= json_encode($sales) ?>'></canvas>
                </div>

                <!-- Doughnut chart -->
                <div class="chart-card">
                    <canvas id="usersChart"
                        data-active="<?= $activeUsers ?>"
                        data-deactivated="<?= $deactivatedUsers ?>"></canvas>
                </div>

                <div class="top-course-card">
                    <h3>Najprodavaniji kurs</h3>
                    <p><strong>Kurs:</strong> <?= htmlspecialchars($topCourse['kurs_naziv']) ?></p>
                    <p><strong>Kategorija:</strong> <?= htmlspecialchars($topCourse['kategorija']) ?></p>
                    <p><strong>Prodatih kopija:</strong> <?= $topCourse['broj_prodatih'] ?></p>
                    <p><strong>Cena:</strong> <?= number_format($topCourse['cena'], 2) ?> RSD</p>
                </div>


            </div>

        </div>

</body>

</html>
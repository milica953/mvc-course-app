<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stastistika kurseva</title>
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_user_stats.css">
    <script src="/mvc-course-app/assets/js/admin_nav.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

</head>

<body>
    <div class="layout">
        <?php

        session_start();
        include __DIR__ . '/../layout/admin_nav.php';
        require_once __DIR__ . '../../../core/env.php';
        loadEnv(__DIR__ . '../../../.env');
        include __DIR__ . '../../../config/database.php';

        // ukupno kurseva u sistemu podaci iz DB 
        $sql = "
            SELECT 
	            COUNT(kurs.kurs_id) AS broj_kurseva
            FROM 
                kurs	
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $courseNumber = $stmt->fetch(PDO::FETCH_ASSOC);

        // ukupan broj aktivnih kurseva
        $sql = "
           SELECT 
            IFNULL(COUNT(kurs.kurs_id),0) AS broj_kurseva
            FROM 
                kurs	
            WHERE
                kurs.is_aktivan = :status	
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', '1', PDO::PARAM_STR);
        $stmt->execute();
        $courseActiveNumber = $stmt->fetch(PDO::FETCH_ASSOC);

        //ukupan broj neaktivnih kurseva
        $sql = "
           SELECT 
            IFNULL(COUNT(kurs.kurs_id),0) AS broj_kurseva
            FROM 
                kurs	
            WHERE
                kurs.is_aktivan = :status	
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', '0', PDO::PARAM_STR);
        $stmt->execute();
        $courseDeactiveNumber = $stmt->fetch(PDO::FETCH_ASSOC);

        // filtiranje napravi logiku i failsafe
        ?>
        <div class="content">
            <h1>Stastistika Kurseva</h1>
        <div class="overview-grid">
            <div class="number-of-courses-card">
                <h3>Ukupno kurseva: <?= $courseNumber['broj_kurseva'] ?></h3>
                <p>Aktivnih: <?= $courseActiveNumber['broj_kurseva'] ?></p>
                <p>Neaktivnih:<?= $courseDeactiveNumber['broj_kurseva'] ?></p>
            </div>

            <div class="filter-card">
                <h3>Filtriranje statistike</h3>

                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <label for="date_from">Datum od</label>
                        <input type="date" id="date_from" name="date_from">
                    </div>

                    <div class="filter-group">
                        <label for="date_to">Datum do</label>
                        <input type="date" id="date_to" name="date_to">
                    </div>

                    <div class="filter-actions">
                        <button type="submit">Primeni filter</button>
                        <a href="/mvc-course-app/views/admin/admin_user_stats.php" class="reset-btn">Resetuj</a>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </div>
</body>

</html>
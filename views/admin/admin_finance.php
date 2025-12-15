<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_finance.css">
    <script src="/mvc-course-app/assets/js/admin_finance.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <title>Finasije</title>
</head>

<body>
    <div class="layout">
        <?php
        session_start();
        include __DIR__ . '/../layout/admin_nav.php';
        require_once __DIR__ . '../../../core/env.php';
        loadEnv(__DIR__ . '../../../.env');
        include __DIR__ . '../../../config/database.php';


        // najstarija transakcija
        $sql = "SELECT MIN(kupovina.datum_kupovine) AS najstarija_transakcija FROM kupovina";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $oldestTransactioneDate = $stmt->fetch(PDO::FETCH_ASSOC);

        // formatiranje datuma za HTML input
        $minDate = date('Y-m-d', strtotime($oldestTransactioneDate['najstarija_transakcija']));
        $maxDate = date('Y-m-d'); // danasnji datum


        $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : $minDate;
        $date_to   = isset($_GET['date_to']) ? $_GET['date_to'] : $maxDate;
        //provera
        $fromDate = new DateTime($date_from);
        $toDate   = new DateTime($date_to);

        $diffInYears = $fromDate->diff($toDate)->y;


        // ukupan prihod
        $sql = "
            SELECT 
                kupovina.datum_kupovine AS godina,
                COALESCE(SUM(kupovina.ukupna_cena), 0) AS ukupni_prihod
        FROM kupovina
        WHERE status = :status
        AND kupovina.datum_kupovine BETWEEN :date_from AND :date_to
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno', PDO::PARAM_STR);
        $stmt->bindParam(':date_from', $date_from);
        $stmt->bindParam(':date_to', $date_to);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalRevenue = number_format($result['ukupni_prihod'], 0, ',', '.');

        // Broj transakcija
        $sql = "
            SELECT
                COUNT(kupovina.kupovina_id) AS broj_transakcija,
                kupovina.datum_kupovine AS godina                
        FROM kupovina
        WHERE status = :status
        AND kupovina.datum_kupovine BETWEEN :date_from AND :date_to
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno', PDO::PARAM_STR);
        $stmt->bindParam(':date_from', $date_from);
        $stmt->bindParam(':date_to', $date_to);
        $stmt->execute();
        $numberOfTransactions = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($diffInYears >= 1) {
            $sql = "
        SELECT 
            YEAR(datum_kupovine) AS label,
            SUM(ukupna_cena) AS prihod
        FROM kupovina
        WHERE status = :status
        AND datum_kupovine BETWEEN :date_from AND :date_to
        GROUP BY YEAR(datum_kupovine)
        ORDER BY label
    ";
            $chartTitle = 'Prihod po godinama (izabrani period)';
        } else {
            $sql = "
        SELECT 
            DATE_FORMAT(datum_kupovine, '%Y-%m') AS label,
            SUM(ukupna_cena) AS prihod
        FROM kupovina
        WHERE status = :status
        AND datum_kupovine BETWEEN :date_from AND :date_to
        GROUP BY label
        ORDER BY label
    ";
            $chartTitle = 'Prihod po mesecima (izabrani period)';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', 'placeno');
        $stmt->bindParam(':date_from', $date_from);
        $stmt->bindParam(':date_to', $date_to);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $revenues = [];

        foreach ($data as $row) {
            $labels[] = (string)$row['label'];
            $revenues[] = (float)$row['prihod'];
        }



        ?>


        <div class="content">
            <h1>Finasije Stastistika</h1>

            <div class="overview-grid">

                <div class="filter-card">
                    <h3>Filtriranje statistike</h3>

                    <form method="GET" class="filter-form">
                        <div class="filter-group">
                            <label for="date_from">Datum od</label>
                            <input type="date" id="date_from" name="date_from"
                                min="<?= $minDate ?>"
                                max="<?= $maxDate ?>"
                                value="<?= htmlspecialchars($date_from) ?>">
                        </div>

                        <div class="filter-group">
                            <label for="date_to">Datum do</label>
                            <input type="date" id="date_to" name="date_to"
                                min="<?= $minDate ?>"
                                max="<?= $maxDate ?>"
                                value="<?= htmlspecialchars($date_to) ?>">
                        </div>

                        <div class="filter-actions">
                            <button type="submit">Primeni filter</button>
                            <a href="/mvc-course-app/views/admin/admin_finance.php" class="reset-btn">Resetuj</a>
                        </div>
                    </form>
                </div>

                <!-- Stat card: Ukupan prihod -->
                <div class="stat-card">
                    <h4>Ukupan prihod </h4>
                    <h4>Za period: <?php echo $date_from . " do " . $date_to ?> </h4>
                    <p class="stat-number"><?= $totalRevenue ?> RSD</p>
                </div>

                <!-- Stat card: broj transakcija -->
                <div class="stat-card">
                    <h4>Ukupan broj transakcija </h4>
                    <h4>Za period: <?php echo $date_from . " do " . $date_to ?> </h4>
                    <p class="stat-number"><?= $numberOfTransactions['broj_transakcija'] ?></p>
                </div>

                <!-- Stat card: prosecan iznos po transakciji -->
                <?php
                $totalRevenue = $result['ukupni_prihod'] ?? 0;
                $transactionCount = $numberOfTransactions['broj_transakcija'] ?? 0;

                if ($transactionCount > 0) {
                    $average = $totalRevenue / $transactionCount;
                    $display = number_format($average, 0, ',', '.') . ' RSD';
                } else {
                    $display = '–';
                }
                ?>
                <div class="stat-card">
                    <h4>Prosečan iznos po transakciji</h4>
                    <h4>Za period: <?= $date_from ?> do <?= $date_to ?></h4>
                    <p class="stat-number"><?= $display ?></p>
                </div>

                <div class="bar">
                    <canvas id="monthlyRevenueChart"
                        data-labels='<?= json_encode($labels) ?>'
                        data-revenues='<?= json_encode($revenues) ?>'
                        data-title="<?= htmlspecialchars($chartTitle) ?>">
                    </canvas>

                </div>

            </div>
            <div>
                <p>Grafikon se dinamički prilagođava izabranom periodu –
                    za kraće periode prikazuje mesečne podatke, a za duže godišnje.</p>

            </div>
        </div>
    </div>
</body>

</html>
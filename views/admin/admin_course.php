<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_home.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_user.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_course.css">
    <script defer src="/mvc-course-app/assets/js/search.js"></script>
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include __DIR__ . '../../../config/database.php'; // $pdo konekcija

    // Broj kurseva po stranici
    $po_stranici = 24;

    // Trenutna stranica iz GET parametra
    $stranica = isset($_GET['stranica']) ? (int)$_GET['stranica'] : 1;
    if ($stranica < 1) $stranica = 1;

    // Prebroj ukupno kurseva
    $stmt = $pdo->query("SELECT COUNT(*) FROM kurs");
    $ukupno_kurseva = $stmt->fetchColumn();

    // Izračunaj ukupno stranica
    $ukupno_stranica = ceil($ukupno_kurseva / $po_stranici);

    // Offset za SQL
    $offset = ($stranica - 1) * $po_stranici;

    // Učitaj kurseve za trenutnu stranicu
    $sql = "
    SELECT 
        kurs.kurs_id, 
        kurs.naziv AS kurs_naziv, 
        kurs.opis, 
        kurs.cena, 
        kategorija.naziv AS kategorija_naziv
    FROM kurs 
    INNER JOIN kategorija ON kurs.kategorija_id = kategorija.kategorija_id
    LIMIT :limit OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $po_stranici, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $kursevi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Povuci sve kategorije direktno iz baze
    $kategorije = [];
    $stmtKat = $pdo->query("SELECT naziv FROM kategorija ORDER BY naziv");
    $kategorije = $stmtKat->fetchAll(PDO::FETCH_COLUMN);
    ?>

    <div class="layout">
        <?php include __DIR__ . '/../layout/admin_nav.php'; ?>

        <div class="content">
            <h1>Kursevi</h1>
       <div class="filters">
    <input type="text" id="searchInput" placeholder="Pretraga kurseva...">

    <select id="categoryFilter">
        <option value="all">Sve kategorije</option>
        <?php
        foreach ($kategorije as $kat): ?>
            <option value="<?= htmlspecialchars($kat); ?>"><?= htmlspecialchars($kat); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-holder">
    <?php foreach ($kursevi as $kurs): ?>
        <div class="card">
            <h1><?= htmlspecialchars($kurs['kurs_naziv']); ?></h1>
            <p><?= htmlspecialchars($kurs['opis']); ?></p>
            <p>Cena: <?= htmlspecialchars($kurs['cena']); ?> RSD</p>
            <!-- SAMO NAZIV KATEGORIJE -->
            <p class="category"><?= htmlspecialchars($kurs['kategorija_naziv']); ?></p>
            <div class="actions">
                <a class="edit-button" href="/mvc-course-app/views/admin/admin_update_products_page.php?kurs_id=<?= $kurs['kurs_id'] ?>">Izmeni</a>
                <a class="delete-button" href="/mvc-course-app/controllers/admin_delete_product.php?kurs_id=<?= $kurs['kurs_id']; ?>"
                   onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj kurs?');">Obriši</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

            <div class="paginacija">
                <?php for ($i = 1; $i <= $ukupno_stranica; $i++): ?>
                    <?php if ($i == $stranica): ?>
                        <div><?= $i ?></div>
                    <?php else: ?>
                        <a href="/mvc-course-app/views/admin/admin_course.php?stranica=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>

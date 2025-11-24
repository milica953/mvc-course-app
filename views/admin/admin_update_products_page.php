<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azuriranje proizvoda</title>
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_update_products_page.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_home.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_user.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_nav.css">
    <link rel="stylesheet" href="/mvc-course-app/assets/css/admin_course.css">
    
</head>
<body>
    <?php
    session_start();

    // Učitaj env i bazu
    require_once __DIR__ . '../../../core/env.php';
    loadEnv(__DIR__ . '../../../.env');
    include __DIR__ . '../../../config/database.php';

    


    $id_kursa = $_GET['kurs_id'];

    // Dohvati proizvod
    $sql = "SELECT * FROM kurs WHERE kurs_id = :id_kursa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_kursa', $id_kursa);
    $stmt->execute();
    $kurs = $stmt->fetch(PDO::FETCH_ASSOC);

    // Dohvati kategorije i podkategorije
    $kategorije = $pdo->query("SELECT * FROM kategorija")->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="layout">
        <?php include __DIR__ . '/../layout/admin_nav.php'; ?>

    <div class="content">
        <main class="update-product">
            <h1>Azuriranje proizvoda</h1>
            <form action="/mvc-course-app/controllers/admin_edit_products_logic.php" method="POST" >

                <input type="hidden" name="kurs_id" value="<?= $id_kursa ?>">

                <!-- Kategorija -->
                <label for="kategorija">Kategorija proizvoda:</label>
                <select name="kategorija" id="kategorija" class="select" required>
                    <?php foreach ($kategorije as $kat): ?>
                        <option value="<?= $kat ['kategorija_id'] ?>" 
                            <?= $kat['kategorija_id'] == $kurs['kategorija_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['naziv']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Naziv proizvoda -->
                <label for="naziv">Naziv kursa:</label>
                <input type="text" name="naziv" id="naziv" class="select" 
                    value="<?= htmlspecialchars($kurs['naziv']) ?>">

                <!-- Opis -->
                <label for="opis">Opis kursa:</label>
                <textarea name="opis" id="opis" rows="5" required><?= htmlspecialchars($kurs['opis']) ?></textarea>

                <!-- Cena -->
                <label for="cena">Cena:</label>
                <input type="text" name="cena" id="cena" class="select" 
                    value="<?= htmlspecialchars($kurs['cena']) ?>" required>

                
                
                <!-- Dugmad -->
                <div class="form-actions">
                    <input type="submit" value="Snimi" class="button save">
                    <input type="button" value="Odustani" class="button cancel" 
                        onclick="window.location.href='mvc-course-app/views/admin/admin_course.php';">
                </div>
            </form>
        </main>
    </div>
</body>
</html>

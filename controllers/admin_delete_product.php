<?php
require_once __DIR__ . '/../core/env.php';
loadEnv(__DIR__ . '/../.env');
include __DIR__ . '/../config/database.php';

if (isset($_GET['kurs_id'])) {
    $kurs_id = $_GET['kurs_id'];

    try {
        // 1. Obrisi zavisne zapise u moji_kursevi
        $stmt1 = $pdo->prepare('DELETE FROM moji_kursevi WHERE kurs_id = :kurs_id');
        $stmt1->bindParam(':kurs_id', $kurs_id, PDO::PARAM_INT);
        $stmt1->execute();

        // 2. Obrisi zavisne zapise u recenzija
        $stmt2 = $pdo->prepare('DELETE FROM recenzija WHERE kurs_id = :kurs_id');
        $stmt2->bindParam(':kurs_id', $kurs_id, PDO::PARAM_INT);
        $stmt2->execute();

        // 3. Obrisi zavisne zapise u stavka_korpe
        $stmt3 = $pdo->prepare('DELETE FROM stavka_korpe WHERE kurs_id = :kurs_id');
        $stmt3->bindParam(':kurs_id', $kurs_id, PDO::PARAM_INT);
        $stmt3->execute();

        // 4. Obrisi sam kurs
        $stmt4 = $pdo->prepare('DELETE FROM kurs WHERE kurs_id = :kurs_id');
        $stmt4->bindParam(':kurs_id', $kurs_id, PDO::PARAM_INT);
        $stmt4->execute();

        // Poruka i redirect
        echo "<script>
                alert('Kurs je uspešno obrisan!');
                window.location.href='/mvc-course-app/views/admin/admin_course.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "Greška pri brisanju kursa: " . $e->getMessage();
    }
} else {
    echo "ID kursa nije definisan!";
}
?>

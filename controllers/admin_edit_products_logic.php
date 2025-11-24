<?php
require_once __DIR__ . '/../core/env.php';
loadEnv(__DIR__ . '/../.env');
include __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kurs_id = $_POST['kurs_id'];
        $kategorija_id = $_POST['kategorija'];
        $naziv = $_POST['naziv'];
        $opis = $_POST['opis'];
        $cena = $_POST['cena'];

        try {
                $sql = "UPDATE kurs 
                SET kategorija_id = :kategorija, 
                    naziv = :naziv, 
                    opis = :opis, 
                    cena = :cena 
                WHERE kurs_id = :kurs_id";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':kategorija', $kategorija_id);
                $stmt->bindParam(':naziv', $naziv);
                $stmt->bindParam(':opis', $opis);
                $stmt->bindParam(':cena', $cena);
                $stmt->bindParam(':kurs_id', $kurs_id);
                $stmt->execute();

                echo '<script>
                 alert("Kurs je uspešno ažuriran!");
                 window.location.href="/mvc-course-app/views/admin/admin_course.php";
              </script>';
        } catch (PDOException $e) {
                echo '<script>
                alert("Došlo je do greške prilikom ažuriranja kursa: ' . addslashes($e->getMessage()) . '");
                window.history.back();
              </script>';
        }
}

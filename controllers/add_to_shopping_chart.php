<?php
session_start();

// Učitaj .env
require_once __DIR__ . '/../core/env.php';
loadEnv(__DIR__ . '/../.env');

// Učitaj bazu
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['korisnik_id'])) {
    die("Niste ulogovani, ne možete dodati proizvod u korpu!");
}

$trenutno_aktivan_korisnik_id = $_SESSION['korisnik_id'];
$kurs_id = $_POST["kurs_id"];

// 1. Proveri da li već postoji korpa
$upit = "SELECT korpa_id FROM korpa WHERE korisnik_id = :korisnik_id LIMIT 1";
$stmt = $pdo->prepare($upit);
$stmt->bindParam(':korisnik_id', $trenutno_aktivan_korisnik_id, PDO::PARAM_INT);
$stmt->execute();
$korpa = $stmt->fetch(PDO::FETCH_ASSOC);

if ($korpa) {
    $korpa_id = $korpa['korpa_id'];
} else {
    $upit = "INSERT INTO korpa (korisnik_id) VALUES (:korisnik_id)";
    $stmt = $pdo->prepare($upit);
    $stmt->bindParam(':korisnik_id', $trenutno_aktivan_korisnik_id, PDO::PARAM_INT);
    $stmt->execute();
    $korpa_id = $pdo->lastInsertId();
}

// 2. Dodaj stavku u korpu
$sql = "INSERT INTO stavka_korpe (korpa_id, kurs_id ) 
        VALUES (:korpa_id, :kurs_id)";
$stmt = $pdo->prepare($sql);


$stmt->bindParam(':korpa_id', $korpa_id, PDO::PARAM_INT);
$stmt->bindParam(':kurs_id', $kurs_id, PDO::PARAM_INT);
//$stmt->bindParam(':kupovina', $kupovina_id, PDO::PARAM_NULL);

$stmt->execute();

header("Location: /mvc-course-app/views/course/course.php");
exit;

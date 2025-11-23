<?php
session_start();

require_once __DIR__ . '/../core/env.php';
loadEnv(__DIR__ . '/../.env');
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['korisnik_id'])) {
    die("Niste ulogovani.");
}

$korisnik_id = $_SESSION['korisnik_id'];

// 1. Uzimamo sve stavke iz korpe
$sql = "SELECT sk.kurs_id, c.cena 
        FROM stavka_korpe sk
        JOIN korpa k ON sk.korpa_id = k.korpa_id
        JOIN kurs c ON sk.kurs_id = c.kurs_id
        WHERE k.korisnik_id = :korisnik_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':korisnik_id', $korisnik_id);
$stmt->execute();
$stavke = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$stavke) {
    die("Korpa je prazna.");
}

// Izračunaj ukupnu cenu
$ukupno = 0;
foreach ($stavke as $s) {
    $ukupno += $s['cena'];
}

// 2. Upisujemo kupovinu
$sql = "INSERT INTO kupovina (korisnik_id, datum_kupovine, ukupna_cena, status)
        VALUES (:korisnik_id, NOW(), :ukupna, 'placeno')";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':korisnik_id', $korisnik_id);
$stmt->bindParam(':ukupna', $ukupno);
$stmt->execute();

$kupovina_id = $pdo->lastInsertId();

// 3. Dodajemo kurseve korisniku (moji_kursevi)
$sql = "INSERT IGNORE INTO moji_kursevi (korisnik_id, kurs_id, datum_kupovine_kursa)
        VALUES (:korisnik_id, :kurs_id, NOW())";

$stmt = $pdo->prepare($sql);

foreach ($stavke as $s) {
    $stmt->bindParam(':korisnik_id', $korisnik_id);
    $stmt->bindParam(':kurs_id', $s['kurs_id']);
    $stmt->execute();
}

// 4. Brišemo korpu
$sql = "DELETE FROM korpa WHERE korisnik_id = :korisnik_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':korisnik_id', $korisnik_id);
$stmt->execute();

// 5. Gotovo
header("Location: /mvc-course-app/views/user/purchase_success.php");
exit;

<?php
session_start();
require_once __DIR__ . '/../core/env.php';
loadEnv(__DIR__ . '/../.env');
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['korisnik_id'])) {
    die("Niste ulogovani!");
}

$korisnik_id = $_SESSION['korisnik_id'];

$sql = "INSERT INTO kupovina (korisnik_id, datum_kupovine, status)
        VALUES (:korisnik_id, NOW(), 'ceka_placanje')";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':korisnik_id', $korisnik_id);
$stmt->execute();

$kupovina_id = $pdo->lastInsertId();

// PREBACIVANJE STAVKI IZ KORPE U TABELU stavka_kupovine...

header("Location: /mvc-course-app/views/payment.php?id=$kupovina_id");
exit;
?>
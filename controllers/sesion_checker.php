<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['korisnik_id'])) {
    session_destroy();
    header("Location:index.php");
    exit();
}
?>
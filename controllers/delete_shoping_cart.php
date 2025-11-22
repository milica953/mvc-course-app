<?php
    require_once __DIR__ . '/../core/env.php';
    loadEnv(__DIR__ . '/../.env');
    include '../Config/database.php';

    $stavka_id = $_GET['id'];

    $sql = "DELETE FROM stavka_korpe WHERE stavka_korpe_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $stavka_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: /mvc-course-app/views/user/shoping_cart.php");
exit;

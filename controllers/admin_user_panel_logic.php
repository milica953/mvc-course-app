<?php
        
        require_once __DIR__ . '/../core/env.php';
        loadEnv(__DIR__ . '/../.env');
        include __DIR__ . '../../config/database.php';


        $korisnik_id = $_GET['korisnik_id'];
        $status =  $_GET['status'];

        $upit = "UPDATE korisnik SET is_aktivan = :is_aktivan WHERE korisnik_id = :korisnik_id";

        $stmt = $pdo->prepare($upit);
        
        $stmt->bindParam(':korisnik_id', $korisnik_id);
        $stmt->bindParam(':is_aktivan', $status);

        $stmt->execute();

        header("Location:/mvc-course-app/views/admin/admin_user.php");
?>
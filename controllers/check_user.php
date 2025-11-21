<?php

    require_once __DIR__ . '/../core/env.php';
    loadEnv(__DIR__ . '/../.env');

    if (isset($_POST['username'], $_POST['password']) && !empty($_POST['username'] && $_POST['password'])) {
        
        include '../Config/database.php';

        $username =  $_POST['username'];
        $password = $_POST['password'];
        $aktivan = 1; // pogledaj zasto hardkodujemo a ne preuzimamo iz  db?

        $upit = $pdo->prepare( "SELECT * FROM korisnik WHERE username = :username AND password_hash = :password AND is_aktivan = :aktivan"); 
            
        $upit ->bindParam(':username', $username );
        $upit ->bindParam(':password', $password );
        $upit ->bindParam(':aktivan', $aktivan );
        
        $upit->execute();
        
        $rezultat = $upit->fetch(PDO::FETCH_ASSOC);


        if(!$rezultat){
            header("Location: /mvc-course-app/views/auth/login.php?error=2");
            exit();

        } else{

            session_start();
            $_SESSION['korisnik_id'] = $rezultat['korisnik_id'];
            $_SESSION['ime'] = $rezultat['ime'];
            $_SESSION['prezime'] = $rezultat['prezime'];
            $_SESSION['uloga_id'] = $rezultat['uloga_id'];
            
            /*dodaj za admina da prebaci na njegovu stranicu*/ 
            header("Location:../index.php");
        }

    }else{
        header("Location: /mvc-course-app/views/auth/login.php?error=1");
        exit();
    }

?>
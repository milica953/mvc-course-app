<?php 

    require_once __DIR__ . '/../core/env.php';
    loadEnv(__DIR__ . '/../.env');

    if (
        isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['passwordCheck']) &&
        !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email']) &&
        !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['passwordCheck'])) 
{
        include '../config/database.php';

        $ime = $_POST['fname'];
        $prezime = $_POST['lname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordCheck = $_POST['passwordCheck'];
        $uloga_id = 2;
        $is_aktivan = 1;

         // provera lozinke da li se preklapaju..
        if ($password !== $passwordCheck) {
            header("Location: /mvc-course-app/views/auth/sing_up.php?lozinka=1");
            exit();
        }

         $sql = "INSERT INTO korisnik (uloga_id,email, ime, prezime, username, password_hash,is_aktivan ) 
                    VALUES (:uloga_id,:email,:ime, :prezime, :username, :password_hash, :aktivan)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uloga_id', $uloga_id);
        $stmt ->bindParam(':username', $username);
        $stmt ->bindParam(':password_hash', $password);
        $stmt->bindParam(':ime', $ime);
        $stmt ->bindParam(':prezime', $prezime);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':aktivan', $is_aktivan);
        
        
        $stmt->execute();
        
        header("Location:/mvc-course-app/views/auth/sing_up.php?registracija=1");


    }else {

        header("location:/mvc-course-app/views/auth/sing_up.php?error=3");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/nav.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/footer.css">
</head>

<body>
    <?php
    include_once __DIR__ . '../../layout/nav.php';

    $errorMessage = "";
    
    if (isset($_GET['error'])) {

        if ($_GET['error'] == 1) {
            $errorMessage = "Niste uneli sve parametre.";
        }

        if ($_GET['error'] == 2) {
            $errorMessage = "Pogrešan username ili lozinka, ili je nalog deaktiviran. Kontaktirajte administratora na mejl admin12355588@mkconsulting12.commm";
        }
    }

    ?>
    <div class="holder-login">
        <div class="login-container">
            <h2>Login</h2>

            <form action="../../controllers/check_user.php" method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn">Login</button>
            </form>
            <p>Ako nemate nalog <a href="sing_up.php">Registrujte se</a></p>

            <?php if (!empty($errorMessage)): ?>
                <p class="error-msg"><?= $errorMessage ?></p>
            <?php endif; ?>

        </div>
    </div>
    <?php include_once __DIR__ . '../../layout/footer.php'; ?>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../../assets/css/sing_up.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/nav.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/footer.css">
</head>

<body>
    <?php
    include_once __DIR__ . '../../layout/nav.php';

    $errorMessage = "";

   

    if (isset($_GET['error']) && $_GET['error'] == 3) {
        $errorMessage = 'Sve informacije moraju biti unete.';
    }

    if (isset($_GET['lozinka']) && $_GET['lozinka'] == 1) {
        $errorMessage = 'Niste lepo uneli ponovnu lozinku.';
    }

    if (isset($_GET['registracija']) && $_GET['registracija'] == 1) {
        $errorMessage = 'Vaš nalog je registrovan';
    }

    ?>
    <div class="holder-singup">
        <div class="singup-container">
            <h2>singup</h2>

            <form action="../../controllers/registration_processing.php" method="POST">
                <div class="form-group">
                    <label for="fname">Ime</label>
                    <input type="text" id="fname" name="fname" placeholder="Ime" onfocus="checkName()" onkeyup="checkName()" onblur="clearMessage()">
                    <span id="lfname"></span>
                </div>

                <div class="form-group">
                    <label for="lname">Prezime</label>
                    <input type="text" id="lname" name="lname" placeholder="Prezime" onfocus="checkPrezime()" onkeyup="checkPrezime()" onblur="clearMessageP()">
                    <span id="llname"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" onfocus="checkEmail()" onkeyup="checkEmail()" onblur="clearMessageE()">
                    <span id="lemail"></span>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" onfocus="checkUsername()" onkeyup="checkUsername()" onblur="clearMessageU()">
                    <span id="lusername"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>

                <div class="form-group">
                    <label for="passwordCheck">Ponovi password</label>
                    <input type="password" id="passwordCheck" name="passwordCheck" placeholder="Ponovi password" onfocus="checkPassword()" onkeyup="checkPassword()" onblur="clearMessagePassword()">
                    <span id="lpasswordCheck"></span>
                </div>

                <button type="submit" class="btn">singup</button>
            </form>
            <p>AKO IMATE NALOG <a href="login.php" id="login">ULOGUJTE SE</a></p>

            <?php if (!empty($errorMessage)): ?>
                <p class="error-msg"><?= $errorMessage ?></p>
            <?php endif; ?>

        </div>
    </div>
    <?php include_once __DIR__ . '../../layout/footer.php'; ?>

</body>

</html>
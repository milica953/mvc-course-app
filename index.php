<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <script src="assets/js/rewiew.js"></script>
    <title>Document</title>
</head>


<body>
    <?php
    include_once __DIR__ . '/views/layout/nav.php';
    require_once __DIR__ . '/core/env.php';
    loadEnv(__DIR__ . '/.env');
    include_once __DIR__ . '/config/database.php';

    // Izvlačenje prvih 20 recenzija
    $upit = "SELECT 
            recenzija.kurs_id,
            recenzija.korisnik_id,
            recenzija.opis,
            kurs.naziv,
            korisnik.ime
        FROM recenzija
        INNER JOIN kurs ON recenzija.kurs_id = kurs.kurs_id
        INNER JOIN korisnik ON recenzija.korisnik_id = korisnik.korisnik_id
        LIMIT 10";

    $stmt = $pdo->prepare($upit);
    $stmt->execute();
    $recenzije = $stmt->fetchAll(PDO::FETCH_ASSOC);




    ?>
        <main class="main-home">
        <!-- Sekcija sa tekstom i linkom (leva strana) -->
        <section class="text-section">
            <h1>„Samo radom na sebi postaješ bolji, zato upiši kurs i pronađi posao online.“</h1>
            <a href="/mvc-course-app/views/course/course.php" class="button-course">
                <p>Pogledaj kurseve</p>
            </a>
        </section>

        <!-- Sekcija sa recenzijama (desna strana) -->
        <section class="review-container">
            <div class="review-panel" id="reviewPanel">
                <?php foreach ($recenzije as $index => $recenzija): ?>
                    <!-- Dodat 'review-fade' klasa za tranziciju -->
                    <div class="review-card glass-panel review-fade" <?php echo $index === 0 ? 'style="display:block;"' : ''; ?>>
                        <p><strong><?php echo $recenzija['naziv']; ?></strong></p>
                        <p><?php echo $recenzija['ime']; ?></p>
                        <br>
                        <p><?php echo $recenzija['opis']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Kontrole su uklonjene da bi se recenzije automatski smenjivale bez interakcije korisnika -->
        </section>
    </main>



    <section class="about-section">
        <div class="about-content">
            <div class="about-text">
                <h2>O NAMA</h2>
                <p>Mi smo edukativna platforma posvećena tome da ti pomognemo da naučiš IT veštine uz praktičan rad i podršku mentora. Naš cilj je da ti pomognemo da pronađeš online posao ili klijente i izgradiš karijeru iz udobnosti svog doma.</p>

                <ul class="about-list">
                    <li>✔Praktični kursevi sa realnim projektima</li>
                    <li>✔ Mentorska podrška tokom celog učenja</li>
                    <li>✔ Fokus na pronalaženje posla i prvih klijenata</li>
                </ul>

                <div class="statistics">
                    <div><span>900+</span>
                        <p>Uspešnih polaznika</p>
                    </div>
                    <div><span>18+</span>
                        <p>Aktivnih kurseva</p>
                    </div>
                    <div><span>95%</span>
                        <p>Preporuke polaznika</p>
                    </div>
                </div>
            </div>

            <div class="about-image">
                <img src="assets/img/photo.png" alt="O nama">
            </div>
        </div>
    </section>
    <?php include_once __DIR__ . '/views/layout/footer.php'; ?>
</body>

</html>
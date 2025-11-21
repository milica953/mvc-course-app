<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <title>Document</title>
</head>

<body>
     <?php include_once __DIR__ . '/views/layout/nav.php'; ?>
    <main class="main-home">
        <section class="text-section">
            <h1>„Samo radom na sebi postaješ bolji, zato upiši kurs i pronađi posao online.“</h1>
            <input type="button" value="Pogledaj kurseve" class="button-course" onclick="location.href">
        </section>

        <section>
            <div class="rewiew-panel">
                <div class="review-cards-1 glass-panel">
                    <p>ime kursa</p>
                    <p>ime polaznika</p>
                    <br>
                    <p>opis kursa iz baze povucen</p>
                    <input type="button" value="Pogledaj kurs" class="button-course" onclick="location.href">
                </div>

                <div class="review-cards-1 glass-panel">
                    <p>ime kursa</p>
                    <p>ime polaznika</p>
                    <br>
                    <p>opis kursa iz baze povucen</p>
                    <input type="button" value="Pogledaj kurs" class="button-course" onclick="location.href">
                </div>

                <div class="review-cards-1 glass-panel">
                    <p>ime kursa</p>
                    <p>ime polaznika</p>
                    <br>
                    <p>opis kursa iz baze povucen</p>
                    <input type="button" value="Pogledaj kurs" class="button-course" onclick="location.href">
                </div>

            </div>
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
document.addEventListener('DOMContentLoaded', (event) => {
    let currentIndex = 0;
    const reviews = document.querySelectorAll('.review-card');
    const intervalTime = 2000; // Rotira svakih 5 sekundi (5000ms)

    // Funkcija za prikazivanje određene recenzije
    function showReview(index) {
        // Uklanja 'active' klasu sa svih recenzija
        reviews.forEach(r => r.classList.remove('active'));
        
        // Dodaje 'active' klasu trenutnoj recenziji
        if (reviews[index]) {
            reviews[index].classList.add('active');
        }
    }

    // Funkcija za prelazak na sledeću recenziju
    function nextReview() {
        currentIndex = (currentIndex + 1) % reviews.length;
        showReview(currentIndex);
    }

    // Inicijalno prikaži prvu recenziju (ukoliko već nije prikazana PHP-om)
    if (reviews.length > 0) {
        showReview(currentIndex);
    }

    // Automatski rotiraj svakih 5 sekundi
    setInterval(nextReview, intervalTime);
});

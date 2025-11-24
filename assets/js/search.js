const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const cards = document.querySelectorAll(".card");

function filterCourses() {
    const searchValue = searchInput.value.toLowerCase();
    const categoryValue = categoryFilter.value.toLowerCase();

    cards.forEach(card => {
        const title = card.querySelector("h1").innerText.toLowerCase();
        
        // Pronađi kategoriju unutar <p class="category">
        const categoryElem = card.querySelector(".category");
        const category = categoryElem ? categoryElem.innerText.replace("Kategorija:", "").trim().toLowerCase() : "";

        const matchesSearch = title.includes(searchValue);
        const matchesCategory = categoryValue === "all" || category.includes(categoryValue);

        card.style.display = (matchesSearch && matchesCategory) ? "block" : "none";
    });
}

searchInput.addEventListener("keyup", filterCourses);
categoryFilter.addEventListener("change", filterCourses);

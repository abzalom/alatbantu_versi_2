const sidebar = document.querySelector("aside");
const burgerIcon = document.querySelector(".icon-burger");

// Toggle sidebar saat burger button diklik
burgerIcon.addEventListener("click", function (event) {
    event.stopPropagation(); // Mencegah event klik menyebar ke document
    sidebar.classList.toggle("expanded");
});

// Tutup sidebar jika klik terjadi di luar aside dan burger button
document.addEventListener("click", function (event) {
    if (!sidebar.contains(event.target) && !burgerIcon.contains(event.target)) {
        sidebar.classList.remove("expanded");
    }
});

document.querySelectorAll(".menu-item > .sidebar-menu-link > a").forEach((menuLink) => {
    menuLink.addEventListener("click", function () {
        let icon = this.querySelector(".icon-expand");
        if (icon) {
            icon.classList.toggle("rotate"); // Putar ikon saat diklik
        }
    });
});

// Event keyup untuk input filter
document.querySelectorAll(".filter-input").forEach((input) => {
    input.addEventListener("keyup", function () {
        let value = this.value.toLowerCase().trim();

        let tableSelector = this.dataset.table;
        let rows = document.querySelectorAll(`table${tableSelector} tbody tr`);

        rows.forEach((row) => {
            let cells = row.querySelectorAll("td");
            let match = false;

            // Loop melalui semua sel kecuali yang terakhir
            cells.forEach((cell, index) => {
                // if (index === cells.length) return; // Lewati sel terakhir
                let textValue = cell.textContent || cell.innerText;
                if (textValue.toLowerCase().includes(value)) {
                    match = true;
                }
            });

            row.style.display = match ? "" : "none";
        });
    });
});

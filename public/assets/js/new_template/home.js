$(document).ready(function () {
    // Pastikan plugin ChartDataLabels sudah dimuat
    // Chart.register(ChartDataLabels);

    // var ctx = document.getElementById("sgChart").getContext("2d");

    // var sgChart = new Chart(ctx, {
    //     type: "doughnut",
    //     data: {
    //         labels: [
    //             "Belanja Pendidikan", "Belanja Kesehatan", "Belanja Infrastruktur",
    //             "Belanja Pertanahan", "Belanja Pemberdayaan Ekonomi Masyarakat",
    //             "Belanja Komunikasi Publik", "Belanja Infrastruktur Perhubungan",
    //             "Belanja Infrastruktur Energi Listrik"
    //         ],
    //         datasets: [{
    //             label: "Klasifikasi Belanja",
    //             data: [
    //                 21791784000, 25970724000, 19200000000,
    //                 500000000, 26809702000,
    //                 1455548000, 2000000000,
    //                 200000000
    //             ],
    //             backgroundColor: [
    //                 "rgba(255, 99, 132, 0.6)", "rgba(54, 162, 235, 0.6)",
    //                 "rgba(255, 206, 86, 0.6)", "rgba(75, 192, 192, 0.6)",
    //                 "rgba(153, 102, 255, 0.6)", "rgba(255, 159, 64, 0.6)",
    //                 "rgba(100, 149, 237, 0.6)", "rgba(220, 20, 60, 0.6)"
    //             ],
    //             borderColor: [
    //                 "rgba(255, 99, 132, 1)", "rgba(54, 162, 235, 1)",
    //                 "rgba(255, 206, 86, 1)", "rgba(75, 192, 192, 1)",
    //                 "rgba(153, 102, 255, 1)", "rgba(255, 159, 64, 1)",
    //                 "rgba(100, 149, 237, 1)", "rgba(220, 20, 60, 1)"
    //             ],
    //             borderWidth: 1,
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         // indexAxis: "y", // 🔹 Membuat tampilan horizontal
    //         scales: {
    //             y: {
    //                 beginAtZero: true,
    //                 ticks: {
    //                     callback: function (value) {
    //                         return "Rp. " + (value / 1000000).toLocaleString("id-ID") + "J";
    //                         // 🔹 Format ke dalam "Juta" dengan pemisah ribuan
    //                     }
    //                 }
    //             }
    //         },
    //         plugins: {
    //             legend: {
    //                 display: true
    //             },
    //             datalabels: {
    //                 anchor: "end",
    //                 align: "right",
    //                 formatter: function (value) {
    //                     return ((value / 205814807000) * 100).toFixed(2) + "%";

    //                     // 🔹 Format label di atas batang
    //                 },
    //                 color: "#000",
    //                 font: {
    //                     weight: "bold"
    //                 }
    //             }
    //         }
    //     },
    //     plugins: [ChartDataLabels]
    // });



    const sidebar = document.querySelector("aside");
    const burgerIcon = document.querySelector(".icon-burger");

    // Event klik untuk toggle sidebar
    burgerIcon.addEventListener("click", function () {
        sidebar.classList.toggle("expanded");
    });

    document.querySelectorAll(".menu-item > .sidebar-menu-link > a").forEach((menuLink) => {
        menuLink.addEventListener("click", function () {
            let icon = this.querySelector(".icon-expand");
            if (icon) {
                icon.classList.toggle("rotate"); // Putar ikon saat diklik
            }
        });
    });

    // ambil data dari /dummy/app/menus.json
    // fetch("/dummy/app/menus.json")
    //     .then((response) => response.json())
    //     .then((data) => {
    //         const sidebarMenu = document.querySelector("#sidebar-menu");
    //         sidebarMenu.innerHTML = "";
    //         data.forEach((menu) => {
    //             let nameId = menu.name.toLowerCase().replace(" ", "-");
    //             let link =
    //                 menu.subs.length > 0 ?
    //                 `<a href="#${nameId}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="${nameId}">
    //                         ${menu.name}
    //                         <i class="fa-solid fa-chevron-circle-left icon-expand"></i>
    //                     </a>` :
    //                 `<a href="/resource${menu.path}">${menu.name}</a>`;
    //             let submenus = menu.subs.map((sub) => {
    //                 return `<li class="sidebar-submenu-link">
    //                             <a href="/resource${sub.path}">${sub.name}</a>
    //                         </li>`;
    //             });
    //             let active = menu.name === "Home" ? "active" : "";
    //             sidebarMenu.innerHTML += `
    //                 <li class="menu-item ${active}">
    //                     <div class="sidebar-menu-link">
    //                         <i class="fa-solid fa-${menu.icon} icon-menu"></i>
    //                         ${link}
    //                     </div>
    //                 </li>
    //                 <ul class="sidebar-submenu collapse" id="${nameId}">
    //                     ${submenus.join("")}
    //                 </ul>
    //             `;

    //             document.querySelectorAll(".menu-item > .sidebar-menu-link > a").forEach((menuLink) => {
    //                 menuLink.addEventListener("click", function () {
    //                     let icon = this.querySelector(".icon-expand");

    //                     if (icon) {
    //                         icon.classList.toggle("rotate"); // Putar ikon saat diklik
    //                     }
    //                 });
    //             });
    //         });
    //     });
});

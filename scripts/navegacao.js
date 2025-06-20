// ...menu sanduíche existente...
const menuToggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("sidebar-open");
    menuToggle.classList.toggle("open");
});

// SPA simples: alterna telas
const mainSections = document.querySelectorAll(".box-main");
const sidebarButtons = document.querySelectorAll(
    ".sidebar-buttons .button-enviar[data-target]"
);

sidebarButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const targetId = btn.getAttribute("data-target");
        mainSections.forEach((section) => {
            section.style.display = section.id === targetId ? "flex" : "none";
        });
        // Fecha o menu sanduíche no mobile ao clicar
        sidebar.classList.remove("sidebar-open");
        menuToggle.classList.remove("open");
    });
});

// Exibe apenas a primeira tela ao carregar
mainSections.forEach((section, idx) => {
    section.style.display = idx === 0 ? "flex" : "none";
});
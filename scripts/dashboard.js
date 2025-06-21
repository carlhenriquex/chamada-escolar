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

function gerirInputDeDeficiencia(statusCheckbox) {
    let inputDeficiencia = document.getElementById("texto-deficiencia");
    if (statusCheckbox === true) {
        inputDeficiencia.disabled = false;
        inputDeficiencia.style.backgroundColor = "";
    } else {
        inputDeficiencia.disabled = true;
        inputDeficiencia.style.backgroundColor = "#ccc";
    }
}

// CHECKBOX DEFICIENCIA E RESPONSAVEL DO CADASTRO DE ALUNO

function gerirSeletorDoResponsavel(statusCheckbox) {
    let seletorResponsavel = document.getElementById("seletor-responsavel");
    let sectionResponsaveis = document.getElementById("section-responsaveis");
    if (statusCheckbox === true) {
        seletorResponsavel.disabled = false;
        seletorResponsavel.required = true;
        seletorResponsavel.style.backgroundColor = "";
        sectionResponsaveis.style.display = "none";
    } else {
        seletorResponsavel.disabled = true;
        seletorResponsavel.required = false;
        seletorResponsavel.style.backgroundColor = "#ccc";
        sectionResponsaveis.style.display = "";
    }
}
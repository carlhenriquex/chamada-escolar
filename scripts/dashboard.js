// menu sanduíche existente
const menuToggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("sidebar-open");
    menuToggle.classList.toggle("open");
});


const mainSections = document.querySelectorAll(".box-main");
const sidebarButtons = document.querySelectorAll(".sidebar-buttons .button-enviar");

function exibirTela(id) {
    mainSections.forEach(section => {
        section.style.display = section.id === id ? "flex" : "none";
    });
    window.location.hash = `#${id}`;
}

// Trocar de tela ao clicar no botão
sidebarButtons.forEach(button => {
    button.addEventListener("click", () => {
        const destino = button.dataset.target;
        exibirTela(destino);
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});


// Mostrar a tela correta ao carregar a página
const hash = window.location.hash || "#tela-01";
const telaInicial = hash.substring(1); // remove o #
exibirTela(telaInicial);


// CHECKBOX DEFICIENCIA E RESPONSAVEL DO CADASTRO DE ALUNO

function gerirInputDeDeficiencia(statusCheckbox) {
    const inputDeficiencia = document.getElementById("texto-deficiencia");

    inputDeficiencia.value = ""; // Limpa texto ao alternar

    if (statusCheckbox === true) {
        inputDeficiencia.disabled = false;
        inputDeficiencia.required = true;
        inputDeficiencia.style.backgroundColor = "";
    } else {
        inputDeficiencia.disabled = true;
        inputDeficiencia.required = false;
        inputDeficiencia.style.backgroundColor = "#ccc";
    }
}


function gerirSeletorDoResponsavel(statusCheckbox) {
    const seletorResponsavel = document.getElementById("seletor-responsavel");
    const sectionResponsaveis = document.getElementById("section-responsaveis");
    const inputsResponsavel = sectionResponsaveis.querySelectorAll("input, select");

    seletorResponsavel.value = ""; // Limpa o campo ao alternar

    if (statusCheckbox === true) {
        // Usa responsável já cadastrado
        seletorResponsavel.disabled = false;
        seletorResponsavel.required = true;
        seletorResponsavel.style.backgroundColor = "";
        sectionResponsaveis.style.display = "none";

        // Desativa os required dos campos ocultos
        inputsResponsavel.forEach(input => {
            input.dataset.originalRequired = input.required;
            input.required = false;
        });
    } else {
        // Vai cadastrar novo responsável
        seletorResponsavel.disabled = true;
        seletorResponsavel.required = false;
        seletorResponsavel.style.backgroundColor = "#ccc";
        sectionResponsaveis.style.display = "";

        // Restaura os required conforme estavam originalmente
        inputsResponsavel.forEach(input => {
            if (input.dataset.originalRequired === "true") {
                input.required = true;
            }
        });
    }
}

// Troca visual da unidade
const botoes = document.querySelectorAll('.botao-unidade');
const tabelas = document.querySelectorAll('.tabela-unidade');

botoes.forEach(botao => {
    botao.addEventListener('click', () => {
        const unidade = botao.getAttribute('data-unidade');

        botoes.forEach(b => b.classList.remove('ativo'));
        botao.classList.add('ativo');

        tabelas.forEach(tabela => {
            tabela.style.display = tabela.id === `unidade-${unidade}` ? 'block' : 'none';
        });
    });
});

// Mostrar a primeira tabela por padrão
document.getElementById('unidade-1').style.display = 'block';

// Cálculo da média ao alterar notas
function calcularMedia(input) {
    const row = input.closest('tr');
    const n1 = parseFloat(row.querySelector('input[name$="[n1]"]').value) || 0;
    const n2 = parseFloat(row.querySelector('input[name$="[n2]"]').value) || 0;
    const media = ((n1 + n2) / 2).toFixed(1);
    row.querySelector('.media-input').value = media;
}


/* TROCAR MES DO DASHBOARD RESPONSAVEIS */


function trocarMes(mes, ano) {
    const url = new URL(window.location.href);
    url.searchParams.set("mes", mes);
    url.searchParams.set("ano", ano);
    url.hash = "tela-02";
    window.location.href = url.toString();
}

window.addEventListener("load", () => {
    if (window.location.hash === "#tela-02") {
        // Rola ao topo suavemente ao carregar
        setTimeout(() => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        }, 100);
    }
});

function mostrarUnidade(unidade) {
    document.querySelectorAll('.bloco-unidade').forEach(div => div.style.display = 'none');
    const bloco = document.getElementById("unidade-" + unidade);
    if (bloco) bloco.style.display = 'block';
    window.scrollTo({ top: 0, behavior: "smooth" });
}
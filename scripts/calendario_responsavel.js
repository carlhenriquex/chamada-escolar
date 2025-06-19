document.getElementById("vf-mesAno").addEventListener("change", function () {
    const valorSelecionado = this.value;
    const [ano, mes] = valorSelecionado.split("-").map(Number);
    gerarTabela(mes, ano);
});

function gerarTabela(mes, ano) {
    const corpoTabela = document.getElementById("vf-tabela-corpo");
    corpoTabela.innerHTML = "";

    const diasNoMes = new Date(ano, mes, 0).getDate();
    let semana = new Array(5).fill("");

    for (let dia = 1; dia <= diasNoMes; dia++) {
    const data = new Date(ano, mes - 1, dia);
    const diaSemana = data.getDay();

    if (diaSemana >= 1 && diaSemana <= 5) {
        const status = Math.random() > 0.3 ? "✓" : "X";
        const classe = status === "✓" ? "vf-presente" : "vf-falta";
        semana[diaSemana - 1] = `<td class="${classe}">${String(dia).padStart(2, '0')} - ${status}</td>`;
    }

    if (diaSemana === 5 || dia === diasNoMes) {
        const linha = semana.map(d => d || "<td></td>").join("");
        corpoTabela.innerHTML += `<tr>${linha}</tr>`;
        semana = new Array(5).fill("");
    }
    }
}

window.addEventListener("DOMContentLoaded", () => {
    const agora = new Date();
    const mesAtual = `${agora.getFullYear()}-${String(agora.getMonth() + 1).padStart(2, '0')}`;
    const input = document.getElementById("vf-mesAno");
    input.value = mesAtual;
    gerarTabela(agora.getMonth() + 1, agora.getFullYear());
});
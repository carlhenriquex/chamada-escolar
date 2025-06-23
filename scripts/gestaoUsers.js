// GESTAO DE USUARIOS

const tabLinks = document.querySelectorAll(".tab-link");
const tabContents = document.querySelectorAll(".tab-content");

tabLinks.forEach(btn => {
    btn.addEventListener("click", () => {
        tabLinks.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const target = btn.getAttribute("data-target");
        tabContents.forEach(tc => {
            tc.classList.remove("active");
            if (tc.id === target) tc.classList.add("active");
        });
    });
});

// FORMULARIO DE EDIÇÃO OCULTO

function toggleEditar(id) {
  const form = document.getElementById('form-editar-' + id);
  if (form) {
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
  } else {
    console.warn('Formulário de edição não encontrado para ID:', id);
  }
}

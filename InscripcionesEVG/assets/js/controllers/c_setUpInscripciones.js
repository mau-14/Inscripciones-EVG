import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";

const esCoordinador = window.configUsuario?.esCoordinador;

if (esCoordinador) {
	const modal = new ModalConfirmacion({
		titulo: "Selecciona Curso y Clase",
		contenidoPersonalizado: `
      <label>Curso:
        <select id="select-curso">
          <option value="">Selecciona un curso</option>
          <option value="1ESO">1º ESO</option>
          <option value="2ESO">2º ESO</option>
        </select>
      </label>
      <label>Clase:
        <select id="select-clase">
          <option value="">Selecciona una clase</option>
          <option value="A">A</option>
          <option value="B">B</option>
        </select>
      </label>
    `,
		onAceptar: () => {
			const curso = document.getElementById("select-curso").value;
			const clase = document.getElementById("select-clase").value;

			if (!curso || !clase) {
				alert("Debes seleccionar ambos campos.");
				return;
			}

			sessionStorage.setItem("cursoSeleccionado", curso);
			sessionStorage.setItem("claseSeleccionada", clase);
		},
		onCancelar: () => {
			window.location.href = "/InscripcionesEVG/index.php";
		},
		bloquearCierre: true,
	});

	modal.mostrar();
}

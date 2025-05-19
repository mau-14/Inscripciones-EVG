import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";
import M_obtenerEtapasYClases from "/InscripcionesEVG/assets/js/models/m_obtenerEtapasYClases.js";
import { ErrorDialog } from "/InscripcionesEVG/assets/js/utils/errorHandler.js";

const esCoordinador = window.configUsuario?.esCoordinador;
const errorDialog = new ErrorDialog();

if (esCoordinador) {
	const obj = new M_obtenerEtapasYClases();
	const datos = await obj.obtenerEtapasYClases();

	const opcionesEtapas = datos
		.map(
			(etapa) =>
				`<option value="${etapa.idEtapa}">${etapa.nombreEtapa}</option>`,
		)
		.join("");

	const modal = new ModalConfirmacion({
		titulo: "Selecciona Etapa y Clase",
		contenidoPersonalizado: `
    <label>Etapa:
      <select id="select-curso">
        <option value="">Selecciona una etapa</option>
        ${opcionesEtapas}
      </select>
    </label>
    <label>Clase:
      <select id="select-clase" disabled>
        <option value="">Selecciona una clase</option>
      </select>
    </label>
  `,
		onMostrar: () => {
			const selectCurso = document.getElementById("select-curso");
			const selectClase = document.getElementById("select-clase");

			selectCurso.addEventListener("change", () => {
				const etapaSeleccionada = selectCurso.value;

				selectClase.innerHTML = `<option value="">Selecciona una clase</option>`;

				if (!etapaSeleccionada) {
					selectClase.disabled = true;
					return;
				}

				const etapa = datos.find((e) => e.idEtapa == etapaSeleccionada);

				if (etapa && etapa.clases.length > 0) {
					etapa.clases.forEach((clase) => {
						const option = document.createElement("option");
						option.value = clase.idClase;
						option.textContent = clase.nombre;
						selectClase.appendChild(option);
					});
					selectClase.disabled = false;
				} else {
					selectClase.disabled = true;
				}
			});
		},
		onAceptar: () => {
			const curso = document.getElementById("select-curso").value;
			const clase = document.getElementById("select-clase").value;
			console.log(curso, clase);

			if (!curso || !clase) {
				errorDialog.show("Debes seleccionar ambos campos");
				return false;
			}

			sessionStorage.setItem("cursoSeleccionado", curso);
			sessionStorage.setItem("claseSeleccionada", clase);
		},
		onCancelar: () => {
			window.location.href = "/InscripcionesEVG/index.php";
		},
	});
}

import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";
import M_obtenerEtapasYClases from "/InscripcionesEVG/assets/js/models/m_obtenerEtapasYClases.js";
import { ErrorDialog } from "/InscripcionesEVG/assets/js/utils/errorHandler.js";
import {
	rellenarSelectsConAlumnos,
	rellenarSelectsConSeleccionados,
} from "/InscripcionesEVG/assets/js/controllers/c_obtenerAlumnos.js";

const errorDialog = new ErrorDialog();

export async function setUpInscripciones() {
	const loader = new Loader("Cargando...");
	const obj = new M_obtenerEtapasYClases();
	const datos = await obj.obtenerEtapasYClases();

	const opcionesEtapas = datos
		.map(
			(etapa) =>
				`<option value="${etapa.idEtapa}">${etapa.nombreEtapa}</option>`,
		)
		.join("");
	loader.ocultar();
	new ModalConfirmacion({
		titulo: "Selecciona Etapa y Clase",
		contenidoPersonalizado: `
    <label>Etapa:
      <select id="select-etapa">
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
			const selectEtapa = document.getElementById("select-etapa");
			const selectClase = document.getElementById("select-clase");

			selectEtapa.addEventListener("change", () => {
				const etapaSeleccionada = selectEtapa.value;

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
		onAceptar: async () => {
			const loader = new Loader("Cargando...");

			const etapa = document.getElementById("select-etapa").value;
			const clase = document.getElementById("select-clase").value;
			const selectClase = document.getElementById("select-clase");

			const claseTexto = selectClase.options[selectClase.selectedIndex].text;

			if (!etapa || !clase) {
				errorDialog.show("Debes seleccionar ambos campos");

				loader.ocultar();
				return false;
			}
			const form = document.getElementById("formIns");

			const h2 = document.createElement("h2");
			h2.style.display = "block";

			h2.innerHTML = `Clase seleccionada: <span class="claseTexto">${claseTexto}</span>`;
			form.parentNode.insertBefore(h2, form);
			console.log("ID CLASE" + clase);

			try {
				const response = await fetch(
					"/InscripcionesEVG/index.php?controlador=alumnosSeleccionados&accion=comprobar&j=1",
					{
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({ idClase: clase }),
					},
				);
				const data = await response.json();
				console.log(data);
				if (data.success) {
					await rellenarSelectsConSeleccionados(clase);
				} else {
					await rellenarSelectsConAlumnos(clase);
				}
				document.getElementById("ContenidoPrincipal").style.display = "block";
				return true;
			} catch (error) {
				console.error(error);
			} finally {
				loader.ocultar();
			}

			// sessionStorage.setItem("etapaSeleccionada", etapa);
			// sessionStorage.setItem("claseSeleccionada", clase);
		},
		onCancelar: () => {
			window.location.href = "/InscripcionesEVG/views/menuInscripciones.php";
		},
	});
}

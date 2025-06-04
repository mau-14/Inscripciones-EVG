import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";
import M_inscribirAlumnosTO from "/InscripcionesEVG/assets/js/models/m_inscribirAlumnosTO.js";
import { ErrorDialog } from "/InscripcionesEVG/assets/js/utils/errorHandler.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";

const boton = document.getElementById("inscripcionAlumnos");

const errorDialog = new ErrorDialog();

boton.addEventListener("click", (e) => {
	e.preventDefault();

	const resultado = obtenerInscripcion();
	if (!resultado) return;
	new ModalConfirmacion({
		titulo: "Confirme operación",
		mensaje: "¿Estás seguro de inscribir a los alumnos?",
		onAceptar: async () => {
			const loader = new Loader("Cargando...");
			const obj = new M_inscribirAlumnosTO();
			const data = await obj.inscribirAlumnos(resultado);

			if (data.success) {
				loader.ocultar();
				errorDialog.show(data.success, true);
			} else {
				loader.ocultar();
				errorDialog.show("Esta mal " + data.error);
				return;
			}
		},
		onCancelar: () => {
			console.log("cancelar");
		},
	});
});

function obtenerInscripcion() {
	const categorias = ["camposPruebasMasculina", "camposPruebasFemenina"];
	let resultado = {
		M: { P: {}, C: {} },
		F: { P: {}, C: {} },
	};

	let hayErrorEnTipoC = false;

	categorias.forEach((categoriaId) => {
		const genero = categoriaId.includes("Masculina") ? "M" : "F";
		const campos = document.querySelectorAll(`#${categoriaId} .campo`);

		campos.forEach((campo) => {
			const pruebaId = campo.querySelector("label").id;
			const selects = campo.querySelectorAll("select");

			selects.forEach((select) => {
				const tipo = select.name.toUpperCase(); // "P" o "C"
				const alumnoId = select.value;

				if (alumnoId !== "" && (tipo === "P" || tipo === "C")) {
					if (!resultado[genero][tipo][pruebaId]) {
						resultado[genero][tipo][pruebaId] = [];
					}
					resultado[genero][tipo][pruebaId].push(Number(alumnoId));
				}
			});
		});
	});

	// Validar de forma genérica: si alguna prueba tipo C no tiene 4 alumnos
	for (const genero of ["M", "F"]) {
		const pruebasC = resultado[genero]["C"];
		for (const idPrueba in pruebasC) {
			const inscripciones = pruebasC[idPrueba];
			if (inscripciones.length !== 4) {
				hayErrorEnTipoC = true;
				break;
			}
		}
		if (hayErrorEnTipoC) break;
	}

	if (hayErrorEnTipoC) {
		errorDialog.show("4*100 son mínimo 4 participantes");
		return null;
	}
	const hayInscripciones = Object.values(resultado).some((genero) =>
		Object.values(genero).some((tipo) =>
			Object.values(tipo).some((alumnos) => alumnos.length > 0),
		),
	);

	if (!hayInscripciones) {
		errorDialog.show("Inscripción vacía");
		return null;
	}

	resultado = JSON.stringify(resultado, null, 2);
	return resultado;
}

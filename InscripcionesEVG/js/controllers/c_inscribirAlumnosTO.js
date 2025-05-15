import { ModalConfirmacion } from "/InscripcionesEVG/js/utils/modalConfirmacion.js";

const boton = document.getElementById("inscripcionAlumnos");

boton.addEventListener("click", (e) => {
	e.preventDefault();
	new ModalConfirmacion({
		titulo: "Confirme operación",
		mensaje: "¿Estás seguro de inscribir a los alumnos?",
		onAceptar: () => {
			obtenerInscripcion();
		},
		onCancelar: () => {
			console.log("cancelar");
		},
	});
});

function obtenerInscripcion() {
	const categorias = ["camposPruebasMasculina", "camposPruebasFemenina"];
	const resultado = {
		M: { P: {}, C: {} },
		F: { P: {}, C: {} },
	};

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

	console.log("JSON final:", JSON.stringify(resultado, null, 2));
}

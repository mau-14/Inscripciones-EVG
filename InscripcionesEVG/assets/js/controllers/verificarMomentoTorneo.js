import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";

window.addEventListener("DOMContentLoaded", () => {
	const momentoActual = window.MOMENTO_ACTUAL;

	// el id del Momento es 41
	const esMomentoTorneo = momentoActual.idMomento == 0; // ajusta según tu lógica

	const enlaceTorneo = document.querySelector(
		'a[href="/InscripcionesEVG/views/inscripcionesTO.php"]',
	);

	if (enlaceTorneo) {
		enlaceTorneo.addEventListener("click", (e) => {
			if (!esMomentoTorneo) {
				e.preventDefault(); // Evita que siga el enlace
				new ModalConfirmacion({
					titulo: "No disponible",
					mensaje:
						"Actualmente no es el momento para inscribirse al Torneo Olímpico.",
					onAceptar: () => {},
					onCancelar: () => {},
				});
			}
		});
	}
});

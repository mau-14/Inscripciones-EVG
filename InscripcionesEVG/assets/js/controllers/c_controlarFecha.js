import M_obtenerMomentos from "/InscripcionesEVG/assets/js/models/m_obtenerMomentos.js";

function obtenerMomentoActual(momentos) {
	const hoy = new Date();
	const hoyStr = hoy.toISOString().split("T")[0]; // Formato "YYYY-MM-DD"

	for (const momento of momentos) {
		if (hoyStr >= momento.fecha_inicio && hoyStr <= momento.fecha_fin) {
			return momento;
		}
	}

	return null;
}

async function controlarFecha() {
	try {
		const gestorMomentos = new M_obtenerMomentos();
		const momentos = await gestorMomentos.obtenerMomentos();

		const hoy = new Date();

		// Buscar el momento que contiene la fecha actual
		const momentoActual = momentos.find((m) => {
			const inicio = new Date(m.fecha_inicio);
			const fin = new Date(m.fecha_fin);
			return hoy >= inicio && hoy <= fin;
		});

		if (momentoActual) {
			console.log("Momento actual:", momentoActual);

			// Enviar momento actual al backend para guardarlo en sesión
			await fetch(
				"/InscripcionesEVG/index.php?controlador=controlarFecha&accion=guardarMomentoActivo&j=1",
				{
					method: "POST",
					headers: { "Content-Type": "application/json" },
					body: JSON.stringify(momentoActual),
				},
			);
		}
	} catch (error) {
		console.error("Error al controlar fecha:", error);
	}
}

// Ejecutar al cargar
controlarFecha();

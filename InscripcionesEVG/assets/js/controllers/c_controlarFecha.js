import M_obtenerMomentos from "/InscripcionesEVG/assets/js/models/m_obtenerMomentos.js";

async function controlarFecha() {
	try {
		const gestorMomentos = new M_obtenerMomentos();
		const momentos = await gestorMomentos.obtenerMomentos();

		const hoy = new Date();

		// Filtra los momentos activos hoy
		const momentosActuales = momentos.filter((m) => {
			const inicio = new Date(m.fecha_inicio);
			const fin = new Date(m.fecha_fin);
			return hoy >= inicio && hoy <= fin;
		});

		// Separa el momento torneo y los demás
		let momentoTorneoOlimpico = null;
		let momentoActual = null;
		console.log(momentosActuales);

		for (const momento of momentosActuales) {
			if (momento.idMomento == 0) {
				momentoTorneoOlimpico = momento;
			} else {
				momentoActual = momento;
			}
		}

		console.log("Momento actual:", momentoActual);
		console.log("Momento Torneo Olímpico:", momentoTorneoOlimpico);

		// Puedes enviar ambos al backend si lo necesitas
		await fetch(
			"/InscripcionesEVG/index.php?controlador=controlarFecha&accion=guardarMomentosActivos&j=1",
			{
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({
					momentoActual,
					momentoTorneoOlimpico,
				}),
			},
		);
	} catch (error) {
		console.error("Error al controlar fecha:", error);
	}
}

// Ejecutar al cargar
controlarFecha();

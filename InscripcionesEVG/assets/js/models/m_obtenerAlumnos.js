class M_obtenerAlumnos {
	async obtenerAlumnos(idClase) {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=obtenerAlumnos&accion=obtenerAlumnos&j=1",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify({ idClase: idClase }),
				},
			);
			const data = await response.json();
			console.log("ALUMNOS", data);
			return data;
		} catch (error) {
			console.error(error);
		}
	}

	async obtenerAlumnosInscripcionesTO(idPruebaM, idPruebaF) {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=obtenerAlumnos&accion=obtenerInscripcionesAlumnosTO&j=1",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify({ idPruebaM: idPruebaM, idPruebaF: idPruebaF }),
				},
			);
			const data = await response.json();

			console.log("ALUMNOS", data);
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerAlumnos;

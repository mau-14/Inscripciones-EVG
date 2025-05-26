class M_inscribirAlumnosTO {
	async inscribirAlumnos(datos) {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=inscribirAlumnosTO&accion=inscribirAlumnos&j=1",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: datos,
				},
			);
			const data = await response.json();
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_inscribirAlumnosTO;

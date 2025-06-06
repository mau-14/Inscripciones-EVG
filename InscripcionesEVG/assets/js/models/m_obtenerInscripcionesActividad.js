class M_obtenerInscripcionesActividad {
	async obtenerAlumnosInscripcionesActividad(idActividad) {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=obtenerActividades&accion=obtenerInscripcionesAlumnosActividad&j=1",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify({ idActividad: idActividad }),
				},
			);
			const data = await response.json();

			console.log("Alumnos ACTIVIDAD", data);
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerInscripcionesActividad;

class M_obtenerAlumnos {
	async obtenerAlumnos() {
		try {
			const response = await fetch(
				"/InscripcionesEVG/app/controllers/c_obtenerAlumnos.php",
			);
			const data = await response.json();
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerAlumnos;

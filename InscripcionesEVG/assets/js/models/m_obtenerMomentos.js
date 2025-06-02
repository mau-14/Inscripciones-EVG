class M_obtenerMomentos {
	async obtenerMomentos() {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=controlarFecha&accion=extraerFecha",
			);
			const data = await response.json();
			console.log("MOMENTOS:", data);
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerMomentos;

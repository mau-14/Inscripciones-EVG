class M_obtenerEtapasYClases {
	async obtenerEtapasYClases() {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=obtenerEtapasYClases&accion=obtenerEtapasYClases",
			);
			const data = await response.json();
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerEtapasYClases;

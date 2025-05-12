class M_obtenerPruebas {
	async obtenerPruebas() {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_obtenerPruebas.php",
			);
			const data = await response.json();
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerPruebas;

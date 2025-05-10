class M_obtenerPruebas {
	async obtenerPruebas() {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_obtenerPruebas.php",
			);
			const data = await response.json();
			console.log(data);
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerPruebas;

var obj = new M_obtenerPruebas();
obj.obtenerPruebas();

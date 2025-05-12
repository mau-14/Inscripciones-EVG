class M_crudInscripcionesTO {
	async insertInscripciones(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_insertInscripcion.php",
				{
					method: "POST", // Usamos POST para enviar datos
					headers: {
						"Content-Type": "application/json", // Indicamos que el cuerpo es JSON
					},
					body: datos,
				},
			);

			const data = await response.json();
			console.log(data);
		} catch (error) {
			console.error("Error al enviar inscripción:", error);
		}
	}
}

export default M_crudInscripcionesTO;

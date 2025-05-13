import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

class M_crudInscripcionesTO {
	async insertInscripciones(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_insertInscripcion.php",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: datos,
				},
			);

			const data = await response.json();

			if (data.error) {
				errorDialog.show(data.error);
			} else {
				errorDialog.show(data.success, true);
			}
		} catch (error) {
			console.error("Error al enviar inscripción:", error);
		}
	}

	async borrarInscripcion(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_deleteInscripciones.php",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: datos,
				},
			);

			const data = await response.json();

			if (data.error) {
				errorDialog.show(data.error);
			} else {
				errorDialog.show(data.success, true);
			}
		} catch (error) {
			console.error("Error al enviar inscripción:", error);
		}
	}
}

export default M_crudInscripcionesTO;

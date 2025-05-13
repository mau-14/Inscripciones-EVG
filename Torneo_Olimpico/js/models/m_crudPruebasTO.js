import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

class M_crudPruebasTO {
	async insertPrueba(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_insertPruebaTO.php",
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
			console.error("Error al añadir prueba:", error);
		}
	}

	async borrarPrueba(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_borrarPruebasTO.php",
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
			console.error("Error al borrar prueba:", error);
		}
	}

	async modificarPrueba(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_modificarPruebasTO.php",
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
			console.error("Error al modificar prueba:", error);
		}
	}
}

export default M_crudPruebasTO;

import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

/**
 * Modelo para realizar operaciones CRUD (crear, borrar, modificar)
 * sobre las pruebas del Torneo Olímpico mediante peticiones `fetch`
 * a controladores PHP en el servidor.
 */
class M_crudPruebasTO {
	/**
	 * Inserta una nueva prueba en el sistema.
	 *
	 * @async
	 * @method insertPrueba
	 * @param {string} datos - Cuerpo de la solicitud en formato JSON.
	 * @returns {Promise<Object|undefined>} La respuesta del servidor si es exitosa.
	 * @throws {Error} Si ocurre un error de red o procesamiento.
	 */
	async insertPrueba(datos) {
		try {
			const response = await fetch(
				"/Torneo_Olimpico/app/controllers/c_insertPruebasTO.php",
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
				return data;
			}
		} catch (error) {
			console.error("Error al añadir prueba:", error);
			throw error;
		}
	}

	/**
	 * Elimina una prueba del sistema.
	 *
	 * @async
	 * @method borrarPrueba
	 * @param {string} datos - Cuerpo de la solicitud en formato JSON.
	 * @returns {Promise<Object|undefined>} La respuesta del servidor si es exitosa.
	 * @throws {Error} Si ocurre un error de red o procesamiento.
	 */
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
				return data;
			}
		} catch (error) {
			console.error("Error al borrar prueba:", error);
			throw error;
		}
	}

	/**
	 * Modifica una prueba existente en el sistema.
	 *
	 * @async
	 * @method modificarPrueba
	 * @param {string} datos - Cuerpo de la solicitud en formato JSON.
	 * @returns {Promise<Object|undefined>} La respuesta del servidor si es exitosa.
	 * @throws {Error} Si ocurre un error de red o procesamiento.
	 */
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
				return data;
			}
		} catch (error) {
			console.error("Error al modificar prueba:", error);
			throw error;
		}
	}
}

export default M_crudPruebasTO;

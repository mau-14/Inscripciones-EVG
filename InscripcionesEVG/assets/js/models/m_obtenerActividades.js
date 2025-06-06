/**
 * Modelo encargado de obtener la lista de pruebas del Torneo Olímpico
 * desde el backend mediante una petición `fetch`.
 */
class M_obtenerActividades {
	/**
	 * Realiza una petición al servidor para obtener todas las pruebas.
	 *
	 * @async
	 * @method obtenerPruebas
	 * @returns {Promise<Object[]|undefined>} Un array de objetos con datos de pruebas, o `undefined` si ocurre un error.
	 */
	async obtenerActividades() {
		try {
			const response = await fetch(
				"/InscripcionesEVG/index.php?controlador=obtenerActividades&accion=obtenerActividades",
			);
			const data = await response.json();
			return data;
		} catch (error) {
			console.error(error);
		}
	}
}

export default M_obtenerActividades;

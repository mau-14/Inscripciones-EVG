class MomentosModel {
	constructor() {
		this.baseURL = "/InscripcionesEVG/index.php?controlador=momentos";
	}

	async mostrarMomentos() {
		try {
			const response = await fetch(`${this.baseURL}&accion=cMostrarMomentos`);
			return await response.json();
		} catch (error) {
			console.error("Error al mostrar momentos:", error);
			throw error;
		}
	}

	async insertarMomento(formData) {
		try {
			const response = await fetch(`${this.baseURL}&accion=cInsertarMomento`, {
				method: "POST",
				body: formData,
			});
			return await response.json();
		} catch (error) {
			console.error("Error al insertar momento:", error);
			throw error;
		}
	}

	async editarMomento(formData) {
		try {
			const response = await fetch(`${this.baseURL}&accion=cEditarMomento`, {
				method: "POST",
				body: formData,
			});
			return await response.json();
		} catch (error) {
			console.error("Error al editar momento:", error);
			throw error;
		}
	}

	async eliminarMomento(idMomento) {
		try {
			const response = await fetch(
				`${this.baseURL}&accion=cEliminarMomento&idMomento=${idMomento}`,
			);
			return await response.json();
		} catch (error) {
			console.error("Error al eliminar momento:", error);
			throw error;
		}
	}
}

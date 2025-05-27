class ActividadesController {
	constructor() {
		this.model = new ActividadesModel();
		this.idActividadAEliminar = null;
		this.initializeEventListeners();
	}

	initializeEventListeners() {
		// Formulario de nueva actividad
		const formActividad = document.getElementById("formActividad");
		if (formActividad) {
			formActividad.addEventListener("submit", (e) =>
				this.handleInsertarActividad(e),
			);
		}

		// Formulario de editar actividad
		const formEditarActividad = document.getElementById("formEditarActividad");
		if (formEditarActividad) {
			formEditarActividad.addEventListener("submit", (e) =>
				this.handleEditarActividad(e),
			);
		}

		// Botones de eliminar
		document.querySelectorAll(".eliminar").forEach((boton) => {
			boton.addEventListener("click", () => {
				this.idActividadAEliminar = boton.dataset.id;
				document.getElementById("modalConfirmar").style.display = "block";
			});
		});

		// Botón de confirmar eliminación
		const btnConfirmarEliminar = document.getElementById(
			"btnConfirmarEliminar",
		);
		if (btnConfirmarEliminar) {
			btnConfirmarEliminar.addEventListener("click", () =>
				this.handleEliminarActividad(),
			);
		}

		// Botón para cerrar el modal de error
		const btnCerrarError = document.getElementById("btnCerrarError");
		if (btnCerrarError) {
			btnCerrarError.addEventListener("click", () => {
				document.getElementById("modalError").style.display = "none";
			});
		}
	}

	validarInsertarActividad(formData) {
		const nombre = formData.get("nombre");
		const maxParticipantes = formData.get("maxParticipantes");
		const tipo = formData.get("tipo");
		const fecha = formData.get("fecha");
		const hora = formData.get("hora");

		if (!nombre || nombre.trim() === "") {
			throw new Error("El nombre de la actividad es obligatorio");
		}

		if (!maxParticipantes || maxParticipantes.trim() === "") {
			throw new Error("El número máximo de participantes es obligatorio");
		}

		if (isNaN(maxParticipantes) || parseInt(maxParticipantes) <= 0) {
			throw new Error(
				"El número máximo de participantes debe ser un número positivo",
			);
		}

		if (!tipo || tipo.trim() === "") {
			throw new Error("El tipo de actividad es obligatorio");
		}

		if (fecha && hora) {
			const fechaHora = new Date(`${fecha}T${hora}`);
			const ahora = new Date();
			if (fechaHora < ahora) {
				throw new Error("La fecha y hora no pueden ser anteriores a la actual");
			}
		}

		return true;
	}

	validarEditarActividad(formData) {
		const idActividad = formData.get("idActividad");
		const nombre = formData.get("editarNombre");
		const maxParticipantes = formData.get("editarMaxParticipantes");
		const fecha = formData.get("editarFecha");
		const hora = formData.get("editarHora");

		if (!idActividad || idActividad.trim() === "") {
			throw new Error("No se ha podido identificar la actividad a editar");
		}

		if (!nombre || nombre.trim() === "") {
			throw new Error("El nombre de la actividad es obligatorio");
		}

		if (!maxParticipantes || maxParticipantes.trim() === "") {
			throw new Error("El número máximo de participantes es obligatorio");
		}

		if (isNaN(maxParticipantes) || parseInt(maxParticipantes) <= 0) {
			throw new Error(
				"El número máximo de participantes debe ser un número positivo",
			);
		}

		if (fecha && hora) {
			const fechaHora = new Date(`${fecha}T${hora}`);
			const ahora = new Date();
			if (fechaHora < ahora) {
				throw new Error("La fecha y hora no pueden ser anteriores a la actual");
			}
		}

		return true;
	}

	async handleInsertarActividad(e) {
		e.preventDefault();
		const formData = new FormData(e.target);

		try {
			// Validar antes de enviar
			this.validarInsertarActividad(formData);

			const resultado = await this.model.insertarActividad(formData);
			if (resultado.success) {
				window.location.reload();
			} else {
				this.mostrarError(resultado.error || "Error al insertar la actividad");
			}
		} catch (error) {
			this.mostrarError(error.message);
		}
	}

	async handleEditarActividad(e) {
		e.preventDefault();
		const formData = new FormData(e.target);

		try {
			// Validar antes de enviar
			this.validarEditarActividad(formData);

			const resultado = await this.model.editarActividad(formData);
			if (resultado.success) {
				document.getElementById("modalEditar").style.display = "none";
				window.location.reload();
			} else {
				document.getElementById("modalEditar").style.display = "none";
				this.mostrarError(resultado.error || "Error al editar la actividad");
			}
		} catch (error) {
			document.getElementById("modalEditar").style.display = "none";
			this.mostrarError(error.message);
		}
	}

	async handleEliminarActividad() {
		if (!this.idActividadAEliminar) {
			this.mostrarError("No se ha podido identificar la actividad a eliminar");
			return;
		}

		try {
			const resultado = await this.model.eliminarActividad(
				this.idActividadAEliminar,
			);
			if (resultado.success) {
				document.getElementById("modalConfirmar").style.display = "none";
				window.location.reload();
			} else {
				document.getElementById("modalConfirmar").style.display = "none";
				this.mostrarError(resultado.error || "Error al eliminar la actividad");
			}
		} catch (error) {
			document.getElementById("modalConfirmar").style.display = "none";
			this.mostrarError(error.message);
		}
	}

	mostrarError(mensaje) {
		const modalError = document.getElementById("modalError");
		const errorMsgText = document.getElementById("errorMsgText");
		if (modalError && errorMsgText) {
			errorMsgText.textContent = mensaje;
			modalError.style.display = "block";
		} else {
			alert(mensaje);
		}
	}
}

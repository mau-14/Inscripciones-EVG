class MomentosController {
	constructor() {
		this.model = new MomentosModel();
		this.idMomentoAEliminar = null;
		this.initializeEventListeners();
	}

	initializeEventListeners() {
		// Formulario de nuevo momento
		const formMomento = document.getElementById("formMomento");
		if (formMomento) {
			formMomento.addEventListener("submit", (e) =>
				this.handleInsertarMomento(e),
			);
		}

		// Formulario de editar momento
		const formEditarMomento = document.getElementById("formEditarMomento");
		if (formEditarMomento) {
			formEditarMomento.addEventListener("submit", (e) =>
				this.handleEditarMomento(e),
			);
		}

		// Botones de eliminar
		document.querySelectorAll(".eliminar").forEach((boton) => {
			boton.addEventListener("click", () => {
				this.idMomentoAEliminar = boton.dataset.id;
				document.getElementById("modalConfirmar").style.display = "block";
			});
		});

		// Botón de confirmar eliminación
		const btnConfirmarEliminar = document.getElementById(
			"btnConfirmarEliminar",
		);
		if (btnConfirmarEliminar) {
			btnConfirmarEliminar.addEventListener("click", () =>
				this.handleEliminarMomento(),
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

	validarInsertarMomento(formData) {
		const nombre = formData.get("nombre");
		const fechaInicio = formData.get("fechaInicio");
		const fechaFin = formData.get("fechaFin");

		if (!nombre || nombre.trim() === "") {
			throw new Error("El nombre del momento es obligatorio");
		}

		if (fechaInicio && fechaFin) {
			const inicio = new Date(fechaInicio);
			const fin = new Date(fechaFin);
			if (inicio > fin) {
				throw new Error(
					"La fecha de inicio no puede ser posterior a la fecha de fin",
				);
			}
		}

		return true;
	}

	validarEditarMomento(formData) {
		const idMomento = formData.get("idMomento");
		const nombre = formData.get("nombre");
		const fechaInicio = formData.get("fechaInicio");
		const fechaFin = formData.get("fechaFin");

		if (!idMomento || idMomento.trim() === "") {
			throw new Error("No se ha podido identificar el momento a editar");
		}

		if (!nombre || nombre.trim() === "") {
			throw new Error("El nombre del momento es obligatorio");
		}

		if (fechaInicio && fechaFin) {
			const inicio = new Date(fechaInicio);
			const fin = new Date(fechaFin);
			if (inicio > fin) {
				throw new Error(
					"La fecha de inicio no puede ser posterior a la fecha de fin",
				);
			}
		}

		return true;
	}

	async handleInsertarMomento(e) {
		e.preventDefault();
		const formData = new FormData(e.target);
		try {
			this.validarInsertarMomento(formData);
			const resultado = await this.model.insertarMomento(formData);
			if (resultado.success) {
				window.location.reload();
			} else {
				this.mostrarError(resultado.error || "Error al insertar el momento");
			}
		} catch (error) {
			this.mostrarError(error.message);
		}
	}

	async handleEditarMomento(e) {
		e.preventDefault();
		const formData = new FormData(e.target);
		try {
			this.validarEditarMomento(formData);
			const resultado = await this.model.editarMomento(formData);
			if (resultado.success) {
				document.getElementById("modalEditar").style.display = "none";
				window.location.reload();
			} else {
				document.getElementById("modalEditar").style.display = "none";
				this.mostrarError(resultado.error || "Error al editar el momento");
			}
		} catch (error) {
			document.getElementById("modalEditar").style.display = "none";
			this.mostrarError(error.message);
		}
	}

	async handleEliminarMomento() {
		if (!this.idMomentoAEliminar) {
			this.mostrarError("No se ha podido identificar el momento a eliminar");
			return;
		}

		try {
			const resultado = await this.model.eliminarMomento(
				this.idMomentoAEliminar,
			);
			if (resultado.success) {
				document.getElementById("modalConfirmar").style.display = "none";
				window.location.reload();
			} else {
				document.getElementById("modalConfirmar").style.display = "none";
				this.mostrarError(resultado.error || "Error al eliminar el momento");
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

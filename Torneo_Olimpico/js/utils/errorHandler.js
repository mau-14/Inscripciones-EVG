/**
 * Clase para mostrar un diálogo de errores o mensajes de éxito en pantalla.
 * Crea dinámicamente un modal y lo gestiona desde JavaScript puro.
 */
export class ErrorDialog {
	constructor() {
		/** @type {HTMLDivElement} */
		this.dialog = document.createElement("div");
		this.dialog.id = "error-dialog";
		this.dialog.classList.add("error-dialog");
		this.dialog.style.display = "none"; // Oculto por defecto

		/** @type {HTMLDivElement} */
		this.content = document.createElement("div");
		this.content.classList.add("error-dialog-content");

		/** @type {HTMLHeadingElement} */
		this.title = document.createElement("h2");
		this.title.style.marginTop = "0";
		this.content.appendChild(this.title);

		/** @type {HTMLSpanElement} */
		this.message = document.createElement("span");
		this.content.appendChild(this.message);

		/** @type {HTMLButtonElement} */
		this.closeButton = document.createElement("button");
		this.closeButton.textContent = "Cerrar";
		this.closeButton.onclick = () => this.hide(); // Cierra el modal
		this.content.appendChild(this.closeButton);

		// Añadir el contenido al contenedor del modal
		this.dialog.appendChild(this.content);

		// Insertar el modal al final del body
		document.body.appendChild(this.dialog);
	}

	/**
	 * Muestra el diálogo con un mensaje y color dependiendo de si es éxito o error.
	 *
	 * @param {string} message - Mensaje a mostrar.
	 * @param {boolean} [isSuccess=false] - Si es `true`, se mostrará como mensaje de éxito (verde).
	 */
	show(message, isSuccess = false) {
		this.title.textContent = isSuccess ? "Éxito" : "Error";
		this.message.textContent = message;

		if (isSuccess) {
			this.content.style.backgroundColor = "#4CAF50"; // Verde
			this.title.style.color = "white";
		} else {
			this.content.style.backgroundColor = "#f44336"; // Rojo
			this.title.style.color = "white";
		}

		this.message.style.color = "white";
		this.dialog.style.display = "flex";
	}

	/**
	 * Oculta el diálogo.
	 */
	hide() {
		this.dialog.style.display = "none";
	}
}

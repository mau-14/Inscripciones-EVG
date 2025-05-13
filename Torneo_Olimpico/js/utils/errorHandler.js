export class ErrorDialog {
	constructor() {
		// Crear el contenedor del modal
		this.dialog = document.createElement("div");
		this.dialog.id = "error-dialog";
		this.dialog.classList.add("error-dialog");
		this.dialog.style.display = "none"; // Iniciar oculto

		// Crear el contenido del modal
		this.content = document.createElement("div");
		this.content.classList.add("error-dialog-content");

		// Crear el título del modal (Éxito/Error)
		this.title = document.createElement("h2");
		this.title.style.marginTop = "0";
		this.content.appendChild(this.title);

		// Crear el mensaje
		this.message = document.createElement("span");
		this.content.appendChild(this.message);

		// Crear el botón de cerrar
		this.closeButton = document.createElement("button");
		this.closeButton.textContent = "Cerrar";
		this.closeButton.onclick = () => this.hide(); // Cerrar el modal
		this.content.appendChild(this.closeButton);

		// Añadir el contenido al contenedor
		this.dialog.appendChild(this.content);

		// Agregar al body
		document.body.appendChild(this.dialog);
	}

	// Mostrar el diálogo con título y color según éxito/error
	show(message, isSuccess = false) {
		this.title.textContent = isSuccess ? "Éxito" : "Error";
		this.message.textContent = message;

		if (isSuccess) {
			this.content.style.backgroundColor = "#4CAF50"; // Verde brillante
			this.title.style.color = "white";
		} else {
			this.content.style.backgroundColor = "#f44336"; // Rojo estándar
			this.title.style.color = "white";
		}

		this.message.style.color = "white";
		this.dialog.style.display = "flex";
	}

	// Ocultar el diálogo
	hide() {
		this.dialog.style.display = "none";
	}
}

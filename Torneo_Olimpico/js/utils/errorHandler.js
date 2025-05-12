export class ErrorDialog {
	constructor() {
		// Crear el contenedor del modal de error
		this.dialog = document.createElement("div");
		this.dialog.id = "error-dialog";
		this.dialog.classList.add("error-dialog");
		this.dialog.style.display = "none"; // Iniciar oculto

		// Crear el contenido del modal de error
		this.content = document.createElement("div");
		this.content.classList.add("error-dialog-content");

		// Crear el mensaje de error
		this.message = document.createElement("span");
		this.content.appendChild(this.message);

		// Crear el botón de cerrar
		this.closeButton = document.createElement("button");
		this.closeButton.textContent = "Cerrar";
		this.closeButton.onclick = () => this.hide(); // Cerrar el modal al hacer clic
		this.content.appendChild(this.closeButton);

		// Añadir el contenido al contenedor del diálogo
		this.dialog.appendChild(this.content);

		// Agregar el modal al body
		document.body.appendChild(this.dialog);
	}

	// Método para mostrar el diálogo de error con un mensaje
	show(message) {
		this.message.textContent = message;
		this.dialog.style.display = "flex"; // Mostrar el modal
	}

	// Método para ocultar el diálogo
	hide() {
		this.dialog.style.display = "none"; // Ocultar el modal
	}
}

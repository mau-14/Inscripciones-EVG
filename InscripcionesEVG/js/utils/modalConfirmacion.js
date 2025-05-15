export class ModalConfirmacion {
	constructor({ titulo, mensaje, onAceptar, onCancelar }) {
		this.titulo = titulo;
		this.mensaje = mensaje;
		this.onAceptar = onAceptar;
		this.onCancelar = onCancelar || (() => {}); // Función por defecto

		this.crearModal();
	}

	crearModal() {
		// Crear elementos
		this.modal = document.createElement("div");
		this.modal.className = "modal";
		this.modal.style.display = "flex";

		const contenido = document.createElement("div");
		contenido.className = "modal-content";

		const h3 = document.createElement("h3");
		h3.textContent = this.titulo;

		const p = document.createElement("p");
		p.textContent = this.mensaje;

		const botones = document.createElement("div");
		botones.className = "botones";

		const btnAceptar = document.createElement("button");
		btnAceptar.textContent = "Aceptar";
		btnAceptar.className = "aceptar";
		btnAceptar.addEventListener("click", () => {
			this.cerrar();
			this.onAceptar();
		});

		const btnCancelar = document.createElement("button");
		btnCancelar.textContent = "Cancelar";
		btnCancelar.className = "cancelar";
		btnCancelar.addEventListener("click", () => {
			this.cerrar();
			this.onCancelar();
		});

		// Componer elementos
		botones.appendChild(btnAceptar);
		botones.appendChild(btnCancelar);
		contenido.appendChild(h3);
		contenido.appendChild(p);
		contenido.appendChild(botones);
		this.modal.appendChild(contenido);
		document.body.appendChild(this.modal);
	}

	cerrar() {
		this.modal.remove();
	}
}

export class ModalConfirmacion {
	constructor({
		titulo,
		mensaje,
		contenidoPersonalizado,
		onAceptar,
		onCancelar,
		onMostrar,
	}) {
		this.titulo = titulo;
		this.mensaje = mensaje || "";
		this.contenidoPersonalizado = contenidoPersonalizado || "";
		this.onAceptar = onAceptar;
		this.onCancelar = onCancelar || (() => {});
		this.onMostrar = onMostrar || (() => {});

		this.crearModal();
	}

	crearModal() {
		this.modal = document.createElement("div");
		this.modal.className = "modalUniversal";
		this.modal.style.display = "flex";

		const contenido = document.createElement("div");
		contenido.className = "modalUniversal-content";

		const h3 = document.createElement("h3");
		h3.textContent = this.titulo;

		const p = document.createElement("p");
		p.textContent = this.mensaje;

		const divContenido = document.createElement("div");
		divContenido.innerHTML = this.contenidoPersonalizado;

		const botones = document.createElement("div");
		botones.className = "botones";

		const btnAceptar = document.createElement("button");
		btnAceptar.textContent = "Aceptar";
		btnAceptar.className = "aceptar";
		btnAceptar.addEventListener("click", () => {
			if (this.onAceptar() === false) return; // Evitar cerrar si onAceptar devuelve false
			this.cerrar();
		});

		const btnCancelar = document.createElement("button");
		btnCancelar.textContent = "Cancelar";
		btnCancelar.className = "cancelar";
		btnCancelar.addEventListener("click", () => {
			this.cerrar();
			this.onCancelar();
		});

		botones.appendChild(btnAceptar);
		botones.appendChild(btnCancelar);

		contenido.appendChild(h3);
		if (this.mensaje) contenido.appendChild(p);
		contenido.appendChild(divContenido);
		contenido.appendChild(botones);

		this.modal.appendChild(contenido);
		document.body.appendChild(this.modal);

		// Aquí el contenido ya está en el DOM, puedes llamar a onMostrar
		this.onMostrar();
	}

	cerrar() {
		this.modal.remove();
	}
}

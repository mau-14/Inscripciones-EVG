export class Loader {
	constructor(texto = "Cargando...") {
		this.loaderId = `loader-${Date.now()}`;
		this.crearLoaderDOM(texto);
		this.mostrar();
	}

	crearLoaderDOM(texto) {
		const html = `
      <div id="${this.loaderId}" class="loader-modal">
        <div class="loader-content">
          <div class="spinner"></div>
          <span class="spinner-text">${texto}</span>
        </div>
      </div>
    `;

		// Añadir al final del body
		document.body.insertAdjacentHTML("beforeend", html);
		this.loader = document.getElementById(this.loaderId);
	}

	mostrar() {
		this.loader.style.display = "flex";
	}

	ocultar() {
		this.loader.style.display = "none";
		this.loader.remove(); // Limpia del DOM si ya no lo necesitas
	}
}

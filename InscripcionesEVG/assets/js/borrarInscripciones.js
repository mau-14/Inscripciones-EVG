import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";
import { ErrorDialog } from "/InscripcionesEVG/assets/js/utils/errorHandler.js";

document.addEventListener("DOMContentLoaded", () => {
	const btnBorrar = document.querySelector(".btn-borrar");

	const errorDialog = new ErrorDialog();

	btnBorrar.addEventListener("click", () => {
		// Primer modal: pedir contraseña
		new ModalConfirmacion({
			titulo: "Confirmar borrado",
			contenidoPersonalizado: `
        <label for="password-input">Introduce la contraseña para confirmar:</label>
        <input type="password" id="password-input" style="width: 50%; padding: 0.5rem; margin-top: 0.5rem;" />
      `,
			onAceptar: () => {
				const input = document.getElementById("password-input");
				const password = input.value.trim();

				if (password === "") {
					alert("La contraseña no puede estar vacía.");
					return false; // No cerrar modal
				}

				if (password !== "eliminarTodo") {
					alert("Contraseña incorrecta.");
					return false; // No cerrar modal
				}

				// Si la contraseña es correcta, mostramos el segundo modal
				mostrarSegundoModal();
				return true; // Cierra el primer modal
			},
			onCancelar: () => {
				console.log("Borrado cancelado");
			},
		});
	});

	function mostrarSegundoModal() {
		new ModalConfirmacion({
			titulo: "Confirmar borrado",
			mensaje: "¿Estás seguro?",
			onAceptar: () => {
				mostrarTercerModal();
				return true; // Cierra segundo modal
			},
			onCancelar: () => {
				console.log("Borrado cancelado en segundo modal");
			},
		});
	}

	function mostrarTercerModal() {
		new ModalConfirmacion({
			titulo: "Confirmación final",
			mensaje: "¿Estás realmente seguro?",
			onAceptar: async () => {
				const loader = new Loader("Borrando...");
				try {
					const response = await fetch(
						"/InscripcionesEVG/index.php?controlador=borrarInscripciones&accion=borrarTodas",
					);
					const data = await response.json();
					if (data.success) {
						loader.ocultar();
						errorDialog.show("Inscripciones borradas", true);
					} else {
						loader.ocultar();
						errorDialog.show(data.mensaje);
					}
				} catch (error) {
					loader.ocultar();
					errorDialog.show(error);
					console.error(error);
				}

				loader.ocultar();
				return true; // Cierra tercer modal
			},
			onCancelar: () => {
				console.log("Borrado cancelado en tercer modal");
			},
		});
	}
});

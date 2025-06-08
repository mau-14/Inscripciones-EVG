import M_obtenerAlumnos from "/InscripcionesEVG/assets/js/models/m_obtenerAlumnos.js";
import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";

async function rellenarSelectsConAlumnos(idClase) {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos(idClase);

		const contenedorMasculino = document.getElementById(
			"camposPruebasMasculina",
		);
		const contenedorFemenino = document.getElementById("camposPruebasFemenina");

		if (!contenedorMasculino || !contenedorFemenino) {
			console.warn("Contenedores no encontrados.");
			return;
		}

		const selectsMasculinos = contenedorMasculino.querySelectorAll("select");
		const selectsFemeninos = contenedorFemenino.querySelectorAll("select");

		const seleccionadosGenerales = new Set(); // Para selects normales
		const seleccionadosTipoC = new Set(); // Para selects con name "C"
		const anteriores = new Map(); // Guardamos el valor anterior de cada select

		// Función para actualizar los selects con depuración
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name === "C";

				options.forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return;

					// Ocultar las opciones de alumnos seleccionados
					if (esTipoC) {
						option.style.display =
							seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId
								? "none"
								: "";
					} else {
						option.style.display =
							seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId
								? "none"
								: "";
					}
				});
			});
		}
		// Rellenar selects masculinos
		selectsMasculinos.forEach((select) => {
			const esTipoC = select.name === "C";
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => {
					if (a.sexo !== "M") return false;
					if (!esTipoC && seleccionadosGenerales.has(a.idAlumno.toString()))
						return false;
					return true;
				})
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Rellenar selects femeninos
		selectsFemeninos.forEach((select) => {
			const esTipoC = select.name === "C";
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => {
					if (a.sexo !== "F") return false;
					if (!esTipoC && seleccionadosGenerales.has(a.idAlumno.toString()))
						return false;
					return true;
				})
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Añadir eventos de cambio a todos los selects
		[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
			anteriores.set(select, ""); // valor inicial vacío

			select.addEventListener("change", (event) => {
				const actual = event.target.value.toString();
				const anterior = anteriores.get(select);
				const esTipoC = select.name === "C";

				console.log(
					`Cambio en select name="${select.name}": anterior="${anterior}" -> actual="${actual}"`,
				);

				// Eliminar el valor anterior si existía
				if (anterior) {
					if (esTipoC) {
						seleccionadosTipoC.delete(anterior);
						console.log(`Quitado de seleccionadosTipoC: ${anterior}`);
					} else {
						seleccionadosGenerales.delete(anterior);
						console.log(`Quitado de seleccionadosGenerales: ${anterior}`);
					}
				}

				// Añadir el valor actual si es válido
				if (actual) {
					if (esTipoC) {
						seleccionadosTipoC.add(actual);
						console.log(`Añadido a seleccionadosTipoC: ${actual}`);
					} else {
						seleccionadosGenerales.add(actual);
						console.log(`Añadido a seleccionadosGenerales: ${actual}`);
					}
				}

				anteriores.set(select, actual); // Actualizamos el valor anterior
				actualizarSelects(); // Actualizamos la vista de los selects
			});
		});

		actualizarSelects(); // Inicializar estados
	} catch (error) {
		console.error("Error al rellenar los selects con alumnos:", error);
	}
}
async function rellenarSelectsConSeleccionados(idClase) {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos(idClase);
		console.log(alumnos);

		const response = await fetch(
			"/InscripcionesEVG/index.php?controlador=alumnosSeleccionados&accion=extraer&j=1",
			{
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({ idClase }),
			},
		);
		const seleccionados = await response.json();
		console.log("Seleccionados:", seleccionados);

		const contenedorMasculino = document.getElementById(
			"camposPruebasMasculina",
		);
		const contenedorFemenino = document.getElementById("camposPruebasFemenina");

		if (!contenedorMasculino || !contenedorFemenino) {
			console.warn("Contenedores no encontrados.");
			return;
		}

		const selectsMasculinos = contenedorMasculino.querySelectorAll("select");
		const selectsFemeninos = contenedorFemenino.querySelectorAll("select");

		const seleccionadosGenerales = new Set();
		const seleccionadosTipoC = new Set();
		const anteriores = new Map();

		// Función para actualizar los selects
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name === "C";

				options.forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return;

					// Ocultar las opciones de alumnos seleccionados
					if (esTipoC) {
						option.style.display =
							seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId
								? "none"
								: "";
					} else {
						option.style.display =
							seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId
								? "none"
								: "";
					}
				});
			});
		}

		function rellenarSelects(selects, sexo) {
			const usadosPorPruebaTipo = new Map();

			const selectsPorPrueba = new Map();
			selects.forEach((select) => {
				const idPrueba = select.getAttribute("data-idprueba");
				if (!selectsPorPrueba.has(idPrueba)) {
					selectsPorPrueba.set(idPrueba, []);
				}
				selectsPorPrueba.get(idPrueba).push(select);
			});

			selectsPorPrueba.forEach((selectsPrueba, idPrueba) => {
				const selectsPorTipo = new Map();
				selectsPrueba.forEach((sel) => {
					const tipo = sel.name;
					if (!selectsPorTipo.has(tipo)) selectsPorTipo.set(tipo, []);
					selectsPorTipo.get(tipo).push(sel);
				});

				selectsPorTipo.forEach((selectsTipo, tipo) => {
					const keyPruebaTipo = `${idPrueba}_${tipo}`;
					if (!usadosPorPruebaTipo.has(keyPruebaTipo)) {
						usadosPorPruebaTipo.set(keyPruebaTipo, new Set());
					}
					const usados = usadosPorPruebaTipo.get(keyPruebaTipo);

					const idsSeleccionados =
						(seleccionados[sexo] &&
							seleccionados[sexo][tipo] &&
							seleccionados[sexo][tipo][idPrueba]) ||
						[];

					// Ordenar selects por data-index para procesar en orden
					selectsTipo.sort(
						(a, b) =>
							parseInt(a.getAttribute("data-index") || "0") -
							parseInt(b.getAttribute("data-index") || "0"),
					);

					selectsTipo.forEach((select, idx) => {
						select.innerHTML = `<option value="">Selecciona</option>`;

						const idSel = idsSeleccionados[idx]; // id seleccionado para este select
						const idSelStr = idSel ? idSel.toString() : "";

						// Añadir la opción seleccionada del select actual
						if (idSel) {
							const alumno = alumnos.find(
								(a) => a.idAlumno.toString() === idSelStr && a.sexo === sexo,
							);
							if (alumno) {
								const option = document.createElement("option");
								option.value = alumno.idAlumno;
								option.textContent = alumno.nombre;
								option.selected = true;
								select.appendChild(option);

								// Marca este id como usado para los siguientes selects
								usados.add(idSelStr);

								if (tipo === "C") {
									seleccionadosTipoC.add(idSelStr);
								} else {
									seleccionadosGenerales.add(idSelStr);
								}
								anteriores.set(select, idSelStr);
							}
						} else {
							if (!anteriores.has(select)) {
								anteriores.set(select, "");
							}
						}

						// Añadir las demás opciones no usadas
						alumnos
							.filter(
								(a) => a.sexo === sexo && a.idAlumno.toString() !== idSelStr,
							)
							.forEach((alumno) => {
								const option = document.createElement("option");
								option.value = alumno.idAlumno;
								option.textContent = alumno.nombre;

								// Si el alumno ya está usado en otro select, no lo mostramos
								if (usados.has(alumno.idAlumno.toString())) {
									option.style.display = "none"; // Ocultamos la opción
								}

								select.appendChild(option);
							});
					});
				});
			});
			// *** IMPORTANTE *** Actualizar deshabilitado después de rellenar TODOS los selects
			actualizarSelects();
		}
		rellenarSelects(selectsMasculinos, "M");
		rellenarSelects(selectsFemeninos, "F");

		[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
			select.addEventListener("change", (event) => {
				const actual = event.target.value;
				const anterior = anteriores.get(select);
				const esTipoC = select.name === "C";

				if (anterior) {
					if (esTipoC) {
						seleccionadosTipoC.delete(anterior);
					} else {
						seleccionadosGenerales.delete(anterior);
					}
				}

				if (actual) {
					if (esTipoC) {
						seleccionadosTipoC.add(actual);
					} else {
						seleccionadosGenerales.add(actual);
					}
				}

				anteriores.set(select, actual);
				actualizarSelects(); // Actualizamos la vista de los selects
			});
		});

		// Inicializar estado de opciones deshabilitadas
		actualizarSelects();
	} catch (error) {
		console.error("Error al inicializar los selects:", error);
	}
}

async function obtenerExceldePruebas(idPruebaM, idPruebaF) {
	const modelo = new M_obtenerAlumnos();
	const excels = await modelo.obtenerAlumnosInscripcionesTO(
		idPruebaM,
		idPruebaF,
	);
	console.log("EXCELS", excels);

	if (
		!excels ||
		(Array.isArray(excels) && excels.length === 0) || // si es un array
		(typeof excels === "object" && Object.keys(excels).length === 0) // si es objeto sin propiedades
	) {
		new ModalConfirmacion({
			titulo: "No disponible",
			mensaje: "Esta prueba no tiene inscripciones",
			onAceptar: () => {},
			onCancelar: () => {},
		});
		return; // no continuar
	}
	const response = await fetch(
		"/InscripcionesEVG/controllers/generar_excel.php",
		{
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(excels),
		},
	);

	if (!response.ok) {
		alert("Error al generar el Excel");
		return;
	}

	// Obtener el nombre del archivo desde la cabecera
	let filename = "archivo.xlsx"; // por defecto
	const disposition = response.headers.get("Content-Disposition");
	if (disposition && disposition.includes("filename=")) {
		const match = disposition.match(/filename="?([^"]+)"?/);
		if (match && match[1]) {
			filename = match[1];
		}
	}

	const blob = await response.blob();
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement("a");
	a.href = url;
	a.download = filename;
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	window.URL.revokeObjectURL(url);
}

async function obtenerZipDePruebas(pares) {
	const modelo = new M_obtenerAlumnos();
	const datosParaExcel = [];

	for (const par of pares) {
		const alumnos = await modelo.obtenerAlumnosInscripcionesTO(
			par.inputM,
			par.inputF,
		);

		if (alumnos && Array.isArray(alumnos) && alumnos.length > 0) {
			datosParaExcel.push(alumnos);
		}
	}

	if (datosParaExcel.length === 0) {
		new ModalConfirmacion({
			titulo: "No disponible",
			mensaje: "Ninguna de las pruebas tiene inscripciones",
			onAceptar: () => {},
			onCancelar: () => {},
		});
		return;
	}

	const response = await fetch(
		"/InscripcionesEVG/controllers/generar_zip.php",
		{
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(datosParaExcel), // ENVIAMOS ARRAY DE ARRAYS
		},
	);

	if (!response.ok) {
		alert("Error al generar el ZIP");
		return;
	}

	const blob = await response.blob();
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement("a");
	a.href = url;
	a.download = "pruebas_excel.zip";
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	window.URL.revokeObjectURL(url);
}
export {
	rellenarSelectsConAlumnos,
	rellenarSelectsConSeleccionados,
	obtenerExceldePruebas,
	obtenerZipDePruebas,
};

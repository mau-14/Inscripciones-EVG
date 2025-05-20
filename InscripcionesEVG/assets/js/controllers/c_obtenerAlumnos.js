import M_obtenerAlumnos from "/InscripcionesEVG/assets/js/models/m_obtenerAlumnos.js";

async function rellenarSelectsConAlumnos() {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos();

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
		const seleccionadosTipoC = new Set(); // Para selects con "-C"
		const anteriores = new Map(); // Guardamos el valor anterior de cada select

		// Función para actualizar los selects
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name === "C";

				options.forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return;

					if (esTipoC) {
						option.disabled =
							seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					} else {
						option.disabled =
							seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					}
				});
			});
		}

		// Rellenar selects masculinos
		selectsMasculinos.forEach((select) => {
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => a.sexo === "M")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Rellenar selects femeninos
		selectsFemeninos.forEach((select) => {
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => a.sexo === "F")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Añadir eventos de cambio
		[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
			anteriores.set(select, ""); // valor inicial vacío

			select.addEventListener("change", (event) => {
				const actual = event.target.value;
				const anterior = anteriores.get(select);
				const esTipoC = select.name === "C";

				// Eliminar anterior si existía
				if (anterior) {
					if (esTipoC) {
						seleccionadosTipoC.delete(anterior);
					} else {
						seleccionadosGenerales.delete(anterior);
					}
				}

				// Añadir actual si es válido
				if (actual) {
					if (esTipoC) {
						seleccionadosTipoC.add(actual);
					} else {
						seleccionadosGenerales.add(actual);
					}
				}

				anteriores.set(select, actual); // Actualizar el valor anterior
				actualizarSelects();
			});
		});

		actualizarSelects(); // Inicial
	} catch (error) {
		console.error("Error al rellenar los selects con alumnos:", error);
	}
}

async function rellenarSelectsConSeleccionados(idClase) {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos();

		// Obtener alumnos ya seleccionados desde el backend
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

		function actualizarSelects() {
			// Recorrer todos los selects, tanto masculinos como femeninos
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name === "C";

				options.forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return; // salto opciones sin value

					if (esTipoC) {
						// Si es tipo C, sólo deshabilitamos si ya está seleccionado en otro select distinto (pero para tipo C)
						option.disabled =
							seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					} else {
						// Para los otros tipos, deshabilitamos si el alumno está seleccionado en cualquier otro select (salvo en el actual)
						// es decir, no puede estar en dos pruebas distintas ni en el mismo tipo distinto.
						option.disabled =
							seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					}
				});
			});
		}

		// Función para rellenar selects y seleccionar los alumnos que ya estaban
		function rellenarSelects(selects, sexo) {
			// Registro global de alumnos usados por idPrueba + tipo
			const usadosPorPruebaTipo = new Map(); // key: `${idPrueba}_${tipo}` -> Set de idsAlumno

			// Agrupamos selects por data-idprueba
			const selectsPorPrueba = new Map();

			selects.forEach((select) => {
				const idPrueba = select.getAttribute("data-idprueba");
				if (!selectsPorPrueba.has(idPrueba)) {
					selectsPorPrueba.set(idPrueba, []);
				}
				selectsPorPrueba.get(idPrueba).push(select);
			});

			selectsPorPrueba.forEach((selectsPrueba, idPrueba) => {
				// Agrupamos por tipo
				const selectsPorTipo = new Map();
				selectsPrueba.forEach((sel) => {
					const tipo = sel.name;
					if (!selectsPorTipo.has(tipo)) selectsPorTipo.set(tipo, []);
					selectsPorTipo.get(tipo).push(sel);
				});

				selectsPorTipo.forEach((selectsTipo, tipo) => {
					// Key única para controlar usados en prueba+tipo
					const keyPruebaTipo = `${idPrueba}_${tipo}`;
					if (!usadosPorPruebaTipo.has(keyPruebaTipo)) {
						usadosPorPruebaTipo.set(keyPruebaTipo, new Set());
					}
					const usados = usadosPorPruebaTipo.get(keyPruebaTipo);

					// Alumnos seleccionados para este sexo, tipo, idPrueba
					const idsSeleccionados =
						(seleccionados[sexo] &&
							seleccionados[sexo][tipo] &&
							seleccionados[sexo][tipo][idPrueba]) ||
						[];

					// Ordenamos selects por data-index
					selectsTipo.sort(
						(a, b) =>
							parseInt(a.getAttribute("data-index") || "0") -
							parseInt(b.getAttribute("data-index") || "0"),
					);

					selectsTipo.forEach((select, idx) => {
						select.innerHTML = `<option value="">Selecciona</option>`;

						// Intentamos asignar alumno seleccionado en posición idx
						const idSel = idsSeleccionados[idx];
						if (idSel && !usados.has(idSel.toString())) {
							const alumno = alumnos.find(
								(a) =>
									a.idAlumno.toString() === idSel.toString() && a.sexo === sexo,
							);
							if (alumno) {
								const option = document.createElement("option");
								option.value = alumno.idAlumno;
								option.textContent = alumno.nombre;
								option.selected = true;
								select.appendChild(option);
								usados.add(idSel.toString());

								// Registro generales
								if (tipo === "C") {
									seleccionadosTipoC.add(idSel.toString());
								} else {
									seleccionadosGenerales.add(idSel.toString());
								}
								anteriores.set(select, idSel.toString());
							}
						} else {
							// No seleccionado previamente
							if (!anteriores.has(select)) {
								anteriores.set(select, "");
							}
						}

						// Añadir resto alumnos que no estén usados en esta prueba+tipo
						alumnos
							.filter(
								(a) => a.sexo === sexo && !usados.has(a.idAlumno.toString()),
							)
							.forEach((alumno) => {
								const option = document.createElement("option");
								option.value = alumno.idAlumno;
								option.textContent = alumno.nombre;
								select.appendChild(option);
							});
					});
				});
			});
		}
		rellenarSelects(selectsMasculinos, "M");
		rellenarSelects(selectsFemeninos, "F");

		// Añadir listeners para actualizar sets y estado al cambiar selección
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
				actualizarSelects();
			});
		});

		actualizarSelects(); // Actualización inicial
	} catch (error) {
		console.error("Error al inicializar los selects:", error);
	}
}

export { rellenarSelectsConAlumnos, rellenarSelectsConSeleccionados };

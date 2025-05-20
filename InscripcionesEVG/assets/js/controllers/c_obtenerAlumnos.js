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

		// Función para rellenar selects y seleccionar los alumnos que ya estaban
		function rellenarSelects(selects, sexo) {
			const usadosPorPrueba = new Map(); // idPrueba => Set de alumnos usados

			// Primero agrupamos selects por data-idprueba para repartir alumnos
			const selectsPorPrueba = new Map();

			selects.forEach((select) => {
				const idPrueba = select.getAttribute("data-idprueba");
				if (!selectsPorPrueba.has(idPrueba)) {
					selectsPorPrueba.set(idPrueba, []);
				}
				selectsPorPrueba.get(idPrueba).push(select);
			});

			// Ahora para cada prueba hacemos la asignación con data-index
			selectsPorPrueba.forEach((selectsPrueba, idPrueba) => {
				// Obtenemos los alumnos seleccionados para esta prueba y sexo
				// Nota: asegurarse que seleccionados[sexo][tipo][idPrueba] existe antes de usar
				// Pero aquí los selects pueden tener diferente tipo (P, C), asumimos el mismo tipo en un grupo?
				// Para simplificar, vamos a hacer el reparto para cada tipo por separado.

				// Mejor agrupamos por tipo dentro de selectsPrueba
				const selectsPorTipo = new Map();
				selectsPrueba.forEach((sel) => {
					const tipo = sel.name;
					if (!selectsPorTipo.has(tipo)) selectsPorTipo.set(tipo, []);
					selectsPorTipo.get(tipo).push(sel);
				});

				selectsPorTipo.forEach((selectsTipo, tipo) => {
					// Alumnos seleccionados para este sexo, tipo, idPrueba
					const idsSeleccionados =
						(seleccionados[sexo] &&
							seleccionados[sexo][tipo] &&
							seleccionados[sexo][tipo][idPrueba]) ||
						[];

					// Set para marcar alumnos usados en esta prueba y tipo
					if (!usadosPorPrueba.has(idPrueba + tipo)) {
						usadosPorPrueba.set(idPrueba + tipo, new Set());
					}
					const usados = usadosPorPrueba.get(idPrueba + tipo);

					// Ordenamos selectsTipo por data-index para repartir alumnos
					selectsTipo.sort(
						(a, b) =>
							parseInt(a.getAttribute("data-index") || "0") -
							parseInt(b.getAttribute("data-index") || "0"),
					);

					// Repartimos alumnos seleccionados entre los selects por índice
					selectsTipo.forEach((select, idx) => {
						select.innerHTML = `<option value="">Selecciona</option>`;

						// Si hay alumno seleccionado para ese índice, lo añadimos y seleccionamos
						const idSel = idsSeleccionados[idx];
						if (idSel) {
							const alumno = alumnos.find(
								(a) =>
									a.idAlumno.toString() === idSel.toString() && a.sexo === sexo,
							);
							if (alumno && !usados.has(idSel.toString())) {
								const option = document.createElement("option");
								option.value = alumno.idAlumno;
								option.textContent = alumno.nombre;
								option.selected = true;
								select.appendChild(option);
								usados.add(idSel.toString());

								// Registrar en sets generales
								if (tipo === "C") {
									seleccionadosTipoC.add(idSel.toString());
								} else {
									seleccionadosGenerales.add(idSel.toString());
								}
								anteriores.set(select, idSel.toString());
							}
						} else {
							// No hay seleccionado para ese índice, poner valor vacío
							if (!anteriores.has(select)) {
								anteriores.set(select, "");
							}
						}

						// Añadir resto de alumnos que no están usados en esta prueba+tipo
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

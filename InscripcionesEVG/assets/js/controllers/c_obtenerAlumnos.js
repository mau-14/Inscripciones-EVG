import M_obtenerAlumnos from "/InscripcionesEVG/assets/js/models/m_obtenerAlumnos.js";

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
		const seleccionadosTipoC = new Set(); // Para selects con "-C"
		const anteriores = new Map(); // Guardamos el valor anterior de cada select

		// Función para actualizar los selects
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name === "C";

				options.forEach((option) => {
					const alumnoId = option.value.toString();
					if (!alumnoId) return;

					const anterior = anteriores.get(select) || "";
					const disabled = esTipoC
						? seleccionadosTipoC.has(alumnoId) && anterior !== alumnoId
						: seleccionadosGenerales.has(alumnoId) && anterior !== alumnoId;

					option.disabled = disabled;
					// Para depuración:
					console.log(
						`Select [${select.name}][${select.getAttribute("data-idprueba")}], option ${option.textContent} (${alumnoId}): disabled=${disabled}`,
					);
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
				const actual = event.target.value.toString();
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

								// Si el alumno ya está usado en otro select, deshabilitar
								if (usados.has(alumno.idAlumno.toString())) {
									option.disabled = true;
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
				actualizarSelects();
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

	const blob = await response.blob();
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement("a");
	a.href = url;
	a.download = "torneo.xlsx";
	a.click();
	window.URL.revokeObjectURL(url);
}

export {
	rellenarSelectsConAlumnos,
	rellenarSelectsConSeleccionados,
	obtenerExceldePruebas,
};

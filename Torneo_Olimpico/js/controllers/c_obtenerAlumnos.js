import M_obtenerAlumnos from "/Torneo_Olimpico/js/models/m_obtenerAlumnos.js";

async function rellenarSelectsConAlumnos() {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos();

		// Seleccionamos todos los selects por categoría
		const contenedorMasculino = document.getElementById(
			"camposPruebasMasculina",
		);
		const contenedorFemenino = document.getElementById("camposPruebasFemenina");

		if (!contenedorMasculino || !contenedorFemenino) {
			console.warn("Contenedores de categorías no encontrados.");
			return;
		}

		// Rellenar selects en categoría masculina
		const selectsMasculinos = contenedorMasculino.querySelectorAll("select");
		selectsMasculinos.forEach((select) => {
			// Vaciar el select (excepto la opción por defecto)
			select.innerHTML = `<option value="">Selecciona</option>`;
			// Agregar solo alumnos con sexo M
			alumnos
				.filter((alumno) => alumno.sexo === "M")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = `${alumno.nombre}`;
					select.appendChild(option);
				});
		});

		// Rellenar selects en categoría femenina
		const selectsFemeninos = contenedorFemenino.querySelectorAll("select");
		selectsFemeninos.forEach((select) => {
			// Vaciar el select (excepto la opción por defecto)
			select.innerHTML = `<option value="">Selecciona</option>`;
			// Agregar solo alumnos con sexo F
			alumnos
				.filter((alumno) => alumno.sexo === "F")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = `${alumno.nombre} ${alumno.apellidos}`;
					select.appendChild(option);
				});
		});
	} catch (error) {
		console.error("Error al rellenar los selects con alumnos:", error);
	}
}

export { rellenarSelectsConAlumnos };

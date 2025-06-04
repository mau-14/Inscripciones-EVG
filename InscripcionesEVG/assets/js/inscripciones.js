document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.selects-grid');
    const form = document.querySelector('form');
    const addButton = document.createElement('button');
    
    // Contador basado en los selects existentes
    const existingSelects = document.querySelectorAll('select[name="alumnos[]"]');
    let counter = existingSelects.length;

    // Estilos para el botón de añadir
    addButton.type = 'button';
    addButton.className = 'btn btn-primary';
    addButton.style.marginTop = '15px';
    addButton.style.marginBottom = '15px';
    addButton.innerHTML = '<i class="fas fa-plus"></i> Añadir otro alumno';
    
    // Insertar el botón después del contenedor de selects
    container.parentNode.insertBefore(addButton, container.nextSibling);

    // Función para actualizar los selects
    function updateSelects() {
        const selects = container.querySelectorAll('select[name="alumnos[]"]');
        const selectedValues = [];

        // Obtener valores ya seleccionados
        selects.forEach(select => {
            if (select.value) selectedValues.push(select.value);
        });

        // Actualizar opciones de cada select
        selects.forEach(select => {
            const currentValue = select.value;
            const options = select.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') return; // No modificar la opción por defecto
                
                // Habilitar todas las opciones primero
                option.disabled = false;
                option.hidden = false;
                
                // Deshabilitar opciones ya seleccionadas en otros selects
                if (option.value !== currentValue && selectedValues.includes(option.value)) {
                    option.disabled = true;
                    option.hidden = true;
                }
            });
        });
    }

    // Función para obtener las opciones de alumnos desde el primer select o desde el formulario oculto
    function getAlumnosOptions() {
        // Primero intentamos obtener las opciones del primer select
        let firstSelect = document.querySelector('select[name="alumnos[]"]');
        
        if (firstSelect) {
            // Si encontramos un select, devolvemos sus opciones
            return Array.from(firstSelect.querySelectorAll('option[value]')).filter(opt => opt.value !== '');
        } else {
            // Si no hay select, buscamos un input oculto con los datos de los alumnos
            const alumnosData = document.querySelector('input[name="alumnos_data"]');
            if (alumnosData && alumnosData.value) {
                try {
                    const alumnos = JSON.parse(alumnosData.value);
                    return alumnos.map(alumno => ({
                        value: alumno.idAlumno,
                        textContent: alumno.nombre
                    }));
                } catch (e) {
                    console.error('Error al parsear los datos de alumnos:', e);
                }
            }
        }
        return [];
    }

    // Función para crear un nuevo select
    function createNewSelect() {
        counter++;
        
        // Obtener opciones de alumnos
        const options = getAlumnosOptions();
        
        if (options.length === 0) {
            console.error('No se encontraron opciones de alumnos disponibles');
            alert('No se encontraron alumnos disponibles para inscribir.');
            return;
        }
        
        // Crear opciones para el nuevo select
        let optionsHTML = '<option value="" disabled selected>Seleccione un alumno</option>';
        
        // Agregar opciones de alumnos
        options.forEach(option => {
            optionsHTML += `<option value="${option.value}">${option.textContent || option.nombre}</option>`;
        });
        
        // Crear el nuevo select con opción por defecto y botón de eliminar
        const newSelectHTML = `
            <div class="form-group select-container" id="container_${counter}">
                <div class="select-wrapper">
                    <label for="alumno_${counter}">
                        <span class="badge">${counter + 1}</span>
                        Alumno
                    </label>
                    <select id="alumno_${counter}" name="alumnos[]" class="form-control">
                        ${optionsHTML}
                    </select>
                    <button type="button" class="btn-remove" title="Eliminar alumno">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Crear un elemento temporal para manipular el HTML
        const temp = document.createElement('div');
        temp.innerHTML = newSelectHTML.trim();
        const newSelect = temp.firstChild;
        
        // Agregar el nuevo select al contenedor
        container.appendChild(newSelect);
        
        // Agregar evento al botón de eliminar
        const removeButton = newSelect.querySelector('.btn-remove');
        removeButton.addEventListener('click', function() {
            removeSelect(this);
        });
        
        // Agregar evento al select
        const newSelectElement = newSelect.querySelector('select');
        newSelectElement.addEventListener('change', updateSelects);
        
        // Actualizar los números de los badges
        updateBadgeNumbers();
        
        // Actualizar el estado de los selects
        updateSelects();
    }

    // Función para actualizar los números de los badges
    function updateBadgeNumbers() {
        const badges = document.querySelectorAll('.select-container .badge');
        badges.forEach((badge, index) => {
            badge.textContent = index + 1;
        });
    }
    
    // Función para eliminar un select
    function removeSelect(button) {
        const container = button.closest('.select-container');
        container.remove();
        updateBadgeNumbers();
        updateSelects();
    }

    // Evento para el botón de añadir
    addButton.addEventListener('click', function() {
        createNewSelect();
    });

    // Inicializar eventos para los selects existentes
    document.querySelectorAll('select[name="alumnos[]"]').forEach(select => {
        select.addEventListener('change', updateSelects);
    });

    // Inicializar el estado de los selects
    updateSelects();

    // Validación para evitar envíos vacíos
    form.addEventListener('submit', function(e) {
        const selects = container.querySelectorAll('select[name="alumnos[]"]');
        let hasSelection = false;
        let emptySelects = 0;
        
        selects.forEach(select => {
            if (select.value === '') {
                emptySelects++;
            } else {
                hasSelection = true;
            }
        });
    });

    // Inicializar la validación de selects
    updateSelects();
    
    // Hacer las funciones disponibles globalmente
    window.removeSelect = removeSelect;
    window.updateBadgeNumbers = updateBadgeNumbers;
});

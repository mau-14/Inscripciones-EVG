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

    // Función para crear un nuevo select
    function createNewSelect() {
        counter++;
        const firstSelect = document.getElementById('alumno_0');
        
        if (!firstSelect) {
            console.error('No se encontró el select de referencia (alumno_0)');
            return;
        }
        
        // Obtener solo las opciones de alumnos (excluyendo la opción por defecto)
        const options = Array.from(firstSelect.querySelectorAll('option[value]')).filter(opt => opt.value !== '');
        
        // Crear opciones para el nuevo select
        let optionsHTML = '<option value="" disabled selected>Seleccione un alumno</option>';
        
        // Agregar opciones de alumnos
        options.forEach(option => {
            optionsHTML += `<option value="${option.value}">${option.textContent}</option>`;
        });
        
        // Crear el nuevo select con opción por defecto
        const newSelect = `
            <div class="form-group">
                <label for="alumno_${counter}">
                    <span class="badge">${counter + 1}</span>
                    Alumno
                </label>
                <select id="alumno_${counter}" name="alumnos[]" class="form-control">
                    ${optionsHTML}
                </select>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newSelect);
        
        // Actualizar los números de los badges
        updateBadgeNumbers();
        
        // Actualizar el estado de los selects
        updateSelects();
        
        // Agregar evento al nuevo select
        const newSelectElement = document.getElementById(`alumno_${counter}`);
        newSelectElement.addEventListener('change', updateSelects);
    }

    // Función para actualizar los números de los badges
    function updateBadgeNumbers() {
        const badges = container.querySelectorAll('.badge');
        badges.forEach((badge, index) => {
            badge.textContent = index + 1;
        });
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

        if (emptySelects === selects.length) {
            e.preventDefault();
            alert('Por favor, seleccione al menos un alumno.');
        } else if (emptySelects > 0) {
            if (!confirm('Hay alumnos sin seleccionar. ¿Desea continuar de todos modos?')) {
                e.preventDefault();
            }
        }
    });

    // Inicializar la validación de selects
    updateSelects();
});

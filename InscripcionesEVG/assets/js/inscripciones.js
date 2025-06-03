document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.selects-grid');
    const form = document.querySelector('form');
    const addButton = document.createElement('button');
    let counter = 1;

    // Estilos para el botón de añadir
    addButton.type = 'button';
    addButton.className = 'btn btn-primary';
    addButton.style.marginTop = '15px';
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
                if (option.value === '') return; // No deshabilitar la opción por defecto
                
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
        const newSelect = `
            <div class="form-group">
                <label for="alumno_${counter}">
                    <span class="badge">${counter}</span>
                    Seleccionar alumno
                </label>
                <select id="alumno_${counter}" name="alumnos[]" class="form-control">
                    ${document.getElementById('alumno_1').querySelectorAll('option:not([value=""])')[0].parentNode.innerHTML}
                </select>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newSelect);
        updateSelects();
        
        // Agregar evento al nuevo select
        const newSelectElement = document.getElementById(`alumno_${counter}`);
        newSelectElement.addEventListener('change', updateSelects);
    }

    // Evento para el botón de añadir
    addButton.addEventListener('click', function() {
        createNewSelect();
    });

    // Agregar evento a los selects existentes
    const existingSelects = container.querySelectorAll('select[name="alumnos[]"]');
    existingSelects.forEach(select => {
        select.addEventListener('change', updateSelects);
    });

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

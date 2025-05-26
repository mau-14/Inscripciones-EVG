class ActividadesModel {
    constructor() {
        this.baseUrl = './index.php?controlador=actividades';
    }

    async mostrarActividades(idMomento) {
        try {
            const response = await fetch(`${this.baseUrl}&accion=cMostrarActividadesporIdMomento&momento=${idMomento}`);
            return await response.json();
        } catch (error) {
            console.error('Error al obtener actividades:', error);
            throw error;
        }
    }

    async insertarActividad(formData) {
        try {
            const response = await fetch(`${this.baseUrl}&accion=cInsertarActividad`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error al insertar actividad:', error);
            throw error;
        }
    }

    async editarActividad(formData) {
        try {
            const response = await fetch(`${this.baseUrl}&accion=cEditarActividad`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error al editar actividad:', error);
            throw error;
        }
    }

    async eliminarActividad(idActividad) {
        try {
            const response = await fetch(`${this.baseUrl}&accion=cEliminarActividad&idActividad=${idActividad}`);
            return await response.json();
        } catch (error) {
            console.error('Error al eliminar actividad:', error);
            throw error;
        }
    }
} 
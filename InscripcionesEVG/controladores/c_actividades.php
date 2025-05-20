<?php
Class Cactividades {
    private $objactividades;
    public $vista;

    public function __construct() {
        require_once("modelos/m_actividades.php");
        $this->objactividades = new Mactividades();
    }

    public function cMostrarActividadesporIdMomento() {
        $this->vista = 'mostrarActividades';
        $idMomento = $_POST['momento'];
        $resultado = $this->objactividades->mMostrarActividadesporIdMomento($idMomento);
        if (is_array($resultado)) {
            return $resultado;
        }
    }
}
?>
<?php
Class Cmomentos {
    private $objmomentos;
    public $vista;

    public function __construct() {
        require_once("modelos/m_momentos.php");
        $this->objmomentos = new Mmomentos();
    }

    public function cMostrarMomentos() {
        $this->vista = 'mostrarMomentos';
        $resultado = $this->objmomentos->mMostrarMomentos();
        if (is_array($resultado)) {
            return $resultado;
        }
    }
}
?>
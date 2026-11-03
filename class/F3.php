<?php

require_once __DIR__ . '/F2.php';

class F3 extends Monoplaza {
    public $nombreAcademia;
    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia, $nombreAcademia) {
        parent::__construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia);
        $this->nombreAcademia = $nombreAcademia;
        $this->maximoParticipantes = 30;
        $this->puntoVueltaRapida = false;
        $this->tablaPuntos = [
            1 => 10,
            2 => 8,
            3 => 7,
            4 => 6,
            5 => 5,
            6 => 4,
            7 => 3,
            8 => 2,
            9 => 1,
        ];
    }

    public function subirCategoria($tienePuntosSuperlicencia) {
        return new F2(
            $this->nombrePiloto,
            $this->nacionalidad,
            $this->numeroMonoplaza,
            $this->escuderia,
            $tienePuntosSuperlicencia
        );
    }
}

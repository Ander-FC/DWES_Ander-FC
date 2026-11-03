<?php

require_once __DIR__ . '/F3.php';

class F4 extends Monoplaza {
    public $paisCategoria;
    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia, $paisCategoria) {
        parent::__construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia);
        $this->paisCategoria = $paisCategoria;
        $this->maximoParticipantes = 30;
        $this->puntoVueltaRapida = false;
        $this->tablaPuntos = [
            1 => 25,
            2 => 18,
            3 => 15,
            4 => 12,
            5 => 10,
            6 => 8,
            7 => 6,
            8 => 4,
            9 => 2,
            10 => 1,
        ];
    }

    public function subirCategoria($nombreAcademia) {
        return new F3(
            $this->nombrePiloto,
            $this->nacionalidad,
            $this->numeroMonoplaza,
            $this->escuderia,
            $nombreAcademia
        );
    }
}

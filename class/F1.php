<?php

require_once __DIR__ . '/Monoplaza.php';

class F1 extends Monoplaza {
    public $patrocinadorPrincipal;
    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia, $patrocinadorPrincipal) {
        parent::__construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia);
        $this->patrocinadorPrincipal = $patrocinadorPrincipal;
        $this->maximoParticipantes = 22;
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
}

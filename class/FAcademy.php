<?php

require_once __DIR__ . '/F4.php';

class FAcademy extends Monoplaza {
    public $potenciaMaxima;
    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia, $potenciaMaxima) {
        parent::__construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia);
        $this->potenciaMaxima = $potenciaMaxima;
        $this->maximoParticipantes = 18;
        $this->tablaPuntos = [
            1 => 18,
            2 => 15,
            3 => 12,
            4 => 10,
            5 => 8,
            6 => 6,
            7 => 4,
            8 => 2,
            9 => 1,
        ];
    }

    public function subirCategoria($paisCategoria){
        return new F4(
            $this->nombrePiloto,
            $this->nacionalidad,
            $this->numeroMonoplaza,
            $this->escuderia,
            $paisCategoria
        );
    }
}

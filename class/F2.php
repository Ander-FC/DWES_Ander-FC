<?php

require_once __DIR__ . '/F1.php';

class F2 extends Monoplaza {
    public $tienePuntosSuperlicencia;
    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia, $tienePuntosSuperlicencia) {
        parent::__construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia);
        $this->tienePuntosSuperlicencia = $tienePuntosSuperlicencia;
        $this->maximoParticipantes = 24;
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

    public function subirCategoria($patrocinadorPrincipal) {
        if (!$this->tienePuntosSuperlicencia) {
            throw new Exception('El piloto no tiene los puntos necesarios de superlicencia.');
        }

        return new F1(
            $this->nombrePiloto,
            $this->nacionalidad,
            $this->numeroMonoplaza,
            $this->escuderia,
            $patrocinadorPrincipal
        );
    }
}

<?php

abstract class Monoplaza {
    public $nombrePiloto;
    public $nacionalidad;
    public $numeroMonoplaza;
    public $escuderia;
    public $puntos = 0;
    protected $maximoParticipantes;
    protected $tablaPuntos;
    protected $puntoVueltaRapida = true;

    public function __construct($nombrePiloto, $nacionalidad, $numeroMonoplaza, $escuderia) {
        $this->nombrePiloto = $nombrePiloto;
        $this->nacionalidad = $nacionalidad;
        $this->numeroMonoplaza = $numeroMonoplaza;
        $this->escuderia = $escuderia;
    }

    public function posicionValida($posicion) {
        return $posicion >= 1 && $posicion <= $this->maximoParticipantes;
    }

    public function otorgarPuntos($posicion, $vueltaRapida) {
        if (!$this->posicionValida($posicion)) {
            throw new InvalidArgumentException('La posición no es válida.');
        }

        $puntos = isset($this->tablaPuntos[$posicion]) ? $this->tablaPuntos[$posicion] : 0;
        if ($this->puntoVueltaRapida && $vueltaRapida && $posicion <= 10) {
            $puntos++;
        }

        $this->puntos += $puntos;
    }
}

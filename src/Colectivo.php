<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;
    protected $empresa;
	protected $numero;

    public function __construct($empresa, $linea, $numero) {
        $this->empresa = $empresa;
		$this->linea = $linea;
		$this->numero = $numero;
    }

    public function linea(){
		return $this->linea;
	}

	public function empresa(){
		return $this->empresa;
	}

	public function numero(){
		return $this->numero;
	}

	public function pagarCon(TarjetaInterface $tarjeta){
		$result = $tarjeta->abonarPasaje($this);
		
		if($result == True)
		{
			$boleto = new Boleto($tarjeta->valorDelPasaje(), $this, $tarjeta);
			return $boleto;
		}
		else
		{
			return False;
		}
	}

}

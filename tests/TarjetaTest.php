<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
    	$tiempoReal = new Tiempo;
        $tarjeta = new Tarjeta($tiempoReal);

        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);

        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 30);
	
	$this->assertTrue($tarjeta->recargar(30));
	$this->assertEquals($tarjeta->obtenerSaldo(), 60);

	$this->assertTrue($tarjeta->recargar(50));
	$this->assertEquals($tarjeta->obtenerSaldo(), 110);

	$this->assertTrue($tarjeta->recargar(100));
	$this->assertEquals($tarjeta->obtenerSaldo(), 210);

	$this->assertTrue($tarjeta->recargar(510.15));
	$this->assertEquals($tarjeta->obtenerSaldo(), 802.08);

	$this->assertTrue($tarjeta->recargar(962.59));
	$this->assertEquals($tarjeta->obtenerSaldo(), 1986.25);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
    	$tiempoReal = new Tiempo;
      $tarjeta = new Tarjeta($tiempoReal);

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }
	 /**
     * Comprueba que el abono del pasaje es satisfactorio.
     */
	public function testAbonoTarjeta() {
		$tiempoReal = new Tiempo;
		$colectivo = new Colectivo();
		$tarjeta = new Tarjeta($tiempoReal);
		$tarjeta->recargar(20);
		$tarjeta->abonarPasaje($colectivo);
		$this->assertEquals($tarjeta->valorDelPasaje(),14.8);
		$this->assertEquals($tarjeta->obtenerSaldo(), 5.2);
	}

	public function testRecargaPlus() {
		$tiempoReal = new Tiempo;
		$tarjeta = new Tarjeta($tiempoReal);
		$colectivo = new Colectivo();
		$tarjeta->abonarPasaje($colectivo);
		$tarjeta->abonarPasaje($colectivo);
		$tarjeta->recargar(50);
		$tarjeta->abonarPasaje($colectivo);
		$this->assertEquals($tarjeta->obtenerSaldo(), 5.6); //Abono 2 plus + pasaje = 44.4
	}

	public function testTrasbordo() {
		$tiempoFalso = new TiempoFalso;
		$tarjeta = new Tarjeta($tiempoFalso);
		$tarjeta->recargar(50);
		$colectivo1 = new Colectivo("Semtur","Azul","102");
		$this->assertTrue($colectivo1->pagarCon($tarjeta) instanceof Boleto);
		$this->assertEquals($tarjeta->obtenerSaldo(), 35.2);
		$tiempoFalso -> avanzar(600);
		$colectivo2 = new Colectivo("Semtur","Amarillo","145");
		$this->assertTrue($colectivo2->pagarCon($tarjeta) instanceof Boleto);
		$this->assertEquals($tarjeta->obtenerSaldo(), 30.267);
	}

	public function testTiempoSesentaMin(){
		$tiempo = new TiempoFalso;
		$tarjeta = new Tarjeta($tiempo);
		$tiempo->avanzar(1538072993); //Jueves 30/9/2018 a las 15:30
		$tarjeta->recargar(50);
		$colectivo1 = new Colectivo("Semtur","Azul","102");
		$colectivo2 = new Colectivo("Semtur","Amarillo","145");
		$colectivo1->pagarCon($tarjeta);
		$this->assertEquals($tarjeta->obtenerSaldo(), 35.2);
		$tiempo-> avanzar(3000);
		$colectivo2->pagarCon($tarjeta);
		$this->assertEquals($tarjeta->obtenerSaldo(), 30.267);
	}
}

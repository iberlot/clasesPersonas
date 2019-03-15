<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 16 nov. 2018
 * @lenguage PHP
 * @name class_direccion.php
 * @version 0.1 version inicial del archivo.
 * @package
 * @project
 */
require_once 'class_conexion.php';

/*
 * Querido programador:
 *
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 *
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 *
 * totalHorasPerdidasAqui = 0
 *
 */
class Direcciones
{
	/**
	 *
	 * @var object objeto inicializado del tipo class_db
	 */
	private $db;

	/**
	 * Tipo de direccion, corresponde a si es la direccion de residencia = 0 (datos que en la base comiensan con r), de nacimiento = 1 o profecional = 2
	 * Cabe la posibilidad de que en en futuro se creen nuevos tipos de direcciones.
	 *
	 * @var integer
	 */
	protected $tipo = 0;

	/**
	 * Pais de nacimiento de la persona si tipo es igual 1.
	 * Pais de residencia de la persona si tipo es igual 0.
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.country - VARCHAR2(3 BYTE) si tipo es igual 1.
	 *      @ubicacionBase appgral.person.rcountry - VARCHAR2(3 BYTE) si tipo es igual 0.
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.country
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	protected $country = "";

	/**
	 * Provincia de nacimiento de la persona si tipo es igual 1.
	 * Provincia de residencia de la persona si tipo es igual 0.
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.poldiv - VARCHAR2(3 BYTE) si tipo es igual 1.
	 *      @ubicacionBase appgral.person.rpoldiv - VARCHAR2(3 BYTE) si tipo es igual 0.
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.poldiv
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	protected $poldiv = "";

	/**
	 * Ciudad de nacimiento de la persona si tipo es igual 1.
	 * Ciudad de residencia de la persona si tipo es igual 0.
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.city - VARCHAR2(10 BYTE) si tipo es igual 1.
	 *      @ubicacionBase appgral.person.rcity - VARCHAR2(10 BYTE) si tipo es igual 0.
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.city
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	protected $city = "";

	/**
	 * Calle de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CALLE'
	 */
	protected $direCalle = "";

	/**
	 * Numero de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'NRO'
	 */
	protected $direNumero = "";

	/**
	 * Piso de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'PISO'
	 */
	protected $direPiso = "";

	/**
	 * Dto de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'DEPTO'
	 */
	protected $direDto = "";

	/**
	 * Codigo postal de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CODPOS'
	 */
	protected $direCodPos = "";

	/**
	 * Direccion en string que une calle numero y muchas veces depto en un solo campo.
	 *
	 * @todo lo ideal es tratarlo para que quede separado de la forma que corresponde
	 *
	 * @var string <Br>
	 *      @ubicacionBase sueldos.personal.domicilio - VARCHAR2(30 BYTE)
	 */
	protected $domicilio = "";

	public function __toString()
	{
		return $this->direCalle . " " . $this->direNumero . " " . (isset ($this->direPiso) ? $this->direPiso . " " : "") . (isset ($this->direDto) ? $this->direDto . " " : "") . (isset ($this->direCodPos) ? "(CP:" . $this->direCodPos . ") " : "") . $this->city . " " . $this->getPoldiv () . " " . $this->country;
	}

	public function __construct()
	{
		$this->db = Conexion::openConnection ();
	}

	public function __construct1($tipo, $country, $poldiv, $city)
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setCountry ($country);
		$this->setCity ($city);
	}

	public function __construct2($tipo, $country, $poldiv, $city, $direCalle, $direNumero, $direCodPos = "", $direPiso = "", $direDto = "")
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setCountry ($country);
		$this->setCity ($city);
	}

	/**
	 * Busca los datos de la direccion de una persona en la base basados en el numero de person.
	 *
	 * @param int $person
	 *        	numero de identificacion de la persona.
	 * @param number $tipo
	 *        	tipo de direccion que se va buscar
	 * @throws Exception
	 */
	public function recuperar_dire_person($person, $tipo = 0)
	{
		$this->setTipo ($tipo);

		if ($tipo == 0)
		{
			$this->db->debug = true;
			$this->db->dieOnError = true;
			$this->db->mostrarErrores = true;

			print_r ("xxxxxxxx");
			if ($recu = $this->db->realizarSelect ("appgral.person", "person = $person"))
			{
				$this->setCountry ($recu['RCOUNTRY']);
				$this->setPoldiv ($recu['RPOLDIV']);
				$this->setCity ($recu['RCITY']);

				print_r ("zzzzzzzzz");

				if ($recu = $this->db->realizarSelectAll ("appgral.apers", "person = $person AND pattrib='DOMI'"))
				{

					print_r ("yyyyyyyy");
					print_r ($recu);
					// --PISO DOMI
					// --CALLE DOMI
					// --DEPTO DOMI
					// --NRO DOMI
					// --CODPOS DOMI
				}
			}
			else
			{
				throw new Exception ('Persona no encontrada.');
			}
		}
		elseif ($tipo == 1)
		{
			if ($recu = $this->db->realizarSelect ("appgral.person", "person = :person"))
			{
				$this->setCountry ($recu['COUNTRY']);
				$this->setPoldiv ($recu['POLDIV']);
				$this->setCity ($recu['CITY']);
			}
			else
			{
				throw new Exception ('Persona no encontrada.');
			}
		}
		elseif ($tipo == 2)
		{
			if ($recu = $this->db->realizarSelectAll ("appgral.apers", "person = $person AND pattrib='DOMP'"))
			{
				// $this->setCountry ($recu['COUNTRY']);
				// $this->setPoldiv ($recu['POLDIV']);
				// $this->setCity ($recu['CITY']);
				// --PISO DOMP
				// --NRO DOMP
				// --DEPTO DOMP
				// --CODPOS DOMP
				// --POLDIV DOMP
				// --CITY DOMP
				// --CALLE DOMP
				// --COUNTRY DOMP
			}
		}

		$this->setDomicilio ($recu['ADDRESS']);
	}

	/*
	 * ***********************************************************
	 * Gettes and Setters
	 * ************************************************************
	 */

	/**
	 *
	 * @return number
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 *
	 * @param number $tipo
	 */
	public function setTipo($tipo)
	{
		$this->tipo = $tipo;
	}

	/**
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 *
	 * @param string $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	/**
	 *
	 * @return string
	 */
	public function getPoldiv()
	{
		return $this->poldiv;
	}

	/**
	 *
	 * @param string $poldiv
	 */
	public function setPoldiv($poldiv)
	{
		$this->poldiv = $poldiv;
	}

	/**
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 *
	 * @param string $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 *
	 * @return string
	 */
	public function getDireCalle()
	{
		return $this->direCalle;
	}

	/**
	 *
	 * @param string $direCalle
	 */
	public function setDireCalle($direCalle)
	{
		$this->direCalle = $direCalle;
	}

	/**
	 *
	 * @return string
	 */
	public function getDireNumero()
	{
		return $this->direNumero;
	}

	/**
	 *
	 * @param string $direNumero
	 */
	public function setDireNumero($direNumero)
	{
		$this->direNumero = $direNumero;
	}

	/**
	 *
	 * @return string
	 */
	public function getDirePiso()
	{
		return $this->direPiso;
	}

	/**
	 *
	 * @param string $direPiso
	 */
	public function setDirePiso($direPiso)
	{
		$this->direPiso = $direPiso;
	}

	/**
	 *
	 * @return string
	 */
	public function getDireDto()
	{
		return $this->direDto;
	}

	/**
	 *
	 * @param string $direDto
	 */
	public function setDireDto($direDto)
	{
		$this->direDto = $direDto;
	}

	/**
	 *
	 * @return string
	 */
	public function getDireCodPos()
	{
		return $this->direCodPos;
	}

	/**
	 *
	 * @param string $direCodPos
	 */
	public function setDireCodPos($direCodPos)
	{
		$this->direCodPos = $direCodPos;
	}

	/**
	 *
	 * @return string
	 */
	public function getDomicilio()
	{
		return $this->domicilio;
	}

	/**
	 *
	 * @param string $domicilio
	 */
	public function setDomicilio($domicilio)
	{
		$this->domicilio = $domicilio;
	}
}
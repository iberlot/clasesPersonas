<?php

/**
 * Archivo de alojamiento de la clase empleados.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 16 nov. 2018
 * @lenguage PHP
 * @name class_Empleados.php
 * @version 0.1 version inicial del archivo.
 */
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
require_once 'class_Personas.php';
require_once 'concepPersLiqui.php';

/**
 * Clase derivada de Personas con los atributos propios de los empleados.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @name Empleados
 */
abstract class Empleados extends Personas
{

	/**
	 * Numero de legajo del area de personal de la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.legajo - NUMBER(8,0)
	 */
	protected $legajo = "";

	/**
	 * Fecha en la que ingresa la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.finicio - DATE
	 */
	protected $fechaIngreso = "";

	/**
	 * Fecha de baja de la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.fbaja - DATE
	 */
	protected $fechaBaja = "";

	/**
	 * Fecha de reingreso
	 *
	 * @var string
	 */
	protected $reingreso = "";

	/**
	 * Fecha de inicio en el cargo
	 *
	 * @var string
	 */
	protected $inicioCargo = "";

	/**
	 * Actividad de la persona
	 *
	 * @var string
	 */
	protected $actividad = "";

	/**
	 * Antiguedad de la persona.
	 *
	 * @var string
	 */
	protected $antiguedad = "";

	/**
	 * caja de ahorro
	 *
	 * @var string
	 */
	protected $cajaDeAhorro = "";

	/**
	 * Caja jubilatoria
	 *
	 * @var string
	 */
	protected $cajaJubilacion = "";

	/**
	 * Cargo
	 *
	 * @var string
	 */
	protected $cargo = "";

	/**
	 * Codigo de alta
	 *
	 * @var string
	 */
	protected $codigoAlta = "";

	/**
	 * titulo
	 *
	 * @var string
	 */
	protected $titulo = "";

	/**
	 * Codigo del titulo
	 *
	 * @var string
	 */
	protected $codigoTitulo = "";

	/**
	 * $nroJubilacion.
	 *
	 * @var string
	 */
	protected $nroJubilacion = "";

	/**
	 * $nroSindicato.
	 *
	 * @var string
	 */
	protected $nroSindicato = "";

	/**
	 * $obraSocial.
	 *
	 * @var string
	 */
	protected $obraSocial = "";

	/**
	 * $redito.
	 *
	 * @var string
	 */
	protected $redito = "";

	/**
	 * $seguro
	 *
	 * @var string
	 */
	protected $seguro = "";

	/**
	 * $sucursalCtaBanco
	 *
	 * @var string
	 */
	protected $sucursalCtaBanco = "";

	/**
	 * $tipoCtaBanco
	 *
	 * @var string
	 */
	protected $tipoCtaBanco = "";

	/**
	 * $unidadContrato
	 *
	 * @var string
	 */
	protected $unidadContrato = "";

	/**
	 * $tipobco
	 *
	 * @var string
	 */
	protected $tipobco = "";
	protected $conseptPerm;

	/**
	 * Constructor de la clase.
	 *
	 * @param class_db $db
	 * @param int $person
	 */
	public function __construct($db = null, $person = null)
	{
		parent::__construct ($person, $db);

		if (!isset ($db) or empty ($db) or $db == null)
		{
			if (!$this->db = Sitios::openConnection ())
			{
				global $db;

				if (isset ($db) and !empty ($db) and $db != null)
				{
					$this->db = $db;
				}
			}
		}
		else
		{
			$this->db = $db;
		}
		$this->conseptPerm = new concepPersLiqui ();
		if ($person != null)
		{
			$this->recuDatosPersonal ();
		}
	}

	public function recuValoresConsep($id_liquidacion)
	{
		$this->conseptPerm->recuperarValores ($id_liquidacion, $this->person);
	}

	public function recuDatosPersonal()
	{
		$sql = "SELECT legajo, antiguedad, cargo, TO_CHAR(fecha_baja,'DD-MM-YYYY') AS fecha_baja, TO_CHAR(iniciocargo,'DD-MM-YYYY') AS iniciocargo, TO_CHAR(fecha_ingreso,'DD-MM-YYYY') AS fecha_ingreso, codtitulo	 FROM sueldos.personal WHERE person = :person";

		$parametros = array ();
		$parametros[] = $this->person;

		$result = $this->db->query ($sql, true, $parametros);

		$datos = $this->db->fetch_array ($result);

		// $this->setFaesca ($concepto['FAESCA']);

		$this->setLegajo ($datos['LEGAJO']);
		$this->setAntiguedad ($datos['ANTIGUEDAD']);
		$this->setCargo ($datos['CARGO']);
		$this->setFechaBaja ($datos['FECHA_BAJA']);
		$this->setInicioCargo ($datos['INICIOCARGO']);
		$this->setFechaIngreso ($datos['FECHA_INGRESO']);
		$this->setTitulo ($datos['CODTITULO']);
	}

	/**
	 * Retorna el valor del atributo $legajo
	 *
	 * @return number $legajo el dato de la variable.
	 */
	public function getLegajo()
	{
		return $this->legajo;
	}

	/**
	 * Setter del parametro $legajo de la clase.
	 *
	 * @param number $legajo
	 *        	dato a cargar en la variable.
	 */
	public function setLegajo($legajo)
	{
		$this->legajo = $legajo;
	}

	/**
	 * Retorna el valor del atributo $fechaIngreso
	 *
	 * @return number $fechaIngreso el dato de la variable.
	 */
	public function getFechaIngreso()
	{
		return $this->fechaIngreso;
	}

	/**
	 * Setter del parametro $fechaIngreso de la clase.
	 *
	 * @param number $fechaIngreso
	 *        	dato a cargar en la variable.
	 */
	public function setFechaIngreso($fechaIngreso)
	{
		$this->fechaIngreso = $fechaIngreso;
	}

	/**
	 * Retorna el valor del atributo $fechaBaja
	 *
	 * @return number $fechaBaja el dato de la variable.
	 */
	public function getFechaBaja()
	{
		return $this->fechaBaja;
	}

	/**
	 * Setter del parametro $fechaBaja de la clase.
	 *
	 * @param number $fechaBaja
	 *        	dato a cargar en la variable.
	 */
	public function setFechaBaja($fechaBaja)
	{
		$this->fechaBaja = $fechaBaja;
	}

	/**
	 * Retorna el valor del atributo $reingreso
	 *
	 * @return string $reingreso el dato de la variable.
	 */
	public function getReingreso()
	{
		return $this->reingreso;
	}

	/**
	 * Setter del parametro $reingreso de la clase.
	 *
	 * @param string $reingreso
	 *        	dato a cargar en la variable.
	 */
	public function setReingreso($reingreso)
	{
		$this->reingreso = $reingreso;
	}

	/**
	 * Retorna el valor del atributo $inicioCargo
	 *
	 * @return string $inicioCargo el dato de la variable.
	 */
	public function getInicioCargo()
	{
		return $this->inicioCargo;
	}

	/**
	 * Setter del parametro $inicioCargo de la clase.
	 *
	 * @param string $inicioCargo
	 *        	dato a cargar en la variable.
	 */
	public function setInicioCargo($inicioCargo)
	{
		$this->inicioCargo = $inicioCargo;
	}

	/**
	 * Retorna el valor del atributo $actividad
	 *
	 * @return string $actividad el dato de la variable.
	 */
	public function getActividad()
	{
		return $this->actividad;
	}

	/**
	 * Setter del parametro $actividad de la clase.
	 *
	 * @param string $actividad
	 *        	dato a cargar en la variable.
	 */
	public function setActividad($actividad)
	{
		$this->actividad = $actividad;
	}

	/**
	 * Retorna el valor del atributo $antiguedad
	 *
	 * @return string $antiguedad el dato de la variable.
	 */
	public function getAntiguedad()
	{
		return $this->antiguedad;
	}

	/**
	 * Setter del parametro $antiguedad de la clase.
	 *
	 * @param string $antiguedad
	 *        	dato a cargar en la variable.
	 */
	public function setAntiguedad($antiguedad)
	{
		$this->antiguedad = $antiguedad;
	}

	/**
	 * Retorna el valor del atributo $cajaDeAhorro
	 *
	 * @return string $cajaDeAhorro el dato de la variable.
	 */
	public function getCajaDeAhorro()
	{
		return $this->cajaDeAhorro;
	}

	/**
	 * Setter del parametro $cajaDeAhorro de la clase.
	 *
	 * @param string $cajaDeAhorro
	 *        	dato a cargar en la variable.
	 */
	public function setCajaDeAhorro($cajaDeAhorro)
	{
		$this->cajaDeAhorro = $cajaDeAhorro;
	}

	/**
	 * Retorna el valor del atributo $cajaJubilacion
	 *
	 * @return string $cajaJubilacion el dato de la variable.
	 */
	public function getCajaJubilacion()
	{
		return $this->cajaJubilacion;
	}

	/**
	 * Setter del parametro $cajaJubilacion de la clase.
	 *
	 * @param string $cajaJubilacion
	 *        	dato a cargar en la variable.
	 */
	public function setCajaJubilacion($cajaJubilacion)
	{
		$this->cajaJubilacion = $cajaJubilacion;
	}

	/**
	 * Retorna el valor del atributo $cargo
	 *
	 * @return string $cargo el dato de la variable.
	 */
	public function getCargo()
	{
		return $this->cargo;
	}

	/**
	 * Setter del parametro $cargo de la clase.
	 *
	 * @param string $cargo
	 *        	dato a cargar en la variable.
	 */
	public function setCargo($cargo)
	{
		$this->cargo = $cargo;
	}

	/**
	 * Retorna el valor del atributo $codigoAlta
	 *
	 * @return string $codigoAlta el dato de la variable.
	 */
	public function getCodigoAlta()
	{
		return $this->codigoAlta;
	}

	/**
	 * Setter del parametro $codigoAlta de la clase.
	 *
	 * @param string $codigoAlta
	 *        	dato a cargar en la variable.
	 */
	public function setCodigoAlta($codigoAlta)
	{
		$this->codigoAlta = $codigoAlta;
	}

	/**
	 * Retorna el valor del atributo $titulo
	 *
	 * @return string $titulo el dato de la variable.
	 */
	public function getTitulo()
	{
		return $this->titulo;
	}

	/**
	 * Setter del parametro $titulo de la clase.
	 *
	 * @param string $titulo
	 *        	dato a cargar en la variable.
	 */
	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
	}

	/**
	 * Retorna el valor del atributo $codigoTitulo
	 *
	 * @return string $codigoTitulo el dato de la variable.
	 */
	public function getCodigoTitulo()
	{
		return $this->codigoTitulo;
	}

	/**
	 * Setter del parametro $codigoTitulo de la clase.
	 *
	 * @param string $codigoTitulo
	 *        	dato a cargar en la variable.
	 */
	public function setCodigoTitulo($codigoTitulo)
	{
		$this->codigoTitulo = $codigoTitulo;
	}

	/**
	 * Retorna el valor del atributo $nroJubilacion
	 *
	 * @return string $nroJubilacion el dato de la variable.
	 */
	public function getNroJubilacion()
	{
		return $this->nroJubilacion;
	}

	/**
	 * Setter del parametro $nroJubilacion de la clase.
	 *
	 * @param string $nroJubilacion
	 *        	dato a cargar en la variable.
	 */
	public function setNroJubilacion($nroJubilacion)
	{
		$this->nroJubilacion = $nroJubilacion;
	}

	/**
	 * Retorna el valor del atributo $nroSindicato
	 *
	 * @return string $nroSindicato el dato de la variable.
	 */
	public function getNroSindicato()
	{
		return $this->nroSindicato;
	}

	/**
	 * Setter del parametro $nroSindicato de la clase.
	 *
	 * @param string $nroSindicato
	 *        	dato a cargar en la variable.
	 */
	public function setNroSindicato($nroSindicato)
	{
		$this->nroSindicato = $nroSindicato;
	}

	/**
	 * Retorna el valor del atributo $obraSocial
	 *
	 * @return string $obraSocial el dato de la variable.
	 */
	public function getObraSocial()
	{
		return $this->obraSocial;
	}

	/**
	 * Setter del parametro $obraSocial de la clase.
	 *
	 * @param string $obraSocial
	 *        	dato a cargar en la variable.
	 */
	public function setObraSocial($obraSocial)
	{
		$this->obraSocial = $obraSocial;
	}

	/**
	 * Retorna el valor del atributo $redito
	 *
	 * @return string $redito el dato de la variable.
	 */
	public function getRedito()
	{
		return $this->redito;
	}

	/**
	 * Setter del parametro $redito de la clase.
	 *
	 * @param string $redito
	 *        	dato a cargar en la variable.
	 */
	public function setRedito($redito)
	{
		$this->redito = $redito;
	}

	/**
	 * Retorna el valor del atributo $seguro
	 *
	 * @return string $seguro el dato de la variable.
	 */
	public function getSeguro()
	{
		return $this->seguro;
	}

	/**
	 * Setter del parametro $seguro de la clase.
	 *
	 * @param string $seguro
	 *        	dato a cargar en la variable.
	 */
	public function setSeguro($seguro)
	{
		$this->seguro = $seguro;
	}

	/**
	 * Retorna el valor del atributo $sucursalCtaBanco
	 *
	 * @return string $sucursalCtaBanco el dato de la variable.
	 */
	public function getSucursalCtaBanco()
	{
		return $this->sucursalCtaBanco;
	}

	/**
	 * Setter del parametro $sucursalCtaBanco de la clase.
	 *
	 * @param string $sucursalCtaBanco
	 *        	dato a cargar en la variable.
	 */
	public function setSucursalCtaBanco($sucursalCtaBanco)
	{
		$this->sucursalCtaBanco = $sucursalCtaBanco;
	}

	/**
	 * Retorna el valor del atributo $tipoCtaBanco
	 *
	 * @return string $tipoCtaBanco el dato de la variable.
	 */
	public function getTipoCtaBanco()
	{
		return $this->tipoCtaBanco;
	}

	/**
	 * Setter del parametro $tipoCtaBanco de la clase.
	 *
	 * @param string $tipoCtaBanco
	 *        	dato a cargar en la variable.
	 */
	public function setTipoCtaBanco($tipoCtaBanco)
	{
		$this->tipoCtaBanco = $tipoCtaBanco;
	}

	/**
	 * Retorna el valor del atributo $unidadContrato
	 *
	 * @return string $unidadContrato el dato de la variable.
	 */
	public function getUnidadContrato()
	{
		return $this->unidadContrato;
	}

	/**
	 * Setter del parametro $unidadContrato de la clase.
	 *
	 * @param string $unidadContrato
	 *        	dato a cargar en la variable.
	 */
	public function setUnidadContrato($unidadContrato)
	{
		$this->unidadContrato = $unidadContrato;
	}

	/**
	 * Retorna el valor del atributo $tipobco
	 *
	 * @return string $tipobco el dato de la variable.
	 */
	public function getTipobco()
	{
		return $this->tipobco;
	}

	/**
	 * Setter del parametro $tipobco de la clase.
	 *
	 * @param string $tipobco
	 *        	dato a cargar en la variable.
	 */
	public function setTipobco($tipobco)
	{
		$this->tipobco = $tipobco;
	}

	/**
	 * Retorna el valor del atributo $conseptPerm
	 *
	 * @return concepPersLiqui $conseptPerm el dato de la variable.
	 */
	public function getConseptPerm()
	{
		return $this->conseptPerm;
	}

	/**
	 * Setter del parametro $conseptPerm de la clase.
	 *
	 * @param concepPersLiqui $conseptPerm
	 *        	dato a cargar en la variable.
	 */
	public function setConseptPerm($conseptPerm)
	{
		$this->conseptPerm = $conseptPerm;
	}
}

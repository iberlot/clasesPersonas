<?php

/**
 * Archivo que contiene la cales Administrativos
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
 * totalHorasPerdidasAqui = 2
 *
 */

/**
 * Description of class_Administrativos
 *
 * @author lquiroga <@> lquiroga@usal.edu.ar
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 15 mar. 2019
 * @version 1.0
 * @lenguage PHP
 * @name class_Administrativos
 */
class Administrativos extends Empleados
{

	/**
	 * Unidad a la que pertenece el Administrativo
	 *
	 * @var int
	 */
	protected $unidad;

	/**
	 * Edificio a la que pertenece el Administrativo
	 *
	 * @var int
	 */
	protected $edificio;

	/**
	 * Sede a la que pertenece el Administrativo
	 *
	 * @var int
	 */
	protected $sede;

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
	}

	/**
	 * Obtiene los datos de la tabla web.cuenta , para obtener la unidad , edificio y sede del usuario
	 *
	 * @param int $person
	 */
	public function obtenerUnidad($db, $person)
	{
		$param = array (
				$person
		);

		$db2 = $db;

		$query = "SELECT LPAD(unidad, 2, '0') unidad FROM web.cuenta WHERE cuenta IN(SELECT cuenta FROM portal.usuario_web WHERE person = :person)";

		$result = $db2->query ($query, true, $param);

		while ($fila = $db2->fetch_array ($result))
		{

			$salida[] = $fila['UNIDAD'];
		}

		$this->setUnidad ($salida);

		return $salida;
	}

	/*
	 * *********************************************
	 * Iniciamos los getters and setters
	 * *********************************************
	 */
	/**
	 * Retorna el valor del atributo $unidad
	 *
	 * @return number $unidad el dato de la variable.
	 */
	public function getUnidad()
	{
		return $this->unidad;
	}

	/**
	 * Retorna el valor del atributo $edificio
	 *
	 * @return number $edificio el dato de la variable.
	 */
	public function getEdificio()
	{
		return $this->edificio;
	}

	/**
	 * Retorna el valor del atributo $sede
	 *
	 * @return number $sede el dato de la variable.
	 */
	public function getSede()
	{
		return $this->sede;
	}

	/**
	 * Setter del parametro $unidad de la clase.
	 *
	 * @param number $unidad
	 *        	dato a cargar en la variable.
	 */
	public function setUnidad($unidad)
	{
		$this->unidad = $unidad;
	}

	/**
	 * Setter del parametro $edificio de la clase.
	 *
	 * @param number $edificio
	 *        	dato a cargar en la variable.
	 */
	public function setEdificio($edificio)
	{
		$this->edificio = $edificio;
	}

	/**
	 * Setter del parametro $sede de la clase.
	 *
	 * @param number $sede
	 *        	dato a cargar en la variable.
	 */
	public function setSede($sede)
	{
		$this->sede = $sede;
	}
}

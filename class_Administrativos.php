<?php

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
 * @name class_Empleados.php
 *
 *
 */
class class_Administrativos extends Empleados
{

	// Unidad academica
	protected $unidad;
	// Edificio perteneciente
	protected $edificio;
	// Edificio perteneciente
	protected $sede;

	public function __construct($db, $person = null)
	{
		$this->db = $db;

		/*
		 * if (!is_null ($id)){
		 * $this->buscar_PersonXPerson($person);
		 * }
		 */
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

		$query = "select LPAD(UNIDAD, 2, '0') UNIDAD from web.cuenta where CUENTA IN(select cuenta from  " . "portal.usuario_web where person = :person)";

		$result = $db2->query ($query, $esParam = true, $param);

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
	function getUnidad()
	{
		return $this->unidad;
	}

	function getEdificio()
	{
		return $this->edificio;
	}

	function getSede()
	{
		return $this->sede;
	}

	function setUnidad($unidad)
	{
		$this->unidad = $unidad;
	}

	function setEdificio($edificio)
	{
		$this->edificio = $edificio;
	}

	function setSede($sede)
	{
		$this->sede = $sede;
	}
}

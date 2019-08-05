<?php

/**
 * Archivo de la clase Sucursales
 *
 *
 * @author lquiroga - lquiroga@gmail.com
 *
 * @since 25 jun. 2019
 * @lenguage PHP
 * @name class_caja.php
 * @version 0.1 version inicial del archivo.
 *

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
 *
 * Manejo de caja del sistema tesoreria
 */
class SucursalesCajas
{
	protected $db;
	protected $idsucursal;
	protected $nombre;
	protected $direccion;
	protected $localidad;
	protected $telefono;

	function __construct($db, $idsucursal = null)
	{
		$this->db = $db;
	}

	function getAll()
	{
		$query = " select * from SUCURSALESCAJAS";

		$result = $this->db->query ($query);

		while ($fila = $this->db->fetch_array ($result))
		{

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 *
	 * @FIXME para esta funcion deberia exisitr una clase aparte
	 * @return array
	 */
	function getAllItemsMenu()
	{
		$query = " SELECT * FROM opcionesmenucajatesoreria";

		$result = $this->db->query ($query);

		while ($fila = $this->db->fetch_array ($result))
		{

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 * loadData
	 * Carga propiedades del objeta que vienen desde la DB
	 *
	 * IDSUCURSAL
	 * NOMBRE
	 * DIRECCION
	 * LOCALIDAD
	 * TELEFONO
	 *
	 * @param array $fila
	 *        	return objet sucursales
	 */
	public function loadData($fila)
	{
		$this->setIdsucursal ($fila['IDSUCURSAL']);
		$this->setNombre ($fila['NOMBRE']);
		$this->setDireccion ($fila['DIRECCION']);
		$this->setLocalidad ($fila['LOCALIDAD']);
		$this->setTelefono ($fila['TELEFONO']);
	}

	/* * GETTERS* */
	function getDb()
	{
		return $this->db;
	}

	function getIdsucursal()
	{
		return $this->idsucursal;
	}

	function getNombre()
	{
		return $this->nombre;
	}

	function getDireccion()
	{
		return $this->direccion;
	}

	function getLocalidad()
	{
		return $this->localidad;
	}

	function getTelefono()
	{
		return $this->telefono;
	}

	/* * GETTERS* */
	function setDb($db)
	{
		$this->db = $db;
	}

	function setIdsucursal($idsucursal)
	{
		$this->idsucursal = $idsucursal;
	}

	function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	function setDireccion($direccion)
	{
		$this->direccion = $direccion;
	}

	function setLocalidad($localidad)
	{
		$this->localidad = $localidad;
	}

	function setTelefono($telefono)
	{
		$this->telefono = $telefono;
	}
}

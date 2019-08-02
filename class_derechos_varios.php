<?php
/**
 * Archivo principar de la clase.
 *
 * @author lquiroga <@> lquiroga@gmail.com
 * @todo FechaC 28/02/2019 - Lenguaje PHP
 *
 * @name class_derechos_varios.php
 *
 *
 */
require_once ("/web/html/classesUSAL/class_Personas.php");

class DerechosVarios
{
	protected $db;
	protected $idderechosvarios;
	protected $descripcion;
	protected $estado;
	protected $cod_cobol;
	protected $importe;

	public function __construct($db, $id = null)
	{
		$this->db = $db;

		if ($id != null && trim ($id) != '')
		{

			$parametros = array (
					$id
			);

			$query = "SELECT * FROM CAJADERECHOSVARIOS WHERE IDDERECHOSVARIOS = $id";

			$result = $this->db->query ($query, true, $parametros);

			$this->loadData ($this->db->fetch_array ($result));
		}
		else
		{
			$this->getAll ();
		}
	}

	/**
	 * Devuelve todos los registros de la tabla
	 *
	 * @return array Array con los resultados de
	 */
	public function getAll()
	{

		// TODO: Implement getAll() method.
		$query = "SELECT * FROM cajaderechosvarios WHERE estado = 1";

		$result = $this->db->query ($query);

		while ($fila = $this->db->fetch_array ($result))
		{

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 * Registra los pagos de los derechos varios
	 * realizados por un usuario administrador de tesoreria
	 *
	 * @param String $datos
	 */
	public function registrarPagoDerecho($datos)
	{
		if ($datos)
		{

			$insercion = $this->db->realizarInsert ($datos, 'SOLITRAMPAGOSDVARIOS');

			return $insercion;
		}
	}

	/**
	 * Carga propiedades del objeta que vienen desde la DB
	 *
	 * @param array $fila
	 *        	return objet derechos varios
	 *
	 */
	public function loadData($fila)
	{
		if (isset ($fila['IDDERECHOSVARIOS']))
		{
			$this->set_idderechosvarios ($fila['IDDERECHOSVARIOS']);
		}

		if (isset ($fila['DESCRIPCION']))
		{
			$this->set_descripcion ($fila['DESCRIPCION']);
		}

		if (isset ($fila['COD_COBOL']))
		{
			$this->set_cod_cobol ($fila['COD_COBOL']);
		}

		if (isset ($fila['ESTADO']))
		{
			$this->set_estado ($fila['ESTADO']);
		}

		if (isset ($fila['IMPORTE']))
		{
			$this->set_importe ($fila['IMPORTE']);
		}
	}

	/**
	 * ******GETER*******
	 */
	function get_db()
	{
		return $this->db;
	}

	function get_idderechosvarios()
	{
		return $this->idderechosvarios;
	}

	function get_descripcion()
	{
		return $this->descripcion;
	}

	function get_estado()
	{
		return $this->estado;
	}

	function get_cod_cobol()
	{
		return $this->cod_cobol;
	}

	function get_importe()
	{
		return $this->importe;
	}

	/**
	 * ******GETER*******
	 */
	function set_idderechosvarios($idderechosvarios)
	{
		$this->idderechosvarios = $idderechosvarios;
	}

	function set_descripcion($descripcion)
	{
		$this->descripcion = $descripcion;
	}

	function set_estado($estado)
	{
		$this->estado = $estado;
	}

	function set_cod_cobol($cod_cobol)
	{
		$this->cod_cobol = $cod_cobol;
	}

	function set_importe($importe)
	{
		$this->importe = $importe;
	}
}

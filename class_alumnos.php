<?php

/**
 * Archivo de la clase Alumnos
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author lquiroga - lquiroga@gmail.com
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name class_alumnos.php
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

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author lquiroga - lquiroga@gmail.com
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name Alumnos
 */
class Alumnos extends Personas
{
	protected $idcentrocosto;
	protected $carrera_descrip;
	protected $carrera;

	public function __construct($id = null)
	{
		$this->db = Conexion::openConnection ();

		if (!is_null ($id))
		{
			$this->findByPerson ($id);
		}
	}

	/**
	 * findByPerson busca alumno por person
	 *
	 * @param int $person
	 *        	id person
	 */
	public function findByPerson($person)
	{
		$this->set_person ($person);

		$anio_actual = date ("Y");

		$parametros = array (
				$anio_actual,
				$this->get_person ()
		);

		$query = "SELECT DISTINCT
				    carstu.career,
				    carstu.branch,
				    perdoc.typdoc,
				    perdoc.docno,
				    ccalu.idcentrodecosto,
				    ccalu.person,
				    person.lname,
				    person.fname,
				    career.descrip
				FROM
				    appgral.person
				    JOIN appgral.perdoc ON person.person = perdoc.person
				    JOIN studentc.carstu ON person.person = carstu.student
				    JOIN studentc.career ON carstu.career = career.code
				    JOIN tesoreria.ccalu ON person.person = ccalu.person
				    JOIN interfaz.aux_ccalu ON perdoc.docno = aux_ccalu.nrodoc
				WHERE
				        ccalu.aniocc =:anio
				    AND
				        person.person =:person";

		$result = $this->db->query ($query, $esParam = true, $parametros);

		$this->loadData ($this->db->fetch_array ($result));
	}

	/**
	 * En base a un criterio de busqueda (apellido , dni o nombre) devuelve alumno en objeto
	 * dentro de un array
	 *
	 * @param
	 *        	type string $criterio
	 *
	 * @return array[object] ->Alumnos
	 */
	public function findByProps($criterio)
	{
		$anio_actual = date ("Y");

		$criterio1 = $criterio2 = $criterio3 = $criterio;

		$parametros = array (
				$anio_actual,
				strtoupper ($criterio1),
				strtoupper ($criterio2),
				strtoupper ($criterio3)

		);

		$query = "SELECT
				    person.person,
				    person.lname,
				    person.fname
				FROM
				    appgral.person
				    JOIN appgral.perdoc ON person.person = perdoc.person
				    JOIN tesoreria.ccalu ON person.person = ccalu.person
				WHERE
				        ccalu.aniocc =:anio
				    AND (
				            lname LIKE '%' ||:busq1 || '%'
				        OR
				            fname LIKE '%' ||:busq2 || '%'
				        OR
				            docno LIKE '%' ||:busq3 || '%'
				    )";

		$result = $this->db->query ($query, $esParam = true, $parametros);

		while ($fila = $this->db->fetch_array ($result))
		{

			$alumno = new Alumnos ($fila['PERSON']);

			$salida[] = $alumno;
		}

		return $salida;
	}

	/**
	 * loadData
	 * Carga propiedades del objeta que vienen desde la DB
	 *
	 * @param array $fila
	 *        	return objet alumno
	 */
	public function loadData($fila)
	{
		$this->set_person ($fila['PERSON']);
		$this->set_nombre ($fila['FNAME'] . ' ' . $fila['LNAME']);
		$this->set_typodoc ($fila['TYPDOC']);
		$this->set_nrodoc ($fila['DOCNO']);
		$this->set_carrera ($fila['CAREER']);
		$this->set_lname ($fila['LNAME']);
		$this->set_fname ($fila['FNAME']);
		$this->set_carrera_descrip ($fila['DESCRIP']);
		$this->set_idcentrocosto ($fila['IDCENTRODECOSTO']);
		$this->set_foto ($this->get_Photo ($fila['PERSON']));
	}

	/* SETTER Y GEGTTERS */
	function get_person()
	{
		return $this->person;
	}

	function get_nombre()
	{
		return $this->nombre;
	}

	function get_typodoc()
	{
		return $this->typodoc;
	}

	function get_nrodoc()
	{
		return $this->nrodoc;
	}

	function get_carrera()
	{
		return $this->carrera;
	}

	function get_lname()
	{
		return $this->lname;
	}

	function get_conexion()
	{
		return $this->conexion;
	}

	function get_fname()
	{
		return $this->fname;
	}

	function get_carrera_descrip()
	{
		return $this->carrera_descrip;
	}

	function get_idcentrocosto()
	{
		return $this->idcentrocosto;
	}

	function get_foto()
	{
		return $this->foto;
	}

	function set_person($person)
	{
		$this->person = $person;
	}

	function set_nombre($nombre)
	{
		$this->nombre = $nombre;
	}

	function set_typodoc($typodoc)
	{
		$this->typodoc = $typodoc;
	}

	function set_nrodoc($nrodoc)
	{
		$this->nrodoc = $nrodoc;
	}

	function set_carrera($carrera)
	{
		$this->carrera = $carrera;
	}

	function set_lname($lname)
	{
		$this->lname = $lname;
	}

	function set_fname($fname)
	{
		$this->fname = $fname;
	}

	function set_conexion($conexion)
	{
		$this->conexion = $conexion;
	}

	function set_carrera_descrip($carrera_descrip)
	{
		$this->carrera_descrip = $carrera_descrip;
	}

	function set_idcentrocosto($idcentrocosto)
	{
		$this->idcentrocosto = $idcentrocosto;
	}

	function set_foto($foto)
	{
		$this->foto = $foto;
	}
}

?>
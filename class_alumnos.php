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
require_once ("class_Personas.php");

/**
 * Clase que maneja los atributos de los alumnos
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author lquiroga - lquiroga@gmail.com
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name Alumnos
 *
 * @version 1.1 Se elimino el parametro id, este es equivalente al person de la clase padre. iberlot <@> iberlot@usal.edu.ar 2/7/19
 */
class Alumnos extends Personas
{
	// FIXME La clase tiene un error de implementacion a corregir en la brevedad, las carreres deberian implementarse por medio de otra clase y asociarlas por medio de una lista al alumno. Con la forma actual un alumno podria tener una sola carrera. . iberlot <@> iberlot@usal.edu.ar 2/7/19

	/**
	 * Identificador del centro de costo.
	 *
	 * @var int
	 */
	protected $idcentrocosto;

	/**
	 * Descripcion de la carrera.
	 *
	 * @var string
	 */
	protected $carrera_descrip;

	/**
	 * Identificador de la carrera.
	 *
	 * @var int
	 */
	protected $carrera;

	/**
	 * Identificador del estado de la carrera.
	 * Los valores posibles son:
	 * FINALPASS, 2
	 * EQUIVAL, 3
	 * POSTPONED, 4
	 * COUREXAM, 5
	 * PREEXAM, 6
	 * FAILED, 7
	 * COURLOST, 8
	 * COURFAIL 9
	 * CURSADAABANDONADA 10
	 *
	 * XXX En el set realiza una comprovacion asi que hay que actualizarlo si llega el caso.
	 *
	 * @var int
	 */
	protected $carrera_stat;

	/**
	 * Identificador de la facultad.
	 *
	 * @var int
	 */
	protected $fa;

	/**
	 * Identificador de la cede.
	 *
	 * @var int
	 */
	protected $es;

	/**
	 * Identificador de la carrera.
	 *
	 * @var int
	 */
	protected $ca;

	/**
	 * Identificador del plan.
	 *
	 * @var int
	 */
	protected $plan;

	/**
	 * Descripcion de la unidad.
	 *
	 * @var string
	 */
	protected $desc_unidad_alumno;

	public function __construct($db = null, $person = null, $centrocosto = null)
	{
		parent::__construct ($db, $person);

		if ($person != null && trim ($person) != '')
		{

			if ($centrocosto != null && trim ($centrocosto) != '')
			{

				$this->findByPerson ($person, $centrocosto);
			}
			else
			{

				$this->findByPerson ($person);
			}
		}
	}

	/**
	 * findByPerson busca alumno por person
	 *
	 * @param int $person
	 *        	id person
	 */
	public function findByPerson($person, $centrocosto = null)
	{
		$this->setPerson ($person);

		$anio_actual = date ("Y");

		$parametros = array (
				$anio_actual,
				$this->person
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
                    carstu.career,
                    carstu.plan,
                    carstu.stat,
				    career.descrip
				FROM
				    appgral.person
				    JOIN appgral.perdoc ON person.person = perdoc.person
				    JOIN studentc.carstu ON person.person = carstu.student
				    JOIN studentc.career ON carstu.career = career.code
                    JOIN contaduria.centrodecosto ON centrodecosto.fa||centrodecosto.ca = career.code and centrodecosto.es = carstu.BRANCH
                    JOIN tesoreria.ccalu ON person.person = ccalu.person and centrodecosto.idcentrodecosto = ccalu.idcentrodecosto
				WHERE
				        ccalu.aniocc =:anio
				    AND
				        person.person =:person";

		// JOIN interfaz.aux_ccalu ON perdoc.docno = aux_ccalu.nrodoc

		if ($centrocosto != null)
		{
			$query .= " AND ccalu.idcentrodecosto = :centrocosto";
			array_push ($parametros, $centrocosto);
		}

		print_r ($query);
		print_r ($parametros);

		$result = $this->db->query ($query, true, $parametros);

		$this->loadData ($this->db->fetch_array ($result));
	}

	/**
	 * En base a un criterio de busqueda (apellido , dni o nombre) devuelve alumno en objeto
	 * dentro de un array
	 *
	 * @param string $criterio
	 *        	String con el dato a buscar en lname, fname o docno
	 * @param
	 *        	array de enteros con las faescas a usar de limitadores en la busqueda.
	 *
	 * @return array[Alumnos] array con los alumnos que entran en esas categorias. En caso de no haber encontrado ningun resultado retornara false.
	 */
	public function findByProps($criterio, $fa = null)
	{
		$anio_actual = date ("Y");

		$criterio1 = $criterio2 = $criterio3 = $criterio;

		$parametros = array (
				$anio_actual,
				strtoupper ($criterio1),
				strtoupper ($criterio2),
				strtoupper ($criterio3)
		);

		$select = "";
		$join = "";
		$where = "";

		if ($fa != null)
		{
			$unidades = "";

			foreach ($fa as $row)
			{
				$unidades .= $row . ',';
			}
			$unidades .= '00';

			$select = ", LPAD(career.facu, 2, '0') facu,
                         centrodecosto.idcentrodecosto ";

			$join = "JOIN studentc.carstu ON person.person = carstu.student
                     JOIN studentc.career ON carstu.career = career.code
                     JOIN contaduria.centrodecosto ON centrodecosto.fa||centrodecosto.ca = career.code and centrodecosto.es = carstu.BRANCH
                     JOIN tesoreria.ccalu ON person.person = ccalu.person and centrodecosto.idcentrodecosto = ccalu.idcentrodecosto";

			$where = "AND facu IN( " . $unidades . " )
						GROUP BY centrodecosto.idcentrodecosto, ccalu.person, person.lname, person.fname,facu";
		}

		$query = "SELECT
				    person.person,
				    person.lname,
				    person.fname
					" . $select . "
				FROM
				    appgral.person
				    JOIN appgral.perdoc ON person.person = perdoc.person
				    JOIN tesoreria.ccalu ON person.person = ccalu.person
					" . $join . "
				WHERE
				        ccalu.aniocc =:anio
				    AND (
				            lname LIKE '%' ||:busq1 || '%'
				        OR
				            fname LIKE '%' ||:busq2 || '%'
				        OR
				            docno LIKE '%' ||:busq3 || '%'
				    )" . $where;

		$result = $this->db->query ($query, true, $parametros);

		while ($fila = $this->db->fetch_array ($result))
		{
			if (isset ($fila['IDCENTRODECOSTO']))
			{
				$alumno = new Alumnos ($this->db, $fila['PERSON'], $fila['IDCENTRODECOSTO']);

				$salida[] = $alumno;
			}
			else
			{
				$alumno = new Alumnos ($this->db, $fila['PERSON']);

				$salida[] = $alumno;
			}
		}

		if (empty ($salida))
		{
			return false;
		}
		return $salida;
	}

	/**
	 * En base al centrocosto seteo la faesca fa-es-ca.
	 *
	 * @param int $idcentrocosto
	 * @return string String con la faesca en caso de no encontrarla retornara false.
	 */
	public function obtenerSeterarFaesca($idcentrocosto)
	{
		$param = array (
				$idcentrocosto
		);

		$query = "SELECT * FROM contaduria.centrodecosto WHERE idcentrodecosto = :centrocosto";

		$scfaes = $this->db->query ($query, true, $param);

		if ($scfaes)
		{
			$arr_asoc = $this->db->fetch_array ($scfaes);

			$this->setFa ($arr_asoc['FA']);
			$this->setEs ($arr_asoc['ES']);
			$this->setCa ($arr_asoc['CA']);

			return ($this->getFa () . $this->getEs () . $this->getCa ());
		}

		return false;
	}

	/**
	 * En base a la facultad obtengo el nombre de la misma
	 *
	 * @param int $fa
	 * @return string Nombre de la facultad en caso de no encontrarla retornara false.
	 */
	public function obtener_unidad_por_fa($fa)
	{
		$param = array (
				$fa
		);

		$query = "SELECT sdesc FROM studentc.facu WHERE LPAD(code, 2, '0') = LPAD(:fa, 2, '0') ";

		$scfaes = $this->db->query ($query, true, $param);

		if ($scfaes)
		{
			$arr_asoc = $this->db->fetch_array ($scfaes);

			return ($arr_asoc['SDESC']);
		}

		return false;
	}

	/**
	 * MateriasAprxPlanCarrera muestra las materias aprobadas en
	 * base a un plan especifico una carrera y un alumno
	 *
	 * @version 0.2 Se elimino el parametro person que se le pasaba a la funcion, como estamos en una clase deberia usar el person del objeto creado. iberlot <@> iberlot@usal.edu.ar 2/7/19
	 *
	 * @param int $carrera
	 * @param int $plan
	 * @param array $estados
	 *        	Los estados posibles son:
	 *        	FINALPASS, 2
	 *        	EQUIVAL, 3
	 *        	POSTPONED, 4
	 *        	COUREXAM, 5
	 *        	PREEXAM, 6
	 *        	FAILED, 7
	 *        	COURLOST, 8
	 *        	COURFAIL 9
	 *        	CURSADAABANDONADA 10
	 *
	 * @param number $cuatrimestre
	 *        	-->
	 *        	esta serteado en menos dos , por que existen cuatrimestres -1 0 y 1
	 *
	 *
	 * @return array de materias q no estan en el listado que le pasamos para excluir
	 */
	public function MateriasAprxPlanCarrera($carrera, $plan, $estados, $cuatrimestre = -2)
	{
		$query = "SELECT stusubj.subject, course.quarter, stusubj.stat
				  FROM studentc.stusubj
        		  JOIN studentc.course ON stusubj.course = course.code
				  WHERE stusubj.student=:person AND stusubj.career=:carrera AND stusubj.plan= :plan
						AND stusubj.stat IN (" . $estados . ")";

		$parametros = array (
				$person,
				$carrera,
				$plan
		);

		if ($cuatrimestre != -2)
		{
			$query .= "AND course.quarter = :cuatri";

			$parametros[] = $cuatrimestre;
		}

		$subjectMaterias = $this->db->query ($query, true, $parametros);

		$subject_x_estado = array ();

		while ($fila = $this->db->fetch_array ($subjectMaterias))
		{
			$subject_x_estado[] = $fila['SUBJECT'];
		}

		return $subject_x_estado;
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
		$this->setPerson ($fila['PERSON']);
		$this->setCarrera ($fila['CAREER']);
		$this->setApellido ($fila['LNAME']);
		$this->setNombre ($fila['FNAME']);
		$this->setCarrera_descrip ($fila['DESCRIP']);
		$this->setIdcentrocosto ($fila['IDCENTRODECOSTO']);
		$this->setPlan ($fila['PLAN']);
		$this->setCarrera_stat ($fila['STAT']);

		/* seteo la faesca del alumno */
		if (isset ($fila['IDCENTRODECOSTO']))
		{
			$this->obtenerSeterarFaesca ($fila['IDCENTRODECOSTO']);
		}

		$this->setFoto_persona ($this->get_Photo ($fila['PERSON']));
		// $this->set_foto ($this->get_Photo_alumno ($fila['PERSON']));

		/* en base a la fa obtengo el nomber de la unidad a la cual pertenece el alumno */
		$this->setDesc_unidad_alumno ($this->obtener_unidad_por_fa ($this->getFa ()));
	}

	/**
	 *
	 * @return int el dato de la variable $idcentrocosto
	 */
	public function getIdcentrocosto()
	{
		return $this->idcentrocosto;
	}

	/**
	 *
	 * @return string el dato de la variable $carrera_descrip
	 */
	public function getCarrera_descrip()
	{
		return $this->carrera_descrip;
	}

	/**
	 *
	 * @return int el dato de la variable $carrera
	 */
	public function getCarrera()
	{
		return $this->carrera;
	}

	/**
	 *
	 * @return int el dato de la variable $carrera_stat
	 */
	public function getCarrera_stat()
	{
		return $this->carrera_stat;
	}

	/**
	 *
	 * @return int el dato de la variable $fa
	 */
	public function getFa()
	{
		return $this->fa;
	}

	/**
	 *
	 * @return int el dato de la variable $es
	 */
	public function getEs()
	{
		return $this->es;
	}

	/**
	 *
	 * @return int el dato de la variable $ca
	 */
	public function getCa()
	{
		return $this->ca;
	}

	/**
	 *
	 * @return int el dato de la variable $plan
	 */
	public function getPlan()
	{
		return $this->plan;
	}

	/**
	 *
	 * @return string el dato de la variable $desc_unidad_alumno
	 */
	public function getDesc_unidad_alumno()
	{
		return $this->desc_unidad_alumno;
	}

	/**
	 * Retorna la descripcion asociada a cada estado de la carrera.
	 *
	 * @return string
	 */
	public function get_descripcion_estado_carrera()
	{
		if ($this->carrera_stat != "")
		{
			switch ($this->carrera_stat)
			{
				case 0 :
					return "ANOTADO";
					break;
				case 1 :
					return "CURSADA APROBADA";
					break;
				case 2 :
					return "FINALPASS";
					break;
				case 3 :
					return "EQUIVAL";
					break;
				case 4 :
					return "POSTPONED";
					break;
				case 5 :
					return "COUREXAM";
					break;
				case 6 :
					return "FINALPASS";
					break;
				case 7 :
					return "FAILED";
					break;
				case 8 :
					return "COURLOST";
					break;
				case 9 :
					return "COURFAIL";
					break;
				case 10 :
					return "CURSADAABANDONADA";
					break;
			}
		}
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $idcentrocosto
	 */
	public function setIdcentrocosto($idcentrocosto)
	{
		$this->idcentrocosto = $idcentrocosto;
	}

	/**
	 *
	 * @param
	 *        	string a cargar en la variable $carrera_descrip
	 */
	public function setCarrera_descrip($carrera_descrip)
	{
		$this->carrera_descrip = $carrera_descrip;
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $carrera
	 */
	public function setCarrera($carrera)
	{
		$this->carrera = $carrera;
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $carrera_stat
	 */
	public function setCarrera_stat($carrera_stat)
	{
		if ($carrera_stat > -1 && $carrera_stat < 11)
		{
			$this->carrera_stat = $carrera_stat;
		}
		else
		{
			throw new Exception ("Codigo de estado de carrera invalido. |" . $carrera_stat . "|");
		}
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $fa
	 */
	public function setFa($fa)
	{
		$this->fa = $fa;
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $es
	 */
	public function setEs($es)
	{
		$this->es = $es;
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $ca
	 */
	public function setCa($ca)
	{
		$this->ca = $ca;
	}

	/**
	 *
	 * @param
	 *        	int a cargar en la variable $plan
	 */
	public function setPlan($plan)
	{
		$this->plan = $plan;
	}

	/**
	 *
	 * @param
	 *        	string a cargar en la variable $desc_unidad_alumno
	 */
	public function setDesc_unidad_alumno($desc_unidad_alumno)
	{
		$this->desc_unidad_alumno = $desc_unidad_alumno;
	}
}
?>
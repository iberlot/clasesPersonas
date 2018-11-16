<?php

/**
 * Archivo principar de la clase.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @todo FechaC 19/2/2016 - Lenguaje PHP
 *
 * @name class_persona.php
 *
 */

/**
 * Clase encargada del manejo de todos los datos referentes a la persona.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @name class_persona
 *
 * @version 0.1 - Version de inicio
 *
 * @package Classes_USAL
 *
 * @category General
 *
 * @todo El usuario que se conecta a la base debe tener los siguientes permisos -
 *       - SELECT :
 *       portal.usuario_web | appgral.apers | appgral.person | appgral.perdoc | appgral.personca | interfaz.estadocredenca | appgral.lnumber
 *       - UPDATE :
 *       appgral.apers | appgral.lnumber | appgral.perdoc
 *       - INSERT :
 *       appgral.apers | appgral.perdoc |
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
 * totalHorasPerdidasAqui = 106
 *
 */
abstract class Personas
{

	/**
	 * Apellido de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.lname - VARCHAR2(50 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 * @internal Este dato siempre tiene que estar en mayuscula.
	 *           $apellido = strtoupper($apellido);
	 */
	protected $apellido = "";

	/**
	 * Nombre de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.fname - VARCHAR2(50 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 * @internal Este campo deberia tener la primer letra en mayuscula y el resto siempre en minuscula
	 *           $realname = ucwords($realname);
	 */
	protected $nombre = "";

	/**
	 * XXX convertir a funcion
	 *
	 * @var string Nombre completo de la persona (formado de la forma $apellido . " " . $realname)
	 */
	// public $nombreCompleto = "";

	/**
	 *
	 * @var int Numero de person (id de la tabla) de la persona
	 *      PERSON - NUMBER(8,0)
	 */
	protected $person = "";

	/**
	 * Direccion de mail de la persona
	 *
	 * @var array <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'E-MAIL'
	 */
	protected $email = array ();

	/**
	 * Numero de telefono de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMERO'
	 */
	protected $telefono = array ();

	/**
	 * Direccion donde se encuentra la foto de la persona dentro de la carpeta de fotos
	 * se arma de la siguiente manera
	 *
	 * @version 2 Se modifico para que se usara el person en vez del doc asi no se generan conflictos
	 *          cuando hay cambios en los mismos.
	 * @var string <code>
	 *      <?php
	 *      substr($person, - 1)."/".substr($person, - 2, 1)."/".substr($person, - 3, 1)."/".$person.".jpg";
	 *      ?>
	 *      </code>
	 *
	 * @example para el Person 112469 quedaria:
	 *          9/6/4/112469.jpg
	 */
	protected $foto_persona = "";

	/**
	 * Fecha de nacimiento de la persona
	 *
	 * @var string - Date <Br>
	 *      @ubicacionBase appgral.person.birdate - DATE
	 *
	 * @todo el formato correcto para pasar este dato deberia ser 'RRRR-MM-DD' o su equivalente Año-mes-dia.
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	protected $fechaNacimiento = "";

	/**
	 * Estado civil de la persona
	 *
	 * @var String <Br>
	 *      @ubicacionBase appgral.person.marstat - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Soltero/a <Br>
	 *      1 = Casado/a <Br>
	 *      2 = Viudo/a <Br>
	 *      3 = Separado/a <Br>
	 *      4 = Divorciado/da <Br>
	 *      5 = Union de hecho
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 */
	protected $estadoCivil = "";

	/**
	 * Nacionalidad de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.nation - VARCHAR2(3 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       La tabla de referencia al dato es appgral.country.
	 *       Mas espesificamente appgral.country.nation
	 */
	protected $nacionalidad = "";

	/**
	 * Tipo de nacionalizacion de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.person.tnation - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Argentino <Br>
	 *      1 = Extrangero <Br>
	 *      2 = Naturalizado <Br>
	 *      3 = Por Opcion
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 */
	protected $tipoNacionalidad = "";

	/**
	 * Sexo de la persona
	 *
	 * @var String <Br>
	 *      @ubicacionBase appgral.person.sex - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Mujer <Br>
	 *      1 = Hombre
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	protected $sexo = "";

	/**
	 * Array de objetos Direcciones de la persona
	 *
	 * @var array|Direcciones
	 */
	protected $direccion = array ();
	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL AREA DE PERSONAL *
	 * ******************************************************************************
	 */

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES A LA CARGA FAMILIAR *
	 * ******************************************************************************
	 */
	/**
	 * $esposa
	 *
	 * @var string
	 */
	public $esposa = "";
	/**
	 * $familiarACargo
	 *
	 * @var string
	 */
	public $familiarACargo = "";
	/**
	 * $FamiliaNumerosa
	 *
	 * @var string
	 */
	public $FamiliaNumerosa = "";
	/**
	 * $hijos
	 *
	 * @var string
	 */
	public $hijos = "";
	/**
	 * $guarderia
	 *
	 * @var string
	 */
	public $guarderia = "";
	/**
	 * $hijoIncapasitado
	 *
	 * @var string
	 */
	public $hijoIncapasitado = "";
	/**
	 * $prenatal
	 *
	 * @var string
	 */
	public $prenatal = "";
	/**
	 * $preescolar
	 *
	 * @var string
	 */
	public $preescolar = "";
	/**
	 * $escuelaMedia
	 *
	 * @var string
	 */
	public $escuelaMedia = "";
	/**
	 * $escuelaPrimaria
	 *
	 * @var string
	 */
	public $escuelaPrimaria = "";

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL FUNCIONAMIENTO DEL SISTEMA *
	 * ******************************************************************************
	 *
	 *
	 * ACTIVE NUMBER(1,0)
	 * INCOUNTRYSINCE DATE
	 * RELIGION NUMBER(2,0)
	 * QBROTHER NUMBER(2,0)
	 * QSON NUMBER(2,0)
	 */

	/*
	 * ************************************************************************
	 * Aca empiezan las funciones de la clase
	 * ************************************************************************
	 */
	/**
	 * Devuelve el nombre y el apellido para un person dado.
	 *
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datosAUsar
	 *        	- Es impresindible que contenga el inice "person" de lo contrario devolvera error.
	 * @throws Exception - Tanto si no se pasa el person como si no se puede recuperar valor.
	 * @return string[] - Con los campos LNAME y FNAME.
	 */
	public function getNombreYApellido($db, $datosAUsar)
	{
		try
		{
			if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
			{
				$where[] = " person = :person ";
				$parametros[] = $datosAUsar['person'];

				if ($where != "")
				{
					$where = implode (" AND ", $where);

					$where = " AND " . $where;
				}

				$sql = "SELECT lname, fname FROM appgral.person WHERE 1=1 " . $where;

				if ($result = $db->query ($sql, $esParam = true, $parametros))
				{
					$rst = $db->fetch_array ($result);

					return $rst;
				}
				else
				{
					throw new Exception ('ERROR: No se pudo realizar la busqueda en appgral.person.');
				}
			}
			else
			{
				throw new Exception ('ERROR: El person es obligatorio.');
			}
		}
		catch (Exception $e)
		{

			$this->errores ($e);

			if ($db->debug == true)
			{
				return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			}
			else

			{
				return $e->getMessage ();
			}

			if ($db->dieOnError == true)
			{
				exit ();
			}
		}
	}

	/*
	 * ************************************************************************
	 * Funciones para la creacion de personas
	 *
	 * ************************************************************************
	 */

	/**
	 * Inserta el numero y tipo de documento en la tabla appgral.perdoc
	 *
	 * @param array $arrayDatosPersona
	 *        	Tiene como indices obligatorios docNumero y docTipo.
	 *
	 * @todo adicionalmente updatea el registro en appgral.lnumber para generarle el nuevo person.
	 *
	 * @throws Exception
	 * @return int|boolean - el person en caso de realizarce todo sin problema y false si no.array
	 *
	 *         En caso de que el parametro docTipo sea numerico realiza la siguiente convecion ( 1=LE, 2=LC, 7=DNI )
	 */
	public function nuevoPerdoc($arrayDatosPersona)
	{
		global $db;

		$resultado = true;
		echo "****************************************";
		return;
		// print_r($arrayDatosPersona);

		try
		{
			if (!isset ($arrayDatosPersona['docTipo']) or $arrayDatosPersona['docTipo'] == "")
			{
				throw new Exception ('El tipo de documento no puede ser nulo! ');
			}
			if (!isset ($arrayDatosPersona['docNumero']) or $arrayDatosPersona['docNumero'] == "")
			{
				throw new Exception ('El numero de documento no puede ser nulo! ');
			}

			if (is_numeric ($arrayDatosPersona['docTipo']))
			{
				if (ltrim ($arrayDatosPersona['docTipo'], 0) == 7)
				{
					$arrayDatosPersona['docTipo'] = "DNI";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 2)
				{
					$arrayDatosPersona['docTipo'] = "LC";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 4)
				{
					$arrayDatosPersona['docTipo'] = "PAS";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 1)
				{
					$arrayDatosPersona['docTipo'] = "LE";
				}
				else
				{
					throw new Exception ('El campo tipo es un numero y no pertenece a ninguno de los listados! ');
				}
			}

			$person = $this->buscarPerdoc ($arrayDatosPersona);

			if ($person != "")
			{
				throw new Exception ('El numero de documento tiene un registro en appgral.auditaperdoc con el person $person ! ');
			}

			$sql = "UPDATE appgral.lnumber" . $this->db_link . " SET lnum=lnum+1 WHERE classname = 'intersoft.appgral.schemas.appgral.Person'";

			if (!$db->query ($sql))
			{
				throw new Exception ('error!');
			}

			// Recuperamos el person a utilizar
			$sql = "SELECT (lnum) lnum FROM appgral.lnumber" . $this->db_link . " WHERE classname = 'intersoft.appgral.schemas.appgral.Person'";

			if (!$result = $db->query ($sql))
			{
				throw new Exception ('error!');
			}

			$person = $db->fetch_array ($result);

			$person = $person['LNUM'];

			// Insertamos el documento en Perdoc
			$sql = "INSERT INTO appgral.perdoc" . $this->db_link . " (person, typdoc, docno ) VALUES (:person, :typdoc, :docno )";

			$parametros[0] = $person;
			$parametros[1] = $arrayDatosPersona['docTipo'];
			$parametros[2] = $arrayDatosPersona['docNumero'];

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $person;
			}
			else
			{
				throw new Exception ('error!');
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza la insecion en la tabla appgral.catXPerson previamente comprueba que los datos no existan en esa tabla devolviendo un error en caso contrario.
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, categoria, fIngreso, fbaja, legajo
	 *
	 * @global $db - coneccion a la base de datos.
	 * @global $_SESSION - Requiere acceder a las siguirenres variables de session 'person' y 'app'.
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function nuevoCatXPerson($arrayDatosPersona)
	{
		global $db, $_SESSION;

		$resultado = true;

		try
		{
			if ($this->buscarCatXPerson ($arrayDatosPersona) != false)
			{
				throw new Exception ('Ya existe en catXPerson!');
			}

			$i = 0;
			$sqlValores = ") VALUES (SYSDATE";
			$sqlCampos = "";

			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['person']))
				{
					$sqlCampos = $sqlCampos . ", person";

					$sqlValores = $sqlValores . ", :person";

					$parametros[$i] = $arrayDatosPersona['person'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['categoria']) or $arrayDatosPersona['categoria'] == "")
			{
				throw new Exception ('el dato categoria no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['categoria']))
				{
					$sqlCampos = $sqlCampos . ", categoria";

					$sqlValores = $sqlValores . ", :categoria";

					$parametros[$i] = $arrayDatosPersona['categoria'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['fIngreso']) or $arrayDatosPersona['fIngreso'] == "")
			{
				throw new Exception ('el dato fIngreso no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['fIngreso']))
				{
					$sqlCampos = $sqlCampos . ", finicio";

					$sqlValores = $sqlValores . ", TO_DATE(:finicio, 'YYYY-MM-DD')";

					$parametros[$i] = $arrayDatosPersona['fIngreso'];

					$i++;
				}
			}
			if (isset ($arrayDatosPersona['fbaja']) and $arrayDatosPersona['fbaja'] != "")
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
				{
					$sqlCampos = $sqlCampos . ", fbaja";

					$sqlValores = $sqlValores . ", TO_DATE(:fbaja, 'YYYY-MM-DD')";

					$parametros[$i] = $arrayDatosPersona['fbaja'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['legajo']) or $arrayDatosPersona['legajo'] == "")
			{
				throw new Exception ('el dato legajo no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['legajo']))
				{
					$sqlCampos = $sqlCampos . ", legajo";

					$sqlValores = $sqlValores . ", :legajo";

					$parametros[$i] = $arrayDatosPersona['legajo'];

					$i++;
				}
			}
			if (!isset ($_SESSION['person']) or $_SESSION['person'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer el person de la misma! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($_SESSION['person']))
				{
					$sqlCampos = $sqlCampos . ", muid";

					$sqlValores = $sqlValores . ", :muid";

					$parametros[$i] = $_SESSION['person'];

					$i++;
				}
			}
			if (!isset ($_SESSION['app']) or $_SESSION['app'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer la app de la misma! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($_SESSION['app']))
				{
					$sqlCampos = $sqlCampos . ", idaplicacion";

					$sqlValores = $sqlValores . ", :idaplicacion";

					$parametros[$i] = $_SESSION['app'];

					$i++;
				}
			}

			$sql = "INSERT INTO appgral.catxperson" . $this->db_link . " (mtime";
			$sqlValores .= ")";

			$sql = $sql . $sqlCampos . $sqlValores;

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $resultado;
			}
			else
			{
				throw new Exception ('Error al insertar en appgral.catxperson!');
			}
			return $resultado;
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
	}

	/**
	 * Realiza el update en la tabla appgral.catXPerson previamente comprueba que los datos no existan en esa tabla devolviendo un error en caso contrario.
	 *
	 * @param array $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, legajo
	 *
	 * @global $db - coneccion a la base de datos.
	 * @global $_SESSION - Requiere acceder a las siguirenres variables de session 'person' y 'app'.
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function updateCatXPerson($arrayDatosPersona)
	{
		global $db, $_SESSION;
		$extraWhere = '';
		$resultado = true;

		try
		{
			if ($this->buscarCatXPerson ($arrayDatosPersona) == false)
			{
				throw new Exception ('El person ' . $arrayDatosPersona['person'] . 'no existe en catXPerson!');
			}

			$a = 0;
			$campos = "";

			if (isset ($arrayDatosPersona['categoria']) and $this->comprobarExisteDato ($arrayDatosPersona['categoria']))
			{
				$parametros[$a] = $arrayDatosPersona['categoria'];
				$a++;

				$campos .= ", categoria = :categoria";
			}

			if (isset ($arrayDatosPersona['fIngreso']) and $this->comprobarExisteDato ($arrayDatosPersona['fIngreso']))
			{
				$parametros[$a] = $arrayDatosPersona['fIngreso'];
				$a++;

				$campos .= ", finicio = TO_DATE(:finicio, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['fbaja']) and $this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
			{
				$parametros[$a] = $arrayDatosPersona['fbaja'];
				$a++;

				$campos .= ", fbaja = TO_DATE(:fbaja, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['legajo']) and $this->comprobarExisteDato ($arrayDatosPersona['legajo']))
			{
				$parametros[$a] = $arrayDatosPersona['legajo'];
				$a++;

				$campos .= ", legajo = :legajo";
			}

			if (!isset ($_SESSION['person']) or $_SESSION['person'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer el person de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['person'];
				$a++;

				$campos .= ", muid = :muid";
			}

			if (!isset ($_SESSION['app']) or $_SESSION['app'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer la app de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['app'];
				$a++;

				$campos .= ", idaplicacion = :idaplicacion";
			}

			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			else
			{
				$parametros[$a] = $arrayDatosPersona['person'];
				$a++;

				$wer = "AND person = :person";
			}

			if (isset ($arrayDatosPersona['categoria']) and $this->comprobarExisteDato ($arrayDatosPersona['categoria']))
			{
				$extraWhere = " AND LTRIM(LTRIM(categoria, '0')) = LTRIM(LTRIM(:categoria, '0')) ";
				$parametros[$a] = $arrayDatosPersona['categoria'];

				$a++;
			}

			$sql = "UPDATE appgral.catxperson" . $this->db_link . " SET mtime = SYSDATE" . $campos . " WHERE 1=1 " . $wer . $extraWhere;

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $resultado;
			}
			else
			{
				throw new Exception ('error!');
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza el insert en la tabla appgral.person
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, apellido, realname, country, poldiv, city, birdate, nation, sexo, marstat, rcountry, rpoldiv, rcity, tnation
	 * @throws Exception
	 *
	 * @global $db - coneccion a la base de datos.
	 *
	 * @return boolean
	 */
	public function inserPerson($arrayDatosPersona)
	{
		global $db;
		$resultado = true;

		try
		{
			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['apellido']) or $arrayDatosPersona['apellido'] == "")
			{
				throw new Exception ('el dato apellido no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['realname']) or $arrayDatosPersona['realname'] == "")
			{
				throw new Exception ('el dato realname no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['country']) or $arrayDatosPersona['country'] == "")
			{
				throw new Exception ('el dato country no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['poldiv']) or $arrayDatosPersona['poldiv'] == "")
			{
				throw new Exception ('el dato poldiv no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['city']) or $arrayDatosPersona['city'] == "")
			{
				throw new Exception ('el dato city no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['birdate']) or $arrayDatosPersona['birdate'] == "")
			{
				throw new Exception ('el dato birdate no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['nation']) or $arrayDatosPersona['nation'] == "")
			{
				throw new Exception ('el dato nation no fue correctamene pasado! ');
			}
			else
			{
				$arrayDatosPersona['nation'] = $this->recuNacion ($arrayDatosPersona['nation']);
			}
			if (!isset ($arrayDatosPersona['sexo']) or $arrayDatosPersona['sexo'] == "")
			{
				throw new Exception ('el dato sexo no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['marstat']) or $arrayDatosPersona['marstat'] == "")
			{
				// FIXME verificar si el funcionamiento de esto es correcto - 2017/04/05 iberlot
				// throw new Exception('el dato marstat no fue correctamene pasado! ');

				$arrayDatosPersona['marstat'] = '0';
			}
			if (!isset ($arrayDatosPersona['rcountry']) or $arrayDatosPersona['rcountry'] == "")
			{
				throw new Exception ('el dato rcountry no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['rpoldiv']) or $arrayDatosPersona['rpoldiv'] == "")
			{
				throw new Exception ('el dato rpoldiv no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['rcity']) or $arrayDatosPersona['rcity'] == "")
			{
				throw new Exception ('el dato rcity no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['tnation']) or $arrayDatosPersona['tnation'] == "")
			{
				// FIXMENo se por que no reconoce el campo tnation ???
				// throw new Exception('el dato tnation no fue correctamene pasado! ');
				$arrayDatosPersona['tnation'] = 0;
			}

			if (!is_numeric ($arrayDatosPersona['sexo']))
			{
				if ($arrayDatosPersona['sexo'] == 'V')
				{
					$arrayDatosPersona['sexo'] = "1";
				}
				elseif ($arrayDatosPersona['sexo'] == 'M')
				{
					$arrayDatosPersona['sexo'] = "0";
				}
			}

			// Recuperamos el person a utilizar
			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros = "";
			$parametros[0] = $arrayDatosPersona['person'];

			$result = $db->query ($sql, $esParam = true, $parametros);

			$persona = $db->fetch_array ($result);

			if ($persona == "" or $persona == NULL)
			{
				$sqlNuevoPer = "INSERT INTO appgral.person" . $this->db_link . "
	(person, lname, fname, country, poldiv, city, birdate, nation, sex, marstat, address, rcountry, rpoldiv, rcity, telep, tnation)
	VALUES
	(:person, upper(:lname), :fname, :country, :poldiv, :city, TO_DATE(:birdate, 'RRRR-MM-DD'), :nation, :sex, :marstat, 'DOMI', :rcountry, :rpoldiv, :rcity, 'TELE', :tnation)";

				$parametros = "";
				$parametros[0] = $arrayDatosPersona['person'];
				$parametros[1] = $arrayDatosPersona['apellido'];
				$parametros[2] = $arrayDatosPersona['realname'];
				$parametros[3] = $arrayDatosPersona['country'];
				$parametros[4] = $arrayDatosPersona['poldiv'];
				$parametros[5] = $arrayDatosPersona['city'];
				$parametros[6] = $arrayDatosPersona['birdate'];
				$parametros[7] = $arrayDatosPersona['nation'];
				$parametros[8] = $arrayDatosPersona['sexo'];
				$parametros[9] = $arrayDatosPersona['marstat'];
				$parametros[10] = $arrayDatosPersona['rcountry'];
				$parametros[11] = $arrayDatosPersona['rpoldiv'];
				$parametros[12] = $arrayDatosPersona['rcity'];
				$parametros[13] = $arrayDatosPersona['tnation'];

				if ($db->query ($sqlNuevoPer, $esParam = true, $parametros))
				{
					return true;
				}
				else
				{
					throw new Exception ('No se pudo realizar la insercion en appgral.person! ');
				}
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza el insert de todos los campos requeridos para la creacion de personas
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, apellido, realname, country, poldiv, city, birdate, nation, sexo, marstat, rcountry, rpoldiv, rcity, tnation
	 *
	 * @throws Exception
	 *
	 * @global $db - coneccion a la base de datos.
	 *
	 * @return number
	 */
	public function nuevaPersona($arrayDatosPersona)
	{
		global $db;
		$resultado = true;

		try
		{
			if (!$person = $this->nuevoPerdoc ($arrayDatosPersona))
			{
				throw new Exception ('No se pudo dar de alta en perdoc ');
			}

			$arrayDatosPersona['person'] = $person;

			$arrayDatosPersona['nation'] = $this->recuNacion ($arrayDatosPersona['nation']);

			if ($arrayDatosPersona['tnation'] == "")
			{
				if ($arrayDatosPersona['nation'] == 'ARG')
				{
					$arrayDatosPersona['tnation'] = 0;
				}
				else
				{
					$arrayDatosPersona['tnation'] = 1;
				}
			}

			if (!$this->inserPerson ($arrayDatosPersona))
			{
				throw new Exception ('No se pudo dar de alta en person ');
			}

			// FIXME hay que verificar si conviene realizarlo asi o de alguna otra manres

			if (isset ($arrayDatosPersona['email']) and $arrayDatosPersona['email'] != "")
			{
				if (!$this->modApers ("INSERT", 'TELE', 'E-MAIL', $arrayDatosPersona['email'], $person))
				{
					throw new Exception ('No se pudo insertar el E-MAIL en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direCalle']) and $arrayDatosPersona['direCalle'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'CALLE', $arrayDatosPersona['direCalle'], $person))
				{
					throw new Exception ('No se pudo insertar la CALLE en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direNumero']) and $arrayDatosPersona['direNumero'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'NRO', $arrayDatosPersona['direNumero'], $person))
				{
					throw new Exception ('No se pudo insertar el NUMERO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direPiso']) and $arrayDatosPersona['direPiso'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'PISO', $arrayDatosPersona['direPiso'], $person))
				{
					throw new Exception ('No se pudo insertar el PISO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direDto']) and $arrayDatosPersona['direDto'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'DEPTO', $arrayDatosPersona['direDto'], $person))
				{
					throw new Exception ('No se pudo insertar el DEPARTAMENTO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direCodPos']) and $arrayDatosPersona['direCodPos'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'CODPOS', $arrayDatosPersona['direCodPos'], $person))
				{
					throw new Exception ('No se pudo insertar el CODIGO POSTAL en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['personTelPersonal']) and $arrayDatosPersona['personTelPersonal'] != "")
			{
				if (!$this->modApers ("INSERT", 'TELE', 'NUMERO', $arrayDatosPersona['personTelPersonal'], $person))
				{
					throw new Exception ('No se pudo insertar el TELEFONO en appgral.appers ');
				}
			}

			$this->nuevoCatXPerson ($arrayDatosPersona);
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		$db->commit ();
		$resultado = $arrayDatosPersona['person'];

		return $resultado;
	}

	/**
	 * Comprueva la existencia de un dato distinto de cero.
	 *
	 * @param mixed $variable
	 * @return boolean
	 */
	private function comprobarExisteDato($variable)
	{
		if ($variable != "")
		{
			if ($variable != 'NULL')
			{
				if (is_numeric ($variable) and $variable != 0)
				{
					return true;
				}
				elseif (is_numeric ($variable) and $variable == 0)
				{
					return false;
				}
				elseif (!is_numeric ($variable))
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Recupera los datos de appgral.country,
	 *
	 * @param string $nacion
	 *        	- dato a buscar en la tabla appgral.country
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @return string - codigo de la nacion
	 */
	private function recuNacion($nacion, $db)
	{
		if (isset ($nacion))
		{
			$sql = "SELECT * FROM appgral.country WHERE TRIM(upper(country)) = TRIM(upper(:country)) OR TRIM(upper(descrip)) = TRIM(upper(:descrip)) OR TRIM(upper(nation)) = TRIM(upper(:nation))";

			$parametros = "";
			$parametros[0] = $nacion;
			$parametros[1] = $nacion;
			$parametros[2] = $nacion;

			$result = $db->query ($sql, $esParam = true, $parametros);

			$pais = $db->fetch_array ($result);

			$nacion = $pais['COUNTRY'];
		}

		if (!isset ($nacion) or $nacion == "")
		{
			$nacion = 'ARG';
		}

		return $nacion;
	}
}
?>
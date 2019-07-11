<?php
/**
 * Archivo principar de la clase.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @since 19/2/2016 - Lenguaje PHP
 *
 * @name class_persona.php
 *
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
require_once ("class_Documentos.php");
require_once ("class_Direcciones.php");
require_once ("class_credenciales.php");

/**
 * Clase encargada del manejo de todos los datos referentes a la persona.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @name class_persona
 *
 * @version 0.1 - Version de inicio
 *
 * @todo El usuario que se conecta a la base debe tener los siguientes permisos -
 *       - SELECT :
 *       portal.usuario_web | appgral.apers | appgral.person | appgral.perdoc | appgral.personca | interfaz.estadocredenca | appgral.lnumber
 *       - UPDATE :
 *       appgral.apers | appgral.lnumber | appgral.perdoc
 *       - INSERT :
 *       appgral.apers | appgral.perdoc |
 */
abstract class Personas
{
	/**
	 *
	 * @var object objeto inicializado del tipo class_db
	 */
	protected $db;

	/**
	 *
	 * @var int Numero de person (id de la tabla) de la persona
	 *      PERSON - NUMBER(8,0)
	 */
	protected $person = 0;

	/**
	 *
	 * @var array Lista de documetos de la persona.
	 */
	protected $documentos = array ();

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
	 * @var Direcciones[]
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
	protected $esposa = "";
	/**
	 * $familiarACargo
	 *
	 * @var string
	 */
	protected $familiarACargo = "";
	/**
	 * $FamiliaNumerosa
	 *
	 * @var string
	 */
	protected $FamiliaNumerosa = "";
	/**
	 * $hijos
	 *
	 * @var string
	 */
	protected $hijos = "";
	/**
	 * $guarderia
	 *
	 * @var string
	 */
	protected $guarderia = "";
	/**
	 * $hijoIncapasitado
	 *
	 * @var string
	 */
	protected $hijoIncapasitado = "";
	/**
	 * $prenatal
	 *
	 * @var string
	 */
	protected $prenatal = "";
	/**
	 * $preescolar
	 *
	 * @var string
	 */
	protected $preescolar = "";
	/**
	 * $escuelaMedia
	 *
	 * @var string
	 */
	protected $escuelaMedia = "";
	/**
	 * $escuelaPrimaria
	 *
	 * @var string
	 */
	protected $escuelaPrimaria = "";

	/**
	 *
	 * @var Credenciales
	 */
	protected $credencial = null;

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
	public function __construct($person = null, $db = null)
	{
		if (!isset ($db) or empty ($db) or $db == null)
		{
			global $db;

			if (!isset ($db) or empty ($db) or $db == null)
			{
				$this->db = Sitios::openConnection ();
			}
			else
			{
				$this->db = $db;
			}
		}
		else
		{
			$this->db = $db;
		}

		if (!is_null ($person))
		{
			$this->buscar_PersonXPerson ($person);

			for($i = 0; $i < 3; $i ++)
			{
				$direccion = new Direcciones ($i);
				$direccion->recuperar_dire_person ($person, $i);
			}
			$this->email = array ();
			$this->telefono = array ();
			$this->foto_persona = "";
			$this->direccion = array ();
			$this->esposa = "";
			$this->familiarACargo = "";
			$this->FamiliaNumerosa = "";
			$this->hijos = "";
			$this->guarderia = "";
			$this->hijoIncapasitado = "";
			$this->prenatal = "";
			$this->preescolar = "";
			$this->escuelaMedia = "";
			$this->escuelaPrimaria = "";

			$this->credencial = new Credenciales ($db, $person);
		}
		else
		{
			$this->credencial = new Credenciales ($db);
		}
	}

	/**
	 * Devuelve el nombre y el apellido para un person dado.
	 *
	 * @param mixed[] $datosAUsar
	 *        	- Es impresindible que contenga el inice "person" de lo contrario devolvera error.
	 * @throws Exception - Tanto si no se pasa el person como si no se puede recuperar valor.
	 * @return string[] - Con los campos LNAME y FNAME.
	 */
	public function getNombreYApellido($datosAUsar)
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

			if ($result = $this->db->query ($sql, true, $parametros))
			{
				$rst = $this->db->fetch_array ($result);

				return $rst;
			}
			else
			{
				throw new Exception ('ERROR: No se pudo realizar la busqueda en appgral.person.');
			}
		}
		else
		{
			throw new Exception ('ERROR: El person es obligatorio!!!.');
		}
	}

	/**
	 * En base al person del alumno obtiene la foto
	 *
	 * @param int $person
	 *
	 * @return string url de la foto
	 */
	public function get_Photo($person)
	{
		$foto1 = substr ($person, -1, 1);

		$foto2 = substr ($person, -2, 1);

		$foto3 = substr ($person, -3, 1);

		$url_foto = 'http://roma2.usal.edu.ar/FotosPerson/' . $foto1 . '/' . $foto2 . '/' . $foto3 . '/' . $person . '.jpg';

		if (getimagesize ($url_foto))
		{

			$url_foto = '/FotosPerson/' . $foto1 . '/' . $foto2 . '/' . $foto3 . '/' . $person . '.jpg';
		}
		else
		{

			$url_foto = '/FotosPerson/sinfoto1.jpg';
		}

		return $url_foto;
	}

	/*
	 * ************************************************************************
	 * Funciones para la creacion de personas
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
		$resultado = true;
		// echo "****************************************";
		return;

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

			if (!$this->db->query ($sql))
			{
				throw new Exception ('error!');
			}

			// Recuperamos el person a utilizar
			$sql = "SELECT (lnum) lnum FROM appgral.lnumber" . $this->db_link . " WHERE classname = 'intersoft.appgral.schemas.appgral.Person'";

			if (!$result = $this->db->query ($sql))
			{
				throw new Exception ('error!');
			}

			$person = $this->db->fetch_array ($result);

			$person = $person['LNUM'];

			// Insertamos el documento en Perdoc
			$sql = "INSERT INTO appgral.perdoc" . $this->db_link . " (person, typdoc, docno ) VALUES (:person, :typdoc, :docno )";

			$parametros[0] = $person;
			$parametros[1] = $arrayDatosPersona['docTipo'];
			$parametros[2] = $arrayDatosPersona['docNumero'];

			if ($this->db->query ($sql, true, $parametros))
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
			$this->db->rollback ();
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
	 * @throws Exception
	 * @return boolean
	 */
	public function nuevoCatXPerson($arrayDatosPersona)
	{
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

					$i ++;
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

					$i ++;
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

					$i ++;
				}
			}
			if (isset ($arrayDatosPersona['fbaja']) and $arrayDatosPersona['fbaja'] != "")
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
				{
					$sqlCampos = $sqlCampos . ", fbaja";

					$sqlValores = $sqlValores . ", TO_DATE(:fbaja, 'YYYY-MM-DD')";

					$parametros[$i] = $arrayDatosPersona['fbaja'];

					$i ++;
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

					$i ++;
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

					$i ++;
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

					$i ++;
				}
			}

			$sql = "INSERT INTO appgral.catxperson" . $this->db_link . " (mtime";
			$sqlValores .= ")";

			$sql = $sql . $sqlCampos . $sqlValores;

			if ($this->db->query ($sql, true, $parametros))
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
			$this->db->rollback ();
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
	 * @throws Exception
	 * @return boolean
	 */
	public function updateCatXPerson($arrayDatosPersona)
	{
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
				$a ++;

				$campos .= ", categoria = :categoria";
			}

			if (isset ($arrayDatosPersona['fIngreso']) and $this->comprobarExisteDato ($arrayDatosPersona['fIngreso']))
			{
				$parametros[$a] = $arrayDatosPersona['fIngreso'];
				$a ++;

				$campos .= ", finicio = TO_DATE(:finicio, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['fbaja']) and $this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
			{
				$parametros[$a] = $arrayDatosPersona['fbaja'];
				$a ++;

				$campos .= ", fbaja = TO_DATE(:fbaja, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['legajo']) and $this->comprobarExisteDato ($arrayDatosPersona['legajo']))
			{
				$parametros[$a] = $arrayDatosPersona['legajo'];
				$a ++;

				$campos .= ", legajo = :legajo";
			}

			if (!isset ($_SESSION['person']) or $_SESSION['person'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer el person de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['person'];
				$a ++;

				$campos .= ", muid = :muid";
			}

			if (!isset ($_SESSION['app']) or $_SESSION['app'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer la app de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['app'];
				$a ++;

				$campos .= ", idaplicacion = :idaplicacion";
			}

			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			else
			{
				$parametros[$a] = $arrayDatosPersona['person'];
				$a ++;

				$wer = "AND person = :person";
			}

			if (isset ($arrayDatosPersona['categoria']) and $this->comprobarExisteDato ($arrayDatosPersona['categoria']))
			{
				$extraWhere = " AND LTRIM(LTRIM(categoria, '0')) = LTRIM(LTRIM(:categoria, '0')) ";
				$parametros[$a] = $arrayDatosPersona['categoria'];

				$a ++;
			}

			$sql = "UPDATE appgral.catxperson" . $this->db_link . " SET mtime = SYSDATE" . $campos . " WHERE 1=1 " . $wer . $extraWhere;

			if ($this->db->query ($sql, true, $parametros))
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
			$this->db->rollback ();
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
	 *
	 * @return boolean
	 */
	public function inserPerson($arrayDatosPersona)
	{
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

			$result = $this->db->query ($sql, true, $parametros);

			$persona = $this->db->fetch_array ($result);

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

				if ($this->db->query ($sqlNuevoPer, true, $parametros))
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
			$this->db->rollback ();
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
	 * @return number
	 */
	public function nuevaPersona($arrayDatosPersona)
	{
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
			$this->db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		$this->db->commit ();
		$resultado = $arrayDatosPersona['person'];

		return $resultado;
	}

	/**
	 * Setea los atributos con los datos recuperados de la tabla appgral.person en base al person pasado
	 *
	 * @name buscar_PersonXPerson
	 *
	 * @param int $person
	 *        	person a buscar
	 */
	public function buscar_PersonXPerson($person)
	{
		$sql = "SELECT * FROM appgral.person WHERE person = :person";

		$parametros[0] = $person;

		$result = $this->db->query ($sql, true, $parametros);

		if ($recu = $this->db->fetch_array ($result))
		{
			$this->setPerson ($recu['PERSON']);
			$this->setApellido ($recu['LNAME']);
			$this->setNombre ($recu['FNAME']);
			$this->setFechaNacimiento ($recu['BIRDATE']);
			$this->setNacionalidad ($recu['NATION']);
			$this->setSexo ($recu['SEX']);
			$this->setEstadoCivil ($recu['MARSTAT']);

			$this->setTipoNacionalidad ($recu['TNATION']);

			// $this->setTelefono ($recu['TELEP']);
		}
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
	 * @return string - codigo de la nacion
	 */
	private function recuNacion($nacion)
	{
		if (isset ($nacion))
		{
			$sql = "SELECT * FROM appgral.country WHERE TRIM(upper(country)) = TRIM(upper(:country)) OR TRIM(upper(descrip)) = TRIM(upper(:descrip)) OR TRIM(upper(nation)) = TRIM(upper(:nation))";

			$parametros = "";
			$parametros[0] = $nacion;
			$parametros[1] = $nacion;
			$parametros[2] = $nacion;

			$result = $this->db->query ($sql, true, $parametros);

			$pais = $this->db->fetch_array ($result);

			$nacion = $pais['COUNTRY'];
		}

		if (!isset ($nacion) or $nacion == "")
		{
			$nacion = 'ARG';
		}

		return $nacion;
	}

	/*
	 * *********************************************
	 * Iniciamos los getters and setters
	 * *********************************************
	 */
	/**
	 *
	 * @return string
	 */
	public function getApellido()
	{
		return $this->apellido;
	}

	/**
	 *
	 * @return string
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 *
	 * @return number
	 */
	public function getPerson()
	{
		return $this->person;
	}

	/**
	 *
	 * @return array
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 *
	 * @return number
	 */
	public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	 *
	 * @return string
	 */
	public function getFoto_persona()
	{
		return $this->foto_persona;
	}

	/**
	 *
	 * @return string
	 */
	public function getFechaNacimiento()
	{
		return $this->fechaNacimiento;
	}

	/**
	 *
	 * @return string
	 */
	public function getEstadoCivil()
	{
		return $this->estadoCivil;
	}

	/**
	 *
	 * @return string
	 */
	public function getNacionalidad()
	{
		return $this->nacionalidad;
	}

	/**
	 *
	 * @return number
	 */
	public function getTipoNacionalidad()
	{
		return $this->tipoNacionalidad;
	}

	/**
	 *
	 * @return string
	 */
	public function getSexo()
	{
		return $this->sexo;
	}

	/**
	 *
	 * @return array
	 */
	public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	 *
	 * @return string
	 */
	public function getEsposa()
	{
		return $this->esposa;
	}

	/**
	 *
	 * @return string
	 */
	public function getFamiliarACargo()
	{
		return $this->familiarACargo;
	}

	/**
	 *
	 * @return string
	 */
	public function getFamiliaNumerosa()
	{
		return $this->FamiliaNumerosa;
	}

	/**
	 *
	 * @return string
	 */
	public function getHijos()
	{
		return $this->hijos;
	}

	/**
	 *
	 * @return string
	 */
	public function getGuarderia()
	{
		return $this->guarderia;
	}

	/**
	 *
	 * @return string
	 */
	public function getHijoIncapasitado()
	{
		return $this->hijoIncapasitado;
	}

	/**
	 *
	 * @return string
	 */
	public function getPrenatal()
	{
		return $this->prenatal;
	}

	/**
	 *
	 * @return string
	 */
	public function getPreescolar()
	{
		return $this->preescolar;
	}

	/**
	 *
	 * @return string
	 */
	public function getEscuelaMedia()
	{
		return $this->escuelaMedia;
	}

	/**
	 *
	 * @return string
	 */
	public function getEscuelaPrimaria()
	{
		return $this->escuelaPrimaria;
	}

	/**
	 *
	 * @param string $apellido
	 */
	public function setApellido($apellido)
	{
		$this->apellido = $apellido;
	}

	/**
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	/**
	 *
	 * @param number $person
	 */
	public function setPerson($person)
	{
		$this->person = $person;
	}

	/**
	 *
	 * @param array $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 *
	 * @param number $telefono
	 */
	public function setTelefono($telefono)
	{
		$this->telefono = $telefono;
	}

	/**
	 *
	 * @param string $foto_persona
	 */
	public function setFoto_persona($foto_persona)
	{
		$this->foto_persona = $foto_persona;
	}

	/**
	 *
	 * @param string $fechaNacimiento
	 */
	public function setFechaNacimiento($fechaNacimiento)
	{
		$this->fechaNacimiento = $fechaNacimiento;
	}

	/**
	 *
	 * @param string $estadoCivil
	 */
	public function setEstadoCivil($estadoCivil)
	{
		$this->estadoCivil = $estadoCivil;
	}

	/**
	 *
	 * @param string $nacionalidad
	 */
	public function setNacionalidad($nacionalidad)
	{
		$this->nacionalidad = $nacionalidad;
	}

	/**
	 *
	 * @param number $tipoNacionalidad
	 */
	public function setTipoNacionalidad($tipoNacionalidad)
	{
		$this->tipoNacionalidad = $tipoNacionalidad;
	}

	/**
	 *
	 * @param string $sexo
	 */
	public function setSexo($sexo)
	{
		$this->sexo = $sexo;
	}

	/**
	 *
	 * @param
	 *        	Ambigous <array, Direcciones> $direccion
	 */
	public function setDireccion($direccion)
	{
		$this->direccion = $direccion;
	}

	/**
	 *
	 * @param string $esposa
	 */
	public function setEsposa($esposa)
	{
		$this->esposa = $esposa;
	}

	/**
	 *
	 * @param string $familiarACargo
	 */
	public function setFamiliarACargo($familiarACargo)
	{
		$this->familiarACargo = $familiarACargo;
	}

	/**
	 *
	 * @param string $FamiliaNumerosa
	 */
	public function setFamiliaNumerosa($FamiliaNumerosa)
	{
		$this->FamiliaNumerosa = $FamiliaNumerosa;
	}

	/**
	 *
	 * @param string $hijos
	 */
	public function setHijos($hijos)
	{
		$this->hijos = $hijos;
	}

	/**
	 *
	 * @param string $guarderia
	 */
	public function setGuarderia($guarderia)
	{
		$this->guarderia = $guarderia;
	}

	/**
	 *
	 * @param string $hijoIncapasitado
	 */
	public function setHijoIncapasitado($hijoIncapasitado)
	{
		$this->hijoIncapasitado = $hijoIncapasitado;
	}

	/**
	 *
	 * @param string $prenatal
	 */
	public function setPrenatal($prenatal)
	{
		$this->prenatal = $prenatal;
	}

	/**
	 *
	 * @param string $preescolar
	 */
	public function setPreescolar($preescolar)
	{
		$this->preescolar = $preescolar;
	}

	/**
	 *
	 * @param string $escuelaMedia
	 */
	public function setEscuelaMedia($escuelaMedia)
	{
		$this->escuelaMedia = $escuelaMedia;
	}

	/**
	 *
	 * @param string $escuelaPrimaria
	 */
	public function setEscuelaPrimaria($escuelaPrimaria)
	{
		$this->escuelaPrimaria = $escuelaPrimaria;
	}

	public function nuevaDireccion($direccion)
	{
		$this->direccion[] = $direccion;
	}

	/**
	 * Crea un nuevo objeto direccion y lo agrega al array de direcciones.
	 *
	 * @param array $datos
	 *        	los posibles valores aceptados para los indices del array son:
	 *        	tipo, pais, poldiv, city, calle, numero, codigoPostal, piso, departamento
	 */
	public function nuevaDireccionDatos($datos)
	{
		$tipo = 0;
		$pais = "";
		$poldiv = "";
		$city = "";
		$calle = "";
		$numero = 0;
		$codigoPostal = "";
		$piso = 0;
		$departamento = "";

		foreach ($datos as $clave => $valor)
		{
			if ($valor != "" && $valor != 0)
			{
				${$clave} = $valor;
			}
		}

		$this->direccion[] = new Direcciones ($tipo, $pais, $poldiv, $city, $calle, $numero, $codigoPostal, $piso, $departamento);
	}

	/**
	 * Acualiza los datos de una direccion de la lista.
	 * Para esto comprueba que la informacion pasada sea diferente de la existente para la direccion.
	 *
	 * @param int $index
	 *        	indice de la direccion en el array de direcciones
	 * @param array $datos
	 *        	array con los datos a updetear los posibles valores aceptados para los indices del array son:
	 *        	tipo, pais, poldiv, city, calle, numero, codigoPostal, piso, departamento
	 */
	public function uppdateDireccion($index, $datos)
	{
		if (isset ($datos['tipo']) && $datos['tipo'] != 0 && ($datos['tipo']) != $this->direccion[$index]->getTipo ())
		{
			$this->direccion[$index]->setTipo ($datos['tipo']);
		}
		if (isset ($datos['pais']) && $datos['pais'] != "" && ($datos['pais']) != $this->direccion[$index]->getPais ())
		{
			$this->direccion[$index]->setPais ($datos['pais']);
		}
		if (isset ($datos['poldiv']) && $datos['poldiv'] != "" && ($datos['poldiv']) != $this->direccion[$index]->getPoldiv ())
		{
			$this->direccion[$index]->setPoldiv ($datos['poldiv']);
		}
		if (isset ($datos['city']) && $datos['city'] != "" && ($datos['city']) != $this->direccion[$index]->getCity ())
		{
			$this->direccion[$index]->setCity ($datos['city']);
		}
		if (isset ($datos['calle']) && $datos['calle'] != "" && ($datos['calle']) != $this->direccion[$index]->getCalle ())
		{
			$this->direccion[$index]->setCalle ($datos['calle']);
		}
		if (isset ($datos['numero']) && $datos['numero'] != 0 && ($datos['numero']) != $this->direccion[$index]->getNumero ())
		{
			$this->direccion[$index]->setNumero ($datos['numero']);
		}
		if (isset ($datos['codigoPostal']) && $datos['codigoPostal'] != "" && ($datos['codigoPostal']) != $this->direccion[$index]->getCodigoPostal ())
		{
			$this->direccion[$index]->setCodigoPostal ($datos['codigoPostal']);
		}
		if (isset ($datos['piso']) && $datos['piso'] != 0 && ($datos['piso']) != $this->direccion[$index]->getPiso ())
		{
			$this->direccion[$index]->setPiso ($datos['piso']);
		}
		if (isset ($datos['departamento']) && $datos['departamento'] != "" && ($datos['departamento']) != $this->direccion[$index]->getDepartamento ())
		{
			$this->direccion[$index]->setDepartamento ($datos['departamento']);
		}
	}

	/**
	 * Busca el indice en la lista de una direccion pasandole por parametros la altura y el nombre de la calle.
	 *
	 * @param String $calle
	 *        	Nombre de la calle.
	 * @param int $numero
	 *        	Altura de la direccion.
	 * @return int Entero que representa el indice en el array.
	 */
	public function buscarDireccionIndex($calle, $numero)
	{
		foreach ($this->direccion as $clave => $valor)
		{
			if (strtolower ($valor->getCalle ()) == strtolower ($calle) && trim ($valor->getNumero ()) == trim ($numero))
			{
				return $clave;
			}
		}
	}

	/**
	 * Busca una direccion pasandole por parametros la altura y el nombre de la calle.
	 *
	 * @param String $calle
	 *        	Nombre de la calle.
	 * @param int $numero
	 *        	Altura de la direccion.
	 * @return int Entero que representa el indice en el array.
	 */
	public function buscarDireccion($calle, $numero)
	{
		foreach ($this->direccion as &$valor)
		{
			if (strtolower ($valor->getCalle ()) == strtolower ($calle) && trim ($valor->getNumero ()) == trim ($numero))
			{
				return $valor;
			}
		}
	}

	/**
	 *
	 * @return Credenciales el dato de la variable $credencial
	 */
	public function getCredencial()
	{
		return $this->credencial;
	}

	/**
	 *
	 * @param
	 *        	Credenciales a cargar en la variable $credencial
	 */
	public function setCredencial($credencial)
	{
		$this->credencial = $credencial;
	}

	/**
	 *
	 * @return array el dato de la variable $documentos
	 */
	public function getDocumentos()
	{
		return $this->documentos;
	}

	/**
	 *
	 * @param
	 *        	array a cargar en la variable $documentos
	 */
	public function setDocumentos($documentos)
	{
		$this->documentos = $documentos;
	}

	/**
	 *
	 * @param Documentos $documento
	 */
	public function agregar_documento($documento)
	{
		$this->documentos[] = $documento;
	}

	/**
	 * Crea un nuevo objeto documento en vase a los parametros pasados y lo agrega al array de documentos.
	 *
	 * @param string $tipo_doc
	 * @param int $nro_doc
	 */
	public function nuevo_documneto($tipo_doc, $nro_doc)
	{
		$this->documentos[] = new Documentos ($db, $doc_num, $doc_typ);
	}
}
?>
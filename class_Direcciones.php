<?php
/**
 * Archivo principal de la clase.
 */
/**
 * Documentacion del uso de la clase y sus funciones en proceso.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 16 nov. 2018
 * @name class_direccion.php
 * @version 0.1 version inicial del archivo.
 */
<<<<<<< HEAD
<<<<<<< HEAD
=======
use function Direcciones\cargar_db_apers;

>>>>>>> a4316ed67d0a6d263ad083f9bfaf9cab60c1768e
=======
>>>>>>> 64d744ce8a330698d08d30aafad83e3adcabecdd
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
 * totalHorasPerdidasAqui = 10
 *
 */
/**
 * Clase direcciones, maneja los parametros de las direcciones y agrupa las funciones necesarias para su alta baja y modificacion.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 16 nov. 2018
 * @name Direcciones
 * @version 0.1 version inicial.
 *
 */
class Direcciones
{
	/**
	 * Objeto que se encarga de las conecciones a la base de datos.
	 *
	 * @var object objeto inicializado del tipo class_db
	 */
	private $db;

	/**
	 * Tipo de direccion, corresponde a si es la direccion de residencia = 0 (datos que en la base comiensan con r), de nacimiento = 1 o profecional = 2
	 * Cabe la posibilidad de que en en futuro se creen nuevos tipos de direcciones.
	 *
	 * Si se llegaran a agregar tipos hay que recordar modificar las funciones correspondientes (Ej: getTipo_hf y setTipo).
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
	protected $pais = "";

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
	protected $calle = "";

	/**
	 * Numero de la direccion de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'NRO'
	 */
	protected $numero = 0;

	/**
	 * Piso de la direccion de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'PISO'
	 */
	protected $piso = 0;

	/**
	 * Dto de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'DEPTO'
	 */
	protected $departamento = "";

	/**
	 * Codigo postal de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CODPOS'
	 */
	protected $codigoPostal = "";

	/**
	 * Direccion en string que une calle numero y muchas veces depto en un solo campo.
	 *
	 * @todo lo ideal es tratarlo para que quede separado de la forma que corresponde
	 *
	 * @var string <Br>
	 *      @ubicacionBase sueldos.personal.domicilio - VARCHAR2(30 BYTE)
	 */
	protected $domicilio = "";

	// FIXME Falta agregar soporte para los datos de geolocalizacion. Para esto se van a utilizar los parametros latitud y longitud

	/**
	 * Funcion llamada en caso de hacer una imprecion por pantalla del objeto.
	 * Va a mostrar la direccion de la persona formateada de la forma CALLE NUMERO PISO DTO (CP) CIUDAD PROVINCIA PAIS
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->calle . " " . $this->numero . " " . (isset ($this->piso) ? $this->piso . " " : "") . (isset ($this->departamento) ? $this->departamento . " " : "") . (isset ($this->direCodPos) ? "(CP:" . $this->direCodPos . ") " : "") . $this->city . " " . $this->getPoldiv () . " " . $this->pais;
	}

	/**
	 * Constructos de la clase vacia.
	 * Lo unico que hace es crear la nueva coneccion a la base de datos.
	 */
	public function __construct()
	{
		$this->db = Conexion::openConnection ();
	}

	/**
	 * Constructor de la clase pasandole unicamente los parametros basicos.
	 *
	 * @param int $tipo
	 * @param String $pais
	 * @param String $poldiv
	 * @param String $city
	 */
	public function __construct1($tipo, $pais, $poldiv, $city)
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setPais ($pais);
		$this->setCity ($city);
	}

	/**
	 * Constructor de la clase pasandole unicamente los parametros completos.
	 *
	 * @param int $tipo
	 * @param String $pais
	 * @param String $poldiv
	 * @param String $city
	 * @param String $calle
	 * @param int $numero
	 * @param string $codigoPostal
	 *        	- opcional
	 * @param int $piso
	 *        	- opcional
	 * @param string $departamento
	 *        	- opcional
	 */
	public function __construct2($tipo, $pais, $poldiv, $city, $calle, $numero, $codigoPostal = "", $piso = 0, $departamento = "")
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setPais ($pais);
		$this->setCity ($city);
		$this->setCalle ($calle);
		$this->setNumero ($numero);

		if ($codigoPostal != "")
		{
			$this->setCodigoPostal ($codigoPostal);
		}
		if ($piso != 0)
		{
			$this->setPiso ($piso);
		}
		if ($departamento != "")
		{
			$this->setDepartamento ($departamento);
		}
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
		$buscar = array ();

		$buscar['person'] = $person;

		if ($tipo == 0)
		{
<<<<<<< HEAD
			$this->db->debug = true;
			$this->db->dieOnError = true;
			$this->db->mostrarErrores = true;

			$buscar['person'] = $person;

=======
>>>>>>> a4316ed67d0a6d263ad083f9bfaf9cab60c1768e
			if ($recu = $this->db->realizarSelect ("appgral.person", $buscar))
			{
				$this->setPais ($recu['RCOUNTRY']);
				$this->setPoldiv ($recu['RPOLDIV']);
				$this->setCity ($recu['RCITY']);
				$this->setDomicilio ($recu['ADDRESS']);

				$buscar['pattrib'] = 'DOMI';
				$campos = array ();
				$campos[] = 'SHORTDES';
				$campos[] = 'VAL';

<<<<<<< HEAD
				$buscar['pattrib'] = 'DOMI';

				if ($recu = $this->db->realizarSelectAll ("appgral.apers", $buscar))
				{

					print_r ("yyyyyyyy");
					print_r ($recu);
					// --PISO DOMI
					// --CALLE DOMI
					// --DEPTO DOMI
					// --NRO DOMI
					// --CODPOS DOMI
=======
				if ($recu = $this->db->realizarSelectAll ("appgral.apers", $buscar))
				{
					for($i = 0; $i <= (count ($recu['SHORTDES']) - 1); $i++)
					{
						if ($recu['SHORTDES'][$i] == 'PISO')
						{
							$this->setDirePiso ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'CALLE')
						{
							$this->setDireCalle ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'DEPTO')
						{
							$this->setDireNumero ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'NRO')
						{
							$this->setDireNumero ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'CODPOS')
						{
							$this->setDireCodPos ($recu['VAL'][$i]);
						}
					}
>>>>>>> a4316ed67d0a6d263ad083f9bfaf9cab60c1768e
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
				$this->setPais ($recu['COUNTRY']);
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

				$buscar['pattrib'] = 'DOMP';
				$campos = array ();
				$campos[] = 'SHORTDES';
				$campos[] = 'VAL';

				if ($recu = $this->db->realizarSelectAll ("appgral.apers", $buscar))
				{
					for($i = 0; $i <= (count ($recu['SHORTDES']) - 1); $i++)
					{
						if ($recu['SHORTDES'][$i] == 'PISO')
						{
							$this->setDirePiso ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'CALLE')
						{
							$this->setDireCalle ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'DEPTO')
						{
							$this->setDireNumero ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'NRO')
						{
							$this->setDireNumero ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'CODPOS')
						{
							$this->setDireCodPos ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'COUNTRY')
						{
							$this->setPais ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'POLDIV')
						{
							$this->setPoldiv ($recu['VAL'][$i]);
						}
						elseif ($recu['SHORTDES'][$i] == 'CITY')
						{
							$this->setCity ($recu['VAL'][$i]);
						}
					}
				}
			}
		}
	}

	/**
	 * Graba los datos modificados de la clase en la base de datos.
	 *
	 * @param int $person
	 * @throws Exception
	 */
	public function grabar_direccion($person)
	{
		$this->setTipo ($tipo);
		$buscar = array ();
		$buscar['person'] = $person;

		$datos = array ();

		if ($tipo == 0)
		{
			if ($recu = $this->db->realizarSelect ("appgral.person", $buscar))
			{
				if ($this->pais != $recu['RCOUNTRY'])
				{
					$datos['RCOUNTRY'] = $this->pais;
				}
				if ($this->poldiv != $recu['RPOLDIV'])
				{
					$datos['RPOLDIV'] = $this->poldiv;
				}
				if ($this->city != $recu['RCITY'])
				{
					$datos['RCITY'] = $this->city;
				}

				$buscar['pattrib'] = 'DOMI';
				$campos = array ();
				$campos[] = 'SHORTDES';
				$campos[] = 'VAL';

				$datos2 = array ();
				$datoViejo = array ();

				if ($recu = $this->db->realizarSelectAll ("appgral.apers", $buscar))
				{
					for($i = 0; $i <= (count ($recu['SHORTDES']) - 1); $i++)
					{
						if ($recu['SHORTDES'][$i] == 'PISO')
						{
							if ($recu['VAL'][$i] != $this->piso)
							{
								$datoViejo['PISO'] = $recu['VAL'][$i];
								$datos2['PISO'] = $this->piso;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'CALLE')
						{
							if ($recu['VAL'][$i] != $this->calle)
							{
								$datoViejo['CALLE'] = $recu['VAL'][$i];
								$datos2['CALLE'] = $this->calle;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'DEPTO')
						{
							if ($recu['VAL'][$i] != $this->departamento)
							{
								$datoViejo['DEPTO'] = $recu['VAL'][$i];
								$datos2['DEPTO'] = $this->departamento;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'NRO')
						{
							if ($recu['VAL'][$i] != $this->numero)
							{
								$datoViejo['NRO'] = $recu['VAL'][$i];
								$datos2['NRO'] = $this->numero;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'CODPOS')
						{
							if ($recu['VAL'][$i] != $this->direCodPos)
							{
								$datoViejo['CODPOS'] = $recu['VAL'][$i];
								$datos2['CODPOS'] = $this->direCodPos;
							}
						}
					}
				}

				cargar_db_apers ($person, $datos2, $datoViejo, 'DOMI', 'CODPOS', $this->direCodPos);
				cargar_db_apers ($person, $datos2, $datoViejo, 'DOMI', 'NRO', $this->numero);
				cargar_db_apers ($person, $datos2, $datoViejo, 'DOMI', 'DEPTO', $this->departamento);
				cargar_db_apers ($person, $datos2, $datoViejo, 'DOMI', 'CALLE', $this->calle);
				cargar_db_apers ($person, $datos2, $datoViejo, 'DOMI', 'PISO', $this->piso);
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

				if ($this->pais != $recu['COUNTRY'])
				{
					$datos['COUNTRY'] = $this->pais;
				}
				if ($this->poldiv != $recu['POLDIV'])
				{
					$datos['POLDIV'] = $this->poldiv;
				}
				if ($this->city != $recu['CITY'])
				{
					$datos['CITY'] = $this->city;
				}
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

				$datos2 = array ();
				$datoViejo = array ();

				$buscar['pattrib'] = 'DOMP';
				$campos = array ();
				$campos[] = 'SHORTDES';
				$campos[] = 'VAL';

				if ($recu = $this->db->realizarSelectAll ("appgral.apers", $buscar))
				{
					for($i = 0; $i <= (count ($recu['SHORTDES']) - 1); $i++)
					{
						if ($recu['SHORTDES'][$i] == 'PISO')
						{
							if ($recu['VAL'][$i] != $this->piso)
							{
								$datoViejo['PISO'] = $recu['VAL'][$i];
								$datos2['PISO'] = $this->piso;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'CALLE')
						{
							if ($recu['VAL'][$i] != $this->calle)
							{
								$datoViejo['CALLE'] = $recu['VAL'][$i];
								$datos2['CALLE'] = $this->calle;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'DEPTO')
						{
							if ($recu['VAL'][$i] != $this->departamento)
							{
								$datoViejo['DEPTO'] = $recu['VAL'][$i];
								$datos2['DEPTO'] = $this->departamento;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'NRO')
						{
							if ($recu['VAL'][$i] != $this->numero)
							{
								$datoViejo['NRO'] = $recu['VAL'][$i];
								$datos2['NRO'] = $this->numero;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'CODPOS')
						{
							if ($recu['VAL'][$i] != $this->direCodPos)
							{
								$datoViejo['CODPOS'] = $recu['VAL'][$i];
								$datos2['CODPOS'] = $this->direCodPos;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'COUNTRY')
						{
							if ($recu['VAL'][$i] != $this->pais)
							{
								$datoViejo['COUNTRY'] = $recu['VAL'][$i];
								$datos2['COUNTRY'] = $this->pais;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'POLDIV')
						{
							if ($recu['VAL'][$i] != $this->poldiv)
							{
								$datoViejo['POLDIV'] = $recu['VAL'][$i];
								$datos2['POLDIV'] = $this->poldiv;
							}
						}
						elseif ($recu['SHORTDES'][$i] == 'CITY')
						{
							if ($recu['VAL'][$i] != $this->city)
							{
								$datoViejo['CITY'] = $recu['VAL'][$i];
								$datos2['CITY'] = $this->city;
							}
						}
					}
				}
			}

			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'CODPOS', $this->direCodPos);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'NRO', $this->numero);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'DEPTO', $this->departamento);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'CALLE', $this->calle);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'PISO', $this->piso);

			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'CITY', $this->city);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'POLDIV', $this->poldiv);
			cargar_db_apers ($person, $datos2, $datoViejo, 'DOMP', 'COUNTRY', $this->pais);
		}

		if (!empty ($datos))
		{
			$this->db->realizarUpdate ($datos, "appgral.person", $buscar);
		}
	}

	/**
	 * Recive los datos cargados en la base, los catoja con los actuales de la clase y si corresponde realiza el insert o el update
	 *
	 * @param int $person
	 * @param array $datos2
	 * @param array $datoViejo
	 * @param string $pattrib
	 * @param string $shortdes
	 * @param string $val
	 */
	private function cargar_db_apers($person, $datos2, $datoViejo, $pattrib, $shortdes, $val)
	{
		$tabla = "appgral.apers";

		$data = array ();
		$where = array ();

		if (!isset ($datos2[$shortdes]) and $val != "")
		{
			$data['PERSON'] = $person;
			$data['PATTRIB'] = $pattrib;
			$data['SHORTDES'] = $shortdes;
			$data['VAL'] = $val;

			$this->db->realizarInsert ($data, $tabla);
		}
		elseif (isset ($datos2[$shortdes]) and $val != "" and ($val != $datoViejo[$shortdes]))
		{
			$where['PERSON'] = $person;
			$where['PATTRIB'] = $pattrib;
			$where['SHORTDES'] = $shortdes;

			$data['VAL'] = $val;

			$this->db->realizarUpdate ($data, $tabla, $where);
		}
	}

	/*
	 * ***********************************************************
	 * Gettes and Setters
	 * ************************************************************
	 */

	/**
	 * Retorna el tipo de direccion.
	 *
	 * @return int
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 * Retorna el tipo de direccion formateado para que sea entendible.
	 *
	 * @return String
	 * @throws Exception En caso de no encontrar el tipo.
	 */
	public function getTipo_hf()
	{
		if ($this->tipo == 0)
		{
			return "Direccion de residencia";
		}
		elseif ($this->tipo == 1)
		{
			return "Direccion de nacimiento";
		}
		elseif ($this->tipo == 2)
		{
			return "Direccion profecional";
		}
		else
		{
			throw new Exception ('Tipo no encontrado.');
		}
	}

	/**
	 * Carga el valor pasado en el parametro tipo.
	 * Siempre que el valor pasado este dentro de los permitidos.
	 *
	 * @param number $tipo
	 *        	por el momento puede ser: 0, 1 o 2.
	 * @throws Exception En caso de querer setear un tipo que no este establesido.
	 */
	public function setTipo($tipo)
	{
		if ($tipo == 0 or $tipo == 1 or $tipo == 2)
		{
			$this->tipo = $tipo;
		}
		else
		{
			throw new Exception ('Tipo no permitido.');
		}
	}

	/**
	 * Retorna el codigo de pais seteado.
	 *
	 * @return string
	 */
	public function getPais()
	{
		return $this->pais;
	}

	/**
	 * Retorna el nombre del pais recuperado de la tabla appgral.country para que sea entendible por la persona.
	 *
	 * @throws Exception En caso de no encontrar el valor en la tabla retorna un error.
	 * @return String Nombre del pais.
	 */
	public function getPais_hf()
	{
		$buscar = array ();
		$buscar[] = "descrip";

		$where = array ();
		$where['country'] = $this->pais;

		if ($pais = $this->db->realizarSelect ("appgral.country", $where, $buscar))
		{
			return $pais;
		}
		else
		{
			throw new Exception ('No se encontro la descripcion del pais.');
		}
	}

	/**
	 * Comprueba que el codigo del pais se encuentre en appgral.country en caso de encontrarlo realiza el seteo.
	 *
	 * @throws Exception En caso de no encontrar el valor en la tabla retorna un error.
	 * @param string $pais
	 *        	Codigo del pais a setear.
	 */
	public function setPais($pais)
	{
		$where = array ();
		$where['country'] = $this->pais;

		if ($this->db->realizarSelect ("appgral.country", $where))
		{
			$this->pais = $pais;
		}
		else
		{
			throw new Exception ('No se encontro el pais en la base.');
		}
	}

	/**
	 * Retorna el codigo de la provincia seteado.
	 *
	 * @return string
	 */
	public function getPoldiv()
	{
		return $this->poldiv;
	}

	/**
	 * Retorna el nombre de la provincia recuperado de la tabla appgral.poldiv para que sea entendible por la persona.
	 * Para hacerlo usa el parametro pais de la clase si esta definido, y ARG en caso de que no.
	 *
	 * @throws Exception Retorna error en caso de no encontrar la provincia en la base.
	 * @return String
	 */
	public function getPoldiv_hf()
	{
		$buscar = array ();
		$buscar[] = "descrip";

		$where = array ();
		if ($this->pais != "")
		{
			$where['country'] = $this->pais;
		}
		else
		{
			$where['country'] = 'ARG';
		}
		$where['poldiv'] = $this->poldiv;

		if ($provincia = $this->db->realizarSelect ("appgral.poldiv", $where, $buscar))
		{
			return $provincia;
		}
		else
		{
			throw new Exception ('No se encontro la descripcion de la provincia.');
		}
	}

	/**
	 * Comprueba que el dato pasado por parametro exista en la tabla appgral.poldiv para hacerlo usa el parametro pais de la clase si esta definido, y ARG en caso de que no.
	 *
	 * @param String $poldiv
	 *        	dato con el que setear el parametro
	 * @throws Exception en caso de que no se encuentre el dato.
	 */
	public function setPoldiv($poldiv)
	{
		$where = array ();
		if ($this->pais != "")
		{
			$where['country'] = $this->pais;
		}
		else
		{
			$where['country'] = 'ARG';
		}
		$where['poldiv'] = $this->poldiv;

		if ($this->db->realizarSelect ("appgral.poldiv", $where))
		{
			$this->poldiv = $poldiv;
		}
		else
		{
			throw new Exception ('No se encontro el dato suministrado en la base.');
		}
	}

	/**
	 * Retorna el codigo de la ciudad seteado.
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Retorna el nombre de la ciudad recuperado de la tabla appgral.city para que sea entendible por la persona.
	 * Para hacerlo usa el parametro pais de la clase si esta definido, y ARG en caso de que no.
	 * Y el parametro poldiv en caso de estar establecido.
	 *
	 * @throws Exception en caso de que no se encuentre el dato.
	 * @return String Con la descripcion de la ciudad.
	 */
	public function getCity_hf()
	{
		$buscar = array ();
		$buscar[] = "descrip";

		$where = array ();
		if ($this->pais != "")
		{
			$where['country'] = $this->pais;
		}
		else
		{
			$where['country'] = 'ARG';
		}
		if ($this->poldiv != "")
		{
			$where['poldiv'] = $this->poldiv;
		}

		$where['city'] = $this->city;

		if ($city = $this->db->realizarSelect ("appgral.city", $where, $buscar))
		{
			return $city;
		}
		else
		{
			throw new Exception ('No se encontro la descripcion de la ciudad.');
		}
	}

	/**
	 * Comprueba que el dato pasado por parametro exista en la tabla appgral.city para hacerlo usa el parametro pais de la clase si esta definido, y ARG en caso de que no.
	 * Y el parametro poldiv en caso de estar establecido.
	 *
	 * @param String $city
	 *        	dato con el que setear el parametro
	 * @throws Exception en caso de que no se encuentre el dato.
	 */
	public function setCity($city)
	{
		$where = array ();
		if ($this->pais != "")
		{
			$where['country'] = $this->pais;
		}
		else
		{
			$where['country'] = 'ARG';
		}
		if ($this->poldiv != "")
		{
			$where['poldiv'] = $this->poldiv;
		}

		$where['city'] = $this->city;

		if ($this->db->realizarSelect ("appgral.city", $where))
		{
			$this->city = $city;
		}
		else
		{
			throw new Exception ('No se encontro el dato suministrado en la base.');
		}
	}

	/**
	 * Retorna el valor del parametro calle.
	 *
	 * @return string
	 */
	public function getCalle()
	{
		return $this->calle;
	}

	/**
	 * Setea un nuevo valor en el parametro calle.
	 *
	 * @param string $calle
	 */
	public function setCalle($calle)
	{
		$this->calle = $calle;
	}

	/**
	 * Retorna el valor del parametro numero.
	 *
	 * @return int
	 */
	public function getNumero()
	{
		return $this->numero;
	}

	/**
	 * Comprueba si el parametro pasado es un numero y si esta todo OK setea numero con ese dato.
	 *
	 * @param int $numero
	 */
	public function setNumero($numero)
	{
		if (is_int ($numero))
		{
			$this->numero = $numero;
		}
		else
		{
			throw new Exception ('El dato debe ser un numero entero.');
		}
	}

	/**
	 * Retorna el valor del parametro piso.
	 *
	 * @return int
	 */
	public function getPiso()
	{
		return $this->piso;
	}

	/**
	 * Comprueba si el parametro pasado es un numero y si esta todo OK setea piso con ese dato.
	 *
	 * @param string $piso
	 */
	public function setPiso($piso)
	{
		if (is_int ($piso))
		{
			$this->piso = $piso;
		}
		else
		{
			throw new Exception ('El dato debe ser un numero entero.');
		}
	}

	/**
	 * Retorna el valor del parametro departamento.
	 *
	 * @return string
	 */
	public function getDepartamento()
	{
		return $this->departamento;
	}

	/**
	 * Setea el valor del parametro departamento.
	 *
	 * @param string $departamento
	 */
	public function setDepartamento($departamento)
	{
		$this->departamento = $departamento;
	}

	/**
	 * Retorna el valor del parametro codigoPostal.
	 *
	 * @return string
	 */
	public function getCodigoPostal()
	{
		return $this->codigoPostal;
	}

	/**
	 * Setea el valor del parametro codigoPostal.
	 * XXX Creo que este parametro tambien deberia chequearse en la base de datos.
	 *
	 * @param string $codigoPostal
	 */
	public function setCodigoPostal($codigoPostal)
	{
		$this->codigoPostal = $codigoPostal;
	}

	/**
	 * Retorna el valor del parametro domicilio.
	 *
	 * @return string
	 */
	public function getDomicilio()
	{
		return $this->domicilio;
	}

	/**
	 * Setea el valor del parametro domicilio.
	 *
	 * @param string $domicilio
	 */
	public function setDomicilio($domicilio)
	{
		$this->domicilio = $domicilio;
	}
}
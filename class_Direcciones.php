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

	public function __toString()
	{
		return $this->calle . " " . $this->numero . " " . (isset ($this->piso) ? $this->piso . " " : "") . (isset ($this->departamento) ? $this->departamento . " " : "") . (isset ($this->direCodPos) ? "(CP:" . $this->direCodPos . ") " : "") . $this->city . " " . $this->getPoldiv () . " " . $this->pais;
	}

	public function __construct()
	{
		$this->db = Conexion::openConnection ();
	}

	public function __construct1($tipo, $pais, $poldiv, $city)
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setPais ($pais);
		$this->setCity ($city);
	}

	public function __construct2($tipo, $pais, $poldiv, $city, $calle, $numero, $codigoPostal = "", $piso = "", $departamento = "")
	{
		$this->db = Conexion::openConnection ();

		$this->setTipo ($tipo);
		$this->setPoldiv ($poldiv);
		$this->setPais ($pais);
		$this->setCity ($city);
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
	 *
	 * @return number
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 *
	 * @param number $tipo
	 */
	public function setTipo($tipo)
	{
		$this->tipo = $tipo;
	}

	/**
	 *
	 * @return string
	 */
	public function getPais()
	{
		return $this->pais;
	}

	/**
	 *
	 * @param string $pais
	 */
	public function setPais($pais)
	{
		$this->pais = $pais;
	}

	/**
	 *
	 * @return string
	 */
	public function getPoldiv()
	{
		return $this->poldiv;
	}

	/**
	 *
	 * @param string $poldiv
	 */
	public function setPoldiv($poldiv)
	{
		$this->poldiv = $poldiv;
	}

	/**
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 *
	 * @param string $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 *
	 * @return string
	 */
	public function getCalle()
	{
		return $this->calle;
	}

	/**
	 *
	 * @param string $calle
	 */
	public function setCalle($calle)
	{
		$this->calle = $calle;
	}

	/**
	 *
	 * @return string
	 */
	public function getNumero()
	{
		return $this->numero;
	}

	/**
	 *
	 * @param string $numero
	 */
	public function setNumero($numero)
	{
		$this->numero = $numero;
	}

	/**
	 *
	 * @return string
	 */
	public function getPiso()
	{
		return $this->piso;
	}

	/**
	 *
	 * @param string $piso
	 */
	public function setPiso($piso)
	{
		$this->piso = $piso;
	}

	/**
	 *
	 * @return string
	 */
	public function getDepartamento()
	{
		return $this->departamento;
	}

	/**
	 *
	 * @param string $departamento
	 */
	public function setDepartamento($departamento)
	{
		$this->departamento = $departamento;
	}

	/**
	 *
	 * @return string
	 */
	public function getCodigoPostal()
	{
		return $this->codigoPostal;
	}

	/**
	 *
	 * @param string $codigoPostal
	 */
	public function setCodigoPostal($codigoPostal)
	{
		$this->codigoPostal = $codigoPostal;
	}

	/**
	 *
	 * @return string
	 */
	public function getDomicilio()
	{
		return $this->domicilio;
	}

	/**
	 *
	 * @param string $domicilio
	 */
	public function setDomicilio($domicilio)
	{
		$this->domicilio = $domicilio;
	}
}
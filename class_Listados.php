<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 16 nov. 2018
 * @lenguage PHP
 * @name Personal.php
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
final class listados
{

	/**
	 * Realiza un listado con todos los persons que cumplan una determinada condicion.
	 *
	 * @name listarPerson
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datos
	 *        	- Datos extra con los que realizar la busqueda, puede ser cualquier dato de la tabla, con el indice igual al nombre del campo en minuscula.
	 * @return int[] - array con todos los persons resultantes
	 */
	public function listarPerson($db, $datos = "")
	{
		if (isset ($datos['person']) and $datos['person'] != "")
		{
			$where[] = " person = :person ";
			$parametros[] = $datos['person'];
		}

		if (isset ($datos['lname']) and $datos['lname'] != "")
		{
			$where[] = " lname = :lname ";
			$parametros[] = $datos['lname'];
		}
		if (isset ($datos['fname']) and $datos['fname'] != "")
		{
			$where[] = " fname = :fname ";
			$parametros[] = $datos['fname'];
		}
		if (isset ($datos['country']) and $datos['country'] != "")
		{
			$where[] = " country = :country ";
			$parametros[] = $datos['country'];
		}
		if (isset ($datos['poldiv']) and $datos['poldiv'] != "")
		{
			$where[] = " poldiv = :poldiv ";
			$parametros[] = $datos['poldiv'];
		}
		if (isset ($datos['city']) and $datos['city'] != "")
		{
			$where[] = " city = :city ";
			$parametros[] = $datos['city'];
		}
		if (isset ($datos['birdate']) and $datos['birdate'] != "")
		{
			$where[] = " birdate = :birdate ";
			$parametros[] = $datos['birdate'];
		}
		if (isset ($datos['nation']) and $datos['nation'] != "")
		{
			$where[] = " nation = :nation ";
			$parametros[] = $datos['nation'];
		}
		if (isset ($datos['sex']) and $datos['sex'] != "")
		{
			$where[] = " sex = :sex ";
			$parametros[] = $datos['sex'];
		}
		if (isset ($datos['marstat']) and $datos['marstat'] != "")
		{
			$where[] = " marstat = :marstat ";
			$parametros[] = $datos['marstat'];
		}
		if (isset ($datos['address']) and $datos['address'] != "")
		{
			$where[] = " address = :address ";
			$parametros[] = $datos['address'];
		}
		if (isset ($datos['rcountry']) and $datos['rcountry'] != "")
		{
			$where[] = " rcountry = :rcountry ";
			$parametros[] = $datos['rcountry'];
		}
		if (isset ($datos['rpoldiv']) and $datos['rpoldiv'] != "")
		{
			$where[] = " rpoldiv = :rpoldiv ";
			$parametros[] = $datos['rpoldiv'];
		}
		if (isset ($datos['rcity']) and $datos['rcity'] != "")
		{
			$where[] = " rcity = :rcity ";
			$parametros[] = $datos['rcity'];
		}
		if (isset ($datos['telep']) and $datos['telep'] != "")
		{
			$where[] = " telep = :telep ";
			$parametros[] = $datos['telep'];
		}
		if (isset ($datos['active']) and $datos['active'] != "")
		{
			$where[] = " active = :active ";
			$parametros[] = $datos['active'];
		}
		if (isset ($datos['tnation']) and $datos['tnation'] != "")
		{
			$where[] = " tnation = :tnation ";
			$parametros[] = $datos['tnation'];
		}
		if (isset ($datos['incountrysince']) and $datos['incountrysince'] != "")
		{
			$where[] = " incountrysince = :incountrysince ";
			$parametros[] = $datos['incountrysince'];
		}
		if (isset ($datos['religion']) and $datos['religion'] != "")
		{
			$where[] = " religion = :religion ";
			$parametros[] = $datos['religion'];
		}
		if (isset ($datos['qbrother']) and $datos['qbrother'] != "")
		{
			$where[] = " qbrother = :qbrother ";
			$parametros[] = $datos['qbrother'];
		}
		if (isset ($datos['qson']) and $datos['qson'] != "")
		{
			$where[] = " qson = :qson ";
			$parametros[] = $datos['qson'];
		}

		if ($where != "")
		{
			$where = implode (" AND ", $where);

			$where = " AND " . $where;
		}

		$sql = "SELECT person FROM appgral.person" . $this->db_link . " WHERE 1 = 1 " . $where;

		$result = $db->query ($sql, true, $parametros);

		$rst = $db->fetch_all ($result);

		return $rst;

		// if (1 == 1)
		// {
		// }
		// else
		// {
		// throw new Exception ('ERROR: No se pudo realizar la insercion en sueldos.valorremu.');
		// }
	}

	/**
	 * Realiza un listado con todos los persons que cumplan una determinada condicion de carrera o facultad.
	 *
	 * @name listarPersonFacu
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datos
	 *        	- Datos extra con los que realizar la busqueda, puede ser cualquier dato de la tabla, con el indice igual al nombre del campo en minuscula.
	 * @return int[] - array con todos los persons resultantes
	 */
	public function listarPersonFacu($db, $datos = "")
	{
		if (isset ($datos['facu']) and $datos['facu'] != "")
		{
			$where[] = " career.facu = :facu ";
			$parametros[] = $datos['facu'];
		}
		if (isset ($datos['career']) and $datos['career'] != "")
		{
			$where[] = " carstu.career = :career ";
			$parametros[] = $datos['career'];
		}
		if (isset ($datos['inscdate']) and $datos['inscdate'] != "")
		{
			$where[] = " to_char(carstu.inscdate, 'YYYY') = :inscdate ";
			$parametros[] = $datos['inscdate'];
		}

		if ($where != "")
		{
			$where = implode (" AND ", $where);

			$where = " AND " . $where;
		}

		$sql = " SELECT carstu.student person FROM studentc.carstu" . $this->db_link . " LEFT JOIN studentc.career ON carstu.career=career.code LEFT JOIN studentc.facu ON career.facu=facu.code WHERE 1 = 1 " . $where;

		$result = $db->query ($sql, true, $parametros);

		$rst = $db->fetch_all ($result);

		return $rst;
	}

	/**
	 * Devuelve un array con los valores de apers usando los SHORTDES como claves.
	 *
	 * @name buscarAppers
	 * @param mixed[] $datosAUsar
	 *        	- Requiere que dentro de los datos enviados este si o si el person de la persona
	 *
	 * @return Array
	 */
	public function buscarAppers($datosAUsar)
	{
		global $db;

		if ($datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.apers" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, true, $parametros);

			while ($recu = $db->fetch_array ($result))
			{
				$persona[$recu['SHORTDES']] = $recu['VAL'];
			}
		}
		else
		{
			throw new Exception ('ERROR: El person es obligatorio.');
		}
		if ($persona != "")
		{
			return $persona;
		}
	}

	/**
	 * Devuelve los datos de la tabla person, la busqueda puede realizarse por nro de person, por nombre, apellido o nombre y apellido
	 * En caso de que no recupere ningun dato devuelve 0
	 *
	 * @name buscarPerson
	 *
	 * @param string[] $datosAUsar
	 *        	- 'person' o 'realname'
	 *
	 * @return array - 'person' 'lname' 'fname' 'country' 'poldiv' 'city' 'birdate' 'nation' 'sex' 'marstat' 'address' 'rcountry' 'rpoldiv' 'rcity' 'telep' 'active' 'tnation' 'incountrysince' 'religion' 'qbrother' 'qson'
	 */
	public function buscarPerson($datosAUsar)
	{
		global $db;

		// Comprovamos si pasaron el person para realizar la busqueda
		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, true, $parametros);
		}
		// En caso de que hayan mandado el nombre o el apellido
		else if ((isset ($datosAUsar['realname']) and $datosAUsar['realname'] != "") or (isset ($datosAUsar['apellido']) and $datosAUsar['apellido'] != "") or (isset ($datosAUsar['nombreCompleto']) and $datosAUsar['nombreCompleto'] != ""))
		{
			if ((isset ($datosAUsar['realname'])) and ($datosAUsar['realname'] != ""))
			{
				$realname = $datosAUsar['realname'];
			}
			else
			{
				$realname = "";
			}

			if ((isset ($datosAUsar['apellido'])) and ($datosAUsar['apellido'] != ""))
			{
				$apellido = $datosAUsar['apellido'];
			}
			else
			{
				$apellido = "";
			}

			if ((isset ($datosAUsar['nombreCompleto'])) and ($datosAUsar['nombreCompleto'] != ""))
			{
				$nombreCompleto = $datosAUsar['nombreCompleto'];
			}
			else
			{
				$nombreCompleto = "";
			}

			if ($nombreCompleto == "")
			{
				$nombreCompleto = $realname . " " . $apellido;
			}

			$nombreCompleto = strtoupper ($nombreCompleto);
			$nombreCompleto = htmlentities ($nombreCompleto);
			$nombreCompleto = str_replace (" ", "%", $nombreCompleto);
			$nombreCompleto = "%" . $nombreCompleto . "%";

			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE (UPPER(lname||fname) LIKE UPPER(:nombreCompleto)) OR (UPPER(fname||lname) LIKE UPPER(:nombreCompleto))";

			// $sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $nombreCompleto;
			$parametros[1] = $nombreCompleto;

			$result = $db->query ($sql, true, $parametros);
		}

		$i = 0;

		while ($recu = $db->fetch_array ($result))
		{

			$persona[$i]['person'] = $recu['PERSON'];
			$persona[$i]['lname'] = $recu['LNAME'];
			$persona[$i]['fname'] = $recu['FNAME'];
			$persona[$i]['country'] = $recu['COUNTRY'];
			$persona[$i]['poldiv'] = $recu['POLDIV'];
			$persona[$i]['city'] = $recu['CITY'];
			$persona[$i]['birdate'] = $recu['BIRDATE'];
			$persona[$i]['nation'] = $recu['NATION'];
			$persona[$i]['sex'] = $recu['SEX'];
			$persona[$i]['marstat'] = $recu['MARSTAT'];
			$persona[$i]['address'] = $recu['ADDRESS'];
			$persona[$i]['rcountry'] = $recu['RCOUNTRY'];
			$persona[$i]['rpoldiv'] = $recu['RPOLDIV'];
			$persona[$i]['rcity'] = $recu['RCITY'];
			$persona[$i]['telep'] = $recu['TELEP'];
			$persona[$i]['active'] = $recu['ACTIVE'];
			$persona[$i]['tnation'] = $recu['TNATION'];
			$persona[$i]['incountrysince'] = $recu['INCOUNTRYSINCE'];
			$persona[$i]['religion'] = $recu['RELIGION'];
			$persona[$i]['qbrother'] = $recu['QBROTHER'];
			$persona[$i]['qson'] = $recu['QSON'];

			$i = $i + 1;
		}

		if (isset ($persona) and $persona != "")
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Recibe un array con el person o el numero de documento y devuelve otro con los datos de la tabla perdoc
	 *
	 * @name buscarPerdoc
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'cuil'
	 * @return mixed[] - con los siguientes parametros: person, typdoc, docNumero
	 */
	public function buscarPerdoc($datosAUsar)
	{
		global $db;
		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, true, $parametros);

			if ($result == "")
			{
				$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE person = :person";

				$parametros[0] = $person;

				$result = $db->query ($sql, true, $parametros);
			}
		}
		else if (isset ($datosAUsar['docNumero']) and $datosAUsar['docNumero'] != "")
		{
			$docNumero = $datosAUsar['docNumero'];
			$docNumero = str_replace ('.', '', $docNumero);
			$docNumero = str_replace (' ', '', $docNumero);

			$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

			$parametros[0] = $docNumero;

			$result = $db->query ($sql, true, $parametros);

			if ($result == "")
			{
				$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

				$parametros[0] = $docNumero;

				$result = $db->query ($sql, true, $parametros);
			}
		}
		else if (isset ($datosAUsar['cuil']) and $datosAUsar['cuil'] != "")
		{
			$docNumero = substr ($datosAUsar['cuil'], 2, -1);

			$docNumero = str_replace ('.', '', $docNumero);
			$docNumero = str_replace (' ', '', $docNumero);

			$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

			$parametros[0] = $docNumero;

			$result = $db->query ($sql, true, $parametros);

			if ($result == "")
			{
				$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

				$parametros[0] = $docNumero;

				$result = $db->query ($sql, true, $parametros);
			}
		}
		else
		{
			throw new Exception ('O person o el numero de doc deben contener algun valor ! ' . ":" . $datosAUsar['person'] . ":D:" . $datosAUsar['docNumero'] . ":");
		}

		$i = 0;

		while ($recu = $db->fetch_array ($result))
		{
			$persona[$i]['person'] = $recu['PERSON'];
			$persona[$i]['typdoc'] = $recu['TYPDOC'];
			$persona[$i]['docNumero'] = $recu['DOCNO'];

			$i = $i + 1;
		}

		if (isset ($persona) and $persona != "")
		{
			$resultado = $persona;
		}
		else
		{
			return 0;
		}
		return $resultado;
	}

	/**
	 * Devuelve todos los datos relacionados a la tarjeta de la persona.
	 *
	 * @name buscarTargeta
	 *
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'num_tarj'
	 * @return mixed[] - 'person' 'estadocredencialca' 'email' 'codigoisic' 'nrodechip' 'sca_fecha' 'sca_categoria' 'sca_lote' 'tipo_formulario' 'nrodechip_dec' 'fecha_chip' 'motivo' 'tipo_credencial'
	 */
	public function buscarTargeta($datosAUsar)
	{
		global $db;

		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.personca" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, true, $parametros);
		}
		else if (isset ($datosAUsar['num_tarj']) and $datosAUsar['num_tarj'] != "")
		{
			$num_tarj = $datosAUsar['num_tarj'];

			$sql = "SELECT * FROM appgral.personca" . $this->db_link . " WHERE (nrodechip LIKE :num_tarj) OR (nrodechip_dec LIKE :num_tarj)";

			$parametros[0] = $num_tarj;
			$parametros[1] = $num_tarj;

			$result = $db->query ($sql, true, $parametros);
		}

		$i = 0;

		while ($recu = $db->fetch_array ($result))
		{
			$personca[$i]['person'] = $recu['PERSON'];
			$personca[$i]['estadocredencialca'] = $recu['ESTADOCREDENCIALCA'];
			$personca[$i]['email'] = $recu['EMAIL'];
			$personca[$i]['codigoisic'] = $recu['CODIGOISIC'];
			$personca[$i]['nrodechip'] = $recu['NRODECHIP'];
			$personca[$i]['sca_fecha'] = $recu['SCA_FECHA'];
			$personca[$i]['sca_categoria'] = $recu['SCA_CATEGORIA'];
			$personca[$i]['sca_lote'] = $recu['SCA_LOTE'];
			$personca[$i]['tipo_formulario'] = $recu['TIPO_FORMULARIO'];
			$personca[$i]['nrodechip_dec'] = $recu['NRODECHIP_DEC'];
			$personca[$i]['fecha_chip'] = $recu['FECHA_CHIP'];
			$personca[$i]['motivo'] = $recu['MOTIVO'];
			$personca[$i]['tipo_credencial'] = $recu['TIPO_CREDENCIAL'];

			if ($recu['TIPO_CREDENCIAL'] == 1)
			{
				$personca[$i]['descTipoCreden'] = "Tarjeta USAL-ISIC-GALICIA";
			}
			elseif ($recu['TIPO_CREDENCIAL'] == 2)
			{
				$personca[$i]['descTipoCreden'] = "Tarjeta blanca";
			}

			$estadocredenca = $recu['ESTADOCREDENCIALCA'];

			$sql = "SELECT * FROM interfaz.estadocredenca" . $this->db_link . " WHERE estadocredenca = :estadocredenca";

			$parametros[0] = $estadocredenca;

			$result = $db->query ($sql, true, $parametros);

			$tarjeta = $db->fetch_array ($result);

			$estadoCa = $tarjeta['ESTADOCREDENCA'] . " - " . $tarjeta['DESCRIP'];

			$personca[$i]['estadocredenca'] = $estadoCa;

			$i = $i + 1;
		}

		if ($personca != "")
		{
			return $personca;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Busca los datos de la tabla usuario_web para una persona X.
	 *
	 * @name buscarUsuarioWeb -
	 *
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'altaDeLaCuenta' o 'vtoDeLaCuenta' o 'cuenta' o 'idCuenta'
	 * @return array - 'docno' 'docnoCuenta' 'tipoDocCuenta' 'cuenta' 'nombreCompleto' 'fecha_altaCuenta' 'fecha_vencCuenta' 'fecha_bajaCuenta' 'frase' 'email' 'uid_cCuenta' 'uid_mCuenta' 'fecha_mCuenta' 'academicoCuenta' 'administrativoCuenta' 'alumnoCuenta' 'docenteCuenta' 'genericoCuenta' 'operadorCuenta' 'externoCuenta' 'ultimocambioclaveCuenta' 'ultimoacceso' 'ultimaaplicacion' 'ultimoip' 'person'
	 */
	public function buscarUsuarioWeb($datosAUsar)
	{
		global $db;

		$i = 0;
		$f = 0;

		$sql = "SELECT * FROM portal.usuario_web WHERE 1=:uno ";

		$parametros[$f] = "1";

		if (isset ($datosAUsar['person']) and ($datosAUsar['person'] != ""))
		{
			$person = $datosAUsar['person'];

			$sql = $sql . " AND person = :person";

			$f = $f + 1;

			$parametros[$f] = $person;
		}
		if (isset ($datosAUsar['docNumero']) and ($datosAUsar['docNumero'] != ""))
		{
			$docNumero = $datosAUsar['docNumero'];
			$docNumero = str_replace ('.', '', $docNumero);
			$docNumero = str_replace (' ', '', $docNumero);

			$docNumero = "%" . $docNumero . "%";

			$sql = $sql . " AND LTRIM(LTRIM(nro_doc, '0')) LIKE LTRIM(LTRIM(:nro_doc, '0')) ";

			$f = $f + 1;

			$parametros[$f] = $docNumero;
		}
		if (isset ($datosAUsar['altaDeLaCuenta']) and ($datosAUsar['altaDeLaCuenta'] != ""))
		{
			$altaDeLaCuenta = $datosAUsar['altaDeLaCuenta'];
			$altaDeLaCuenta = htmlentities ($altaDeLaCuenta);

			$sql = $sql . " AND TO_CHAR(fecha_alta, 'yyyy-mm-dd') = :altaDeLaCuenta ";

			$f = $f + 1;

			$parametros[$f] = $altaDeLaCuenta;
		}
		if (isset ($datosAUsar['vtoDeLaCuenta']) and ($datosAUsar['vtoDeLaCuenta'] != ""))
		{
			$vtoDeLaCuenta = $datosAUsar['vtoDeLaCuenta'];
			$vtoDeLaCuenta = htmlentities ($vtoDeLaCuenta);

			$sql = $sql . " AND TO_CHAR(fecha_venc, 'yyyy-mm-dd') = :vtoDeLaCuenta ";

			$f = $f + 1;

			$parametros[$f] = $vtoDeLaCuenta;
		}
		if (isset ($datosAUsar['cuenta']) and ($datosAUsar['cuenta'] != ""))
		{
			$cuenta = $datosAUsar['cuenta'];
			$cuenta = htmlentities ($cuenta);
			$cuenta = "%" . $cuenta . "%";

			$sql = $sql . " AND UPPER(cuenta) LIKE UPPER(:cuenta) ";

			$f = $f + 1;

			$parametros[$f] = $cuenta;
		}
		if (isset ($datosAUsar['idCuenta']) and ($datosAUsar['idCuenta'] != ""))
		{

			$id = $datosAUsar['idCuenta'];

			$sql = $sql . " AND id = :id ";

			$f = $f + 1;

			$parametros[$f] = $id;
		}

		$result = $db->query ($sql, true, $parametros);

		while ($recu = $db->fetch_array ($result))
		{
			$persona[$i]['docNumero'] = $recu['NRO_DOC'];
			$persona[$i]['docnoCuenta'] = $recu['NRO_DOC'];
			$persona[$i]['tipoDocCuenta'] = $recu['TIPO_DOCUMENTO'];
			$persona[$i]['cuenta'] = $recu['CUENTA'];
			$persona[$i]['nombreCompleto'] = $recu['NOMBRE'];
			$persona[$i]['fecha_altaCuenta'] = $recu['FECHA_ALTA'];
			$persona[$i]['fecha_vencCuenta'] = $recu['FECHA_VENC'];
			$persona[$i]['fecha_bajaCuenta'] = $recu['FECHA_BAJA'];
			$persona[$i]['frase'] = $recu['FRASE'];
			$persona[$i]['email'] = $recu['EMAIL'];
			$persona[$i]['uid_cCuenta'] = $recu['UID_C'];
			$persona[$i]['uid_mCuenta'] = $recu['UID_M'];
			$persona[$i]['fecha_mCuenta'] = $recu['FECHA_M'];
			$persona[$i]['academicoCuenta'] = $recu['ACADEMICO'];
			$persona[$i]['administrativoCuenta'] = $recu['ADMINISTRATIVO'];
			$persona[$i]['alumnoCuenta'] = $recu['ALUMNO'];
			$persona[$i]['docenteCuenta'] = $recu['DOCENTE'];
			$persona[$i]['genericoCuenta'] = $recu['GENERICO'];
			$persona[$i]['operadorCuenta'] = $recu['OPERADOR'];
			$persona[$i]['externoCuenta'] = $recu['EXTERNO'];
			$persona[$i]['ultimocambioclaveCuenta'] = $recu['ULTIMOCAMBIOCLAVE'];
			$persona[$i]['ultimoacceso'] = $recu['ULTIMOACCESO'];
			$persona[$i]['ultimaaplicacion'] = $recu['ULTIMAAPLICACION'];
			$persona[$i]['ultimoip'] = $recu['ULTIMOIP'];
			$persona[$i]['person'] = $recu['PERSON'];

			$i = $i + 1;
		}

		if ($persona != "")
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Recibe algun parametro de busqueda y en base a eso recupera los datos de esa persona en las diferentes tablas
	 *
	 * @name datosPersona
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'realname' o 'apellido' o 'nombreCompleto' o 'num_tarj'
	 * @return mixed[] -
	 */
	public function datosPersona($datosAUsar)
	{
		if (isset ($datosAUsar['person']) and ($datosAUsar['person'] != ""))
		{
			$person = $this->buscarPerson ($datosAUsar);
			$perdoc = $this->buscarPerdoc ($datosAUsar);
			$personca = $this->buscarTargeta ($datosAUsar);
			$apers = $this->buscarAppers ($datosAUsar);
			$cuentaWeb = $this->buscarUsuarioWeb ($perdoc);
		}
		elseif (isset ($datosAUsar['docNumero']) and ($datosAUsar['docNumero'] != ""))
		{
			$perdoc = $this->buscarPerdoc ($datosAUsar);

			for($i = 0; $i < count ($perdoc); $i ++)
			{
				$persons = $this->buscarPerson ($perdoc[$i]);
				$person[$i] = $persons[0];

				$personcas = $this->buscarTargeta ($perdoc[$i]);
				$personca[$i] = $personcas[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($perdoc[$i]);
			}
		}
		elseif ((isset ($datosAUsar['realname']) and $datosAUsar['realname'] != "") or (isset ($datosAUsar['apellido']) and $datosAUsar['apellido'] != "") or (isset ($datosAUsar['nombreCompleto']) and $datosAUsar['nombreCompleto'] != ""))
		{
			$person = $this->buscarPerson ($datosAUsar);

			for($i = 0; $i < count ($person); $i ++)
			{
				$perdocs = $this->buscarPerdoc ($person[$i]);
				$perdoc[$i] = $perdocs[0];

				$personcas = $this->buscarTargeta ($person[$i]);
				$personca[$i] = $personcas[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($person[$i]);
			}
		}
		elseif (isset ($datosAUsar['num_tarj']) and $datosAUsar['num_tarj'] != "")
		{
			$personca = $this->buscarTargeta ($datosAUsar);

			for($i = 0; $i < count ($personca); $i ++)
			{
				$persons = $this->buscarPerson ($personca[$i]);
				$person[$i] = $persons[0];

				$perdocs = $this->buscarPerdoc ($personca[$i]);
				$perdoc[$i] = $perdocs[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($personca[$i]);
			}
		}

		for($i = 0; $i < count ($perdoc); $i ++)
		{
			$persona[$i]['person'] = $perdoc[$i]['person'];
			$persona[$i]['typdoc'] = $perdoc[$i]['typdoc'];
			$persona[$i]['docNumero'] = $perdoc[$i]['docNumero'];
		}

		for($i = 0; $i < count ($person); $i ++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $person[$i]['person'];
			}
			$persona[$i]['realname'] = $person[$i]['lname'];
			$persona[$i]['apellido'] = $person[$i]['fname'];
			$persona[$i]['country'] = $person[$i]['country'];
			$persona[$i]['poldiv'] = $person[$i]['poldiv'];
			$persona[$i]['city'] = $person[$i]['city'];
			$persona[$i]['birdate'] = $person[$i]['birdate'];
			$persona[$i]['nation'] = $person[$i]['nation'];
			$persona[$i]['sex'] = $person[$i]['sex'];
			$persona[$i]['marstat'] = $person[$i]['marstat'];
			$persona[$i]['address'] = $person[$i]['address'];
			$persona[$i]['rcountry'] = $person[$i]['rcountry'];
			$persona[$i]['rpoldiv'] = $person[$i]['rpoldiv'];
			$persona[$i]['rcity'] = $person[$i]['rcity'];
			$persona[$i]['telep'] = $person[$i]['telep'];
			$persona[$i]['active'] = $person[$i]['active'];
			$persona[$i]['tnation'] = $person[$i]['tnation'];
			$persona[$i]['incountrysince'] = $person[$i]['incountrysince'];
			$persona[$i]['religion'] = $person[$i]['religion'];
			$persona[$i]['qbrother'] = $person[$i]['qbrother'];
			$persona[$i]['qson'] = $person[$i]['qson'];
		}

		for($i = 0; $i < count ($personca); $i ++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $personca[$i]['person'];
			}
			$persona[$i]['estadocredencialca'] = $personca[$i]['estadocredencialca'];
			$persona[$i]['email'] = $personca[$i]['email'];
			$persona[$i]['codigoisic'] = $personca[$i]['codigoisic'];
			$persona[$i]['nrodechip'] = $personca[$i]['nrodechip'];
			$persona[$i]['sca_fecha'] = $personca[$i]['sca_fecha'];
			$persona[$i]['sca_categoria'] = $personca[$i]['sca_categoria'];
			$persona[$i]['sca_lote'] = $personca[$i]['sca_lote'];
			$persona[$i]['tipo_formulario'] = $personca[$i]['tipo_formulario'];
			$persona[$i]['nrodechip_dec'] = $personca[$i]['nrodechip_dec'];
			$persona[$i]['fecha_chip'] = $personca[$i]['fecha_chip'];
			$persona[$i]['motivo'] = $personca[$i]['motivo'];
			$persona[$i]['tipo_credencial'] = $personca[$i]['tipo_credencial'];
			$persona[$i]['descTipoCreden'] = $personca[$i]['descTipoCreden'];
			$persona[$i]['estadocredenca'] = $personca[$i]['estadocredenca'];
		}

		for($i = 0; $i < count ($apers); $i ++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $apers[$i]['person'];
			}

			if (isset ($apers[$i]['PISO']))
			{
				$persona[$i]['piso'] = $apers[$i]['PISO'];
			}
			if (isset ($apers[$i]['E-MAIL']))
			{
				$persona[$i]['e-mail'] = $apers[$i]['E-MAIL'];
			}
			if (isset ($apers[$i]['CODPOS']))
			{
				$persona[$i]['codpos'] = $apers[$i]['CODPOS'];
			}
			if (isset ($apers[$i]['LOCAL']))
			{
				$persona[$i]['local'] = $apers[$i]['LOCAL'];
			}
			if (isset ($apers[$i]['FAX']))
			{
				$persona[$i]['fax'] = $apers[$i]['FAX'];
			}
			if (isset ($apers[$i]['CALLE']))
			{
				$persona[$i]['calle'] = $apers[$i]['CALLE'];
			}
			if (isset ($apers[$i]['NUMERO']))
			{
				$persona[$i]['numero'] = $apers[$i]['NUMERO'];
			}
			if (isset ($apers[$i]['DEPTO']))
			{
				$persona[$i]['depto'] = $apers[$i]['DEPTO'];
			}
			if (isset ($apers[$i]['NRO']))
			{
				$persona[$i]['nro'] = $apers[$i]['NRO'];
			}
			if (isset ($apers[$i]['PREFIJO']))
			{
				$persona[$i]['prefijo'] = $apers[$i]['PREFIJO'];
			}
			if (isset ($apers[$i]['DESCRIP']))
			{
				$persona[$i]['descrip'] = $apers[$i]['DESCRIP'];
			}
		}

		for($i = 0; $i < count ($cuentaWeb); $i ++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $cuentaWeb[$i]['person'];
			}
			if (!isset ($persona[$i]['docNumero']) or $persona[$i]['docNumero'] == "")
			{
				$persona[$i]['docNumero'] = $cuentaWeb[$i]['docNumero'];
			}
			if (!isset ($persona[$i]['nombreCompleto']) or $persona[$i]['nombreCompleto'] == "")
			{
				$persona[$i]['nombreCompleto'] = $cuentaWeb[$i]['nombreCompleto'];
			}

			$persona[$i]['cuenta'] = $cuentaWeb[$i]['cuenta'];
			$persona[$i]['fecha_altaCuenta'] = $cuentaWeb[$i]['fecha_altaCuenta'];
			$persona[$i]['fecha_vencCuenta'] = $cuentaWeb[$i]['fecha_vencCuenta'];
			$persona[$i]['fecha_bajaCuenta'] = $cuentaWeb[$i]['fecha_bajaCuenta'];
			$persona[$i]['frase'] = $cuentaWeb[$i]['frase'];
			$persona[$i]['email'] = $cuentaWeb[$i]['email'];
			$persona[$i]['uid_cCuenta'] = $cuentaWeb[$i]['uid_cCuenta'];
			$persona[$i]['uid_mCuenta'] = $cuentaWeb[$i]['uid_mCuenta'];
			$persona[$i]['fecha_mCuenta'] = $cuentaWeb[$i]['fecha_mCuenta'];
			$persona[$i]['academicoCuenta'] = $cuentaWeb[$i]['academicoCuenta'];
			$persona[$i]['administrativoCuenta'] = $cuentaWeb[$i]['administrativoCuenta'];
			$persona[$i]['alumnoCuenta'] = $cuentaWeb[$i]['alumnoCuenta'];
			$persona[$i]['docenteCuenta'] = $cuentaWeb[$i]['docenteCuenta'];
			$persona[$i]['genericoCuenta'] = $cuentaWeb[$i]['genericoCuenta'];
			$persona[$i]['operadorCuenta'] = $cuentaWeb[$i]['operadorCuenta'];
			$persona[$i]['externoCuenta'] = $cuentaWeb[$i]['externoCuenta'];
			$persona[$i]['ultimocambioclaveCuenta'] = $cuentaWeb[$i]['ultimocambioclaveCuenta'];
			$persona[$i]['ultimoacceso'] = $cuentaWeb[$i]['ultimoacceso'];
			$persona[$i]['ultimaaplicacion'] = $cuentaWeb[$i]['ultimaaplicacion'];
			$persona[$i]['ultimoip'] = $cuentaWeb[$i]['ultimoip'];
		}

		if (count ($persona[0]) > 0)
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Se encargara de las acciones a realizar sobre los campos de la tabla apers
	 *
	 * @param string $accion
	 *        	- Puede ser UPDATE o INSERT dependiendo de esto es la accion que realizara la funcion
	 * @param mixed $pattrib
	 * @param mixed $shortdes
	 * @param mixed $valor
	 * @param int $person
	 *        	- person sobre el cual trabajara
	 * @return boolean
	 */
	public function modApers($accion, $pattrib, $shortdes, $valor, $person)
	{
		global $db;

		if (!isset ($valor) or $valor == "")
		{
			throw new Exception ('el dato valor no fue correctamene pasado! ');
		}
		if (!isset ($shortdes) or $shortdes == "")
		{
			throw new Exception ('el dato shortdes no fue correctamene pasado! ');
		}
		if (!isset ($pattrib) or $pattrib == "")
		{
			throw new Exception ('el dato pattrib no fue correctamene pasado! ');
		}
		if (!isset ($person) or $person == "")
		{
			throw new Exception ('el dato person no fue correctamene pasado! ');
		}

		if ($accion == "UPDATE")
		{
			$sql = "UPDATE appgral.apers" . $this->db_link . " SET VAL = :valor WHERE PERSON = :person AND PATTRIB = :pattrib AND SHORTDES = :shortdes";
		}
		elseif ($accion == "INSERT")
		{
			$sql = "INSERT INTO appgral.apers" . $this->db_link . " (val, person, pattrib, shortdes, ordno ) VALUES (:valor, :person, :pattrib, :shortdes, -1 )";
		}

		$parametros[0] = $valor;
		$parametros[1] = $person;
		$parametros[2] = $pattrib;
		$parametros[3] = $shortdes;

		if ($db->query ($sql, true, $parametros))
		{
			$resultado = true;
		}
		else
		{
			throw new Exception ('No pudo realizarse la insercion en appgral.apers! ');
		}
		return $resultado;
	}

	/**
	 * Busca el legajo de la persona o el person asociado a un legajo
	 *
	 * @param array $datosAUsar
	 *        	Tiene como indices obligatorios legajo o person.
	 * @return boolean|array - En caso de no encontrar nada devolvera false. Si recupera datos devuelve un array con el person, el legajo, la categoria, la fecha de ingreso y la de baja.
	 */
	public function buscarCatXPerson($datosAUsar)
	{
		global $db;

		$resultado = false;

		$persona = "";

		if ((!isset ($datosAUsar['legajo']) or $datosAUsar['legajo'] == "") and (!isset ($datosAUsar['person']) or $datosAUsar['person'] == ""))
		{
			throw new Exception ('Debe pasarle a la funcion el person o el legajo! ');
		}

		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			// if((isset($datosAUsar['legajo']) and $datosAUsar['legajo'] != ""))
			// {
			// $extraWhere = " AND LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0')) ";
			// $parametros[1] = $datosAUsar['legajo'];
			// }
			// else
			// {
			// $extraWhere = "";
			// }
			if ((isset ($datosAUsar['categoria']) and $datosAUsar['categoria'] != ""))
			{
				$extraWhere = " AND LTRIM(LTRIM(categoria, '0')) = LTRIM(LTRIM(:categoria, '0')) ";
				$parametros[1] = $datosAUsar['categoria'];
			}
			else
			{
				$extraWhere = "";
			}
			$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja, legajo FROM appgral.catxperson" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0'))" . $extraWhere;

			$parametros[0] = $datosAUsar['person'];

			$result = $db->query ($sql, true, $parametros);

			$persona = $db->fetch_array ($result);

			if ($persona != "")
			{
				if (isset ($persona['PERSON']))
				{
					$resultado['person'] = $persona['PERSON'];
				}
				if (isset ($persona['LEGAJO']))
				{
					$resultado['legajo'] = $persona['LEGAJO'];
				}
				if (isset ($persona['CATEGORIA']))
				{
					$resultado['categoria'] = $persona['CATEGORIA'];
				}
				if (isset ($persona['FINICIO']))
				{
					$resultado['fIngreso'] = $persona['FINICIO'];
				}
				if (isset ($persona['FBAJA']))
				{
					$resultado['fbaja'] = $persona['FBAJA'];
				}
			}
			else
			{
				// if((isset($datosAUsar['legajo']) and $datosAUsar['legajo'] != ""))
				// {
				// $parametros[2] = $datosAUsar['legajo'];
				// }
				if ((isset ($datosAUsar['categoria']) and $datosAUsar['categoria'] != ""))
				{
					$parametros[2] = $datosAUsar['categoria'];
				}

				$sqlTime = "SELECT MAX(mtime) person FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0'))";
				$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0')) AND mtime IN (" . $sqlTime . ")" . $extraWhere;

				$parametros[0] = $datosAUsar['person'];
				$parametros[1] = $datosAUsar['person'];

				$result = $db->query ($sql, true, $parametros);

				$persona = $db->fetch_array ($result);

				if ($persona != "")
				{
					if (isset ($persona['PERSON']))
					{
						$resultado['person'] = $persona['PERSON'];
					}
					if (isset ($persona['LEGAJO']))
					{
						$resultado['legajo'] = $persona['LEGAJO'];
					}
					if (isset ($persona['CATEGORIA']))
					{
						$resultado['categoria'] = $persona['CATEGORIA'];
					}
					if (isset ($persona['FINICIO']))
					{
						$resultado['fIngreso'] = $persona['FINICIO'];
					}
					if (isset ($persona['FBAJA']))
					{
						$resultado['fbaja'] = $persona['FBAJA'];
					}
				}
			}
		}
		elseif (isset ($datosAUsar['legajo']) and $datosAUsar['legajo'] != "")
		{
			// print_r("1");
			$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja, legajo FROM appgral.catxperson" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0'))";

			$parametros[0] = $datosAUsar['legajo'];

			$result = $db->query ($sql, true, $parametros);

			$persona = $db->fetch_array ($result);

			if ($persona != "")
			{
				if (isset ($persona['PERSON']))
				{
					$resultado['person'] = $persona['PERSON'];
				}
				if (isset ($persona['LEGAJO']))
				{
					$resultado['legajo'] = $persona['LEGAJO'];
				}
				if (isset ($persona['CATEGORIA']))
				{
					$resultado['categoria'] = $persona['CATEGORIA'];
				}
				if (isset ($persona['FINICIO']))
				{
					$resultado['fIngreso'] = $persona['FINICIO'];
				}
				if (isset ($persona['FBAJA']))
				{
					$resultado['fbaja'] = $persona['FBAJA'];
				}
			}
			else
			{
				$sqlTime = "SELECT MAX(mtime) person FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0'))";
				$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0')) AND mtime IN (" . $sqlTime . ")";

				$parametros[0] = $datosAUsar['legajo'];
				$parametros[1] = $datosAUsar['legajo'];

				$result = $db->query ($sql, true, $parametros);

				$persona = $db->fetch_array ($result);

				if ($persona != "")
				{
					if (isset ($persona['PERSON']))
					{
						$resultado['person'] = $persona['PERSON'];
					}
					if (isset ($persona['LEGAJO']))
					{
						$resultado['legajo'] = $persona['LEGAJO'];
					}
					if (isset ($persona['CATEGORIA']))
					{
						$resultado['categoria'] = $persona['CATEGORIA'];
					}
					if (isset ($persona['FINICIO']))
					{
						$resultado['fIngreso'] = $persona['FINICIO'];
					}
					if (isset ($persona['FBAJA']))
					{
						$resultado['fbaja'] = $persona['FBAJA'];
					}
				}
			}
		}

		return $resultado;
	}
}

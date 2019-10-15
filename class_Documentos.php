<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 16 nov. 2018
 * @lenguage PHP
 * @name class_Documentos.php
 * @version 0.1 version inicial del archivo.
 * @package
 * @project
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
 * @author iberlot
 *         FIXME esta clase deberia ser abstracta y servir de padre a clases como cuil dni pasaporte y que cada una aporte las funcionalidades requeridas.
 */
abstract class Documentos
{

	/**
	 * Numero de documento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 */
	protected $docNumero = "";

	/**
	 * Tipo de documento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.perdoc.typdoc - VARCHAR2(10 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 *       <Br>
	 *       El listado con los tipos de documentos y sus descripciones se encuetran en appgral.tdoc
	 */
	protected $docTipo = "";

	// el cuil es un tipo de documento
	// /**
	// * Numero de cuil de la persona
	// *
	// * @var string <Br>
	// * @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	// * <Br>
	// * Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 0
	// * y que el campo appgral.perdoc.typdoc = 'CUIL'
	// */
	// public $cuil = "";

	/**
	 *
	 * @return string el dato de la variable $docNumero
	 */
	public function getDocNumero()
	{
		return $this->docNumero;
	}

	/**
	 *
	 * @return string el dato de la variable $docTipo
	 */
	public function getDocTipo()
	{
		return $this->docTipo;
	}

	// /**
	// *
	// * @param
	// * string a cargar en la variable $docTipo
	// */
	// public function setDocTipo($docTipo)
	// {
	// $docTipo = strtoupper ($docTipo);

	// $sql = "SELECT typdoc FROM appgral.tdoc";
	// $result = $this->db->query ($sql);
	// $recu = $this->db->fetch_all ($result);

	// if (in_array ($docTipo, $recu['TYPDOC']))
	// {
	// $this->docTipo = $docTipo;
	// }
	// else
	// {
	// throw new Exception ("Tipo de documento no valido. " . $docTipo . ". ");
	// }
	// }

	/**
	 * Setter del parametro $docTipo de la clase.
	 *
	 * @param string $docTipo
	 *        	dato a cargar en la variable.
	 */
	public function setDocTipo($docTipo)
	{
		$this->docTipo = $docTipo;
	}
}
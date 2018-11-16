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
	public $docNumero = "";

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
	public $docTipo = "";

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
	 * Revisarlo e implementarlo en class persona
	 *
	 * Valida el CUIT pasado por parametro.
	 * https://es.wikipedia.org/wiki/Clave_%C3%9Anica_de_Identificaci%C3%B3n_Tributaria
	 *
	 * @param int $cuit
	 */
	public function validarCuit($cuit)
	{
		$cuit = preg_replace ('/[^\d]/', '', (string) $cuit);
		if (strlen ($cuit) != 11)
		{
			return false;
		}
		$acumulado = 0;
		$digitos = str_split ($cuit);
		$digito = array_pop ($digitos);

		for($i = 0; $i < count ($digitos); $i++)
		{
			$acumulado += $digitos[9 - $i] * (2 + ($i % 6));
		}
		$verif = 11 - ($acumulado % 11);
		$verif = $verif == 11 ? 0 : $verif;

		return $digito == $verif;
	}
}
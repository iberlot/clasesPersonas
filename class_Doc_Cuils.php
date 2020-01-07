<?php

/**
 * Archivo principal de la clase.
 */
/**
 * Documentacion del uso de la clase y sus funciones en proceso.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 11 oct. 2019
 * @name class_Doc_Dnis.php
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
 * totalHorasPerdidasAqui = 2
 *
 */
require_once ("class_Documentos.php");

/**
 *
 * @author iberlot
 */
class Cuils extends Documentos
{

	public function __construct($doc_num, $db = null)
	{
		if (!isset ($db) or empty ($db) or $db == null)
		{
			if (!$this->db = Sitios::openConnection ())
			{
				global $db;

				if (isset ($db) and !empty ($db) and $db != null)
				{
					$this->db = $db;
				}
			}
		}
		else
		{
			$this->db = $db;
		}

		$this->setDocNumero ($doc_num);
		$this->setDocTipo ("CUIL");
	}

	/**
	 *
	 * @param
	 *        	string a cargar en la variable $docNumero
	 */
	public function setDocNumero($docNumero)
	{
		$docNumero = preg_replace ("/[^0-9]/", "", $docNumero);

		if (validarCuit ($docNumero))
		{
			$this->docNumero = $docNumero;
		}
		else
		{
			throw new Exception ("Cuil erroneo");
		}
	}

	/**
	 * Revisarlo e implementarlo en class persona
	 *
	 * Valida el CUIT pasado por parametro.
	 * https://es.wikipedia.org/wiki/Clave_%C3%9Anica_de_Identificaci%C3%B3n_Tributaria
	 *
	 * @param int $cuit
	 */
	private function validarCuit($cuit)
	{
		$cuit = preg_replace ('/[^\d]/', '', (string) $cuit);
		if (strlen ($cuit) != 11)
		{
			return false;
		}
		$acumulado = 0;
		$digitos = str_split ($cuit);
		$digito = array_pop ($digitos);

		for($i = 0; $i < count ($digitos); $i ++)
		{
			$acumulado += $digitos[9 - $i] * (2 + ($i % 6));
		}
		$verif = 11 - ($acumulado % 11);
		$verif = $verif == 11 ? 0 : $verif;

		return $digito == $verif;
	}
}

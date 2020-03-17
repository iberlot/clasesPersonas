<?php

/**
 * Archivo principal de la clase.
 */
/**
 * Documentacion del uso de la clase y sus funciones en proceso.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 04 mar. 2020
 * @name class_Doc_LE.php
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
class LEs extends Documentos
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
					$this->db = &$db;
				}
			}
		}
		else
		{
			$this->db = &$db;
		}

		$this->setDocNumero ($doc_num);
		$this->setDocTipo ("LE");
	}

	/**
	 *
	 * @param
	 *        	string a cargar en la variable $docNumero
	 */
	public function setDocNumero($docNumero)
	{
		$docNumero = preg_replace ("/[^0-9]/", "", $docNumero);

		$this->docNumero = $docNumero;
	}
}

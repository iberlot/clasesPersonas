<?php

/**
 * Clase que se ocupa del manejo de todo lo referente a la cuenta de usuario.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 16 nov. 2018
 * @lenguage PHP
 * @name class_cuentas.php
 * @version 0.1 version inicial del archivo.
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
 * totalHorasPerdidasAqui = 0
 *
 */

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 16 nov. 2018
 * @name Cuentas
 * @version 2.0
 * @since 2.0 Los campos booleanos $cuentaAcademica, $cuentaAdministrativa, $cuentaAlumno, $cuentaDocente, $cuentaExterno, $cuentaGenerica y $cuentaOperador convierten en una lista que contiene los que correapondan para cada cuenta.
 * @since 0.1 version inicial
 */
class Cuentas
{
	/**
	 * Cuenta de la persona (nombre de usuario) <Br>
	 * Hay que tener en cuenta que un usuario puede tener mas de una cuenta por
	 * lo que convendria que este campo sea un array para permitir guardar todas alli.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.cuenta - VARCHAR2(120 BYTE)
	 *      <Br>
	 *
	 * @internal Este campo deberia estar siempre en minuscula.
	 *           $cuenta = strtolower($cuenta);
	 */
	protected $cuenta = "";

	/**
	 * Id de la Cuenta de la persona.
	 *
	 * @var int <Br>
	 *      @ubicacionBase portal.usuario_web.id - NUMBER(10,0)
	 */
	protected $idCuenta = "";

	/**
	 * Direccion de mail relacionada a la cuenta de la persona.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.email - VARCHAR2(200 BYTE)
	 */
	protected $emailCuenta = "";

	/**
	 * Array con las diferentes posibilidades de tipo de cuenta.
	 *
	 * @var boolean[] Va a tener los tipos de cuenta como indices y true o false segun corresponda.
	 */
	protected $tipoCuenta = array ();

	/**
	 * Frace de seguridad de la cuenta de la persona utilizada
	 * para la recuperacion de contraseñas.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.frase - VARCHAR2(250 BYTE)
	 */
	protected $fraseDeSeguridad = "";

	/**
	 * Fecha de vencimiento de la cuenta de la persona
	 *
	 * @var string - Date $vencimiento
	 *      @ubicacionBase portal.usuario_web.fecha_venc - DATE
	 */
	protected $vencimiento = "";

	/**
	 * Fecha de alta de la cuenta de la persona
	 *
	 * @var string - Date $alta
	 *      @ubicacionBase portal.usuario_web.fecha_alta - DATE
	 */
	protected $alta = "";

	/**
	 * Fecha de baja de la cuenta de la persona
	 *
	 * @var string - Date $baja
	 *      @ubicacionBase portal.usuario_web.fecha_baja - DATE
	 */
	protected $baja = "";
}
?>
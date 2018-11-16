<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 16 nov. 2018
 * @lenguage PHP
 * @name class_direccion.php
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
class Direcciones
{

	/**
	 * Pais de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.country - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.country
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $country = "";

	/**
	 * Provincia de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.poldiv - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.poldiv
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $poldiv = "";

	/**
	 * Ciudad de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.city - VARCHAR2(10 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.city
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $city = "";

	/**
	 * Pais de residencia de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rcountry - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.country
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rcountry = "";

	/**
	 * Provincia de residencia de la persona.
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rpoldiv - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.poldiv
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rpoldiv = "";

	/**
	 * Ciudad de residencia de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rcity - VARCHAR2(10 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.city
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rcity = "";

	/**
	 * Calle de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CALLE'
	 */
	public $direCalle = "";

	/**
	 * Numero de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'NRO'
	 */
	public $direNumero = "";

	/**
	 * Piso de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'PISO'
	 */
	public $direPiso = "";

	/**
	 * Dto de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'DEPTO'
	 */
	public $direDto = "";

	/**
	 * Codigo postal de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CODPOS'
	 */
	public $direCodPos = "";

	/**
	 * Direccion en string que une calle numero y muchas veces depto en un solo campo.
	 *
	 * @todo lo ideal es tratarlo para que quede separado de la forma que corresponde
	 *
	 * @var string <Br>
	 *      @ubicacionBase sueldos.personal.domicilio - VARCHAR2(30 BYTE)
	 */
	public $domicilio = "";
}
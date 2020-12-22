<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 7 mar. 2019
 * @lenguage PHP
 * @name class_conexion.php
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
require_once ("/web/html/classes/class_db.php");

class Conexion {

    private static $db = null;

    private function __construct() {
        
    }

    /**
     * Conexion a la BD
     */
    public static function openConnection() {
        $host = "ACADEMICA.DESARROLLO";

        $user = "tesoreria";
        $pass = "tesoreria";
        // $base = "tesoreria";
        // $dsn = "oracle:host=$host;dbname=$base;charset=WE8MSWIN1252";

        $db = new class_db($host, $user, $pass, '', 'WE8MSWIN1252', 'oracle');

        // $linkOracle_class->debug=TRUE;

        $db->debug = FALSE;

        $db->connect();

        return $db;
    }

}

?>
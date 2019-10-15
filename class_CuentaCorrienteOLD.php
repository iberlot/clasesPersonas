<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_cuentaCorriente
 * 
 * Maneja cuenta corriente de alumnos
 *
 * @author lquiroga
 */
class CuentaCorriente {

    public $db;
    public $Cta_Matricula = array();
    public $Cta_arancel = array();
    public $beca = array();
    public $mes_beca = array();
    public $Escuelaycarrera;
    public $Anioquecursa;
    public $Matriculaunica;
    public $Tipoalumno;
    public $Cuotadebeca;
    public $Planpago;
    public $Pagoacuenta;
    public $Deudanant;
    public $tramitetitulo;
    public $ingreso;

    public function __construct($db, $student, $idcentrodecosto) {
        $beca = array(
            0,
            0,
            0
        );
        $this->setDb($db);


        $person = $student;


        /*         * *********************** */
        $query = "SELECT STAT FROM  studentc.carstu cs WHERE student = :student and career = :career and branch = :branch";

        $parametros[] = $person;
        $parametros[] = ($arr_json['fa'] . $arr_json['ca']) * 1;
        $parametros[] = $arr_json['es'] * 1;

        $query_debug = "SELECT STAT FROM  studentc.carstu cs WHERE student = " . $parametros[0] . " and career = " . $parametros[1] . " and branch = " . $parametros[2];

        #echo $query_debug ;
        $result = $this->db->query($query, $esParam = true, $parametros);
        $arr_asoc_tipo_alumno = $this->db->fetch_array($result);

        $tipo_alumno_array[] = 'Aspirante';
        $tipo_alumno_array[] = 'Regular';
        $tipo_alumno_array[] = 'Graduado';
        $tipo_alumno_array[] = 'Egresado';

        //SET TIPO ALUMNO
        $this->setTipoalumno($tipo_alumno_array[$arr_asoc_tipo_alumno['STAT']]);

        $query = "SELECT * FROM tesoreria.ccalu WHERE person = :person AND idcentrodecosto = :idcentrodecosto";

        $parametros = "";

        $parametros[0] = $person;
        $parametros[1] = $idcentrodecosto;

        $result = $this->db->query($query, $esParam = true, $parametros);
        
        $contador_mat=1;
        while ($arr_asoc = $this->db->fetch_array($result)) {
            
            
            
            
         $contador_mat = $contador_mat + 1;
        }
        
        /*

        if (isset($valoresaran) and isset($valoresmat) and isset($beca)) {
            $query = "SELECT descripcion FROM interfaz.tipo_alumno WHERE tipo_alumno = :talumno";

            $parametros = "";
            $parametros[0] = $beca[1];

            $result = $this->db->query($query, $esParam = true, $parametros);

            $arr_asoc = $this->db->fetch_array($result);

            $beca[1] = utf8_encode($beca[1] . " - " . $arr_asoc['DESCRIPCION']);


            $arr_json['beca1'] = $beca[1];

            if (isset($arr_asoc['COD_BECANT']) and $arr_asoc['COD_BECANT'] != "") {
                $arr_json['becaant'] = $arr_asoc['COD_BECANT'];
            } else {
                $arr_json['becaant'] = "00";
            }

            $esca = '' . $arr_json['es'] . '' . $arr_json['ca'] . '';
            if ($arr_json['es'] == 0) {
                $esca = '0' . $arr_json['es'] . '' . $arr_json['ca'] . '';
            }

            $query = "SELECT descrip FROM studentc.branch WHERE code = :sede";

            $parametros = "";
            $parametros[0] = $arr_json['es'];

            $result = $this->db->query($query, $esParam = true, $parametros);

            $arr_asoc = $this->db->fetch_array($result);

            $arr_json['sede'] = utf8_encode($arr_asoc['DESCRIP']);

            $query = "SELECT descrip FROM studentc.facu WHERE code = :facu";
            $parametros = "";
            $parametros[0] = $arr_json['fa'];
            $result = $this->db->query($query, $esParam = true, $parametros);
            $arr_asoc = $this->db->fetch_array($result);
            $arr_json['facultad'] = utf8_encode($arr_asoc['DESCRIP']);

            $query = "SELECT descrip FROM studentc.career WHERE code = :facu";
            $parametros = "";
            $parametros[0] = $arr_json['fa'] . $arr_json['ca'];
            $result = $this->db->query($query, $esParam = true, $parametros);
            $arr_asoc = $this->db->fetch_array($result);
            $arr_json['carrera'] = utf8_encode($arr_asoc['DESCRIP']);

            $query = "SELECT lname, fname FROM appgral.person WHERE person = :person";

            $parametros = "";
            $parametros[0] = $person;

            $result = $this->db->query($query, $esParam = true, $parametros);

            $arr_asoc = $this->db->fetch_array($result);

            $arr_json['nombreyapellido'] = utf8_encode($arr_asoc['LNAME'] . ", " . $arr_asoc['FNAME']);
        }
*/
        return $arr_json;
    }

    function getDb() {
        return $this->db;
    }

    function getCta_Matrícula() {
        return $this->Cta_Matrícula;
    }

    function getCta_arancel() {
        return $this->Cta_arancel;
    }

    function getEscuelaycarrera() {
        return $this->Escuelaycarrera;
    }

    function getAnioquecursa() {
        return $this->Anioquecursa;
    }

    function getMatriculaunica() {
        return $this->Matriculaunica;
    }

    function getTipoalumno() {
        return $this->Tipoalumno;
    }

    function getCuotadebeca() {
        return $this->Cuotadebeca;
    }

    function getPlanpago() {
        return $this->Planpago;
    }

    function getPagoacuenta() {
        return $this->Pagoacuenta;
    }

    function getDeudanant() {
        return $this->Deudanant;
    }

    function getTramitetitulo() {
        return $this->tramitetitulo;
    }

    function getIngreso() {
        return $this->ingreso;
    }

    function setDb($db) {
        $this->db = $db;
    }

    function setCta_Matricula($Cta_Matrícula) {
        $this->Cta_Matrícula[] = $Cta_Matrícula;
    }

    function setCta_arancel($Cta_arancel) {
        $this->Cta_arancel[] = $Cta_arancel;
    }

    function setEscuelaycarrera($Escuelaycarrera) {
        $this->Escuelaycarrera = $Escuelaycarrera;
    }

    function setAnioquecursa($Anioquecursa) {
        $this->Anioquecursa = $Anioquecursa;
    }

    function setMatriculaunica($Matriculaunica) {
        $this->Matriculaunica = $Matriculaunica;
    }

    function setTipoalumno($Tipoalumno) {
        $this->Tipoalumno = $Tipoalumno;
    }

    function setCuotadebeca($Cuotadebeca) {
        $this->Cuotadebeca = $Cuotadebeca;
    }

    function setPlanpago($Planpago) {
        $this->Planpago = $Planpago;
    }

    function setPagoacuenta($Pagoacuenta) {
        $this->Pagoacuenta = $Pagoacuenta;
    }

    function setDeudanant($Deudanant) {
        $this->Deudanant = $Deudanant;
    }

    function setTramitetitulo($tramitetitulo) {
        $this->tramitetitulo = $tramitetitulo;
    }

    function setIngreso($ingreso) {
        $this->ingreso = $ingreso;
    }

    /**
     *
     * @param array $email
     */
    public function agregarMatricula($matricula) {
        $this->Cta_Matricula[] = $matricula;
    }

    function getBeca() {
        return $this->beca;
    }

    function getMes_beca() {
        return $this->mes_beca;
    }

    function setBeca($beca) {
        $this->beca[] = $beca;
    }

    function setMes_beca($mes_beca) {
        $this->mes_beca[] = $mes_beca;
    }

}

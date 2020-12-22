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
class AlumnoCuentaCorriente {

    protected $db;
    protected $Cta_Matrícula = array();
    protected $Cta_arancel = array();
    protected $Escuelaycarrera;
    protected $Anioquecursa;
    protected $Matriculaunica;
    protected $Tipoalumno;
    protected $Cuotadebeca;
    protected $Planpago;
    protected $Pagoacuenta;
    protected $Deudanant;
    protected $tramitetitulo;
    protected $ingreso;

    public function __construct($db, $student, $idcentrodecosto) {

        $this->setDb($db);

        $i = 0;
        $person = $student;

        // Necesito Nombre y apellido 
        // select de carreras

        /*         * *********************** */
        $query = "SELECT faesca, denominacion, idcentrodecosto FROM contaduria.centrodecosto "
                . "WHERE idcentrodecosto = :idcentrodecosto";

        $parametros[] = $idcentrodecosto;
        $result = $this->db->query($query, $esParam = true, $parametros);
        $arr_asoc = $this->db->fetch_array($result);

        $arr_json['carreras'] = $arr_asoc['DENOMINACION'];
        $faesca = $arr_asoc['FAESCA'];

        $arr_json['faesca'] = $faesca;
        $arr_json['fa'] = substr($faesca, 0, 2);
        $arr_json['es'] = substr($faesca, 2, 2);
        $arr_json['ca'] = substr($faesca, 4, 2);

        /*         * *********************** */
        $query = "SELECT STAT FROM  studentc.carstu cs WHERE student = :student and career = :career and branch = :branch";

        $parametros[] = $_GET['student'];
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

        $arr_json['tipoalumno'] = $tipo_alumno_array[$arr_asoc_tipo_alumno['STAT']];

        $query = "SELECT * FROM tesoreria.ccalu WHERE person = :person AND idcentrodecosto = :idcentrodecosto";

        $parametros = "";

        $parametros[0] = $person;
        $parametros[1] = $idcentrodecosto;

        $result = $this->db->query($query, $esParam = true, $parametros);

        while ($arr_asoc = $this->db->fetch_array($result)) {
            $arr_json['imp_mat_unica'] = number_format($arr_asoc['IMP_MAT_UNICA'], 2, ',', '.') . ' ';

            if ($arr_asoc['DEUDA_ANTERIOR'] == 1) {
                $arr_json['deuda_anterior'] = "Si";
            } else {
                $arr_json['deuda_anterior'] = "No";
            }

            $arr_json['anio_carre_cc'] = $arr_asoc['ANIO_CARRE_CC'];

            $idcentrodecosto = $arr_asoc['IDCENTRODECOSTO'];
            $arr_json['idcentrodecosto'] = $idcentrodecosto;

            $arr_json['pagoacuenta'] = convenum($arr_asoc['MAT_PROX_AN']);

            switch ($arr_asoc['RESERVA']) {
                case 'g':
                    $arr_json['tramitetitulo'] = 'Graduado en tramite';
                    break;
                case 'G':
                    $arr_json['tramitetitulo'] = "Graduado";
                    break;
                case 'i':
                    $arr_json['tramitetitulo'] = "Intermedio en tramite";
                    break;
                case 'I':
                    $arr_json['tramitetitulo'] = "Intermedio graduado";
                    break;
                default:
                    $arr_json['tramitetitulo'] = $arr_asoc['RESERVA'];
                    break;
            }

            $arr_json['ingreso'] = $arr_asoc['INGRESO'];

            $esca = '' . $arr_json['es'] . $arr_json['ca'] . '';

            $arr_json['esca'] = $esca;
            if ($arr_asoc['PLAN_DEU'] == 0) {
                $arr_json['plandeu'] = 'Sin planes de pago';
            } else {
                $arr_json['plandeu'] = $arr_asoc['PLAN_DEU'];
            }

            for ($i = 1; $i <= 11; $i++) {

                if ($i <= 3) {
                    $beca[$i] = $arr_asoc['BECA' . $i . ''];
                    $arr_json['beca' . $i] = $beca[$i];

                    $arr_json['mesbeca' . $i] = $arr_asoc['MES_BECA' . $i . ''];
                }
                if ($i <= 4) {
                    $valoresmat[$i] = convenum($arr_asoc['IMP_MAT' . $i . '']);
                    $arr_json['valoresmat' . $i] = $valoresmat[$i];
                }

                $arr_json['valoresaran' . $i] = convenum($arr_asoc['IMP_ARAN' . $i . '']);
            }
        }

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

    function setCta_Matrícula($Cta_Matrícula) {
        $this->Cta_Matrícula = $Cta_Matrícula;
    }

    function setCta_arancel($Cta_arancel) {
        $this->Cta_arancel = $Cta_arancel;
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

}

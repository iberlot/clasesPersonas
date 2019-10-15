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
    public $aniocc;
    public $idCentrodeCosto;
    public $anio_carrera_cc;
    public $plan_deu;
    public $mat_prox_an;
    public $inscdate;

    public function __construct($db, $student, $idcentrodecosto, $anio = null) {
        $beca = array(
            0,
            0,
            0
        );

        $this->setDb($db);


        $person = $student;

        $query2 = "SELECT faesca, denominacion, idcentrodecosto "
                . "FROM contaduria.centrodecosto "
                . "WHERE idcentrodecosto = :idcentrodecosto";

        $parametros = "";
        $parametros[0] = $idcentrodecosto;

        $result = $db->query($query2, $esParam = true, $parametros);

        $arr_asoc = $db->fetch_array($result);

        $idcentrodecosto = utf8_encode($arr_asoc['IDCENTRODECOSTO']);
        $faesca = $arr_asoc['FAESCA'];

        $arr_json['carreras'] = $arr_asoc['DENOMINACION'];

        $arr_json['fa'] = substr($faesca, 0, 2);
        $arr_json['es'] = substr($faesca, 2, 2);
        $arr_json['ca'] = substr($faesca, 4, 2);


        /*         * *********************** */
        $query = "SELECT STAT FROM  studentc.carstu cs WHERE student = :student and career = :career and branch = :branch";

        $parametros[] = $person;
        $parametros[] = ($arr_json['fa'] . $arr_json['ca']) * 1;
        $parametros[] = $arr_json['es'] * 1;

        //$query_debug = "SELECT STAT FROM  studentc.carstu cs WHERE student = " . $parametros[0] . " and career = " . $parametros[1] . " and branch = " . $parametros[2];
        #echo $query_debug ;
        $result = $this->db->query($query, $esParam = true, $parametros);

        $arr_asoc_tipo_alumno = $this->db->fetch_array($result);

        $tipo_alumno_array[] = 'Aspirante';
        $tipo_alumno_array[] = 'Regular';
        $tipo_alumno_array[] = 'Graduado';
        $tipo_alumno_array[] = 'Egresado';

        //SET TIPO ALUMNO
        //  $this->setTipoalumno($tipo_alumno_array[$arr_asoc_tipo_alumno['STAT']]);

        $sql_ccalu = "SELECT * FROM TESORERIA.CCALU 
            JOIN STUDENTC.CARSTU ON ccalu.PERSON = carstu.STUDENT 
            JOIN STUDENTC.CAREER ON CARSTU.CAREER =CAREER.CODE
            WHERE  PERSON = :ida  and IDCENTRODECOSTO = :centrocosto AND  
            CCALU.ANIOCC = :anio_actual ";


        // Array parametros 
        $param = array(
            $person,
            $idcentrodecosto
        );

        if ($anio == null) {
            $param[] = date('Y');
        } else {
            $param[] = $anio;
        }
        // ejecuto
        $sccobro = $this->db->query($sql_ccalu, $esParam = true, $param);

        $arr_asoc = $this->db->fetch_assoc($sccobro);

        if ($arr_asoc) {
            /*

             */
            $this->loadData($arr_asoc);
        }
    }

    /**
     * CUENTA CORRIENTE CON RECARGOS ,DESCUENTOS Y FUERAS DE TERMINO
     */
    function ctacteReal(){
        // print_r($arr_asoc);
        /*
         * 
         * DE ALGUNA FORMA DEBEMOS RECONOCER EL TIPO DE CARRERA Q SE ESTA PBUSCANDO Y TRATAR DE IDENTIFICARLA 
         * EN LA TABLA VENCIMIENTOS_ESPECIALES PARA APLICARLE EL VENCIMIENTO CORRESPONDOENTE.
         * SI NO MATCHEA SE LE ASIGNA EL VENCIMIENTO DEFAULT PARA TODO LO QUE NO SEA VENICMIENTO ESPECIAL 
         *
         */

        //new_var_dump($arr_asoc['INSCDATE']);

        $date = new DateTime($this->getInscdate());

        $anio_insc = $date->format('Y');

        $mes_insc = $date->format('m');

        $anio_carrera = $this->getAniocc();

        $fecha_caja = (explode('/', date('d/m/y')));

        $mes_caja = $fecha_caja[1];


        if ($anio_carrera == 0) {

            $anio_carrera = 1;
        }

        $cuatriinscribe = 0;

        //BUSCO SI HAY ALGUN VENCIMIENTO ESPECIAL , ESPECIFICO DE ESE CENTRO DE COSTO
        $id_matriculas = $this->obtener_id_vencimiento($this->getIdCentrodeCosto(),$anio_carrera, $mes_insc, $cuatriinscribe, 1);

            print_r($id_matriculas);
  
        $vencimiento_matricula = $this->obtener_fechas_vencimientos($id_matriculas);

        //$vencimiento_matricula2 =obtener_fechas_vencimientos($id_matriculas);

        $turno = $_SESSION['trn'];

        //var_dump($_SESSION);

        $c = 1;

        print_r($vencimiento_matricula);

        /* recorro los 4 primeros registros que son matriculas e inscripcion */
        $contador_mat = 1;

        //var_dump( obtener_excepciones_arancel($ida , $_SESSION['fecha_format']));
        //new_var_dump($vencimiento_arancel);
        //var_dump($vencimiento_matricula);
        if ($vencimiento_matricula) {

            foreach ($vencimiento_matricula as $key => $value) {

                $i = $contador_mat;

                $IMPORTE_MATRICULA[$i] = number_format((float) $arr_asoc['IMP_MAT' . $i . ''], 2, ',', '');

                // SI LOS IMPORTES SON MAYORES A CERO
                if (($IMPORTE_MATRICULA[$i] > 0) and ( $IMPORTE_MATRICULA[$i] < 999999)) {

                    $fecha_vencimiento = $vencimiento_matricula[$key]['DIA'] . '/' . $vencimiento_matricula[$key]['MES'] . '/18';

                    //chequeo si correozsponde recargo
                    if (obtener_excepciones_matriculas($alumno->getPerson(), $_SESSION['fecha_format']) == 1) {

                        /* calcular recargo de matricula */
                        $recargo = recargo_matricula_arancel($fecha_vencimiento, $_SESSION['fecha_format'], $IMPORTE_MATRICULA[$i], $recargo_diario);
                    } else {

                        $recargo = 0;
                    }

                    $MATPAGAR[$i] = 2; // VALOR POR DEFECTO PARA PAGOS
                    // Calculo re cargo para las matriculas devuelve un array con el recargo y el recargo diarios */
                    //$valores3           = recargomat($i, $vencimiento_matricula[$key]['DIA'], $pdiario, $IMPORTE_MATRICULA[$i], $c);                    
                    // total de todos los recargos por fuera de termino
                    $recargo_arancel_ftermino = obtener_pago_fuera_temino($fecha_vencimiento, $_SESSION['fecha_format']);

                    // valor total de la matricula con los recargos y bonificaciones
                    $totcuota = $IMPORTE_MATRICULA[$i] + $recargo + $recargo_arancel_ftermino;

                    // Total de todas las matriculas sumadas
                    $totmat += $IMPORTE_MATRICULA[$i];

                    // total de todos los recargos por dias
                    $recargralmat += $recargo;

                    // el id de� codigo delas matriculas en tabla conceptos es 8
                    $codigo = 8;

                    // Chequep si el mov ya fue ingresado en el dia
                    $ya_registrado = ya_regis_dia($alumno->getPerson(), $codigo, $i, $fecha, $alumno->getCarrera());

                    // si ya fue registrado no muestro el input check
                    if ($ya_registrado) {

                        $dis = 'none';
                    } else {

                        $dis = 'block';
                    }

                    // valor total de la matricula con los recargos y bonificaciones
                    $totdeuda += $IMPORTE_MATRICULA[$i] + $recargo + $recargo_arancel_ftermino;

                    if ($IMPORTE_MATRICULA[$i]) {

                        echo ''
                        . '<tr>'
                        . '<td align="center" >' . $c . '</td>'
                        . '<td align="center">' . $i . '</td>'
                        . '<td align="center">' . $vencimiento_matricula[$key]['DIA'] . '/' . $vencimiento_matricula[$key]['MES'] . '</td>'
                        . '<td align="center">' . $tcuota . '</td>'
                        . '<td align="center">$ ' . convenum($IMPORTE_MATRICULA[$i]) . '</td>'
                        . '<td align="center">$ ' . convenum($recargo) . '</td>'
                        . '<td align="center">$ ' . convenum(0) . '</td>'
                        . '<td>$ ' . convenum($recargo_arancel_ftermino) . '</td>'
                        . '<td>$ ' . convenum($totcuota) . '</td>'
                        . '<td align="center">$' . convenum($totdeuda) . '</td>'
                        . '<td align="center">'
                        . '<input style="display:' . $dis . ';" type="checkbox" id="check_' . $c . '" '
                        . 'name="pos[]" class="select_check" value="' . '' .
                        $c . '+'
                        . $totdeuda . '+'
                        . $recargo_arancel_ftermino . '+' // recargo
                        . $arr_asoc['IMP_MAT' . $i . ''] . '+' . $i . '+' . // nro_mov
                        0 . '+' . 0 . '+'
                        . $recargo . '+'
                        . $recargo . '+' // recargo por dia
                        . $codigo . '+' . // 0 es una matricula
                        $recargralmat . '">' . '</tr>';
                        $c ++;
                    }
                } else {

                    $MATPAGAR[$i] = 1;
                }

                $contador_mat = $contador_mat + 1;
            } // hasta aca recorro matriculas
        }

        $contador = 1;
    }

    /**
     * 
     * En base a los parametros busca si existe vencimiento para ese CENTRO DE COSTO
     * 
     * @param $id_centro_costo  numeric
     * @param $anio_insc        numeric
     * @param $mes_insc         numeric
     * @param $cuatri           numeris
     * @param $tipo             numeric
     *
     * @return id vencimientos
     * 
     */
    public function obtener_id_vencimiento($id_centro_costo, $anio_insc, $mes_insc, $cuatri, $tipo = 0) {


        $linkOracle_class = $this->db;


        //BUSCO TODOS QUE COINCIDA CON EL CENTRO DE COSTO Y NINGUN OTRO PARAMETRO   
        $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                . "ID_CENTRO_COSTO = :idcentrodecosto AND ANIO_CARRERA IS NULL "
                . "AND CUATRIMESTRE_ANOTO IS NULL AND MES_INSCRIBE "
                . "IS NULL AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo AND ROWNUM = 1 ";
        
        $query_debug = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                . "ID_CENTRO_COSTO = $id_centro_costo AND ANIO_CARRERA IS NULL "
                . "AND CUATRIMESTRE_ANOTO IS NULL AND MES_INSCRIBE "
                . "IS NULL AND ACTIVO = 1 AND TIPO_VENCIMIENTO = $tipo AND ROWNUM = 1 ";
        
        echo($query_debug);
        
        $param = array(
            $id_centro_costo,
            $tipo
        );

        $res = $linkOracle_class->query($query_debug,false);

        $r = '';

        //Concateno los id para ir filtrando en las restantes queries
        while ($arr_asoc2 = oci_fetch_assoc($res)) {

            $r .= $arr_asoc2['ID'] . ',';
        }

        $ids = substr($r, 0, -1);

        if ($ids) {

            return $ids;
        } else {

            //BUSCO TODOS LOS VENCIMIENTOS QUECOINCIDA CON TODOS LOS PARAMETROS
            $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                    . "ID_CENTRO_COSTO = :idcentrodecosto AND ANIO_CARRERA = :anio_carrera "
                    . "AND CUATRIMESTRE_ANOTO = :cuatrimestre_anoto AND MES_INSCRIBE = :mes_inscribe  AND ACTIVO = 1 "
                    . "AND TIPO_VENCIMIENTO = :tipo";

            $param = array(
                $id_centro_costo,
                $anio_insc,
                $cuatri,
                $mes_insc,
                $tipo
            );

            $res = $linkOracle_class->query($query, $esParam = true, $param);

            $r = '';

            //Concateno los id para ir filtrando en las restantes queries
            while ($arr_asoc2 = oci_fetch_assoc($res)) {

                $r .= $arr_asoc2['ID'] . ',';
            }

            $ids = substr($r, 0, -1);

            if ($ids) {

                return $ids;
            } else {

                //BUSCO LOS VENCIMIENTOS QUE COINCIDA CON EL CENTRO DE COSTO Y ALGUNO DE TODOS LOS DEMAS PARAMETROS
                $query = "select * tesoreria.from VENCIMIENTOS_ESPECIALES WHERE "
                        . "ID_CENTRO_COSTO = :idcentrodecosto AND ( ANIO_CARRERA = :anio_carrera "
                        . "OR CUATRIMESTRE_ANOTO = :cuatrimestre_anoto OR MES_INSCRIBE = :mes_inscribe "
                        . " ) AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo";

                $param = array(
                    $id_centro_costo,
                    $anio_insc,
                    $cuatri,
                    $mes_insc,
                    $tipo
                );

                $res = $linkOracle_class->query($query, $esParam = true, $param);

                $r = '';

                $total = 0;

                //Concateno los id para ir filtrando en las restantes queries
                while ($arr_asoc2 = oci_fetch_assoc($res)) {

                    $r .= $arr_asoc2['ID'] . ',';
                    $total = $total + 1;
                }

                $ids = substr($r, 0, -1);

                if ($ids) {

                    return $ids;
                } else {

                    //BUSCO POR CENTRO DE COSTO Y ALGUNO DE TODOS LOS PARAMETROS
                    $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                            . "ID_CENTRO_COSTO = :idcentrodecosto   "
                            . "AND (ANIO_CARRERA = :anio_carrera OR ANIO_CARRERA IS NULL) "
                            . "AND (CUATRIMESTRE_ANOTO= :cuatrimestre_anoto OR CUATRIMESTRE_ANOTO IS NULL) "
                            . "AND (MES_INSCRIBE = :mes_inscribe OR MES_INSCRIBE IS NULL) "
                            . "AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo";

                    $param = array(
                        $id_centro_costo,
                        $anio_insc,
                        $cuatri,
                        $mes_insc,
                        $tipo
                    );

                    $res = $linkOracle_class->query($query, $esParam = true, $param);

                    $r = '';

                    $total = 0;

                    //Concateno los id para ir filtrando en las restantes queries
                    while ($arr_asoc2 = oci_fetch_assoc($res)) {

                        $r .= $arr_asoc2['ID'] . ',';
                        $total = $total + 1;
                    }

                    $ids = substr($r, 0, -1);

                    if ($ids) {
                        return $ids;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    /**
     * 
     * Pasamos un id , y nos retorna si tiene un vencimiento especifico
     * 
     * @param $id_concepto    int  A�o de inscripcion
     *
     * TIPO = 0 ES ARANCEL y 1 ES MATRICULA
     * 
     * @return id vencimientos
     * 
     */
    function obtener_fechas_vencimientos($id_concepto) {


        global $linkOracle_class;


        //Busco las fechas que coincidan con el concepto que le pasamos     
        $query = "SELECT * FROM VENCIMIENTOS_FECHAS WHERE ID_VENCIMIENTO_ESPECIAL = :id_concepto"
                . " ORDER BY NUMERO_CUOTA ASC";

        $param = array(
            $id_concepto
        );

        $res = $linkOracle_class->query($query, $esParam = true, $param);

        $r = array();

        //Concateno los id para ir filtrando en las restantes queries
        while ($arr_asoc = oci_fetch_assoc($res)) {

            $r[] = $arr_asoc;
        }

        if ($r) {

            return $r;
        }
    }

    /**
     *
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *        	return objet form
     *
     */
    public function loadData($fila) {

        if (isset($fila['ANIOCC'])) {
            $this->setAniocc($fila['ANIOCC']);
        };

        if (isset($fila['IDCENTRODECOSTO'])) {
            $this->setIdCentrodeCosto($fila['IDCENTRODECOSTO']);
        };

        if (isset($fila['NRO_MAT'])) {
            
        };

        if (isset($fila['IMP_MAT1'])) {
            $this->agregarMatricula($fila['IMP_MAT1']);
        };

        if (isset($fila['IMP_MAT2'])) {
            $this->agregarMatricula($fila['IMP_MAT2']);
        };

        if (isset($fila['IMP_MAT3'])) {
            $this->agregarMatricula($fila['IMP_MAT3']);
        };

        if (isset($fila['IMP_MAT4'])) {
            $this->agregarMatricula($fila['IMP_MAT4']);
        };

        if (isset($fila['IMP_MAT_UNICA'])) {
            $this->setMatriculaunica($fila['IMP_MAT_UNICA']);
        };

        if (isset($fila['NRO_ARAN'])) {
            
        };

        if (isset($fila['IMP_ARAN1'])) {
            $this->setCta_arancel($fila['IMP_ARAN1']);
        };

        if (isset($fila['IMP_ARAN2'])) {
            $this->setCta_arancel($fila['IMP_ARAN2']);
        };

        if (isset($fila['IMP_ARAN3'])) {
            $this->setCta_arancel($fila['IMP_ARAN3']);
        };

        if (isset($fila['IMP_ARAN4'])) {
            $this->setCta_arancel($fila['IMP_ARAN4']);
        };

        if (isset($fila['IMP_ARAN5'])) {
            $this->setCta_arancel($fila['IMP_ARAN5']);
        };

        if (isset($fila['IMP_ARAN6'])) {
            $this->setCta_arancel($fila['IMP_ARAN6']);
        };

        if (isset($fila['IMP_ARAN7'])) {
            $this->setCta_arancel($fila['IMP_ARAN7']);
        };

        if (isset($fila['IMP_ARAN8'])) {
            $this->setCta_arancel($fila['IMP_ARAN8']);
        };

        if (isset($fila['IMP_ARAN9'])) {
            $this->setCta_arancel($fila['IMP_ARAN9']);
        };

        if (isset($fila['IMP_ARAN10'])) {
            $this->setCta_arancel($fila['IMP_ARAN10']);
        };

        if (isset($fila['IMP_ARAN11'])) {
            $this->setCta_arancel($fila['IMP_ARAN11']);
        };

        if (isset($fila['IMP_ARAN12'])) {
            $this->setCta_arancel($fila['IMP_ARAN12']);
        };

        if (isset($fila['BECA1'])) {
            $this->setBeca($fila['BECA1']);
        };

        if (isset($fila['MES_BECA1'])) {
            $this->setMes_beca($fila['MES_BECA1']);
        };

        if (isset($fila['BECA2'])) {
            $this->setBeca($fila['BECA2']);
        };

        if (isset($fila['MES_BECA2'])) {
            $this->setMes_beca($fila['BECA2']);
        };

        if (isset($fila['BECA3'])) {
            $this->setBeca($fila['BECA3']);
        };

        if (isset($fila['MES_BECA3'])) {
            $this->setMes_beca($fila['BECA3']);
        };

        if (isset($fila['ANIO_CARRE_CC'])) {
            $this->setAnio_carrera_cc($fila['ANIO_CARRE_CC']);
        };

        if (isset($fila['PLAN_DEU'])) {
            $this->setPlan_deu($fila['PLAN_DEU']);
        };

        if (isset($fila['MAT_PROX_AN'])) {
            $this->setMat_prox_an($fila['MAT_PROX_AN']);
        };
        
        if (isset($fila['DEUDA_ANTERIOR'])) {
            $this->setDeudanant($fila['DEUDA_ANTERIOR']);
        };
        
        if (isset($fila['INSCDATE'])) {
                    $this->setInscdate($fila['INSCDATE']);
        };
    }

    /*     * ***********SETTERS & GETTERS*********************** */

    function getInscdate() {
        return $this->inscdate;
    }

    function setInscdate($inscdate) {
        $this->inscdate = $inscdate;
    }

    function getMat_prox_an() {
        return $this->mat_prox_an;
    }

    function setMat_prox_an($mat_prox_an) {
        $this->mat_prox_an = $mat_prox_an;
    }

    function getPlan_deu() {
        return $this->plan_deu;
    }

    function setPlan_deu($plan_deu) {
        $this->plan_deu = $plan_deu;
    }

    function getAnio_carrera_cc() {
        return $this->anio_carrera_cc;
    }

    function setAnio_carrera_cc($anio_carrera_cc) {
        $this->anio_carrera_cc = $anio_carrera_cc;
    }

    function getIdCentrodeCosto() {
        return $this->idCentrodeCosto;
    }

    function setIdCentrodeCosto($idCentrodeCosto) {
        $this->idCentrodeCosto = $idCentrodeCosto;
    }

    function getAniocc() {
        return $this->aniocc;
    }

    function getDb() {
        return $this->db;
    }

    function getCta_Matricula() {
        return $this->Cta_Matricula;
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

    function setCta_Matricula($Cta_Matr�cula) {
        $this->Cta_Matriccula[] = $Cta_Matr�cula;
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

    function setAniocc($aniocc) {
        $this->aniocc = $aniocc;
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

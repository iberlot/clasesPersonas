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
require_once ("/web/html/classes/class_fechas.php");

class CuentaCorriente {

    public $db;
    public $Cta_Matricula           = array();
    public $Cta_arancel             = array();
    public $beca                    = array();
    public $mes_beca                = array();
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
    public $cartype;
    public $person;
    public $recargoMatriculas       = array();
    public $recargoAranceles        = array();
    public $fueraterminoMatriculas  = array();
    public $fueraterminoAranceles   = array();
    public $matriculaTotal          = array();
    public $arancelTotal            = array();

    public function __construct($db, $student, $idcentrodecosto, $anio = null) {

        $beca = array(
            0,
            0,
            0
        );

        $this->setDb($db);


        $this->person = $student;

        $query2 = "SELECT faesca, denominacion, idcentrodecosto "
                . "FROM contaduria.centrodecosto "
                . "WHERE idcentrodecosto = :idcentrodecosto";

        $parametros = array();
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

        $parametros[] = $this->person;
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

        $sql_ccalu = "SELECT * FROM tesoreria.CCALU 
            JOIN STUDENTC.CARSTU ON ccalu.PERSON = carstu.STUDENT 
            JOIN STUDENTC.CAREER ON CARSTU.CAREER =CAREER.CODE
            WHERE  PERSON = :ida  and IDCENTRODECOSTO = :centrocosto AND  
            CCALU.ANIOCC = :anio_actual ";


        // Array parametros 
        $param = array(
            $this->person,
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
            $this->loadData($arr_asoc);            
        }
    }

    /**
     * CUENTA CORRIENTE CON RECARGOS ,DESCUENTOS Y FUERAS DE TERMINO
     */
    function ctacteReal() {
        // print_r($arr_asoc);
        /*
         * 
         * DE ALGUNA FORMA DEBEMOS RECONOCER EL TIPO DE CARRERA Q SE ESTA PBUSCANDO Y TRATAR DE IDENTIFICARLA 
         * EN LA TABLA tesoreria.VENCIMIENTOS_ESPECIALES PARA APLICARLE EL VENCIMIENTO CORRESPONDOENTE.
         * SI NO MATCHEA SE LE ASIGNA EL VENCIMIENTO DEFAULT PARA TODO LO QUE NO SEA VENICMIENTO ESPECIAL 
         *
         */

        $totmat = 0;
        $recargralmat = 0;
        $totdeuda = 0;
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
        $id_matriculas = $this->obtener_id_vencimiento($this->getIdCentrodeCosto(), $anio_carrera, $mes_insc, $cuatriinscribe, 1);

        //SI NO HAY BUSCO POR TIPO DE CARRERA
        if (!$id_matriculas) {

            $id_matriculas = $this->obtener_id_vencimiento_sin_centro_costo($this->getCartype(), $anio_carrera, $mes_insc, $cuatriinscribe, 1);
        }

        $vencimiento_matricula = $this->obtener_fechas_vencimientos($id_matriculas);

        //$vencimiento_matricula2 =obtener_fechas_vencimientos($id_matriculas);
        //$turno = $_SESSION['trn'];
        //var_dump($_SESSION);

        $c = 1;

        /* recorro los 4 primeros registros que son matriculas e inscripcion */
        $contador_mat = 1;

        //var_dump( obtener_excepciones_arancel($ida , $_SESSION['fecha_format']));
        //new_var_dump($vencimiento_arancel);
        //var_dump($vencimiento_matricula);

        $matriculas_array = $this->getCta_Matricula();
    
        $e = array();

        if ($vencimiento_matricula) {

            foreach ($vencimiento_matricula as $key => $value) {

                $i                      = $contador_mat;
                
                if(isset($matriculas_array[$i - 1])){
                    
                    $IMPORTE_MATRICULA[$i]  = (float) $matriculas_array[$i - 1];
                    
                }else{
                    
                    $IMPORTE_MATRICULA[$i]  =0;
                    
                }

                $recargo_diario         = 00.1000;

                $fecha_actual           = date('d-m-y');

                // SI LOS IMPORTES SON MAYORES A CERO
                if (($IMPORTE_MATRICULA[$i] > 0) and ( $IMPORTE_MATRICULA[$i] < 999999)) {

                    $fecha_vencimiento = $vencimiento_matricula[$key]['DIA'] . '-' . $vencimiento_matricula[$key]['MES'] . '-' . date('y');

                    //chequeo si correozsponde recargo                    
                    if ($this->obtener_excepciones_matriculas($this->person, $fecha_actual) == 1) {

                        /* calcular recargo de matricula */
                        $recargo = $this->recargo_matricula_arancel($fecha_vencimiento, $fecha_actual, $IMPORTE_MATRICULA[$i], $recargo_diario);
                    } else {

                        $recargo = 0;
                    }

                    $MATPAGAR[$i] = 2; // VALOR POR DEFECTO PARA PAGOS
                    // Calculo re cargo para las matriculas devuelve un array con el recargo y el recargo diarios */                   
                    // total de todos los recargos por fuera de termino
                    //$recargo_arancel_ftermino = $this->obtener_pago_fuera_temino($fecha_vencimiento, $fecha_actual);
                    $recargo_arancel_ftermino = 0;

                    // valor total de la matricula con los recargos y bonificaciones
                    $totcuota = $IMPORTE_MATRICULA[$i] + $recargo + $recargo_arancel_ftermino;

                    // $e[$i]['matricula'] = $IMPORTE_MATRICULA[$i];
                    $this->setRecargoMatriculas($recargo);
                    //$this->setFueraterminoMatriculas($recargo_arancel_ftermino);
                    $this->setMatriculaTotal($totcuota);


                    if ($IMPORTE_MATRICULA[$i]) {
                        $c ++;
                      }
                                          
                    }else{

                    $MATPAGAR[$i] = 1;
                }

                $contador_mat = $contador_mat + 1;
            } // hasta aca recorro matriculas
        }

        
        /*********ARANCELES***************/
        
        $id_aranceles       = $this->obtener_id_vencimiento($this->getIdCentrodeCosto(),$anio_carrera ,$mes_insc ,$cuatriinscribe ,0);                
              
        $aranceles_array    = $this->getCta_arancel();
        
        //SI NO HAY BUSCO POR TIPO DE CARRERA
        if (!$id_aranceles){
            $id_aranceles = $this->obtener_id_vencimiento_sin_centro_costo($this->getCartype(), $anio_carrera, $mes_insc, $cuatriinscribe, 0);
        }
        
        $vencimiento_aranceles= $this->obtener_fechas_vencimientos($id_aranceles);
        
        $aran= array();
        
        $contador_mat = 1;
        
        if ($id_aranceles) {

            foreach ($vencimiento_aranceles as $key => $value) {
            
                $cont = $contador_mat;

                if(isset( $aranceles_array[$cont- 1])){
                    $importeatancel[$cont] =(float) $aranceles_array[$cont- 1];
                }else{
                     $importeatancel[$cont] =0;
                }
                
                $recargo_diario = 00.1000;

                $fecha_actual = date('d-m-y');

                // SI LOS IMPORTES SON MAYORES A CERO
                if (($importeatancel[$cont] > 0) and ( $importeatancel[$cont] < 999999)){

                    $fecha_vencimiento = $value['DIA'] . '-' . $value['MES'] . '-' . date('y');
                   
                    
                    //chequeo si correozsponde recargo                    
                    if ($this->obtener_excepciones_matriculas($this->person, $fecha_actual) == 1){
                        /* calcular recargo de matricula */
                        $recargo = $this->recargo_matricula_arancel($fecha_vencimiento, $fecha_actual, $importeatancel[$cont], $recargo_diario);
                        
                    } else {
                        $recargo = 0;
                    }

                    $impagar[$cont] = 2; // VALOR POR DEFECTO PARA PAGOS
                    // Calculo re cargo para las matriculas devuelve un array con el recargo y el recargo diarios */                   
                    // total de todos los recargos por fuera de termino
                    $recargo_arancel_ftermino = $this->obtener_pago_fuera_temino($fecha_vencimiento, $fecha_actual);
                    //$recargo_arancel_ftermino = 0;

                    // valor total de la matricula con los recargos y bonificaciones
                    $totcuota = $importeatancel[$cont] + $recargo + $recargo_arancel_ftermino;
                    $this->setArancelTotal($totcuota);
                  

                    // $e[$i]['matricula'] = $IMPORTE_MATRICULA[$i];
                    $this->setRecargoAranceles($recargo);
                    $this->setFueraterminoAranceles($recargo_arancel_ftermino);
                   //$this->setArancelTotal($totcuota);


                    if ($importeatancel[$cont]) {
                        $c ++;
                      }
                                          
                    }else{

                    $impagar[$cont] = 1;
                }
            
                $contador_mat = $contador_mat + 1;
            } // hasta aca recorro matriculas
        }
        
        $contador = 1;
    }

    /*
     * Recargo fuera de termino
     * 
     * Retorna el valor en pesos de lo que se cobra por pago fuera de termino 
     * 
     * @param string $fecha_vencimiento     formato dd/mm/yy
     * @param string $fecha_cajas           formato dd/mm/yy
     * 
     * return $valor el total de la bonicicacion si corresponde
     * 
     */

    function obtener_pago_fuera_temino($fecha_vencimiento, $fecha_caja) {

        $dia_por = $this->dias_pasados($fecha_vencimiento, $fecha_caja);

        /* calculo mes de la caja */
        $fecha_caja = (explode('-', $fecha_caja));

        $mes_caja = $fecha_caja[1];

        /* calculo mes del vencimiento */
        $fecha_venci = (explode('-', $fecha_vencimiento));

        $mes_venci = $fecha_venci[1];

        //si el mes de la caja es menos al mes del vencimiento y si dias_por es distinto de 0
        if ($dia_por != 0 && $mes_caja > $mes_venci) {
            //@fixme  obtener este valor de la base
            return 60;
        } else {
            return 0;
        }
    }

    /**
     * En base a los parametros busca si existe vencimiento para ese TIPO DE CARRERA
     * 
     * @param $tipo_materia numeric
     * @param $anio_insc numeric
     * @param $mes_insc numeris
     * @param $cuatri numeric
     * @param $tipo numeric
     * 
     * 
     *
     * TIPO 0 ES ARANCEL 1 ES MATRICULA
     * 
     * return id vencimientos
     */
    function obtener_id_vencimiento_sin_centro_costo($tipo_materia, $anio_insc, $mes_insc, $cuatri, $tipo = 0) {


        //busco todos los vencimientos que coincidan con el tipo de carrera       
        $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                . "ID_TIPO_CARRERA = :tipo_carrera "
                . "AND ANIO_CARRERA IS NULL "
                . "AND CUATRIMESTRE_ANOTO IS NULL AND MES_INSCRIBE "
                . "IS NULL AND COD_CARRERA IS NULL AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo AND ROWNUM = 1  ";

        $param = array(
            $tipo_materia,
            $tipo
        );

        $res = $this->db->query($query, $esParam = true, $param);

        $r = '';

        //Concateno los id para ir filtrando en las restantes queries
        while ($arr_asoc2 = oci_fetch_assoc($res)) {

            $r .= $arr_asoc2['ID'] . ',';
        }

        $ids = substr($r, 0, -1);

        if ($ids) {

            return $ids;
        } else {

            //busco todos los vencimientos que coincidan con el tipo de carrera y todos los demas parametros        
            $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                    . "ID_TIPO_CARRERA = :tipo_carrera "
                    . "AND ANIO_CARRERA = :anio_carrera "
                    . "AND CUATRIMESTRE_ANOTO = :cuatrimestre_anoto "
                    . "AND MES_INSCRIBE = :mes_inscribe  AND ACTIVO = 1 "
                    . "AND TIPO_VENCIMIENTO = :tipo AND COD_CARRERA IS NULL";

            $param = array(
                $tipo_materia,
                $anio_insc,
                $cuatri,
                $mes_insc,
                $tipo
            );

            $res = $this->db->query($query, $esParam = true, $param);

            $r = '';

            //Concateno los id para ir filtrando en las restantes queries
            while ($arr_asoc2 = oci_fetch_assoc($res)) {

                $r .= $arr_asoc2['ID'] . ',';
            }

            $ids = substr($r, 0, -1);

            if ($ids) {

                return $ids;
            } else {

                //busco todos los vencimientos que coincidan con el tipo de carrera y alguno de los demas parametros
                $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                        . "ID_TIPO_CARRERA = :tipo_carrera "
                        . "AND ( ANIO_CARRERA = $anio_insc "
                        . "OR CUATRIMESTRE_ANOTO = $cuatri OR MES_INSCRIBE = $mes_insc"
                        . " ) AND ACTIVO = 1  AND TIPO_VENCIMIENTO = :tipo AND COD_CARRERA IS NULL";

                $param = array(
                    $tipo_materia,
                    $tipo,
                    $anio_insc,
                    $cuatri,
                    $mes_insc
                );

                $res = $this->db->query($query, $esParam = true, $param);

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

                    //busco todos los vencimientos que coincidan con el tipo de carrera y alguno O NINGUNO de los demas parametros
                    $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                            . "ID_TIPO_CARRERA = :tipo_carrera "
                            . "AND (ANIO_CARRERA = :anio_carrera OR ANIO_CARRERA IS NULL) "
                            . "AND (CUATRIMESTRE_ANOTO= :cuatrimestre_anoto OR CUATRIMESTRE_ANOTO IS NULL) "
                            . "AND (MES_INSCRIBE = :mes_inscribe OR MES_INSCRIBE IS NULL) "
                            . "AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo  AND COD_CARRERA IS NULL";

                    $param = array(
                        $tipo_materia,
                        $anio_insc,
                        $cuatri,
                        $mes_insc,
                        $tipo
                    );

                    $res = $this->db->query($query, $esParam = true, $param);

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


        //BUSCO TODOS QUE COINCIDA CON EL CENTRO DE COSTO Y NINGUN OTRO PARAMETRO   
        $query = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                . "ID_CENTRO_COSTO = :idcentrodecosto AND ANIO_CARRERA IS NULL "
                . "AND CUATRIMESTRE_ANOTO IS NULL AND MES_INSCRIBE "
                . "IS NULL AND ACTIVO = 1 AND TIPO_VENCIMIENTO = :tipo AND ROWNUM = 1 ";

        $query_debug = "select * from tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
                . "ID_CENTRO_COSTO = $id_centro_costo AND ANIO_CARRERA IS NULL "
                . "AND CUATRIMESTRE_ANOTO IS NULL AND MES_INSCRIBE "
                . "IS NULL AND ACTIVO = 1 AND TIPO_VENCIMIENTO = $tipo AND ROWNUM = 1 ";


        $param = array(
            $id_centro_costo,
            $tipo
        );

        $res = $this->db->query($query, true, $param);

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

            $res = $this->db->query($query, $esParam = true, $param);

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
                $query = "select * FROM tesoreria.VENCIMIENTOS_ESPECIALES WHERE "
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

                $res = $this->db->query($query, $esParam = true, $param);

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

                    $res = $this->db->query($query, $esParam = true, $param);

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


        //Busco las fechas que coincidan con el concepto que le pasamos     
        $query = "SELECT * FROM tesoreria.VENCIMIENTOS_FECHAS "
                . "WHERE ID_VENCIMIENTO_ESPECIAL = :id_concepto"
                . " ORDER BY NUMERO_CUOTA ASC";

        $param = array(
            $id_concepto
        );

        $res = $this->db->query($query, $esParam = true, $param);

        $r = array();

        //Concateno los id para ir filtrando en las restantes queries
        while ($arr_asoc = oci_fetch_assoc($res)) {

            $r[] = $arr_asoc;
        }

        if ($r) {

            return $r;
        }
    }

    /*
     * Excepciones recargos matriculas
     * 
     * En base al person y a la fecha de la caja , la funcion retorna un codigo de 3 cifras
     * cifras dias atraso/fuera termino /bonificacion
     * 
     * @param $person            string numero que identifica al alumno
     * 
     * return                     string 3 cifras dias atraso/fuera termino /bonificacion
     *                           EJ : 101-->Se aplica dias de atraso , no se aplice fuera termino , si se aplica bonificacion 
     */

    function obtener_excepciones_matriculas($person, $fecha) {


        $linkOracle_class = $this->db;

        $fecha_caja =$fecha;

        $mes_caja = $fecha_caja[1];

        $norecarga = array(90, 87, 89, 91, 95, 96);


        /*         * ************averiguamos el tipo de carrera******************* */

        $query = "select * from contaduria.centrodecosto cc
            JOIN STUDENTC.career sc ON sc.CODE = concat(LPAD(FA, 2, '0'),LPAD(CA, 2, '0'))
            WHERE IDCENTRODECOSTO = :centrocosto ";

        $param = array(
            $this->getIdCentrodeCosto()
        );

        $tipocareer = $linkOracle_class->query($query, $esParam = true, $param);

        $tipocareer = oci_fetch_array($tipocareer);

        $tipocarrera = $tipocareer['CARTYPE'];
        
        $becas = $this->getBeca();
        //si el tipo de alumno es alguno de los que no recargan
        if (in_array($becas[0], $norecarga)) {

            return 0;
        }

        if ($becas[2] == 1) {

            return 0;
        }

        $mesbeca = $this->getMes_beca();
        //si es el primer a�o de la carrera 
                if ($this->getAnio_carrera_cc() == 1 && ($mesbeca[0] == $mes_caja || $mesbeca[0] == $mes_caja + 1)) {

            return 0;
        }

        //Si no pasa por ninguna de las anterioores es por que si recarga
        return 1;
    }

    /**
     * En base al person y a la fecha de la caja , la funcion retorna un codigo de 3 cifras
     * cifras dias atraso/fuera termino /bonificacion
     * 
     * @author lquiroga
     * @version 2.0
     * @package Cobromatriculasaranceles
     * 
     * 
     * @param $person            string numero que identifica al alumno
     *
     * @param $fecha             string formato dd/mm/yy
     * 
     * 
     * return                     string 3 cifras dias atraso/fuera termino /bonificacion
     *                           EJ : 101-->Se aplica dias de atraso , no se aplice fuera termino , si se aplica bonificacion 
     *      
     */
    function obtener_excepciones_arancel($person, $fecha) {


        $linkOracle_class = $this->db;

        $norecarga = array(90, 87, 89, 91, 95, 96);

        $dias_retraso = 1;
        $fuera_termino = 1;
        $bonificacion = 1;

        $fecha_caja = (explode('/', $fecha));
        $mes_caja = $fecha_caja[1];
        $nobonifica = array(95, 96);


        /*         * ************buscamos el tipo de carrera******************* */

        $query = "select * from contaduria.centrodecosto cc
            JOIN STUDENTC.career sc ON sc.CODE = concat(LPAD(FA, 2, '0'),LPAD(CA, 2, '0'))
            WHERE IDCENTRODECOSTO = :centrocosto ";

        $param = array(
            $this->getIdCentrodeCosto()
        );

        $tipocareer = $linkOracle_class->query($query, $esParam = true, $param);

        $tipocareer = oci_fetch_array($tipocareer);

        $tipocarrera = $tipocareer['CARTYPE'];

        /*         * ******************Buscamos si tiene beca*********************** */

        $query = " SELECT * FROM INTERFAZ.AUX_TABDESC where CODIGO_DESC = :cod_desc ";
        $becas = $this->getBeca();
        $mesbecas = $this->getMes_beca();

        $param = array(
            $beca[0]
        );

        $beca = $linkOracle_class->query($query, $esParam = true, $param);

        $beca = $linkOracle_class->fetch_array($beca);


        if ($beca['CODIGO_DESC']) {

            $codigo_beca = $beca['CODIGO_DESC'];
        } else {

            $codigo_beca = 0;
        }

        //@FIXME NO USAR ESTA FUNCION USAR LA DE LA CLASE DB
        /* CENTROS DE COSTO CURSO DE EXTENSION CA 64 */
        $where = 'ca = 64 and IDCENTRODECOSTO = ' . $this->getIdCentrodeCosto();

        $centros_ex_univ_64 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        /* CENTROS DE COSTO CUPTA ADICIONAL INTERCAMBIO CA 60 */
        $where = 'ca = 60 and IDCENTRODECOSTO = ' . $this->getIdCentrodeCosto();

        $centros_ex_univ_60 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        /* CENTROS DE COSTO CON fa 90 */
        $where = 'fa = 90 and IDCENTRODECOSTO = ' . $this->getIdCentrodeCosto();

        $centros_fa_90 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        /*         * ************************************************************* */

        //CASO 1
        //$dias_retraso == RECARGO
        //ccbeca3 significa q el alumno no cobre recargo , 
        //ccmesbeca3 es si al alumno no le podes cobrar fuera de termino
        //si es beca 3 no se cobra fuera de termino
        if ($becas[2] == 1) {

            $dias_retraso = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 2
        //si es beca 3 no se cobra fuera de termino
        if ($mesbecas[2] == 1) {

            $fuera_termino = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 3
        //si es beca1 dentro de las becas (interfaz.aux_tabdesc) y mes_beca1 es mayor al mes de la caja
        if ($codigo_beca != 0 && $mesbecas[0] <= $mes_caja) {

            $dias_retraso = 0;
            $fuera_termino = 0;
            $bonificacion = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 4
        //si es el primer a�o de la carrera 
        if ($this->getAnio_carrera_cc() == 1 && ($mesbeca[0] == $mes_caja || $mesbeca[0] == $mes_caja + 1)) {

            $dias_retraso = 0;
            $fuera_termino = 0;
            $bonificacion = 1;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 7
        //SI es tipo de alumno 95,96
        if (in_array($beca[0], $norecarga)) {

            $dias_retraso = 1;
            $fuera_termino = 1;
            $bonificacion = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 8
        //Si es aspirante (carreras con faesca 040001 y 040006 y la caja es marzo o abril)
        if ($this->getIdCentrodeCosto() == 206 || $this->getIdCentrodeCosto() == 201) {

            if ($mes_caja == 3 || $mes_caja == 4) {

                $dias_retraso = 1;
                $fuera_termino = 0;
                $bonificacion = 1;

                $codigo = $dias_retraso . $fuera_termino . $bonificacion;

                return $codigo;
            }
            $imparan = $this->setCta_arancel();
            //si el mes es mayor a abril y tiene deuda en la primer cuota
            if ($mes_caja > 4 && $imparan[0] != 0) {

                $dias_retraso = 1;
                $fuera_termino = 0;
                $bonificacion = 1;

                $codigo = $dias_retraso . $fuera_termino . $bonificacion;

                return $codigo;
            }
        }
    }

    /*
     * Recargo matriculas
     * 
     *  Devuelve el valor del recargo de una matricula o arancel  , si corresponde. 
     * 
     * @param $fecha_vencimiento            string formato dd/mm/yy
     * @param $fecha_cajas                  tring formato dd/mm/yy
     * @param $importe_matricula            numeric
     * @param $reca_matri_dia               numeric
     * 
     * return $valor el total de la bonicicacion si corresponde
     */

    function recargo_matricula_arancel($fecha_vencimiento, $fecha_caja, $importe_matricula, $reca_matri_dia) {

        
        $linkOracle_class   = $this->db;
        $fuera_termino      = 0;
        $dias_retraso       = 0;
        $bonificacion       = 0;

        $query = "SELECT * FROM tesoreria.ccalu where person = :person";

        $person = $this->getPerson();

        $param = array(
            $person
        );

        $res = $this->db->query($query, true, $param);

        $ccalu = oci_fetch_array($res);

        /*         * ************buscamos el tipo de carrera******************* */

        $query = "select * from contaduria.centrodecosto cc
            JOIN STUDENTC.career sc ON sc.CODE = concat(LPAD(FA, 2, '0'),LPAD(CA, 2, '0'))
            WHERE IDCENTRODECOSTO = :centrocosto ";

        $param = array(
            $this->getIdCentrodeCosto()
        );

        $tipocareer = $linkOracle_class->query($query, $esParam = true, $param);

        $tipocareer = oci_fetch_array($tipocareer);

        $tipocarrera = $tipocareer['CARTYPE'];


        //@FIXME NO USAR ESTA FUNCION USAR LA DE LA CLASE DB
        /* CENTROS DE COSTO CURSO DE EXTENSION CA 64 */
        $where = 'ca = 64 and IDCENTRODECOSTO = ' . $this->getIdCentrodeCosto();

        $centros_ex_univ_64 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        /* CENTROS DE COSTO CUPTA ADICIONAL INTERCAMBIO CA 60 */
        $where = 'ca = 60 and IDCENTRODECOSTO = ' . $ccalu['IDCENTRODECOSTO'];

        $centros_ex_univ_60 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        /* CENTROS DE COSTO CON fa 90 */
        $where = 'fa = 90 and IDCENTRODECOSTO = ' . $ccalu['IDCENTRODECOSTO'];

        $centros_fa_90 = $this->obtener_dato_tabla('contaduria.centrodecosto', 'IDCENTRODECOSTO', $where, $linkOracle_class);

        $dia_por = $this->dias_pasados($fecha_vencimiento, $fecha_caja);

        if ($dia_por != 0) {

            $recargo = $dia_por * $reca_matri_dia;

            $recargo_cuota = ($importe_matricula * $recargo) / 100;

          //  echo($importe_matricula.'--'.$importe_matricula.'---'.$recargo);
            return $recargo_cuota;
            
        } else {

            return 0;
        }

        //CASO 10
        //curso de exension , cuando el CA  de faesCA es 64
        if ($centros_ex_univ_64[0] == $ccalu['IDCENTRODECOSTO']) {

            $dias_retraso = 1;
            $fuera_termino = 0;
            $bonificacion = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //CASO 11
        //curso de exension , cuando el CA  de faesCA es 60 y es intercambio
        if ($centros_ex_univ_60[0] == $ccalu['IDCENTRODECOSTO'] && $ccalu['INTERCAMBIO'] == 1) {

            $dias_retraso = 1;
            $fuera_termino = 0;
            $bonificacion = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }


        //CASO 14
        //FA NUMERO 90 TRANSPORTPE
        if ($centros_fa_90[0] == $ccalu['IDCENTRODECOSTO']) {

            $dias_retraso = 1;
            $fuera_termino = 0;
            $bonificacion = 0;

            $codigo = $dias_retraso . $fuera_termino . $bonificacion;

            return $codigo;
        }

        //retonorno el codigo que identifica cada concepto
        $codigo = $dias_retraso . $fuera_termino . $bonificacion;

        return $dia_por;
    }

    /**
     * DIAS PASADOS PROPIA DE LA CLASE
     * @param type $fecha_inicio
     * @param type $fecha_fin
     * @return int
     */
    function dias_pasados($fecha_inicio, $fecha_fin) {

        $dias_parametrizados = 0;
        $date_ini = explode("-", $fecha_inicio);

        $datetime = new DateTime();
        $datetime->setDate($date_ini[2], $date_ini[1], $date_ini[0]);
        $datetime->format('d/m/y');

        $date_fin = explode("-", $fecha_fin);
        $datetime_fin = new DateTime();
        $datetime_fin->setDate($date_fin[2], $date_fin[1], $date_fin[0]);
        $datetime_fin->format('d/m/y');

        $diferencia = $datetime->diff($datetime_fin);

        $dia_mes_actual = $date_fin[0];

        //Calculo la diferencia de dias de los meses vencidos , restandole los del mes actual
        if ($datetime >= $datetime_fin) {

            return 0;
        } else {

            $diferencia = $diferencia->days - $dia_mes_actual;
        }

        if ($diferencia > 10) {

            $diferencia = $diferencia - 10;
        }

        //La diferencia de dias del mes actual se rige por estos parametros
        if ($dia_mes_actual < 6) {

            $dias_parametrizados = 5;
        } else if ($dia_mes_actual > 5 && $dia_mes_actual < 11) {

            $dias_parametrizados = 10;
        } else if ($dia_mes_actual > 10 && $dia_mes_actual < 21) {

            $dias_parametrizados = 20;
        } else {

            $dias_parametrizados = $dia_mes_actual;
        }

        $dias_total = $dias_parametrizados + $diferencia;

        return($dias_total);
    }

    /**
     * FUNCION GENERICA PARA OBTENER DATOS DE UNA TABLA CON UN WHERE 
     * 
     * $tabla=tabla en la que buscar
     * 
     * $campo campo que queremos
     * 
     * $where condicion sin el comando where
     *
     */
    function obtener_dato_tabla($tabla, $campo, $where, $linkOracle_class = null) {

        $linkOracle_class == $this->db;

        if ($where) {

            $query = "select $campo from $tabla WHERE " . $where;
        } else {

            $query = "select $campo from  " . $tabla;
        }

        $resul = $linkOracle_class->query($query, $esParam = false);

        $result = oci_fetch_array($resul);

        return($result);
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
        if (isset($fila['CARTYPE'])) {
            $this->setCartype($fila['CARTYPE']);
        };
        if (isset($fila['PERSON'])) {
            $this->setPerson($fila['PERSON']);
        };
        //Una vez seteados rtodos los valores busco las matriculas y aranceles con recargo
        $this->ctacteReal();
    }

    /*     * ***********SETTERS & GETTERS*********************** */

    function getMatriculaTotal() {
        return $this->matriculaTotal;
    }

    function getArancelTotal() {
        return $this->arancelTotal;
    }

    function setMatriculaTotal($matriculaTotal) {
        $this->matriculaTotal[] = $matriculaTotal;
    }

    function setArancelTotal($arancelTotal) {
        $this->arancelTotal[] = $arancelTotal;
    }

    function getFueraterminoMatriculas() {
        return $this->fueraterminoMatriculas;
    }

    function getFueraterminoAranceles() {
        return $this->fueraterminoAranceles;
    }

    function setFueraterminoMatriculas($fueraterminoMatriculas) {
        $this->fueraterminoMatriculas[] = $fueraterminoMatriculas;
    }

    function setFueraterminoAranceles($fueraterminoAranceles) {
        $this->fueraterminoAranceles[] = $fueraterminoAranceles;
    }

    function getRecargoMatriculas() {
        return $this->recargoMatriculas;
    }

    function getRecargoAranceles() {
        return $this->recargoAranceles;
    }

    function setRecargoMatriculas($recargoMatriculas) {
        $this->recargoMatriculas[] = $recargoMatriculas;
    }

    function setRecargoAranceles($recargoAranceles) {
        $this->recargoAranceles[] = $recargoAranceles;
    }

    function getPerson() {
        return $this->person;
    }

    function setPerson($person) {
        $this->person = $person;
    }

    function getCartype() {
        return $this->cartype;
    }

    function setCartype($cartype) {
        $this->cartype = $cartype;
    }

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

    function setCta_Matricula($Cta_Matricula) {
        $this->Cta_Matriccula[] = $Cta_Matricula;
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

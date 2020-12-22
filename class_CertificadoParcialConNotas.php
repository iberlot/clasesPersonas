<?php

/**
 * Archivo principar de la clase..
 *
 * Manejo de archivos del sistema solitram
 *
 * @author lquiroga <@> lquiroga@gmail.com
 * @todo FechaC 28/02/2019 - Lenguaje PHP
 *
 * @name class_formularios.php
 *
 */
// require_once ("DerechosVarios.php");
// require_once ("Carreras.php");
// require_once ("Alumnos.php");

require_once ("/web/html/classesUSAL/class_Personas.php");
require_once ("/web/html/classesUSAL/class_derechos_varios.php");
require_once ("/web/html/classesUSAL/class_alumnos.php");
require_once ("/web/html/classesUSAL/class_carreras.php");
require_once ("/web/html/classesUSAL/class_transacciones.php");
require_once ("/web/html/classesUSAL/class_FormsSolitram.php");
require_once ("/web/html/classesUSAL/class_Sedes.php");

class CertificadoParcialConNotas {

    protected $db;
    protected $id;
    protected $student;
    protected $materias = array();
    protected $fecha;
    protected $firmas = array();
    protected $codecarrera;
    protected $carreraDes;
    protected $unidad;
    protected $unidadDesc;
    protected $template;
    protected $plan;
    protected $centrodecosto;
    protected $tipoua;
    protected $fechaIngreso;
    protected $fechaEgreso;
    protected $presentadoa;
    protected $personFirma1 = array();
    protected $personFirma2 = array();
    protected $personFirma3 = array();

    public function __construct($db, $idform) {

        $this->db = $db;
        // Si no hay id o y si tipo devolvemos el html del form
        $formulario = new FormsSecretariaGral($this->db, NULL, $idform);
        
        $data_form = $formulario->getFormById($idform);
        
        if($data_form['CURSOHASTA']){
                    $this->setEgreso($data_form['CURSOHASTA']);
        }
        
        $code = str_pad($formulario->getFa(), 2, "0", STR_PAD_LEFT) . str_pad($formulario->getCa(), 2, "0", STR_PAD_LEFT);

        $carrera = new Carreras($this->db, $code, $formulario->getPlan());

        $faesca = str_pad($formulario->getFa(), 2, "0", STR_PAD_LEFT) . str_pad($formulario->getEs(), 2, "0", STR_PAD_LEFT) . str_pad($formulario->getCa(), 2, "0", STR_PAD_LEFT);
        
        $this->setPlan($formulario->getPlan());
        $this->setStudent($formulario->get_STUDENT());
        $this->setMaterias($carrera->getMateriasCarreraPorAlumno($formulario->get_STUDENT()));
        $this->setCodeCarrera($carrera->get_code());
        $this->setCarreraDes($carrera->get_descrip());
        $this->setUnidad($carrera->get_facu());
        $this->setUnidadDesc($carrera->get_facu()); //-->averiguar como obtener nombre facu
        $this->setPresentadoa($formulario->get_presentadoa()); //-->averiguar como obtener nombre facu

        /* Seteo personas quevan a firmar el certificado */
        $this->setPersonFirma1($formulario->getPerson());

        $sede = new Sedes($this->db, $formulario->getEs());

        $this->setUnidadDesc($sede->getDescrip());

        /* OBTENGO EL CENTRO DE COSTO */
        $parametros = array(
            $faesca
        );

        $query2 = "select idcentrodecosto from contaduria.centrodecosto where" . " substr('000000'|| :faesca , -6) = FAESCA";
        $result = $this->db->query($query2, true, $parametros);

        $idcentrodecosto = $this->db->fetch_array($result);
        $idcentrodecosto = $idcentrodecosto['IDCENTRODECOSTO'];
        $this->setCentrodecosto($idcentrodecosto);

        /* OBTENGO DECADO Y SEC ACAD EN BASE A FA Y ES TRANTYPE4 SE USA PARA ESTE TIPO CERTIFICADOS */
        $sql_autoridades = "select * from studentc.dirfacu 
        FULL JOIN documento.sellofirma ON dirfacu.PERSON = sellofirma.PERSON
        FULL JOIN documento.archivo  ON SELLOFIRMA.DOCUMEN = archivo.IDDOCUMEN
        where (facu = :fa and branch = :es AND TRANTYPE = 4) or facu = 26 and numsign = 0";

        //echo("select * from studentc.dirfacu where facu = ".$formulario->getFa()." and branch = ".$formulario->getEs()." and trantype = 4");
        $param_autori = array(
            $formulario->getFa(),
            $formulario->getEs()
        );

        $autoridades = array();

        $result = $this->db->query($sql_autoridades, true, $param_autori);

        while ($fila = $this->db->fetch_array($result)) {

            $autoridades[$fila['NUMSIGN']] = $fila;


            /* SI SON ALGUNA DE ESTAS DOS PERSON HARDCODEAMOS LA IMG PARA PRUEBAS */
            /*
              if($fila[12] == 403 ){
              $fila['NOMBREARCH']='403.png';
              }

              if($fila[12] == 5013 ){
              $fila['NOMBREARCH']='5013.png';
              }
             */
            if (isset($fila['NUMSIGN']) && $fila['NUMSIGN'] == 0) {

                $this->setPersonFirma1($fila);
            }

            if (isset($fila['NUMSIGN']) && $fila['NUMSIGN'] == 1) {
                $this->setPersonFirma2($fila);
            }

            /* OBTENGO EL PERSON DE LA SEC RAL */
            if ($fila['FACU'] == 26 && $fila['BRANCH'] == 0 && $fila['NUMSIGN'] == 0) {
                $this->setPersonFirma3($fila);
            }
        }
    }

    /**
     * Obtengo el html del certificado 
     * armado con datos y html
     * */
    function obtenerTemplate($path_Img) {
        
        $notas_array = array (
            'Cero','Uno','Dos',
            'Tres','Cuatro',
            'Cinco','Seis',
            'Siete','Ocho',
            'Nueve','Diez');

        $tiposede[0] = 'Facultad';
        $tiposede[2] = 'Instituto';
        $tiposede[3] = 'Rectorado';
        $tiposede[4] = 'Vicerectorado';
        $tiposede[5] = 'Direcci&oacute;n de intercambio';
        $tiposede[7] = 'Secretar&iacute; General';

        setlocale(LC_TIME, 'spanish');
        $nombremes = strftime("%B", mktime(0, 0, 0, date('m'), 1, 2000));

        $student = new Alumnos($this->db, $this->getStudent(), $this->getCentrodecosto());

        $this->setTipoua($student->getDescua());

        /* NOMBRE SEDE */
        if ($this->getUnidadDesc() == 'ORT' || $this->getUnidadDesc() == 'Centro') {
            $nombresede = "Buenos Aires";
        } else {
            $nombresede = $this->getUnidadDesc();
        }

        $html = "<html><head>
            
         <style>
            html{            
            background-image:url(/images/fondo.png);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            }
        
            body{   
            margin:25px!important;
            background-image:url(/images/fondo.png);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            }
        
        table.minimalistBlack tr {
            border-bottom: 1px solid lightgrey;

        }
        table.minimalistBlack {
          border: 1px solid #CCC; 
          width: 90%;
          text-align: left;
          border-collapse: collapse;
          margin:15px 15px 15px 0px;
        }
        table.minimalistBlack td, table.minimalistBlack th {
          border: none!important;
          padding: 5px 4px;
        }
        table.minimalistBlack tbody td {
          font-size: 13px;
        }
        table.minimalistBlack thead {
          background: green;;
          border-bottom: 1px solid #000000;
        }
        table.minimalistBlack thead th {
          font-size: 14px;
          font-weight: bold;
          color: #000000;
          /*text-align: center;*/
          border-left: 1px solid #D0E4F5;
        }
        table.minimalistBlack thead th:first-child {
          border-left: none;
        }

        table.minimalistBlack tfoot td {
          font-size: 14px;
        }
        
        ul{list-style= none;
        }
        
        ul li img{widht:80px;
        max-width:80px;}
        ul li{
            float: left;
            margin: 0% 8%;
            padding:10px 0px;
            text-align: center;
            list-style: none;
        }

        </style>
        </head>
        <body style='margin:-45px!important;padding:35px;'>
        <div style='width:100%!important;margin:0px;padding:0px;'>
        <h2><img style='width:150px;margin-right:50px;margin-left: 15px;' src='../config/imagenes/logoUsal.png' >
        <span style='margin-top:10px;'>UNIVERSIDAD DEL SALVADOR</span>
        </h2><br/>";

        if ($tiposede[$this->getTipoua()] && $student->getDesc_unidad_alumno()) {
            $html.="<h3>" . $tiposede[$this->getTipoua()] . " de " . $student->getDesc_unidad_alumno() . "</h3><br/>";
        }

        $html.="<p><b>Identificación:</b> " . $student->getDoc()->getDocTipo() . ' ' . $student->getDoc()->getDocNumero() . "</p>";
        $html.="<p><b>Apellido y nombre:</b> " . $student->getApellido() . " " . $student->getNombre() . "</p>";
        $html.="<p><b>Año ingreso:</b> " . $student->getAdmyr() . "</p>";
        
        if( $this->getFechaEgreso()){
            $html.="<p><b>Año egreso:</b> " . $this->getFechaEgreso() . "</p>";
        }else{
            $html.="<p><b>Año egreso:</b>  -  </p>";
        }
        
        $html.="<p><b>Carrera:</b> " . $this->getCarreraDes() . " - Plan: " . $this->getPlan() . "</p>";
        $html.="<p>Dado el contexto actual de aislamiento social, preventivo y obligatorio dispuesto por el"
                . " gobierno nacional argentino ante la pandemia de coronavirus COVID-19, "
                . "la Secretaría Académica de  ";
         if ($tiposede[$this->getTipoua()] && $student->getDesc_unidad_alumno()) {
            $html.= $tiposede[$this->getTipoua()] . " de " . $student->getDesc_unidad_alumno() . "";
        }
        
            $html.=" procede a certificar  la siguiente información:  </p>";

        $html.='<br/><br/><table id="table" class="minimalistBlack" >'
                . '<thead class="minimalistBlack"  
            style="background-color: green;
            border: 1px solid black;"><tr>'
                . '<th>Materia</th>'
                . '<th style="text-align:center;">Nota</th>'
                . '<th style="text-align:center;">Fecha de aprobaci&oacute;n</th>'
                . '<th style="text-align:center;">Libro</th>'
                . '<th style="text-align:center;">Folio</th>'
                . '</tr></thead><tbody>';

        foreach ($this->getMaterias() as $row) {
            $date_appr= new DateTime($row['APPRDATE']);
            $date_appr = $date_appr->format('d-m-Y ');
            $html.="<tr>";
            $html.="<td>" . $row['DESCRIP'] . "</td>";
            $html.="<td style='text-align:center;'>" . $row['RESULT'] . "(".$notas_array[$row['RESULT']].")</td>";
            $html.="<td style='text-align:center;'>" . $date_appr . "</td>";
            $html.="<td style='text-align:center;'>" . $row['BOOK'] . "</td>";
            $html.="<td style='text-align:center;'>" . $row['PAGE'] . "</td>";
            $html.="</tr>";
        }

        $html.="</tbody>";
        $html.="</table><br/><br/>";

        $html.="<p>A pedido del/la alumno/a, se expide el presente certificado provisorio, "
                . "en " . $nombresede . ",<br/> a los " . date('d') . " d&iacute;as del mes "
                . "de " . ucwords($nombremes) . " de " . date('Y') . ", para ser"
                . " presentado ante " . $this->getPresentadoa() . "</p><br/><br/><br/>";


        $person0 = $this->getPersonFirma1();
        $person1 = $this->getPersonFirma2();
        $person2 = $this->getPersonFirma3();

        $html.="<table style='width: 90%;'>"
                . "<tr style='text-align:center;font-size:10px!important;'>";
        
        if(isset($person0['NAME']) && isset($person1['NAME'])){
            
            if (isset($person0['NAME'])) {
                $html.="<td><img style='width:80px;'  src='" . $path_Img . $person0['NOMBREARCH'] . "'><br/></br>" . $person0['NAME'] . "<br/>" . $person0['CHARGE'] . "</td>";
            }

            if (isset($person1['NAME'])) {
                $html.="<td><br/><img style='width:80px;'  src='" . $path_Img . $person1['NOMBREARCH'] . "'><br/></br>" . $person1['NAME'] . "<br/>" . $person1['CHARGE'] . "</td>";
            }
        }else{
            
            if (isset($person0['NAME'])) {
                $html.="<td colspan='2'><img style='width:80px;'  src='" . $path_Img . $person0['NOMBREARCH'] . "'><br/></br>" . $person0['NAME'] . "<br/>" . $person0['CHARGE'] ."";
            }

            if (isset($person1['NAME'])) {
                $html.="<br/><img style='width:80px;'  src='" . $path_Img . $person1['NOMBREARCH'] . "'><br/></br>" . $person1['NAME'] . "<br/>" . $person1['CHARGE'] . "</td>";
            }
            
        }
        $html.="</tr>";
        
        $html.="<tr style='text-align:center;font-size:15px!important;'>";
        $html.="<td colspan='2'><br/><p style='text-align:center;font-size:15px!important;'>Se efect&uacute;a la presente legalizaci&oacute;n al "
             . "solo efecto de certificar que la firma que antecede "
             . "y dice ".$person0['NAME']." es aut&eacute;ntica.<br/></p></td>";        
        $html.="</tr>";
        
        $html.="<tr style='text-align:center;font-size:10px!important;'>";
           if (isset($person2['NAME'])) {
            $html.="<br/><td  colspan='2' align='center'><img style='width:80px'  src='" . $path_Img . $person2['NOMBREARCH'] . "'><br/></br>" . $person2['NAME'] . "<br/>" . $person2['CHARGE'] . "</td>";
        }
        $html.="</tr>";
        $html.="</table>";
        $html.="</div>";
        $html.="</body>";
        $html.="</html";

        return $html;
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

        if (isset($fila['FECHAC'])) {
            $this->set_fecha_crecion($fila['FECHAC']);
        }
    }

    /*     * ************GETTER***************** */

    function getDb() {
        return $this->db;
    }

    function getId() {
        return $this->id;
    }

    function getStudent() {
        return $this->student;
    }

    function getMaterias() {
        return $this->materias;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getFirmas() {
        return $this->firmas;
    }

    function getCodeCarrera() {
        return $this->codecarrera;
    }

    function getCarreraDes() {
        return $this->carreraDes;
    }

    function getUnidad() {
        return $this->unidad;
    }

    function getUnidadDesc() {
        return $this->unidadDesc;
    }

    function getTemplate() {
        return $this->template;
    }

    function getPlan() {
        return $this->plan;
    }

    function getCentrodecosto() {
        return $this->centrodecosto;
    }

    function getTipoua() {
        return $this->tipoua;
    }

    function getPresentadoa() {
        return $this->presentadoa;
    }

    function getPersonFirma1() {
        return $this->personFirma1;
    }

    function getPersonFirma2() {
        return $this->personFirma2;
    }

    function getPersonFirma3() {
        return $this->personFirma3;
    }
    function getFechaIngreso() {
        return $this->fechaIngreso;
    }
    function getFechaEgreso() {
        return $this->fechaEgreso;
    }

    /*     * ************SETTER***************** */

    function setDb($db) {
        $this->db = $db;
    }

    function setPresentadoa($presentadoa) {
        $this->presentadoa = $presentadoa;
    }
    function setIngreso($fechaingreso) {
        $this->fechaIngreso= $fechaingreso;
    }
    function setEgreso($fechaEngreso) {
        $this->fechaEgreso= $fechaEngreso;
    }

    function setTipoua($tipoua) {
        $this->tipoua = $tipoua;
    }

    function setCentrodecosto($centrodecosto) {
        $this->centrodecosto = $centrodecosto;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setStudent($student) {
        $this->student = $student;
    }

    function setMaterias($materias) {
        $this->materias = $materias;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setFirmas($firmas) {
        $this->firmas = $firmas;
    }

    function setCodeCarrera($codecarrera) {
        $this->codecarrera = $codecarrera;
    }

    function setCarreraDes($carreraDes) {
        $this->carreraDes = $carreraDes;
    }

    function setUnidad($unidad) {
        $this->unidad = $unidad;
    }

    function setUnidadDesc($unidadDesc) {
        $this->unidadDesc = $unidadDesc;
    }

    function setTemplate($template) {
        $this->template = $template;
    }

    function setPlan($plan) {
        $this->plan = $plan;
    }

    function setPersonFirma1($personFirma1) {
        $this->personFirma1 = $personFirma1;
    }

    function setPersonFirma2($personFirma2) {
        $this->personFirma2 = $personFirma2;
    }

    function setPersonFirma3($personFirma3) {
        $this->personFirma3 = $personFirma3;
    }

}

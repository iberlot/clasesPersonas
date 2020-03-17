<?php

// require_once ("DerechosVarios.php");
// require_once ("Carreras.php");
// require_once ("Alumnos.php");
// require_once ("Formularios.php");
// require_once ("Session.php");
require_once ("/web/html/classesUSAL/class_Personas.php");
require_once ("/web/html/classesUSAL/class_derechos_varios.php");
require_once ("/web/html/classesUSAL/class_alumnos.php");
require_once ("/web/html/classesUSAL/class_carreras.php");
require_once ("/web/html/classesUSAL/class_FormsSolitram.php");
require_once ("/web/html/classes/class_Session.php");
require_once ("/web/html/classes/class_files.php");

/**
 *
 * Description of FormsSecretariaGral
 *
 * Extension de la clase formularios . Son formularios pero con datos extras
 * que se guardan en la tabla "TESORERIA"."FORMULARIOSECGRAL"
 *
 * @author lquiroga
 * @since 28 Jun. 2019
 * @name FormsSecretariaGral
 * @version 1.0 version inicial
 * 
 */
class FormsSecretariaGral extends Formularios {

    /**
     * Conexion a db
     *
     * @var resource
     */
    protected $db;

    /**
     * Id que identifica el formulario
     *
     * @var int
     */
    protected $id;

    /**
     * Id que identifica al tipo de formulario
     *
     * @var int
     */
    protected $idformulario;

    /**
     * Nombre de la entidad a la cual va a ser presentado el documento solicitado
     *
     * @var string
     */
    protected $presentadoa;

    /**
     * Email personal del alumno     
     *
     * @var string
     */
    protected $emailpersonal;

    /**
     * Telefono celular del alumno
     *
     * @var string
     */
    protected $celular;

    /**
     * Nombre de las materias que se para equivalencia
     *
     * @var string
     */
    protected $obligacadeaprob;

    /**
     * Nombre de las materias que se piden equivalencia en caso de ser necesario
     *
     * @var string
     */
    protected $equivalenciasoli;

    /**
     * Id que identifica al documento subido en la tabla documentos.documento
     *
     * @var int
     */
    protected $iddocumen1;

    /**
     * Id que identifica al documento subido en la tabla documentos.documento
     *
     * @var int
     */
    protected $iddocumen2;

    /**
     * Template hrml de los formularios
     *
     * @var string
     */
    protected $html_template;

    /**
     * Nombre del formulario
     *
     * @var string
     */
    protected $nombre_form;

     /**
     *Ambito nacional o internacional
     *
     * @var int
     */
    protected $ambito;
    
    
     /**
     *Copime
     *
     * @var int
     */
    protected $copime;

    /**
     * Descripcion para mostrar del formuilario seleccionado
     *
     * @var string
     */
    protected $descripcion;

    /**
     * Constructor de la clase.
     *
     * @param class_db $db
     * @param int $tipo
     * @param int $id
     */
    public function __construct($db, $tipo = null, $id = null) {

        $this->db = $db;

        $this->set_tipo_form('Formulario secretaria general');

        // Si no hay id o y si tipo devolvemos el html del form
        if ($tipo != null && $tipo != '' && $id == null && $id == '') {

            $this->template_html($tipo);

            $this->set_descripcion($this->obtenerNombreForm($tipo));
        }

        // Si tipo es null pero id no , devolvemos los datos del form
        if (($tipo == null || $tipo == '') && ($id != null || $id != '')) {

            $parametros = array(
                $id
            );

            $query = "SELECT FORMULARIO.*  , FORMULARIOSECGRAL.*
                        FROM FORMULARIOS JOIN tesoreria.formulariosecgra ON
                        FORMULARIOS.ID = FORMULARIOSECGRAL.IDFORMULARIO
                        WHERE FORMULARIOS.id = :id ";

            $result = $this->db->query($query, true, $parametros);

            if ($result) {

                $arr_asoc = $db->fetch_array($result);

                $this->loadData($arr_asoc);
            }
        }
    }

    /**
     *
     * Obtiene el form de la tabla form , y tambien de form de secretaria
     *
     * @param int $id
     * @return array
     * 
     */
    public function getFormById($id) {

        $parametros = array(
            $id
        );

        // $this->db = Conexion::openConnection();

        $query = "SELECT formulario.* ,
                formulariosecgral.idformulario,
                formulariosecgral.presentadoa,
                formulariosecgral.emailpersonal,
                formulariosecgral.celular,
                formulariosecgral.obligacadeaprob,
                formulariosecgral.equivalenciasoli,
                formulariosecgral.equivalenciasoli,
                formulariosecgral.iddocumen1,
                formulariosecgral.iddocumen2,
                formulariosecgral.PROGRAMA,
                formulariosecgral.HORASCATEDRA,
                formulariosecgral.AMBITO,
                formulariosecgral.PLANESTUDIO,
                formulariosecgral.COPIME
                FROM formulario
		JOIN tesoreria.formulariosecgral ON formulario.id = formulariosecgral.idformulario
                WHERE formulario.id = :id ";

        $result = $this->db->query($query, true, $parametros);

        $form = $this->db->fetch_array($result);

        $form['materias'] = $this->get_materias($form['ID']);

        $form['NOMBRE_FORM'] = $this->obtenerNombreForm($form['IDTIPOFORM']);

        return ($form);
    }

    /**
     * En base al tipo de form que recibimos , mostramos
     * el template correspondiente
     *
     * @param string $tipo
     *        	--> id de tipo formulario
     * @return string con el html
     *
     */
    public function template_html($tipo, $data = null, $lectura = 0) {

        // $fecha_actual = date ("d/m/Y");
        $template = '';

        // Id tipos form , menosres de 100 son tipos de alumnos, formularios de cobranza
        // de 100 a 200 son formularios de secretaria general
        if (!$data) {
            
            $alumno = new Alumnos($this->db, Session::get('personSelect'), Session::get('solitramcentrodecosto'));
            
            $emails_alumno =$alumno->getEmail();
            
            if(isset($emails_alumno[1])){                
                $email  = $emails_alumno[1];                
            }else{
                $email  = $emails_alumno[0];    
            }
            
            $telefono   =$alumno->getTelefono();
            $telefono   =$telefono[0]['NUMERO'];
            
            switch ($tipo) {

                case '110' :

                    $template .='<input type="hidden" value="110" name="IDSECGRAL">' 
                        . '<input type="hidden" value="110" name="tipoform">' 
                        . '<label>*Para ser presentado ante:</label>' 
                        . '<input type="text" name="presentadoa" id="presentadoa" required>'                        
                        . '<label>*Email personal:</label>' 
                        . '<input type="text" name="email" id="email" value="'.$emails_alumno[0].'" >' 
                        . '<label>*Tel&eacute;fono celular:</label>' 
                        . '<input type="text" name="cel" id="cel" value="'.$telefono.'"><br/><br/>' 
                        . '<label class="check_secgral">&Aacute;mbito </label>' 
                        . '<select name="ambito"  id="sleambito_1" onchange="seleccionarambito(1)">'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0">Nacional </option>'
                        . '<option value="1">Internacional</option>'
                        . '</select><br/>' 
                        . '<div class="copime" style="display:none;">'
                        . '<label class="check_secgral" >Copime</label>' 
                        . '<input type="checkbox" id="copime_select" value="1" name="copime" disabled class="select_secgral">'
                        . '</div>'
                        . '<br/><br/>' 
                        . '<p><b>Items a solicitar</b></p>' 
                        . '<label  class="check_secgral"> Plan de Estudio:</label>' 
                        . '<input type="checkbox" name="planestudio" checked><br/>'
                        . '<label class="check_secgral"> Programas:</label>' 
                        . '<input type="checkbox" name="programa"  checked ><br/>'
                        . '<label class="check_secgral">Horas c&aacute;tedra:</label>' 
                        . '<input type="checkbox" name="horascatedras"  ><br/><br/>'
                        . '<label>Plan de estudio:</label><br/>' 
                        . '<input type="file" name="plestudio" id="plestudio"><br/>' 
                        . '<label>Programa de la materia:</label><br/>' 
                        . '<input type="file" name="prmateria" id="prmateria"><br/>';

                    break;

               /* case '111' :

                    $template .= '<input type="hidden" value="111" name="IDSECGRAL">' 
                        . '<input type="hidden" value="111" name="tipoform">' 
                        . '<label>*Para ser presentado ante:</label>' 
                        . '<input type="text" name="presentadoa" id="presentadoa">' 
                        . '<br/><label class="check_secgral">&Aacute;mbito</label>' 
                        . '<select name="ambito"  id="sleambito_2" onchange="seleccionarambito(2)>'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0">Nacional </option>'
                        . '<option value="1">Internacional</option>'
                        . '</select><br/>'
                        . '<div class="copime" style="display:none;">'
                        . '<label  class="check_secgral">Copime</label>' 
                        . '<input type="checkbox" id="copime_select" value="1" name="copime" disabled class="select_secgral">'
                        . '</div><br/><br/><br/>'  ;

                    break;
*/
                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">' 
                        . '<input type="hidden" value="112" name="tipoform">' 
                        . '<label>*Para ser presentado ante:</label>' 
                        . '<input type="text" name="presentadoa" id="presentadoa"><br/>'
                        . '<br/><label class="check_secgral">&Aacute;mbito</label>' 
                        . '<select name="ambito"  id="sleambito_3" onchange="seleccionarambito(3)" >'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0">Nacional </option>'
                        . '<option value="1">Internacional</option>'
                        . '</select><br/>' 
                        . '<label class="check_secgral" style="display:none;">Copime</label>' 
                        . '<div class="copime" style="display:none;">'
                        . '<label  class="check_secgral"  >Copime</label>' 
                        . '<input type="checkbox" id="copime_select" value="1" name="copime" disabled class="select_secgral">'
                        . '</div>'
                        . '<label class="check_secgral" >Tipo de equivalencia</label>'
                        . '<select name="tipoequiv"  id="tipoequiv" >'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0">Interno </option>'
                        . '<option value="1">Externo</option>'
                        . '</select><br/>' ;

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">' 
                        . '<input type="hidden" value="113" name="tipoform">' 
                        . '<label class="check_secgral">&Aacute;mbito</label>' 
                        . '<select name="ambito" id="sleambito_4" onchange="seleccionarambito(4)" >'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0">Nacional </option>'
                        . '<option value="1">Internacional</option>'
                        . '</select><br/>' 
                        . '<div class="copime" style="display:none;">'
                        . '<label  class="check_secgral" >Copime</label>' 
                        . '<input type="checkbox" id="copime_select" value="1" name="copime" disabled class="select_secgral">'
                        . '</div>'
                        . '</br><p>Me es grato dirigirme a usted,' 
                        . ' con el fin de solicitarle sean reconocidas como equivalentes a las materias' 
                        . ' que a continuaci&oacute;n detallo, aprobadas en: </p> ' 
                        . '<br/><p>*Obligaci&oacute;n acad&eacute;mica aprobada:</p><textarea name="obli_acade_aproba" id="obli_acade_aproba" ></textarea>';

                    break;

                default :

                    break;
            }
            
        } else {

            $solicitar = '';
            
              if($data['HORASCATEDRA'] == '1'){
                    $solicitar.='Horas Catedra -';                   
                };
                    
                    if($data['PROGRAMA'] == '1'){                        
                        $solicitar.=' Programa -';  
                    };
                    
                    if($data['PLANESTUDIO'] == '1'){
                       $solicitar.=' Plan de estudios -';  
                    };
                    
                    if($data['AMBITO'] == '1'){
                        $ambito='Internacional';
                    }else{
                        $ambito='Nacional';
                    };
                    
                    if($data['COPIME'] == '1'){
                        $copime='Si';
                        $copime_display= " style='display:inline-block' ";
                    }else{
                        $copime='No';
                        $copime_display=" style='display:none' ";
                    };

            switch ($tipo) {                
                  
                case '110' :                                   

                    $template .= '<input type="hidden" value="110" name="IDSECGRAL">' 
                        . '<input type="hidden" value="110" name="tipoform">'    
                        . '<label><b>Email personal: </b>'.$data['EMAILPERSONAL'].' </label><br/>' 
                        . '<label><b>Tel&eacute;fono celular: </b>'.$data['CELULAR'].' </label><br/>' 
                        . '<label><b>Para ser presentado ante: </b>'.utf8_encode($data['PRESENTADOA']).' </label>'
                        . '</br><label><b>&Aacute;mbito: </b> '.utf8_encode($ambito).'</label>'                        
                        . '<label '.$copime_display.'>COPIME</label><br/>'                                               
                        /*. '<label class="check_secgral">&Aacute;mbito </label>' 
                        . '<select name="ambito"  id="sleambito_1" disabled>'
                        . '<option value="s" >Seleccione</option>'
                        . '<option value="0"  '.$ambito_0.'>Nacional </option>'
                        . '<option value="1"  '.$ambito_1.'>Internacional</option>'
                        . '</select><br/>' */
                        /*. '<div class="copime" '.$copime_display.' ><br/>'
                        . '<label class="check_secgral" >COPIME</label>' 
                        . '<select id="copime_select" name="copime" disabled class="select_secgral" >'
                        . '<option value="1" '.$copime_1.'>SI</option>'
                        . '<option value="0" '.$copime_0.'>NO </option>'
                        . '</select></div>'*/
                        . '<p><b>Items a solicitar: </b>'.substr($solicitar,0,-1).'</p>';

                    if (isset($data['IDDOCUMEN1'])) {

                        $template .= '<label>Archivo 1:</label><br/>';

                        // inicializo la clase
                        $archivo1 = new files($this->db, $data['IDDOCUMEN1']);

                        $template .= '<a href="descargararchivo.php?i=' . $data['IDDOCUMEN1'] . '">' . $archivo1->get_nombrearch() . '-' . $data['IDDOCUMEN1'] . '</a>';
                    }

                    if (isset($data['IDDOCUMEN2'])) {

                        $template .= '<br/><label>Archivo 2:</label><br/>';

                        $archivo2 = new files($this->db, $data['IDDOCUMEN2']);

                        $template .= '<a href="descargararchivo.php?i=' . $data['IDDOCUMEN2'] . '">' . $archivo2->get_nombrearch() . '-' . $data['IDDOCUMEN2'] . '</a>';
                    }

                    break;

              /*  case '111' :

                    $template .= '<input type="hidden" value="111" name="IDSECGRAL">'
                            . '<input type="hidden" value="111" name="tipoform">'
                            . '<label><b>&Aacute;mbito:</b> '.$ambito.'</label>'                        
                            . '<label '.$copime_display.'>COPIME</label><br/>' 
                            . '<label><b>Para ser presentado ante: </b>'.utf8_encode($data['PRESENTADOA']).' </label>'                           
                           /* . '<label class="check_secgral">&Aacute;mbito </label>' 
                            . '<select name="ambito"  id="sleambito_1" disabled>'
                            . '<option value="s" >Seleccione</option>'
                            . '<option value="0"  '.$ambito_0.'>Nacional </option>'
                            . '<option value="1"  '.$ambito_1.'>Internacional</option>'
                            . '</select><br/>' 
                            . '<div class="copime" '.$copime_display.' >'
                            . '<label class="check_secgral" >COPIME</label>' 
                            . '<select id="copime_select" name="copime" disabled class="select_secgral" >'
                            . '<option value="1" '.$copime_1.'>SI</option>'
                            . '<option value="0" '.$copime_0.'>NO </option>'
                            . '</select></div><br/><br/>' ;*/
                           /* . '<br/><br/>' ;

                    break;*/

                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">' 
                            . '<input type="hidden" value="112" name="tipoform">'                                                         
                            . '<label><b>Para ser presentado ante: </b>'.utf8_encode($data['PRESENTADOA']).' </label><br/>' 
                            . '<label><b>&Aacute;mbito:</b> '.$ambito.'</label>'                        
                            . '<label '.$copime_display.'>COPIME</label><br/>'                              
                            /*. '<label class="check_secgral">&Aacute;mbito </label>' 
                            . '<select name="ambito"  id="sleambito_1" disabled>'
                            . '<option value="s" >Seleccione</option>'
                            . '<option value="0"  '.$ambito_0.'>Nacional </option>'
                            . '<option value="1"  '.$ambito_1.'>Internacional</option>'
                            . '</select><br/>' 
                            . '<div class="copime" '.$copime_display.' >'
                            . '<label class="check_secgral" >COPIME</label>' 
                            . '<select id="copime_select" name="copime" disabled class="select_secgral" >'
                            . '<option value="1" '.$copime_1.'>SI</option>'
                            . '<option value="0" '.$copime_0.'>NO </option>'
                            . '</select></div>' ;*/
                            . '<br/>' ;

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">' 
                            . '<input type="hidden" value="113" name="tipoform">' 
                            . '<label><b>&Aacute;mbito:</b> '.$ambito.'</label>'                        
                            . '<label '.$copime_display.'>COPIME</label><br/>'    
                        /* . '<label class="check_secgral">&Aacute;mbito </label>' 
                            . '<select name="ambito"  id="sleambito_1" disabled>'
                            . '<option value="s" >Seleccione</option>'
                            . '<option value="0"  '.$ambito_0.'>Nacional </option>'
                            . '<option value="1"  '.$ambito_1.'>Internacional</option>'
                            . '</select><br/>' 
                            . '<div class="copime" '.$copime_display.' >'
                            . '<label class="check_secgral" >COPIME</label>' 
                            . '<select id="copime_select" name="copime" disabled class="select_secgral" >'
                            . '<option value="1" '.$copime_1.'>SI</option>'
                            . '<option value="0" '.$copime_0.'>NO </option>'
                            . '</select></div>'*/
                            . '</br><p>Me es grato dirigirme a usted,' 
                            . ' con el fin de solicitarle sean reconocidas como equivalentes a las materias' 
                            . ' que a continuaci&oacute;n detallo, aprobadas en: </p> ' 
                            . '<br/><p>Obligaci&oacute;n acad&eacute;mica aprobada:</p>' 
                            . '<textarea disabled  name="obli_acade_aproba" id="obli_acade_aproba" >' . utf8_encode($data['OBLIGACADEAPROB']) . '</textarea>';


                    break;

                default :

                    break;
            }
        }

        // Estos forms son los que necesitan listas de materias , si entra por aca , devuelve un select
        // con las materias , si hay datos devuelve las materias seleccionadas en un div aparte , las demas en
        // select
        if (/*$tipo == '111' ||*/ $tipo == '112' || $tipo == '113') {
           
            $html_mat_sel = '';

            if ((Session::get('personSelect') == null || Session::get('personSelect') == '') && ($data['STUDENT'] == null || $data['STUDENT'] == '')) {

                // throw new Exception('Alumno sin person');
            } else {

                if (!$data) {

                    $alumno = new Alumnos($this->db, Session::get('personSelect'), Session::get('solitramcentrodecosto'));
                } else {

                    $alumno = new Alumnos($this->db, $data['STUDENT'], $data['idcentrodecosto']);
                }

                $carrera = new Carreras($this->db);

                if ($tipo == '113') {

                    $estados = '2,3';

                    $aprobadas = $alumno->MateriasAprxPlanCarrera($alumno->getPerson(), $alumno->getCarrera(), $alumno->getPlan(), $estados);
                                    
                } else {

                    $estados = '2,3';

                    $aprobadas = $alumno->MateriasAprxPlanCarrera($alumno->getPerson(), $alumno->getCarrera(), $alumno->getPlan(), $estados);
                         
                }

                $materias = $carrera->getMateriasPorPlan($alumno->getCarrera(), $alumno->getPlan(), $aprobadas);
                // var_dump($alumno->getCarrera());
                // Si no hay data devuelve el select de materias
                if (!$data) {

                    if ($materias != '') {

                        $template .= '<label>Materias</label>';

                        $template .= '<ul id="listado_materias">';

                        $template .= "<select id='select_materias' >";

                        foreach ($materias as $row) {

                            if ($tipo == '111') {

                                $template .= "<option class='option_materia'  id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . utf8_encode($row["SDESC"]) . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                            
                                
                            } else if ($tipo == '113') {

                                $template .= "<option class='option_materia' ' id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . utf8_encode($row["SDESC"]) . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                            
                                
                            } else {

                                $template .= "<option class='option_materia' id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . utf8_encode($row["SDESC"]) . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                            }
                        }

                        if ($tipo == '111') {

                            $template .= "<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia(5)'><br/>";
                        
                            
                        } else if ($tipo == '113') {

                            $template .= "<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia(10)'><br/>";
                        
                            
                        } else {

                            $template .= "<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia()'><br/>";
                        }

                        $template .= "</select><br/><label>Materias seleccionadas: </label>";
                    } else {

                        $template .= "<label>El alumno no posee materias para seleccionar. </label><br/>";
                    }

                    /*
                     * if($tipo == '111'){
                     * $template.="<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia(5)'><br/>";
                     * }else{
                     * $template.="<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia(10)'><br/>";
                     * }
                     */

                    $template .= "<div id='materiasseleccionadas'><br/></div>";
                    
                } else {
                 
                    // Si hay data devuelve select con materias que no esten seleccionadas y las seleccionadas aparte
                    $total_horas = 0;

                    $mat_cargadas = array();

                    if (isset($data['materias'])) {

                        foreach ($data['materias'] as $row) {

                            $mat_cargadas[] = $row['SUBJECT'];
                        }
                                                
                        if ($materias) {

                            foreach ($materias as $row) {

                                if (in_array($row["SUBJECT"], $mat_cargadas)) {

                                    $total_horas += $row["CARGA_HORARIA"];

                                    $html_mat_sel .= '
                                    <p class="mat_seleccionada mat_seleccionada_' . $row["SUBJECT"] . '"> ' . $row["SUBJECT"] . ' - A&ntilde;o: ' . $row["YR"] . ' - ' . $row["SDESC"] . '  <span title="' . $row["SDESC"] . '"  class="quitar_materia">
                                    </span></p>';

                                    // $html_mat_sel.='<input id="hidde_'.$row["SUBJECT"].'" type="hidden" name="materias[]" value="'.$row["SUBJECT"].'" />';
                                }
                            }
                        }
                    }
             
                    // $template.="<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia()'><br/>";
                    if ($html_mat_sel == '') {

                        $template .= "<br/><label>Materias seleccionadas: </label><div id='materiasseleccionadas'><br/></div>";
                    } else {

                        $template .= "<br/><label>Materias seleccionadas: </label><div id='materiasseleccionadas'><br/>";

                        $template .= $html_mat_sel;

                        $template .= "</div>";
                    }

                    $template .= '<p class="recordatorio_ayuda">Con un total de ' . $total_horas . ' horas reales anuales.</p>';
                    // $template.='<br/><p>Comentario</p><textarea name="mensaje" id="mensaje" ></textarea>';
                }
            }
            
            $template.='<label for="cantidad_materias"></label><input type="text" style="display:none;" name="cantidad_materias" value="0" id="cantidad_materias">';
        }

        if (!$data) {

            $template .= '<br/><p>Comentario</p>' . '<textarea name="mensaje" id="mensaje" ></textarea>';
        } else {

            if ($data["IDESTADO"] == 1) {

                $template .= '<br/><p>Comentario</p>' . '<textarea name="mensaje" id="mensaje" ></textarea>';
            } else {
                /*
                 * $template.= '<br/><p>Comentario</p>'
                 * . '<textarea disabled name="mensaje" id="mensaje" >' . trim($data["COMENTARIO"]) . '</textarea>';
                 */
            }
        }

        $template .= '<div id="loader" class="loader" style="display:none;"> <img src="/images/loading2.gif"> </div>';

        $this->set_html_template($template);

        return $template;
    }

    /**
     *
     * En base al tipo de form obtenemos el nombre
     *
     * @param int $id
     * @return string
     *
     */
    public function obtenerNombreForm($tipo) {

        $nombre = '';

        switch ($tipo) {
            case 110 :
                $nombre = 'Formulario de solicitud de programa';

                break;
            case 111 :
                $nombre = 'Formulario certificado parcial con notas (5 materias)';

                break;
            case 112 :
                $nombre = 'Certificado de equivalencias';

                break;
            case 113 :
                $nombre = 'Certificado de parcial con notas';

                break;

            default :
                break;
        }

        return ($nombre);
    }

    /**
     *
     * saveSecretariaForm : guarda datos adicionales de los forms de secretaria
     *
     * @param array $datos
     *        	DE LA TABLA FORMULARIOMATERIAS
     *
     *        	ID - IDFORMULARIO - PRESENTADOA - TITULOSECUNDARIO - EXPEDIDOPOR - EMAILPERSONAL - CELULAR -
     *        	OBLIGACADEAPROB - EQUIVALENCIASOLI - IDDOCUMEN1 - IDDOCUMEN2
     *
     * @return BOOL
     *
     */
    public function saveSecretariaForm($datos) {

        $datos['ID'] = 'FORMULARIOSECGRAL_SEQ.nextval';

        $datos['IDFORMULARIO'] = $this->db->insert_id('ID', 'FORMULARIO');

        if (isset($datos['MENSAJE'])) {
            $datos['MENSAJE'] = $datos['MENSAJE'];
        }

        if (isset($datos['PRESENTADOA'])) {
            $datos['PRESENTADOA'] = $datos['PRESENTADOA'];
        }

        $insercion = $this->db->realizarInsert($datos, 'FORMULARIOSECGRAL');

        return $insercion;
    }

    /**
     *
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *        	return objet From secretaria gral
     *
     */
    public function loadData($fila) {

        // cargo utilizo el load data de la clase padre
        parent::loadData($fila);

        switch ($this->get_tipo_form()) {

            case 110 :
                $nombre = 'Formulario de solicitud de programa';

                break;
            case 111 :
                $nombre = 'Formulario certificado parcial con notas (5 materias)';

                break;
            case 112 :
                $nombre = 'Formulario certificado parcial con notas (10 materias)';

                break;
            case 113 :
                $nombre = 'Formulario certificado de equivalencias';

                break;

            default :

                break;
        }

        $this->set_nombre_form($nombre);

        if (isset($fila['IDFORMULARIO'])) {
            $this->set_IDFORMULARIO($fila['IDFORMULARIO']);
        }

        if (isset($fila['PRESENTADOA'])) {
            $this->set_PRESENTADOA($fila['PRESENTADOA']);
        }

        if (isset($fila['EMAILPERSONAL'])) {
            $this->set_EMAILPERSONAL($fila['EMAILPERSONAL']);
        }

        if (isset($fila['CELULAR'])) {
            $this->set_celular($fila['CELULAR']);
        }

        if (isset($fila['OBLIGACADEAPROB'])) {
            $this->set_OBLIGACADEAPROB($fila['OBLIGACADEAPROB']);
        }

        if (isset($fila['EQUIVALENCIASOLI'])) {
            $this->set_EQUIVALENCIASOLI($fila['EQUIVALENCIASOLI']);
        }

        if (isset($fila['EQUIVALENCIASOLI'])) {
            $this->set_EQUIVALENCIASOLI($fila['EQUIVALENCIASOLI']);
        }

        if (isset($fila['IDDOCUMEN1'])) {
            $this->set_IDDOCUMEN1($fila['IDDOCUMEN1']);
        }

        if (isset($fila['IDDOCUMEN2'])) {
            $this->set_IDDOCUMEN1($fila['IDDOCUMEN2']);
        }
        
        if (isset($fila['AMBITO'])) {
            $this->setAmbito($fila['AMBITO']);
        }
        if (isset($fila['AMBITO'])) {
            $this->setAmbito($fila['AMBITO']);
        }
        
        if (isset($fila['COPIME'])) {
            $this->setCopime($fila['COPIME']);
        }
    }

    /*     * ********GETTERS******** */

    /**
     * Retorna el valor del atributo descripcion
     *
     * @return string $descripcion el dato de la variable.
     */
    function get_nombre_form() {
        return $this->descripcion;
    }

    /**
     * Retorna el valor del atributo html_template
     *
     * @return string html_template el dato de la variable.
     */
    function get_html_template() {
        return $this->html_template;
    }

    /**
     * Retorna el valor del atributo db
     *
     * @return object db el dato de la variable.
     */
    function get_db() {
        return $this->db;
    }

    /**
     * Retorna el valor del atributo id
     *
     * @return string $id el dato de la variable.
     */
    function get_id() {
        return $this->id;
    }

    /**
     * Retorna el valor del atributo $idformulario
     *
     * @return string idformulario el dato de la variable.
     */
    function get_IDFORMULARIO() {
        return $this->idformulario;
    }

    /**
     * Retorna el valor del atributo $presentadoa
     *
     * @return string $presentadoa el dato de la variable.
     */
    function get_presentadoa() {
        return $this->presentadoa;
    }

    /**
     * Retorna el valor del atributo $titulosecundario
     *
     * @return string $titulosecundario el dato de la variable.
     */
    function get_titulosecundario() {
        return $this->titulosecundario;
    }

    /**
     * Retorna el valor del atributo $expedidopor
     *
     * @return string $expedidopor el dato de la variable.
     */
    function get_expedidopor() {
        return $this->expedidopor;
    }

    /**
     * Retorna el valor del atributo $emailpersonal
     *
     * @return string $emailpersonal el dato de la variable.
     */
    function get_emailpersonal() {
        return $this->emailpersonal;
    }

    /**
     * Retorna el valor del atributo $celular
     *
     * @return number $celular el dato de la variable.
     */
    function get_celular() {
        return $this->celular;
    }

    /**
     * Retorna el valor del atributo $obligacadeaprob
     *
     * @return string $obligacadeaprob el dato de la variable.
     */
    function get_obligacadeaprob() {
        return $this->obligacadeaprob;
    }

    /**
     * Retorna el valor del atributo $equivalenciasoli
     *
     * @return string $equivalenciasoli el dato de la variable.
     */
    function get_equivalenciasoli() {
        return $this->equivalenciasoli;
    }

    /**
     * Retorna el valor del atributo $iddocumen1
     *
     * @return number $iddocumen1 el dato de la variable.
     */
    function get_iddocumen1() {
        return $this->iddocumen1;
    }

    /**
     * Retorna el valor del atributo $iddocumen2
     *
     * @return number $iddocumen2 el dato de la variable.
     */
    function get_iddocumen2() {
        return $this->iddocumen2;
    }

    /**
     * Retorna el valor del atributo $descripcion
     *
     * @return string $descripcion el dato de la variable.
     */
    public function get_descripcion() {
        return $this->descripcion;
    }

    /*     * ********SETTERS******** */

    /**
     * Setter del parametro $edificio de la clase.
     *
     * @param string $nombre form
     *        	dato a cargar en la variable.
     */
    function set_nombre_form($nombre_form) {
        $this->nombre_form = $nombre_form;
    }

    /**
     * Setter del parametro $template de la clase.
     *
     * @param string $html_template 
     * 
     */
    function set_html_template($html_template) {
        $this->html_template = $html_template;
    }

    /**
     * Setter del parametro $db de la clase.
     *
     * @param string $db form
     *        	
     */
    function set_db($db) {
        $this->db = $db;
    }

    /**
     * Setter del parametro $id de la clase.
     *
     * @param int $id form
     *        	dato a cargar en la variable.
     */
    function set_id($id) {
        $this->id = $id;
    }

    /**
     * Setter del parametro $idformulario de la clase.
     *
     * @param int $idformulario form
     *        	dato a cargar en la variable.
     */
    function set_IDFORMULARIO($idformulario) {
        $this->IDFORMULARIO = $idformulario;
    }

    /**
     * Setter del parametro $presentadoa de la clase.
     *
     * @param string $presentadoa form
     *        	dato a cargar en la variable.
     */
    function set_PRESENTADOA($presentadoa) {
        $this->PRESENTADOA = $presentadoa;
    }

    /**
     * Setter del parametro $emailpersonal    de la clase.
     *
     * @param string $emailpersonal form
     *        	dato a cargar en la variable.
     */
    function set_EMAILPERSONAL($emailpersonal) {
        $this->EMAILPERSONAL = $emailpersonal;
    }

    /**
     * Setter del parametro $celular de la clase.
     *
     * @param INT $celular form
     *        	dato a cargar en la variable.
     */
    function set_celular($celular) {
        $this->celular = $celular;
    }

    /**
     * Setter del parametro $obligacadeaprob de la clase.
     *
     * @param string $obligacadeaprob form
     *        	dato a cargar en la variable.
     */
    function set_OBLIGACADEAPROB($obligacadeaprob) {
        $this->OBLIGACADEAPROB = $obligacadeaprob;
    }

    /**
     * Setter del parametro $equivalenciasoli  de la clase.
     *
     * @param string $equivalenciasoli form
     *        	dato a cargar en la variable.
     */
    function set_EQUIVALENCIASOLI($equivalenciasoli) {
        $this->EQUIVALENCIASOLI = $equivalenciasoli;
    }

    /**
     * Setter del parametro $iddocumen1 de la clase.
     *
     * @param int $iddocumen1 form
     *        	dato a cargar en la variable.
     */
    function set_IDDOCUMEN1($iddocumen1) {
        $this->IDDOCUMEN1 = $iddocumen1;
    }

    /**
     * Setter del parametro $iddocumen2 de la clase.
     *
     * @param int $iddocumen2 form
     *        	dato a cargar en la variable.
     */
    function set_IDDOCUMEN2($iddocumen2) {
        $this->IDDOCUMEN2 = $iddocumen2;
    }

    /**
     * Setter del parametro $descripcion de la clase.
     *
     * @param string $descripcion form
     *        	dato a cargar en la variable.
     */
    function set_descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    
    function getAmbito() {
        return $this->ambito;
    }
    
    function getCopime() {
        return $this->copime;
    }
    
    function setAmbito($ambito) {
        $this->ambito = $ambito;
    }
    
    function setCopime($copime) {
        $this->copime = $copime;
    }


}

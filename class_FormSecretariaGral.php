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
 *
 */
class FormsSecretariaGral extends Formularios {

    protected $db;
    protected $id;
    protected $IDFORMULARIO;
    protected $PRESENTADOA;
    protected $TITULOSECUNDARIO;
    protected $EXPEDIDOPOR;
    protected $EMAILPERSONAL;
    protected $celular;
    protected $OBLIGACADEAPROB;
    protected $EQUIVALENCIASOLI;
    protected $IDDOCUMEN1;
    protected $IDDOCUMEN2;
    protected $html_template;
    protected $nombre_form;
    protected $descripcion;

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
     */
    public function getFormById($id) {
        $parametros = array(
            $id
        );

        // $this->db = Conexion::openConnection();

        $query = "SELECT formulario.* ,
	                formulariosecgral.idformulario,
	                formulariosecgral.presentadoa,
	                formulariosecgral.titulosecundario,
	                formulariosecgral.expedidopor,
	                formulariosecgral.emailpersonal,
	                formulariosecgral.celular,
	                formulariosecgral.obligacadeaprob,
	                formulariosecgral.equivalenciasoli,
	                formulariosecgral.iddocumen1,
	                formulariosecgral.iddocumen2
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

            switch ($tipo) {

                case '110' :

                    $template .= '<input type="hidden" value="110" name="IDSECGRAL">' . '<input type="hidden" value="110" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input type="text" name="presentadoa" id="presentadoa" required>' . '<label>*T&iacute;tulo secundario:</label>' . '<input type="text" name="secundario" id="secundario" required>' . '<label>*Expedido por</label>' . '<input type="text" name="expedido" id="expedido" required>' . '<label>*Email personal:</label>' . '<input type="text" name="email" id="email">' . '<label>*Telefono celular:</label>' . '<input type="text" name="cel" id="cel">' . '<label>*Plan de estudio:</label><br/>' . '<input type="file" name="plestudio" id="plestudio"><br/>' . '<label>*Programa de la materia:</label><br/>' . '<input type="file" name="prmateria" id="prmateria"><br/>';

                    break;

                case '111' :

                    $template .= '<input type="hidden" value="111" name="IDSECGRAL">' . '<input type="hidden" value="111" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input type="text" name="presentadoa" id="presentadoa">' . '<label>*T&iacute;tulo secundario:</label>' . '<input type="text" name="secundario" id="secundario">' . '<label>*Expedido por:</label>' . '<input type="text" name="expedido" id="expedido">';

                    break;

                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">' . '<input type="hidden" value="112" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input type="text" name="presentadoa" id="presentadoa">' . '<label>*T&iacute;tulo secundario:</label>' . '<input type="text" name="secundario" id="secundario">' . '<label>*Expedido por:</label>' . '<input type="text" name="expedido" id="expedido">';

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">' . '<input type="hidden" value="113" name="tipoform">' . '</br><p>Me es grato dirigirme a usted ,' . ' con el fin de solicitarle sean reconocidas como equivalentes a las materias' . ' que a continuaci&oacute;n detallo, aprobadas en: </p> ' . '<br/><p>*Obligaci&oacute;n acad&eacute;mica aprobada:</p><textarea name="obli_acade_aproba" id="obli_acade_aproba" ></textarea>';

                    break;

                default :

                    break;
            }
        } else {

            switch ($tipo) {

                case '110' :

                    $template .= '<input type="hidden" value="110" name="IDSECGRAL">' . '<input type="hidden" value="110" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input type="text" disabled name="presentadoa" id="presentadoa" value="' . $data['PRESENTADOA'] . '" required>' . '<label>*T&iacute;tulo secundario:</label>' . '<input type="text" disabled  name="secundario" id="secundario" value="' . $data['TITULOSECUNDARIO'] . '"  required>' . '<label>*Expedido por</label>' . '<input type="text" disabled  name="expedido" id="expedido" value="' . $data['EXPEDIDOPOR'] . '" required>' . '<label>*Email personal:</label>' . '<input type="text" disabled  name="email" id="email" value="' . $data['EMAILPERSONAL'] . '" >' . '<label>*Telefono celular:</label>' . '<input type="text" disabled  name="cel" id="cel" value="' . $data['CELULAR'] . '">';

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

                case '111' :

                    $template .= '<input type="hidden" value="111" name="IDSECGRAL">' . '<input type="hidden" value="111" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input disabled  type="text" name="presentadoa" id="presentadoa" value="' . $data['PRESENTADOA'] . '">' . '<label>*T&iacute;tulo secundario:</label>' . '<input disabled  type="text" name="secundario" id="secundario" value="' . $data['TITULOSECUNDARIO'] . '">' . '<label>*Expedido por</label>' . '<input disabled  type="text" name="expedido" id="expedido" value="' . $data['EXPEDIDOPOR'] . '">';

                    break;

                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">' . '<input type="hidden" value="112" name="tipoform">' . '<label>*Para ser presentado ante:</label>' . '<input disabled  type="text" name="presentadoa" id="presentadoa" value="' . $data['PRESENTADOA'] . '">' . '<label>*T&iacute;tulo secundario:</label>' . '<input disabled  type="text" name="secundario" id="secundario" value="' . $data['TITULOSECUNDARIO'] . '">' . '<label>*Expedido por:</label>' . '<input disabled  type="text" name="expedido" id="expedido" value="' . $data['EXPEDIDOPOR'] . '">';

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">' . '<input type="hidden" value="113" name="tipoform">' . '</br><p>Me es grato dirigirme a usted ,' . ' con el fin de solicitarle sean reconocidas como equivalentes a las materias' . ' que a continuaci&oacute;n detallo, aprobadas en: </p> ' . '<br/><p>Obligaci&oacute;n acad&eacute;mica aprobada:</p>' . '<textarea disabled  name="obli_acade_aproba" id="obli_acade_aproba" >' . $data['OBLIGACADEAPROB'] . '</textarea>';

                    break;

                default :

                    break;
            }
        }

        // Estos forms son los que necesitan listas de materias , si entra por aca , devuelve un select
        // con las materias , si hay datos devuelve las materias seleccionadas en un div aparte , las demas en
        // select
        if ($tipo == '111' || $tipo == '112' || $tipo == '113') {

            $html_mat_sel = '';
            if ((Session::get('personSelect') == null || Session::get('personSelect') == '') && ($data['STUDENT'] == null || $data['STUDENT'] == '' )) {

                //   throw new Exception('Alumno sin person');
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

                // Si no hay data devuelve el select de materias
                if (!$data) {

                    if ($materias != '') {

                        $template .= '<label>Materias</label>';

                        $template .= '<ul id="listado_materias">';

                        $template .= "<select id='select_materias' >";

                        foreach ($materias as $row) {

                            if ($tipo == '111') {

                                $template .= "<option class='option_materia'  id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . $row["SDESC"] . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                            } else if ($tipo == '113') {

                                $template .= "<option class='option_materia' ' id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . $row["SDESC"] . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                            } else {

                                $template .= "<option class='option_materia' id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . $row["SDESC"] . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
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
                $nombre = 'Formulario certificado parcial con notas (10 materias)';

                break;
            case 113 :
                $nombre = 'Formulario certificado de equivalencias';

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

        if (isset($datos['TITULOSECUNDARIO'])) {
            $datos['TITULOSECUNDARIO'] = $datos['TITULOSECUNDARIO'];
        }

        if (isset($datos['EXPEDIDOPOR'])) {
            $datos['EXPEDIDOPOR'] = $datos['EXPEDIDOPOR'];
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

        if (isset($fila['TITULOSECUNDARIO'])) {
            $this->set_TITULOSECUNDARIO($fila['TITULOSECUNDARIO']);
        }

        if (isset($fila['EXPEDIDOPOR'])) {
            $this->set_EXPEDIDOPOR($fila['EXPEDIDOPOR']);
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
    }

    /**
     * *******GETTERS********
     */
    function get_nombre_form() {
        return $this->descripcion;
    }

    function get_html_template() {
        return $this->html_template;
    }

    function get_db() {
        return $this->db;
    }

    function get_id() {
        return $this->id;
    }

    function get_IDFORMULARIO() {
        return $this->IDFORMULARIO;
    }

    function get_presentadoa() {
        return $this->PRESENTADOA;
    }

    function get_titulosecundario() {
        return $this->TITULOSECUNDARIO;
    }

    function get_expedidopor() {
        return $this->EXPEDIDOPOR;
    }

    function get_emailpersonal() {
        return $this->EMAILPERSONAL;
    }

    function get_celular() {
        return $this->celular;
    }

    function get_obligacadeaprob() {
        return $this->OBLIGACADEAPROB;
    }

    function get_equivalenciasoli() {
        return $this->EQUIVALENCIASOLI;
    }

    function get_iddocumen1() {
        return $this->IDDOCUMEN1;
    }

    function get_iddocumen2() {
        return $this->IDDOCUMEN2;
    }

    public function get_descripcion() {
        return $this->descripcion;
    }

    /**
     * *******SETTERS********
     */
    function set_nombre_form($nombre_form) {
        $this->nombre_form = $nombre_form;
    }

    function set_html_template($html_template) {
        $this->html_template = $html_template;
    }

    function set_db($db) {
        $this->db = $db;
    }

    function set_id($id) {
        $this->id = $id;
    }

    function set_IDFORMULARIO($IDFORMULARIO) {
        $this->IDFORMULARIO = $IDFORMULARIO;
    }

    function set_PRESENTADOA($PRESENTADOA) {
        $this->PRESENTADOA = $PRESENTADOA;
    }

    function set_TITULOSECUNDARIO($TITULOSECUNDARIO) {
        $this->TITULOSECUNDARIO = $TITULOSECUNDARIO;
    }

    function set_EXPEDIDOPOR($EXPEDIDOPOR) {
        $this->EXPEDIDOPOR = $EXPEDIDOPOR;
    }

    function set_EMAILPERSONAL($EMAILPERSONAL) {
        $this->EMAILPERSONAL = $EMAILPERSONAL;
    }

    function set_celular($celular) {
        $this->celular = $celular;
    }

    function set_OBLIGACADEAPROB($OBLIGACADEAPROB) {
        $this->OBLIGACADEAPROB = $OBLIGACADEAPROB;
    }

    function set_EQUIVALENCIASOLI($EQUIVALENCIASOLI) {
        $this->EQUIVALENCIASOLI = $EQUIVALENCIASOLI;
    }

    function set_IDDOCUMEN1($IDDOCUMEN1) {
        $this->IDDOCUMEN1 = $IDDOCUMEN1;
    }

    function set_IDDOCUMEN2($IDDOCUMEN2) {
        $this->IDDOCUMEN2 = $IDDOCUMEN2;
    }

    function set_descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

}

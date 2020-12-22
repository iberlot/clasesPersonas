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
require_once ("/web/html/classesUSAL/class_materias.php");
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
     * Ambito nacional o internacional
     *
     * @var int
     */
    protected $ambito;

    /**
     * Copime
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
     * idconcepto para los forms 112 que son derechos varios
     *
     * @var string
     */
    protected $idconcepto;

    /**
     * Indica si el formulariodebe ir al migraciones para validacion
     *
     * @var int
     */
    protected $migraciones;

    /**
     * Indica si el migraciones debe a ir al exterior
     *
     * @var int
     */
    protected $ministerio;

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
        if (($tipo == null || $tipo == '') && ($id != null && $id != '')) {

            parent::__construct($db, null, $id);

            $parametros = array(
                $id
            );

            $query = "SELECT FORMULARIO.*  , FORMULARIOSECGRAL.*
                    FROM FORMULARIO JOIN tesoreria.FORMULARIOSECGRAL ON
                    FORMULARIO.ID = FORMULARIOSECGRAL.IDFORMULARIO
                    WHERE FORMULARIO.id = :id ";

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
                FORMULARIOSECGRAL.idformulario,
                FORMULARIOSECGRAL.presentadoa,
                FORMULARIOSECGRAL.emailpersonal,
                FORMULARIOSECGRAL.celular,
                FORMULARIOSECGRAL.obligacadeaprob,
                FORMULARIOSECGRAL.equivalenciasoli,
                FORMULARIOSECGRAL.equivalenciasoli,
                FORMULARIOSECGRAL.iddocumen1,
                FORMULARIOSECGRAL.iddocumen2,
                FORMULARIOSECGRAL.PROGRAMA,
                FORMULARIOSECGRAL.HORASCATEDRA,
                FORMULARIOSECGRAL.AMBITO,
                FORMULARIOSECGRAL.PLANESTUDIO,
                FORMULARIOSECGRAL.RANKING,
                FORMULARIOSECGRAL.PROMEDIO,
                FORMULARIOSECGRAL.ESCALACALI,
                FORMULARIOSECGRAL.PRACTICAS,
                FORMULARIOSECGRAL.INCUMBENCIA,
                FORMULARIOSECGRAL.IDCONCEPTO
                FROM formulario
		JOIN tesoreria.FORMULARIOSECGRAL ON formulario.id = FORMULARIOSECGRAL.idformulario
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
            //var_dump($alumno);
            $plan = $alumno->getPlan();
            $career_code = $alumno->getCarrera();

            $carrera = new Carreras($this->db, $career_code, $plan);

            $emails_alumno = $alumno->getEmail();

            if (isset($emails_alumno[1])) {
                $email = $emails_alumno[1];
            } else {
                $email = $emails_alumno[0];
            }

            $telefono = $alumno->getTelefono();
            $telefono = $telefono[0]['NUMERO'];

            switch ($tipo) {

                case '110' :

                    $template .='<input type="hidden" value="110" name="IDSECGRAL">'
                            . '<input type="hidden" value="110" name="tipoform">'
                            . '<label>*Para ser presentado ante:</label>'
                            . '<input type="text" name="presentadoa" id="presentadoa" required>'
                            . '<label>*Email personal:</label>'
                            . '<input type="text" name="email" id="email" value="' . $emails_alumno[0] . '" >'
                            . '<label>*Tel&eacute;fono celular:</label>'
                            . '<input type="text" name="cel" id="cel" value="' . $telefono . '"><br/><br/>'
                            . '<label class="check_secgral">&Aacute;mbito </label>'
                            . '<select name="ambito"  id="sleambito_1" onchange="seleccionarambito(1)">'
                            . '<option value="s" >Seleccione</option>'
                            . '<option value="0">Nacional </option>'
                            . '<option value="1">Internacional</option>'
                            . '</select><br/>'
                            . '<div class="copime" style="display:none;">'
                            . '<label class="check_secgral" >Copime</label>'
                            . '<input type="checkbox" id="copime_select" value="1" name="copime" disabled class="select_secgral" />'
                            . '</div>'
                            . '<br/><br/>'
                            . '<p><b>Items a solicitar</b></p>'
                            . '<div class="box1" >'
                            . '<label  class="check_secgral"> Plan de Estudio</label>'
                            . '<input type="checkbox" name="planestudio" checked /><br/>'
                            . '<label class="check_secgral"> Programas</label>'
                            . '<input type="checkbox" name="programa"  checked /><br/>'
                            . '<label class="check_secgral">Horas c&aacute;tedra</label>'
                            . '<input type="checkbox" name="horascatedras"  /><br/>'
                            . '<label class="check_secgral">Alcance del t&iacute;tulo o Incumbencias</label>'
                            . '<input type="checkbox" name="alcance"  /><br/>'
                            . '</div>'
                            . '<div class="box2" >'
                            . '<label class="check_secgral">Escala de calificaci&oacute;n:</label>'
                            . '<input type="checkbox" name="escalacali"  /><br/>'
                            . '<label class="check_secgral">Promedio</label>'
                            . '<input type="checkbox" name="promedio"  /><br/>'
                            . '<label class="check_secgral">Ranking</label>'
                            . '<input type="checkbox" name="ranking"  /><br/>'
                            . '<label class="check_secgral">Pr&aacute;cticas</label>'
                            . '<input type="checkbox" name="practicas"  /><br/><br/>'
                            . '<div>'
                            . '<label>Plan de estudio:</label><br/></br>'
                            . '<input type="file" name="plestudio" id="plestudio"><br/>'
                            . '<label>Programa de la materia</label><br/>'
                            . '<input type="file" name="prmateria" id="prmateria"><br/>';

                    break;

                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">'
                            . '<input type="hidden" value="' . $this->getIdconcepto() . '" name="tipoform">';

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">'
                            . '</br></br><label>Para ser presentado ante:</label></br>'
                            . '<input type="text" name="presentadoa" id="presentadoa" value="" style="margin-top:10px;" >'
                            . '<input type="hidden" value="113" name="tipoform">'
                            . '<br><br><input type="checkbox" name="migraciones"  />'
                            . '<label class="check_secgral check_programa"> Exterior/Migraciones</label> '
                            . '<br><br><input type="checkbox" name="ministerio"  />'
                            . '<label class="check_secgral check_programa">  Valida en Ministerio de Educaci&oacute;n</label> '
                            . '<br><br><input type="checkbox" name="programa"  />'
                            . '<label class="check_secgral check_programa">  Programa</label> '
                            . '<br><br><input type="checkbox" name="copime"  />'
                            . '<label class="check_secgral check_programa">  Copime</label><br/> '
                            . '</br><input type="checkbox" name="pase"  />'
                            . '<label class="check_secgral check_programa">  Es pase</label><br/> '
                            . '<label>Plan de estudio:</label><br/></br>'
                            . '<input type="file" name="plestudio" id="plestudio"><br/>'
                    ;

                    break;

                default :

                    break;
            }
        } else {

            $solicitar = '';

            if ($data['HORASCATEDRA'] == '1') {
                $solicitar.='Horas Catedra -';
            };

            if ($data['PROGRAMA'] == '1') {
                $solicitar.=' Programa -';
            };
            if ($data['ESCALACALI'] == '1') {
                $solicitar.=' Escala de calificaci&oacute;n -';
            };

            if ($data['PLANESTUDIO'] == '1') {
                $solicitar.=' Plan de estudios -';
            };

            if ($data['INCUMBENCIA'] == '1') {
                $solicitar.=' Alcance del t&IACUTE;tulo o Incumbencias -';
            };

            if ($data['PROMEDIO'] == '1') {
                $solicitar.=' Promedio -';
            };

            if ($data['RANKING'] == '1') {
                $solicitar.=' Ranking -';
            };

            if ($data['PRACTICAS'] == '1') {
                $solicitar.=' Pr&aacute;cticas -';
            };

            if ($data['AMBITO'] == '1') {
                $ambito = 'Internacional';
            } else {
                $ambito = 'Nacional';
            };

            if (isset($data['COPIME'])) {

                if ($data['COPIME'] == '1') {

                    $copime = 'Si';
                    $copime_display = " style='display:inline-block' ";
                } else {

                    $copime = 'No';
                    $copime_display = " style='display:none' ";
                };
            } else {

                $copime = 'No';
                $copime_display = " style='display:none' ";
            }

            switch ($tipo) {

                case '110' :

                    $template .= '<input type="hidden" value="110" name="IDSECGRAL">'
                            . '<input type="hidden" value="110" name="tipoform">'
                            . '<label><b>Email personal: </b>' . $data['EMAILPERSONAL'] . ' </label><br/>'
                            . '<label><b>Tel&eacute;fono celular: </b>' . $data['CELULAR'] . ' </label><br/>'
                            . '<label><b>Para ser presentado ante: </b>' . utf8_encode($data['PRESENTADOA']) . ' </label>'
                            . '</br><label><b>&Aacute;mbito: </b> ' . utf8_encode($ambito) . '</label>'
                            . '<label ' . $copime_display . '>COPIME</label><br/>'
                            . '<p><b>Items a solicitar: </b>' . substr($solicitar, 0, -1) . '</p>';

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

                case '112' :

                    $template .= '<input type="hidden" value="112" name="IDSECGRAL">'
                            . '<input type="hidden" value="112" name="tipoform">'
                            . '<br/>';

                    break;

                case '113' :

                    $template .= '<input type="hidden" value="113" name="IDSECGRAL">'
                            . '<input type="hidden" value="113" name="tipoform">'
                            . '<label><b>&Aacute;mbito:</b> ' . $ambito . '</label>'
                            . '<label ' . $copime_display . '>COPIME</label><br/>'
                            . '</br><p>Me es grato dirigirme a usted,'
                            . ' con el fin de solicitarle sean reconocidas como equivalentes a las materias'
                            . ' que a continuaci&oacute;n detallo, aprobadas en: </p> '
                            . '<br/><p>Obligaci&oacute;n acad&eacute;mica aprobada:</p>'
                            . '<label class="check_secgral check_ambito" >   Interno/Externo</label>'
                            . '<select name="ambito"  id="ambito" >'
                            . '<option value="1">Interno</option>'
                            . '<option value="1">Externo</option>'
                            . '</select><br/>'
                            . '<textarea disabled  name="obli_acade_aproba" id="obli_acade_aproba" >' . utf8_encode($data['OBLIGACADEAPROB']) . '</textarea>';



                    $template .= '<label>Archivo 1:</label><br/>';

                    // inicializo la clase
                    $archivo1 = new files($this->db, $data['IDDOCUMEN1']);

                    $template .= '<a href="descargararchivo.php?i=' . $data['IDDOCUMEN1'] . '">' . $archivo1->get_nombrearch() . '-' . $data['IDDOCUMEN1'] . '</a>';


                    break;

                default :

                    break;
            }
        }

        // Estos forms son los que necesitan listas de materias , si entra por aca , devuelve un select
        // con las materias , si hay datos devuelve las materias seleccionadas en un div aparte , las demas e
        if ($tipo == '112' && !$data) {

            /* OBTENGO LAS CARRERAS QUE CURSA EL ALUMNO */
            $carreras = $alumno->obtenerCarrerasAlumnos($alumno->getPerson(), $carrera->get_code());

            //  $template.='<h1>'.count($carreras).'<h1>';
            /*             * ********COLUMNA IZQUIERDA OBLIGATORIO PARA TODOS LOS FORM DE ESTE TIPO INDEPENDIENTE DE LA CANTIDAD DE MATERIAS******* */
            $template.= '<div class="carreras-content">'//bloque central
                    . '<div class="carrera_actual">' //columna izquierda
                    . '<div class="carrera-item">' //contenedor columna izquierda
                    . '<h3>Materias de ' . utf8_encode($alumno->getCarrera_descrip()) . '</h3>'; //titulo de columna


            $template .= '<div class="c-i-option c-i-option1"><br/>'
                    . '<input type="hidden" name="carrera1" value="' . $alumno->getCarrera() . '" >';
            $template .= "<select id='materiaactual' style='margin-top:8px;' name='equivalencia_nueva'  >"
                    . "<option value='s' >Seleccione una materia</option>";

            foreach ($carrera->getMaterias() as $row) {
                $template .= "<option class='option_materia'  id='sel_" . $row["SUBJECT"]
                        . "' value='" . $row["SUBJECT"] . "'> "
                        . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . utf8_encode($row["SDESC"]) . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
            }

            $template .= '</select>';
            $template .= '</div>';
            $template .= '</div>';
            $template .= '</div>';
            /*             * **************cierre columna izquierda***************************************** */
            /*             * **************Columna derecha depende de cantidad de materias***************** */


            switch (count($carreras)) {

                case '0'://
                    //$template .='0';
                    $template .= '<input type="hidden" value="48" name="formdervar" id="formdervar">';
                    $template .= '<div class="carrera_anterior" >'
                            . '<div class="carrera-item">';

                    $template .= '<label><h3>Materias equivalentes</h3></label>'
                            . '<textarea name="materiasexternas" id="materiasexternas"> </textarea>';

                    break;

                case '1'://31410174

                    $carrera2 = new Carreras($this->db, $carreras[0]['CAREER'], $carreras[0]['PLAN']);

                    $template .=
                            '<div class="tipoequiv"><label>Tipo de equivalencia</label>'
                            . '<select id="tipoequiv" onchange="cambiar_form()">'
                            . '<option value="49">Interno</option>'
                            . '<option value="48">Externo</option>'
                            . '<select/>'
                            . '<input type="hidden" value="49" name="formdervar" id="formdervar">'
                            . '</div>';


                    $template .= '<div class="carrera_anterior" ><div class="carrera-item">';

                    $template.='<div class="externo" style="display:none;">'
                            . '<label><h3>Materias equivalentes</h3></label>'
                            . '<textarea name="materiasexternas" id="materiasexternas"> </textarea>'
                            . '</div>';
                    $template.='<div class="interno">'
                            . '<h3>Materias de ' . utf8_encode($carreras[0][3]) . '</h3><br/><br/>';

                    $template .='<input type="hidden" name="plan2" id="plan2" value="' . $carreras[0]['PLAN'] . '" >'
                            . '<input type="hidden" name="carrera2" id="carrera2" value="' . $carreras[0]['CAREER'] . '" >'
                            . '<select  class="option_materia" id="materiasequivalencias" name="equivalencias" multiple>';

                    foreach ($carrera2->getMaterias() as $row) {

                        $template .= "<option   id='sel_" . $row["SUBJECT"] .
                                "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"]
                                . " - " . utf8_encode($row["SDESC"]) . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
                    }

                    $template .= '</select>';
                    $template .='</div>'
                            . '<script>$("#materiasequivalencias").multiSelect('
                            . '{ noneText: "Seleccione las materias",
                    presets: [
                        {
                            name: "Seleccione materias",
                            options: []
                        }
                    ]}'
                            . ');</script>';


                    break;

                default://32738998

                    $template .=
                            '<div class="tipoequiv"><label>Tipo de equivalencia</label>'
                            . '<select id="tipoequiv" onchange="cambiar_form()">'
                            . '<option value="49">Interno</option>'
                            . '<option value="48">Externo</option>'
                            . '<select/>'
                            . '<input type="hidden" value="49" name="formdervar" id="formdervar">'
                            . '</div>';

                    $template .= '<div class="carrera-item" style="margin-top: 15px;">'
                            . '<div class="carrera_anterior" style="margin-top: 0px!important;">';

                    $template.='<div class="externo" style="display:none;">'
                            . '<label><h3>Materias equivalentes</h3></label>'
                            . '<textarea name="materiasexternas" id="materiasexternas"> </textarea>'
                            . '</div>';

                    $template.='<div class="interno">';
                    $template.='<h3>Carreras Cursadas</h3>';
                    $template.='<select id="carreras" name="carrera"  onchange="cambiarCarrera(\'carreras\' , \'materiasequivalencias\')">';
                    $template.='<option value="s" >Seleccione</option>';

                    foreach ($carreras as $row) {
                        $template.='<option value="' . $row["CAREER"] . ' - ' . $row["PLAN"] . '">' . utf8_encode($row["LDESC"]) . ' </option>';
                    }

                    $template.='</select>';

                    /* SELECT DE MATERIAS VACIO QUE SE LLENA LUEGO */
                    /* item de carrera */

                    $template.='<div class="select2"></div>'
                            . '</div>';

                    break;
            }
            /*             * **************TERMINA Columna derecha ***************** */

            $template .= '</div>';
            $template .= '</div>'
                    . '<input type="button" onclick="asignar_equivalencias()" value="Asociar" id="asociar" '
                    . 'class="vex-dialog-button-primary vex-dialog-button vex-first"/>'
                    . '</div>'//cierre columna izquierda
                    . '</div>'//cierre columna izquierda
                    . '</div>'//cierre columna izquierda
                    . '</div>'//cierre columna izquierda
                    . '</div>'; //cierre  bloque central

            /* boton asociar separador */
            $template .= '<div class="equivalencias-result">'//contenedor de resultado
                    . '<table class="minimalistBlack">
                    <thead>
                    <tr>
                    <th>Materia </th>
                    <th colspan="2">Materias Equivalentes</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>'
                    . '</div>'; //cierre de resultado
        }

        //SI ES UN FORM 112 Y EXISTEN DATOS PARA EL MISMO
        if ($tipo == '112' && $data) {

            $materias_form = $this->get_materias($data['ID']);
            /*
              echo('<pre>');
              var_dump($materias_form);
              echo('</pre>');
             */
            $template .= '<div class="equivalencias-result">'//contenedor de resultado
                    . '<table class="minimalistBlack">
                <thead>
                <tr>
                    <th>Materia </th>
                    <th colspan="2">Materias Equivalentes</th>
                </tr>
                </thead>';

            $template .= '<tbody>';

            $materia1comparacion = '';

            foreach ($materias_form as $row) {

                $materia1 = new materias($this->db, $row['SUBJECT1'], $row['PLAN1']);

                if ($row["CAREER2"] != 'undefined' && $row['PLAN2'] != 'undefined' && $row["CAREER2"] != ' ' && $row['PLAN2'] != ' ') {

                    $carrera_equiv = new Carreras($this->db, $row["CAREER2"], $row['PLAN2']);

                    $materia2 = new materias($this->db, $row['SUBJECT2'], $row['PLAN2']);

                    if ($materia1comparacion != $row['SUBJECT1']) {

                        $template .='<tr>';
                        $template .='<td>' . utf8_encode($materia1->getDescrip()) . '</td>';
                        $template .='<td>' . utf8_encode($materia2->getDescrip()) . ' - ' . utf8_encode($carrera_equiv->get_sdesc()) . '</td>';
                        $template .='</tr>';
                    } else {

                        $template .='<tr>';
                        $template .='<td> &nbsp;</td>';
                        $template .='<td>' . utf8_encode($materia2->getDescrip()) . ' - ' . utf8_encode($carrera_equiv->get_sdesc()) . '</td>';
                        $template .='</tr>';
                    }
                } else {

                    if ($materia1comparacion != $row['SUBJECT1']) {

                        $template .='<tr>';
                        $template .='<td>' . utf8_encode($materia1->getDescrip()) . '</td>';
                        $template .='<td>' . $row['SUBJECT2'] . '</td>';
                        $template .='</tr>';
                    } else {

                        $template .='<tr>';
                        $template .='<td> &nbsp;</td>';
                        $template .='<td>' . $row['SUBJECT2'] . '</td>';
                        $template .='</tr>';
                    }
                }

                $materia1comparacion = $row['SUBJECT1'];
            }

            $template .= '<tbody>
                </tbody>
                </table><br/><br/>';
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

        $parametros = array();
        $parametros[] = $tipo;

        $query = "SELECT descripcion
                FROM FORMSECGRAL
                WHERE NUMEROFORM = :tipo";



        $result = $this->db->query($query, true, $parametros);
        $result = $this->db->fetch_array($result);
        $nombre = $result['DESCRIPCION'];

        /* switch ($tipo) {
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
         */
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

        if (isset($fila['COPIME'])) {
            $this->setCopime($fila['COPIME']);
        }

        if (isset($fila['IDCONCEPTO'])) {
            $this->setIdconcepto($fila['IDCONCEPTO']);
        }
        if (isset($fila['MINISTERIO'])) {
            $this->setMinisterio($fila['MINISTERIO']);
        }
        if (isset($fila['MIGRACIONES'])) {
            $this->setExterior($fila['MIGRACIONES']);
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

    function getIdconcepto() {
        return $this->idconcepto;
    }

    function getMigraciones() {
        return $this->migraciones;
    }

    function getMinisterio() {
        return $this->ministerio;
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
        $this->presentadoa = $presentadoa;
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

    function setIdconcepto($idconcepto) {
        $this->idconcepto = $idconcepto;
    }

    function setMinisterio($ministerio) {
        $this->ministerio = $ministerio;
    }

    function setExterior($exterior) {
        $this->migraciones = $exterior;
    }

}

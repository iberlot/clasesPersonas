<?php

/**
 * Archivo de la clase Alumnos
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author lquiroga - lquiroga@usal.edu.ar
 *
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name class_alumnos.php
 * @version 0.1 version inicial del archivo.
 *
 *
 *
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
require_once ("class_Personas.php");

/**
 * Clase que maneja los atributos de los alumnos
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author lquiroga - lquiroga@usal.edu.ar
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name Alumnos
 *
 * @version 1.1 Se elimino el parametro id, este es equivalente al person de la clase padre. iberlot <@> iberlot@usal.edu.ar 2/7/19
 */
class Alumnos extends Personas {
    // FIXME La clase tiene un error de implementacion a corregir en la brevedad, las carreres deberian implementarse por medio de otra clase y asociarlas por medio de una lista al alumno. Con la forma actual un alumno podria tener una sola carrera. . iberlot <@> iberlot@usal.edu.ar 2/7/19

    /**
     * Identificador del centro de costo.
     *
     * @var int
     */
    protected $idcentrocosto;

    /**
     * Descripcion de la carrera.
     *
     * @var string
     */
    protected $carrera_descrip;

    /**
     * Identificador de la carrera.
     *
     * @var int
     */
    protected $carrera;

    /**
     * Identificador del estado de la carrera.
     * Los valores posibles son:
     * FINALPASS, 2
     * EQUIVAL, 3
     * POSTPONED, 4
     * COUREXAM, 5
     * PREEXAM, 6
     * FAILED, 7
     * COURLOST, 8
     * COURFAIL 9
     * CURSADAABANDONADA 10
     *
     * XXX En el set realiza una comprovacion asi que hay que actualizarlo si llega el caso.
     *
     * @var int
     */
    protected $carrera_stat;

    /**
     * Identificador de la facultad.
     *
     * @var int
     */
    protected $fa;

    /**
     * nombre de la descripcion del tipo de alumno
     *
     * @var int
     */
    protected $desc_tip_alum;

    /**
     * Identificador de la cede.
     *
     * @var int
     */
    protected $es;

    /**
     * Identificador de la carrera.
     *
     * @var int
     */
    protected $ca;

    /**
     * Identificador del plan.
     *
     * @var int
     */
    protected $plan;

    /**
     * Descripcion de la unidad.
     *
     * @var string
     */
    protected $desc_unidad_alumno;

    /**
     * Descripcion del titulo secundario.
     *
     * @var string
     */
    protected $desc_titulo_secundario;

    /**
     * Descripcion de la institucion del titulo secundario.
     *
     * @var string
     */
    protected $desc_instit_titulo_secundario;

    /**
     * Año de admision a una carrera.
     *
     * @var string
     */
    protected $admyr;

    /**
     * Tipo de unidad academica
     *
     * @var string
     */
    protected $descua;

    /**
     * Constructor de la clase Alumnos.
     *
     * @param class_db $db
     *        	- Objeto de coneccion a la base.
     * @param int $person
     *        	- Numero identificatorio de la persona.
     * @param int $centrocosto
     */
    public function __construct($db = null, $person = null, $centrocosto = null) {
        parent::__construct($person, $db);

        if ($person != null && trim($person) != '') {

            if ($centrocosto != null && trim($centrocosto) != '') {

                $this->findByPerson($person, $centrocosto);
            } else {

                $this->findByPerson($person);
            }
        }
    }

    /**
     * findByPerson busca alumno por person
     *
     * @param int $person
     *        	id person
     */
    public function findByPerson($person, $centrocosto = null) {
        $this->setPerson($person);
        // $anio_actual = date ("Y");

        $parametros = array();
        $parametros[] = $person;

        $query = "SELECT DISTINCT
                    carstu.career,
                    carstu.branch,
                    carstu.admyr,
                    perdoc.typdoc,
                    perdoc.docno,
                    centrodecosto.idcentrodecosto,
                    person.person,
                    person.lname,
                    person.fname,
                    carstu.career,
                    ccalu.beca1,
                    ccalu.MES_BECA1,
                    ccalu.BECA2,
                    ccalu.MES_BECA2,
                    ccalu.BECA3,
                    ccalu.MES_BECA3,
                    carstu.plan,
                    carstu.stat,
                    career.descrip
                    FROM
                    appgral.person
                    JOIN appgral.perdoc ON person.person = perdoc.person
                    JOIN studentc.carstu ON person.person = carstu.student
                    JOIN studentc.career ON carstu.career = career.code
                    FULL JOIN contaduria.centrodecosto ON
                    lpad(to_char(centrodecosto.fa),2,'0') || lpad(to_char(centrodecosto.ca),2,'0') = lpad(to_char(career.code),4,'0')
                    AND lpad(to_char(centrodecosto.es),2,'0')= lpad(to_char(carstu.branch),2,'0')
                    FULL JOIN tesoreria.ccalu ON person.person = ccalu.person AND centrodecosto.idcentrodecosto = ccalu.idcentrodecosto
                    WHERE
				    person.person = :person ";

        // JOIN interfaz.aux_ccalu ON perdoc.docno = aux_ccalu.nrodoc

        if ($centrocosto != NULL) {
            $query .= " AND centrodecosto.idcentrodecosto = :centrocosto";
            $parametros[] = $centrocosto;
        }

        $result = $this->db->query($query, true, $parametros);

        $this->loadData($this->db->fetch_array($result));
    }

    /**
     * En base a un criterio de busqueda (apellido , dni o nombre) devuelve alumno en objeto
     * dentro de un array
     *
     * @param string $criterio
     *        	String con el dato a buscar en lname, fname o docno
     * @param
     *        	array de enteros con las faescas a usar de limitadores en la busqueda.
     *
     * @return array[Alumnos] array con los alumnos que entran en esas categorias. En caso de no haber encontrado ningun resultado retornara false.
     */
    public function findByProps($criterio, $fa = null) {

        $anio_actual = date("Y");

        $criterio1 = $criterio2 = $criterio3 = $criterio;

        $parametros = array(
            strtoupper($criterio1),
            strtoupper($criterio2),
            strtoupper($criterio3)
        );

        /*  $select = "";
          $join = "";
          $where = "";

          if ($fa != null) {
          $unidades = "";

          foreach ($fa as $row) {
          $unidades .= $row . ',';
          }
          $unidades .= '00';

          $select = ", LPAD(career.facu, 2, '0') facu,
          centrodecosto.idcentrodecosto ";

          $join = "JOIN studentc.carstu ON person.person = carstu.student
          JOIN studentc.career ON carstu.career = career.code
          JOIN contaduria.centrodecosto ON lpad(to_char(centrodecosto.fa),2,'0')||lpad(to_char(centrodecosto.ca),2,'0') = to_char(career.code )
          and lpad(to_char(centrodecosto.es),2,'0') = lpad(to_char(carstu.BRANCH),2,'0')
          ";

          $where = "AND facu IN( " . $unidades . " )
          ";
          }

          $query = "SELECT
          person.person,
          person.lname,
          person.fname
          " . $select . "
          FROM
          appgral.person
          JOIN appgral.perdoc ON person.person = perdoc.person

          " . $join . "
          WHERE
          (UPPER(person.LNAME) LIKE '%' || :busq1 || '%' OR
          UPPER(person.FNAME) LIKE '%' || :busq2 || '%' OR
          docno LIKE '%' || :busq3 || '%'
          )" . $where . ' AND ROWNUM <= 200 order by person.lname, person.fname  asc';
         */
        
          if ($fa != null) {
          $unidades = "";
          
          foreach ($fa as $row) {
            $unidades .= $row . ',';
          }
            $unidades .= '00';
          }
          
        $query = "                    SELECT
                A.person, A.lname, A.fname,
                LPAD(CA.facu, 2, '0') facu, LPAD(C.career,'4','0'),
                LPAD(CO.faesca,6,'0'),
                CO.idcentrodecosto
                 FROM appgral.person A
                 JOIN appgral.perdoc D ON D.person = A.person
                 JOIN studentc.carstu C ON A.person = C.student AND D.person=C.student
                 JOIN studentc.career CA ON C.career = CA.code
                 JOIN contaduria.centrodecosto CO
                 ON lpad(to_char(CO.fa),2,'0')||lpad(to_char(CO.ca),2,'0') = lpad(to_char(CA.code),4,'0')
                 AND lpad(to_char(CO.es),2,'0') = lpad(to_char(C.BRANCH),2,'0')
                 WHERE ";
        
        if ($fa != null) {
            $query.= " CA.facu IN( " . $unidades . " ) AND ";
        } 
          
        $query .=" UPPER(A.LNAME) LIKE '%' || :busq1 || '%' 
            OR UPPER(A.FNAME) LIKE '%' || :busq2 || '%' 
            OR docno LIKE '%' || :busq3 || '%' 
            AND ROWNUM <= 200
            ORDER BY A.lname, A.fname asc  ";

        $result = $this->db->query($query, true, $parametros);

        //echo($query);

        while ($fila = $this->db->fetch_array($result)) {
         
            if (isset($fila['IDCENTRODECOSTO'])) {

                $alumno = new Alumnos($this->db, $fila['PERSON'], $fila['IDCENTRODECOSTO']);
      
                $salida[] = $alumno;
            } else {

                $alumno = new Alumnos($this->db, $fila['PERSON']);

                $salida[] = $alumno;
            }
        }

        if (empty($salida)) {
            return false;
        }
        return $salida;
    }

    /**
     * En base al centrocosto seteo la faesca fa-es-ca.
     *
     * @param int $idcentrocosto
     * @return string String con la faesca en caso de no encontrarla retornara false.
     */
    public function obtenerSeterarFaesca($idcentrocosto) {
        $param = array(
            $idcentrocosto
        );

        $query = "SELECT * FROM contaduria.centrodecosto WHERE idcentrodecosto = :centrocosto";

        $scfaes = $this->db->query($query, true, $param);

        if ($scfaes) {
            $arr_asoc = $this->db->fetch_array($scfaes);

            $this->setFa($arr_asoc['FA']);
            $this->setEs($arr_asoc['ES']);
            $this->setCa($arr_asoc['CA']);

            return ($this->getFa() . $this->getEs() . $this->getCa());
        }

        return false;
    }

    /**
     * Con el student obtengo un array de las carreras del student
     *
     * @param int $student
     * @param string $nocode-->array de code de materias que queermos sacar de la lista esta
     * @return array de carreras
     */
    public function obtenerCarrerasAlumnos($student, $nocode = null) {

        $salida = array();

        $param = array(
            $student
        );

        $query = "select carstu.student ,carstu.career ,carstu.plan , career.ldesc from studentc.carstu 		
                JOIN studentc.career  ON career.code = carstu.CAREER 
                where carstu.student=  :student ";

        if ($nocode != null) {

            $query .= "and carstu.career NOT IN(:filtrocarreras)";

            $param[] = $nocode;
        }

        $result = $this->db->query($query, true, $param);

        while ($fila = $this->db->fetch_array($result)) {

            $salida[] = $fila;
        }

        return $salida;
    }

    /**
     * En base a la facultad obtengo el nombre de la misma
     *
     * @param int $fa
     * @return string Nombre de la facultad en caso de no encontrarla retornara false.
     */
    public function obtener_unidad_por_fa($fa) {
        $param = array(
            $fa
        );

        $query = "SELECT descrip,tipoua FROM studentc.facu WHERE LPAD(code, 2, '0') = LPAD(:fa, 2, '0') ";

        $scfaes = $this->db->query($query, true, $param);

        if ($scfaes) {
            $arr_asoc = $this->db->fetch_array($scfaes);

            $this->setDescua($arr_asoc['TIPOUA']);

            return ($arr_asoc['DESCRIP']);
        }

        return false;
    }

    /**
     * En base a la facultad obtengo el nombre de la misma
     *
     * @param int $fa
     * @return string Nombre de la facultad en caso de no encontrarla retornara false.
     */
    public function obtener_form_sec_gral($estado) {

        $param = array(
            $estado
        );

        $query = " SELECT formsecgral.NUMEROFORM  , FORMSECGRAL.descripcion
                    FROM FORMXSTATCARSTU 
                    JOIN FORMSECGRAL ON FORMXSTATCARSTU.formsecgral = FORMSECGRAL.ID
                    WHERE FORMXSTATCARSTU.statcarstu = :estado 
                    AND FORMSECGRAL.ESTADO=1 ";

        $result = $this->db->query($query, true, $param);

        while ($fila = $this->db->fetch_array($result)) {

            $forms[] = $fila;
        }

        return $forms;
    }

    /**
     * MateriasAprxPlanCarrera muestra las materias aprobadas en
     * base a un plan especifico una carrera y un alumno
     *
     * @version 0.2 Se elimino el parametro person que se le pasaba a la funcion, como estamos en una clase deberia usar el person del objeto creado. iberlot <@> iberlot@usal.edu.ar 2/7/19
     *
     * @param int $carrera
     * @param int $plan
     * @param array $estados
     *        	Los estados posibles son:
     *        	FINALPASS, 2
     *        	EQUIVAL, 3
     *        	POSTPONED, 4
     *        	COUREXAM, 5
     *        	PREEXAM, 6
     *        	FAILED, 7
     *        	COURLOST, 8
     *        	COURFAIL 9
     *        	CURSADAABANDONADA 10
     *
     * @param number $cuatrimestre
     *        	-->
     *        	esta serteado en menos dos , por que existen cuatrimestres -1 0 y 1
     *
     *
     * @return array de materias q no estan en el listado que le pasamos para excluir
     */
    public function MateriasAprxPlanCarrera($carrera, $plan, $estados, $cuatrimestre = -2) {
        $query = "SELECT stusubj.subject, course.quarter, stusubj.stat
                            FROM studentc.stusubj
                            JOIN studentc.course ON stusubj.course = course.code
                            WHERE stusubj.student=:person AND
                            stusubj.career=:carrera AND stusubj.plan=:plan AND stusubj.stat IN (" . $estados . ")";

        $parametros = array(
            $this->person,
            $carrera,
            $plan
        );

        if ($cuatrimestre != -2) {

            $query .= "AND course.quarter = :cuatri";

            $parametros[] = $cuatrimestre;
        }

        $subjectMaterias = $this->db->query($query, true, $parametros);

        $subject_x_estado = array();

        while ($fila = $this->db->fetch_array($subjectMaterias)) {

            $subject_x_estado[] = $fila['SUBJECT'];
        }

        return $subject_x_estado;
    }

    /**
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *        	return objet alumno
     */
    public function loadData($fila) {
        if ($fila['PERSON'] != "") {
            $this->setPerson($fila['PERSON']);
        }
        if ($fila['CAREER'] != "") {
            $this->setCarrera($fila['CAREER']);
        }
        if ($fila['LNAME'] != "") {
            $this->setApellido($fila['LNAME']);
        }
        if ($fila['FNAME'] != "") {
            $this->setNombre($fila['FNAME']);
        }
        if ($fila['DESCRIP'] != "") {
            $this->setCarrera_descrip($fila['DESCRIP']);
        }
        if ($fila['IDCENTRODECOSTO'] != "") {
            $this->setIdcentrocosto($fila['IDCENTRODECOSTO']);
        }
        if ($fila['PLAN'] != "") {
            $this->setPlan($fila['PLAN']);
        }

        if (isset($fila['STAT'])) {
            $this->setCarrera_stat($fila['STAT']);
        }

        /* seteo la faesca del alumno */
        if (isset($fila['IDCENTRODECOSTO'])) {
            $this->obtenerSeterarFaesca($fila['IDCENTRODECOSTO']);
        }

        /* seteo la descripcion del instituto del alumno */
        if (isset($fila['INSTITUTO'])) {
            $this->setDesc_instit_titulo_secundario($fila['INSTITUTO']);
        }

        /* seteo la descripcion del instituto del alumno */
        if (isset($fila['ADMYR'])) {
            $this->setAdmyr($fila['ADMYR']);
        }
        /* seteo la descripcion del titulo secundario */
        if (isset($fila['DESCRIP'])) {
            $this->setDesc_titulo_secundario($fila['DESCRIP']);
        }

        if (isset($fila['BECA1'])) {

            $this->setId_Tipo_alumno($fila['BECA1']);

            $query = "SELECT descripcion FROM interfaz.tipo_alumno WHERE tipo_alumno = :tipo";

            $params = array(
                $fila['BECA1']
            );

            $result = $this->db->query($query, true, $params);

            $descri_tipo = $this->db->fetch_array($result);

            $this->set_desc_tip_alum($descri_tipo['DESCRIPCION']);
        }

        $this->setFoto_persona($this->get_Photo($fila['PERSON']));
        // $this->set_foto ($this->get_Photo_alumno ($fila['PERSON']));

        /* en base a la fa obtengo el nomber de la unidad a la cual pertenece el alumno */
        $this->setDesc_unidad_alumno($this->obtener_unidad_por_fa($this->getFa()));
    }

    /**
     *
     * @return int el dato de la variable $idcentrocosto
     */
    public function getIdcentrocosto() {
        return $this->idcentrocosto;
    }

    /**
     *
     * @return string el dato de la variable $carrera_descrip
     */
    public function getCarrera_descrip() {
        return $this->carrera_descrip;
    }

    /**
     *
     * @return int el dato de la variable $carrera
     */
    public function getCarrera() {
        return $this->carrera;
    }

    /**
     *
     * @return int el dato de la variable $carrera_stat
     */
    public function getCarrera_stat() {
        return $this->carrera_stat;
    }

    /**
     *
     * @return int el dato de la variable $fa
     */
    public function getFa() {
        return $this->fa;
    }

    /**
     *
     * @return int el dato de la variable $es
     */
    public function getEs() {
        return $this->es;
    }

    /**
     *
     * @return int el dato de la variable $ca
     */
    public function getCa() {
        return $this->ca;
    }

    /**
     *
     * @return int el dato de la variable $plan
     */
    public function getPlan() {
        return $this->plan;
    }

    /**
     *
     * @return int el dato de la variable $plan
     */
    public function getDescua() {
        return $this->descua;
    }

    /**
     *
     * @return string el dato de la variable $desc_unidad_alumno
     */
    public function getDesc_unidad_alumno() {
        return $this->desc_unidad_alumno;
    }

    /**
     * Retorna la descripcion asociada a cada estado de la carrera.
     *
     * @return int
     */
    function get_desc_tip_alum() {
        return $this->desc_tip_alum;
    }

    function setId_Tipo_alumno($tipo_alumno) {
        $this->Id_tipo_alumno = $tipo_alumno;
    }

    function set_desc_tip_alum($tipo_alumno) {
        $this->desc_tip_alum = $tipo_alumno;
    }

    /**
     * Retorna la descripcion del titulo secundario.
     *
     * @return string
     */
    function getDesc_titulo_secundario() {
        return $this->desc_titulo_secundario;
    }

    /**
     * Retorna la descripcion de la institucion del titulo secundario.
     *
     * @return string
     */
    function getDesc_instit_titulo_secundario() {
        return $this->desc_instit_titulo_secundario;
    }

    function setDesc_titulo_secundario($desc_titulo_secundario) {
        $this->desc_titulo_secundario = $desc_titulo_secundario;
    }

    function setDesc_instit_titulo_secundario($desc_instit_titulo_secundario) {
        $this->desc_instit_titulo_secundario = $desc_instit_titulo_secundario;
    }

    /**
     * Retorna la descripcion asociada a cada estado de la carrera.
     *
     * @return string
     */
    public function get_descripcion_estado_carrera($code) {

        $sql = "Select descrip from STATCARSTU WHERE CODE = :code";

        $parametros = array(
            $code
        );

        $result = $this->db->query($sql, true, $parametros);

        while ($fila = $this->db->fetch_array($result)) {

            $salida = $fila;
        }

        return $salida;
    }

    /**
     *
     * @param
     *        	int a cargar en la variable $idcentrocosto
     */
    public function setIdcentrocosto($idcentrocosto) {
        $this->idcentrocosto = $idcentrocosto;
    }

    /**
     *
     * @param
     *        	string a cargar en la variable $carrera_descrip
     */
    public function setCarrera_descrip($carrera_descrip) {
        $this->carrera_descrip = $carrera_descrip;
    }

    /**
     *
     * @param
     *        	int a cargar en la variable $carrera
     */
    public function setCarrera($carrera) {
        $this->carrera = $carrera;
    }

    /**
     *
     * @param int $carrera_stat
     *        	cargar en la variable
     */
    public function setCarrera_stat($carrera_stat) {
        if ($carrera_stat > -1 && $carrera_stat < 11) {
            $this->carrera_stat = $carrera_stat;
        } else {
            throw new Exception("Codigo de estado de carrera invalido. |" . $carrera_stat . "|" . $this->person . "|");
        }
    }

    /**
     * Seter del parametro plan
     *
     * @param int $plan
     *        	a cargar en la variable plan de la clase
     */
    public function setPlan($plan) {
        $this->plan = $plan;
    }

    /**
     * Seter del parametro desc_unidad_alumno.
     *
     * @param string $desc_unidad_alumno
     *        	dato a cargar en la variable.
     */
    public function setDesc_unidad_alumno($desc_unidad_alumno) {
        $this->desc_unidad_alumno = $desc_unidad_alumno;
    }

    /**
     * Setter del parametro $fa de la clase.
     *
     * @param number $fa
     *        	dato a cargar en la variable.
     */
    public function setFa($fa) {
        $this->fa = $fa;
    }

    /**
     * Setter del parametro $es de la clase.
     *
     * @param number $es
     *        	dato a cargar en la variable.
     */
    public function setEs($es) {
        $this->es = $es;
    }

    /**
     * Setter del parametro $ca de la clase.
     *
     * @param number $ca
     *        	dato a cargar en la variable.
     */
    public function setCa($ca) {
        $this->ca = $ca;
    }

    /**
     * Busca los student/person de aquellos alumnos que cumplan con las reglas pasadas.
     *
     * @param mixed $carrer
     * @param int $sede
     * @param boolean $activos
     * @return resource
     */
    public function listar_alumnos_carrera_sede($carrer, $sede, $activos = TRUE) {
        $parametros = array();

        $sql = "SELECT student FROM studentc.carstu WHERE 1=1 ";

        if (is_array($carrer)) {
            $sql .= " AND (";
            foreach ($carrer as $key => $value) {
                if ($key == 0) {
                    $sql .= " career = :carrer$key ";
                } else {
                    $sql .= " OR career = :carrer$key ";
                }
                $parametros[] = $value;
            }
            $sql .= ") ";
        } else {
            if ($carrer != null and $carrer != "") {
                $sql .= " AND career = :carrer ";
                $parametros[] = $carrer;
            }
        }

        if ($sede != null and $sede != "") {
            $sql .= " AND branch = :branch ";
            $parametros[] = $sede;
        }

        if ($activos == TRUE) {
            $sql .= " AND studentc.carstu.stat = 1 ";
        } elseif ($activos == FALSE and ! is_null($activos)) {
            $sql .= " AND studentc.carstu.stat = 0 ";
        }

        $result = $this->db->query($sql, true, $parametros);
        // echo $sql;
        return $this->db->fetch_all($result)['STUDENT'];
    }

    function getAdmyr() {
        return $this->admyr;
    }

    function setAdmyr($admyr) {
        $this->admyr = $admyr;
    }

    function setDescua($descua) {
        $this->descua = $descua;
    }

}

?>
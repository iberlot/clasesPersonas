<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * Obtiene datos de carreras.Clase inicial ,completarla
 * con mas metodos y propuiedades
 *
 *
 * @author lquiroga
 */

class Carreras {

    protected $code;
    protected $descrip;
    protected $sdesc;
    protected $cartype;
    protected $active;
    protected $facu;
    protected $acode;
    protected $plan;
    protected $materias = array();
    protected $db;
   


    /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $code
     */
    public function __construct($db, $code = null ,$plan = null) {
        
        $this->db = $db;

        if ($code != null && trim($code) != '') {

            $query = 'SELECT * FROM studentc.CAREER WHERE CODE = :code';

            $parametros = array(
                $code
            );

            $result = $this->db->query($query, true, $parametros);

            $this->loadData($this->db->fetch_array($result));
            
             if ($plan != null && trim($plan) != '') {
                
                $this->set_plan($plan);
                
                $this->getMateriasPorPlan($this->code, $plan);
                         
            }
        }
    }

    /**
     * getMateriasPorPlan
     *
     * @param int $career
     *        	carrera que queremos las materias
     * @param int $plan
     *        	plan perteneciente a esa carrera
     * @param int $notsubject
     *        	podemos excluir materias de la lista
     *
     * @example :
     *          $aprobadasporalumno = [8895,8896,9987,4484];
     *          getMateriasPorPlan(301 ,16 , $aprobadasporalumno)
     *
     * @return array --> nos devuelve array de materias que no haya aprobado el alumno
     *
     * @return array
     */
    public function getMateriasPorPlan($career, $plan, $notsubject = null){
        
        $salida = array();

        /* $query = 'select CAREER ,PLAN,SUBJECT,SDESC ,YR ,ANNUAL ,MODULES from studentc.subxplan ' 
          . 'where '
          . 'CAREER  IN( :career ((Select SUBCAREER FROM studentc.plancar Where plancar.CAREER = :career2 and plan = :plan )))'
          . ' and plan = :plan2'; */
        
        $query = 'select CAREER carrera ,PLAN ,SUBJECT,SDESC ,YR ,ANNUAL ,MODULES 
                from studentc.subxplan 
                where CAREER in 
                ( :career, (Select SUBCAREER fROM studentc.plancar Where plancar.CAREER = :career2 and plan = :plan )) 
                and plan = :plan2 ORDER BY YR ASC';
        
        $parametros = array(
            $career,
            $career,
            $plan,
            $plan
        );
        
        $result = $this->db->query($query, true, $parametros);

        while ($fila = $this->db->fetch_array($result)) {

            // Si es una materia anual
            if ($fila['ANNUAL'] == 1) {

                if ($fila['CAREER'] == 401) {

                    $fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 8;
                } else {

                    $fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 9;
                }
                
            } else {

                if ($fila['CAREER'] == 401) {

                    $fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 4;
                } else {

                    $fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 4.5;
                }
            }

            if ($notsubject != null) {

                if (in_array($fila['SUBJECT'], $notsubject)){

                    $this->setMaterias($fila);
                    
                    $salida[] = $fila;
                }
                
            } else {
                
                $this->setMaterias($fila);
                $salida[] = $fila;
                
            }
        }

        return $salida;
    }
    
    
     /**
     * getSelectMaterias
     *
     * Devuelve un select de materias de carrera , podemos filtrar 
     * por materias y pedir que sea multiple o no
     * 
     * @param int $multiple 
     *
     * @return array
     */
    public function getSelectMaterias( $multiple = null ){         

                        $template .= '<label>Materias</label>';

                        $template .= '<ul id="listado_materias">';

                        $template .= "<select id='select_materias' >";

                        foreach ($this->getMaterias() as $row){

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
                     

                    $template .= "<div id='materiasseleccionadas'><br/></div>";
                    
                    return $template;
                   
                    

        
    }
    
    
    

    /**
     * Carga datos traidos de db en objeto
     */
    protected function loadData($fila) {
        
        $this->set_code($fila['CODE']);
        $this->set_descrip($fila['DESCRIP']);
        $this->set_sdesc($fila['SDESC']);
        $this->set_cartype($fila['CARTYPE']);
        $this->set_active($fila['ACTIVE']);
        $this->set_facu($fila['FACU']);
        $this->set_acode($fila['ACODE']);
        
        $this->getMateriasPorPlan($fila['CODE'], $this->get_plan());
    }

    /**
     * ***SETTERS****
     */
    function get_code() {
        return $this->code;
    }

    function get_descrip() {
        return $this->descrip;
    }

    function get_sdesc() {
        return $this->sdesc;
    }

    function get_cartype() {
        return $this->cartype;
    }

    function get_active() {
        return $this->active;
    }

    function get_facu() {
        return $this->facu;
    }

    function get_acode() {
        return $this->acode;
    }
    
    function get_plan() {
        return $this->plan;
    }
    
    function getMaterias() {
        return $this->materias;
    }
    

    /**
     * ***SETTERS****
     */
    

    function setMaterias($materias) {
        $this->materias[] = $materias;
    }

    function set_code($code) {
        $this->code = $code;
    }

    function set_descrip($descrip) {
        $this->descrip = $descrip;
    }

    function set_sdesc($sdesc) {
        $this->sdesc = $sdesc;
    }

    function set_cartype($cartype) {
        $this->cartype = $cartype;
    }

    function set_active($active) {
        $this->active = $active;
    }

    function set_facu($facu) {
        $this->facu = $facu;
    }

    function set_acode($acode) {
        $this->acode = $acode;
    }
    
    function set_plan($plan) {
        $this->plan = $plan;
    }

}

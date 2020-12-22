<?php


/**
 * Description of class_materias
 *
 * @author lquiroga
 */
class Materias {

    protected $db;
    protected $career;
    protected $plan;
    protected $subject;
    protected $yr;
    protected $module;
    protected $active;
    protected $descrip;
    protected $promocion;
    protected $annual;
    
      /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $code
     */
    public function __construct($db, $subject , $plan ){

        $this->db = $db;
        
        $query="select * from studentc.subxplan where subject = :subject and plan = :plan ";
        
        $parametros = array(            
            $subject,
            $plan
        );

            $result = $this->db->query($query, true, $parametros);

            $this->loadData($this->db->fetch_array($result));
            
    }

    /**
    * 
    * Carga datos traidos de db en objeto
    * 
    */    
    protected function loadData($fila) {
        
        if(isset($fila['CAREER'])){
           $this->setCareer($fila['CAREER']);
        }
            $this->setPlan($fila['PLAN']);
            $this->setSubject($fila['SUBJECT']);
            $this->setYr($fila['YR']);
            $this->setModule($fila['MODULE']);
            $this->setActive($fila['ACTIVE']);
            $this->setDescrip($fila['DESCRIP']);
            
            if(isset($fila['ANUAL'])){
                $this->setAnnual($fila['ANUAL']);
            }
            
            $this->setPromocion($fila['PROMOCION']);

    }
    
    
    /*************GETTERS**************/
    
    function getDb() {
        return $this->db;
    }

    function getCareer() {
        return $this->career;
    }

    function getPlan() {
        return $this->plan;
    }

    function getSubject() {
        return $this->subject;
    }

    function getYr() {
        return $this->yr;
    }

    function getModule() {
        return $this->module;
    }

    function getActive() {
        return $this->active;
    }

    function getDescrip() {
        return $this->descrip;
    }

    function getPromocion() {
        return $this->promocion;
    }

    function getAnnual() {
        return $this->annual;
    }

    /*************SETTERS**************/
    
    function setDb($db) {
        $this->db = $db;
    }

    function setCareer($career) {
        $this->career = $career;
    }

    function setPlan($plan) {
        $this->plan = $plan;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setYr($yr) {
        $this->yr = $yr;
    }

    function setModule($module) {
        $this->module = $module;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setDescrip($descrip) {
        $this->descrip = $descrip;
    }

    function setPromocion($promocion) {
        $this->promocion = $promocion;
    }

    function setAnnual($annual) {
        $this->annual = $annual;
    }

}

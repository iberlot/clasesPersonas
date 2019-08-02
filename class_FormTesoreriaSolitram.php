<?php

require_once ("/web/html/classesUSAL/class_Personas.php");
require_once ("/web/html/classes/class_derechos_varios.php");
require_once ("/web/html/classes/class_alumnos.php");
require_once ("/web/html/classes/class_carreras.php");
require_once ("/web/html/classes/class_FormsSolitram.php");
require_once ("/web/html/classes/class_Session.php");
require_once ("/web/html/classes/class_files.php");

/**
 * 
 * Description of FormTesoreriaSolitram
 *
 * Extension de la clase formularios . Son formularios pero con datos extras 
 * que se guardan en la tabla  "TESORERIA"."FORMULARIOTESORERIA"
 *
 * TABLA : 
 *   ID
 *   IDFORMULARIO
 *   FECHAVENC
 *   STUDENT
 *   IMPORTE
 *   CONCEPTO
 *   IMPORTEFT
 *   IMPORTER
 *   CODCOBOL
 *   NRO
 * 
 * @author lquiroga
 * 
 */

class FormTesoreriaSolitram extends Formularios {    
     
    protected $db;
    protected $FECHAVENC;
    protected $STUDENT;
    protected $IMPORTE;
    protected $IMPORTER;
    protected $CONCEPTO;
    protected $IMPORTEFT;
    protected $CODCOBOL;
    protected $NRO;

    public function __construct( $db , $tipo = null , $id=null){
    
    $this->db = $db;
    
    $this->set_tipo_form('Formulario Tesoreria');
    
    //Si no hay id o y si tipo devolvemos el html del form
      if ($tipo != null && $tipo != '' && $id == null && $id == '' ){
          
                $this->template_html($tipo);     
                
                $this->set_descripcion($this->obtenerNombreForm($tipo));
                
            }
            
            //Si tipo es null pero id no , devolvemos los datos del form
            if (($tipo == null || $tipo == '' ) && ( $id != null || $id != '') ){
                                   
                $parametros = array (
                        $id
                );
                
                $query  = "SELECT FORMULARIOTESORERIA.* ,FORMULARIO.* 
                        FROM TESORERIA.FORMULARIOTESORERIA
                        JOIN TESORERIA.FORMULARIO ON FORMULARIOTESORERIA.IDFORMULARIO = FORMULARIO.ID
                        WHERE FORMULARIOTESORERIA.IDFORMULARIO = :id ";
                
                $result =$this->db->query ($query, $esParam = true, $parametros);

                if ($result) {

                    $arr_asoc = $db->fetch_array($result);

                    $this->loadData($arr_asoc);

            }                   
        }  
        
    }
    
    /**
    * 
    * loadData
    * Carga propiedades del objeta que vienen desde la DB
    * @param array $fila 
    * 
    * return objet From secretaria gral
    * 
    */
    public function loadData($fila){
    
        //cargo utilizo el load data de la clase padre
        parent::loadData($fila);
        

        //$this->set_nombre_form($nombre);
            
        if(isset($fila['FECHAVENC'])){
          $this->setFECHAVENC($fila['FECHAVENC']);
       }
       
        if(isset($fila['STUDENT'])){
          $this->setSTUDENT($fila['STUDENT']);
       }
       
        if(isset($fila['IMPORTE'])){
          $this->setIMPORTE($fila['IMPORTE']);
       }
       
        if(isset($fila['IMPORTER'])){
          $this->setIMPORTER($fila['IMPORTER']);
       }
       
        if(isset($fila['CONCEPTO'])){
          $this->setCONCEPTO($fila['CONCEPTO']);
       }
       
        if(isset($fila['IMPORTEFT'])){
          $this->setIMPORTEFT($fila['IMPORTEFT']);
       }
       
        if(isset($fila['CODCOBOL'])){
          $this->setCODCOBOL($fila['CODCOBOL']);
       }
       
        if(isset($fila['NRO'])){
          $this->setNRO($fila['NRO']);
       }
       
               
    }
       
    
    /************GETTERS********************/
    function getDb() {
        return $this->db;
    }

    function getID() {
        return $this->ID;
    }

    function getFECHAVENC() {
        return $this->FECHAVENC;
    }

    function getSTUDENT() {
        return $this->STUDENT;
    }

    function getIMPORTE() {
        return $this->IMPORTE;
    }
    function getIMPORTER() {
        return $this->IMPORTER;
    }

    function getCONCEPTO() {
        return $this->CONCEPTO;
    }

    function getIMPORTEFT() {
        return $this->IMPORTEFT;
    }


    function getCODCOBOL() {
        return $this->CODCOBOL;
    }

    function getNRO() {
        return $this->NRO;
    }

    
    /************SETTERS********************/
    
    function setDb($db) {
        $this->db = $db;
    }


    function setFECHAVENC($FECHAVENC) {
        $this->FECHAVENC = $FECHAVENC;
    }

    function setSTUDENT($STUDENT) {
        $this->STUDENT = $STUDENT;
    }

    function setIMPORTE($IMPORTE) {
        $this->IMPORTE = $IMPORTE;
    }
    function setIMPORTER($IMPORTER) {
        $this->IMPORTER = $IMPORTER;
    }

    function setCONCEPTO($CONCEPTO) {
        $this->CONCEPTO = $CONCEPTO;
    }

    function setIMPORTEFT($IMPORTEFT) {
        $this->IMPORTEFT = $IMPORTEFT;
    }

    function setCODCOBOL($CODCOBOL) {
        $this->CODCOBOL = $CODCOBOL;
    }

    function setNRO($NRO) {
        $this->NRO = $NRO;
    }

    

}



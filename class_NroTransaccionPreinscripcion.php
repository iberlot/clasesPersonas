<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_NroTransaccionPreinscripcion
 *
 * @author lquiroga
 */
class class_NroTransaccionPreinscripcion {
      
    protected $db;
    protected $nrotran;
    protected $person;   
    
    /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $code
     */
    public function __construct($db, $tran = null ,$person = null) {

        $this->db = $db;
         
        if ($token != null) {

            $querie = "SELECT * FROM PREINGRESO.apers where person = :person and val = :tran and PATTRIB = 'TRAN' ";

            $parametros = array(
                $person,
                $tran                                    
            );

        $tran = $this->db->query($query, true, $parametros);            

        $this->loadData($this->db->fetch_array($result));
        
        }
    }
    
    

    /**
    * Genera un numero de transaccion aleatorio
    * Se puede invocar sin inicializar la clase
    * @return srting
    */
    public static function generaTrans(){ 
            $chars = '0123456789'; 
            $cantchars = strlen($chars); 	
            $cantchars--; 

            $trans = NULL; 

        for($x=1;$x<=12;$x++){

            $pos = rand(0,$cantchars); 
            $trans .= substr($chars,$pos,1); 
        } 

            return $trans; 
    } 
    
    
    /**
    * Nos devuelve el ultimo id de transaccion generado
    * @return string
    */
    public function lastTrans(){
        
            /* obtengo el ultimo person */
        $sel  = "SELECT MAX(VAL) VAL 
                     FROM PREINGRESO.APERS
                     WHERE PATTRIB = 'TRAN'
                     AND SHORTDES = 'TRAN' 
                     ORDER BY PERSON DESC";

        $stmt       = $this->db->query($sel);
        $row        = $this->db->fetch_array($stmt);
        $tran       = $row['VAL'];
        $lastTran   = $tran+1;

        return $lastTran;
    }


    public function insertTrans($person ,$tran){
        
    $ins3 = "INSERT INTO PREINGRESO.APERS (PERSON, PATTRIB, SHORTDES, VAL, ORDNO) 
     VALUES (:lastPerson,'TRAN','TRAN',:tran,-1)";
    
    
        $parametros = array(
            $person,                        
            $tran
        );

        $tran_insertado = $this->db->query($ins3, true, $parametros);
        
        if($tran_insertado){
            return 1;
        }else{
            return 0;            
        }     
        
    }
    
    /**
    * Carga datos traidos de db en objeto
    **/
    protected function loadData($fila) {

        if (isset($fila['PERSON'])) {
                $this->setPerson($fila['PERSON']);
        }
        
        if (isset($fila['TRAN'])) {
                $this->setNrotran($fila['TRAN']);
        }
        
    }
    /*********GETERS********/
    function getNrotran() {
    return $this->nrotran;
    }

    function getPerson() {
        return $this->person;
    }
    
    /*********SETERS********/
    function setNrotran($nrotran) {
        $this->nrotran = $nrotran;
    }

    function setPerson($person) {
        $this->person = $person;
    }
    
}

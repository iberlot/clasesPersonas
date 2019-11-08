<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_PlanDePagoAlumno
 *
 * @author lquiroga
 */
class PlanDePagoAlumno {

    public $db;
    public $id;
    public $anio;
    public $student;
    public $contable;
    public $idcentrodecosto;
    public $fecha;
    public $person;
    public $idestadopp;
    public $tipoitem = array();
    public $nrocuota = array();
    public $fechavencimiento = array();
    public $importe = array();
    public $recargo = array();
    public $cuotadesde = array();
    public $cuotahasta = array();
 
    /**
     * 
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $person
     * 
     */
    public function __construct($db, $student ,$idcentrodecosto){

        $this->db = $db;
        $this->setPerson($student);
        $this->setIdcentrodecosto($idcentrodecosto);
                   
            $parametros = array(
                $student ,
                $idcentrodecosto
            );

            $query = "SELECT * FROM TESORERIA.PLANPAGO WHERE student = :studen "
                    . " AND centrodecosto= :idcentrodecosto  and  ROWNUM = 1";

            $result = $this->db->query($query, true, $parametros);            

            if($result){
                $this->loadData($this->db->fetch_array ($result));
            
            //Obtengo matriculas                
            $this->getMatriculasPlan($this->getId());
            
            //Obtengo cuotas
            $this->getCuotasPlan($this->getId());
            }
                  
    }

    
    
    /**
     * Carga en el objeto las cuotas de matriculas
     * @param int $idplanpago
     */
    public function getMatriculasPlan($idplanpago){
        
            $parametros = array(
                $idplanpago 
            );

            $query = "SELECT * FROM TESORERIA.PLANPAGODETALLE WHERE PLANPAGO = :idplanpago and TIPOPP = 0";
            $query2 = "SELECT * FROM TESORERIA.PLANPAGODETALLE WHERE PLANPAGO = $idplanpago and TIPOPP = 0";

            $result = $this->db->query($query, true, $parametros);            

            if($result){
                
            $salida=array();
            
            while ($fila = $this->db->fetch_array($result)){
                $salida[]=$fila;
                /*
                $this->setTipoitem($fila['TIPOPP']);
                $this->setNrocuota($fila['NROCUOTAPLAN']);
                $this->setFechavencimiento($fila['FECHAVENCIMIENTO']);
                $this->setImporte($fila['IMPORTE']);
                $this->setRecargo($fila['RECARGO']);
                $this->setCuotadesde($fila['CUOTADESDE']);
                $this->setCuotahasta($fila['CUOTAHASTA']);    
                */
            }
              
            return $salida;             
        }
    }
    
    /**
     * Carga en el objeto las cuotas de matriculas
     * @param int $idplanpago
     */
    public function getCuotasPlan($idplanpago){
        
            $parametros = array(
                $idplanpago 
            );

            $query = "SELECT * FROM TESORERIA.PLANPAGODETALLE WHERE PLANPAGO = :idplanpago and TIPOPP = 1";

            $result = $this->db->query($query, true, $parametros);            

            if($result){   
                $salida=array();
                
            while ($fila = $this->db->fetch_array($result)){
                $salida[]=$fila;
                /*
                $this->setTipoitem($fila['TIPOPP']);
                $this->setNrocuota($fila['NROCUOTAPLAN']);
                $this->setFechavencimiento($fila['FECHAVENCIMIENTO']);
                $this->setImporte($fila['IMPORTE']);
                $this->setRecargo($fila['RECARGO']);
                $this->setCuotadesde($fila['CUOTADESDE']);
                $this->setCuotahasta($fila['CUOTAHASTA']);    
                */
            }
              
            return $salida;   
        }
    }
    
    
    
    /**
     *
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *        	return objet form
     *
     * IDPLANPAGO - ANIO - STUDENT - CONTABLE - CENTRODECOSTO
     * FECHA -  PERSON - ESTADOPP
     * 
     * 
     */
    public function loadData($fila) {

            if (isset($fila['IDPLANPAGO'])) {
                $this->setId($fila['IDPLANPAGO']);
            };

            if (isset($fila['ANIO'])) {
                        $this->setAnio($fila['ANIO']);
            };

            if (isset($fila['STUDENT'])) {
                        $this->setStudent($fila['STUDENT']);
            };

            if (isset($fila['CONTABLE'])) {
                        $this->setContable($fila['CONTABLE']);
            };

            if (isset($fila['CENTRODECOSTO'])) {
                        $this->setIdcentrodecosto($fila['CENTRODECOSTO']);
            };   

            if (isset($fila['FECHA'])) {
                        $this->setFecha($fila['FECHA']);
            };

            if (isset($fila['PERSON'])) {
                        $this->setPerson($fila['PERSON']);
            };

            if (isset($fila['ESTADOPP'])) {
                        $this->setIdestadopp($fila['ESTADOPP']);
            };
            
    
        }
        
        
        
        
    /******GETTER******/

    function getDb() {
        return $this->db;
    }

    function getId() {
        return $this->id;
    }

    function getAnio() {
        return $this->anio;
    }

    function getStudent() {
        return $this->student;
    }

    function getContable() {
        return $this->contable;
    }

    function getIdcentrodecosto() {
        return $this->idcentrodecosto;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getPerson() {
        return $this->person;
    }

    function getIdestadopp() {
        return $this->idestadopp;
    }

    function getTipoitem() {
        return $this->tipoitem;
    }

    function getFechavencimiento() {
        return $this->fechavencimiento;
    }

    function getImporte() {
        return $this->importe;
    }

    function getRecargo() {
        return $this->recargo;
    }

    function getCuotadesde() {
        return $this->cuotadesde;
    }

    function getCuotahasta() {
        return $this->cuotahasta;
    }
    
    
    function getNrocuota() {
        return $this->nrocuota;
    }
    

    /******SETTER******/

    function setDb($db) {
        $this->db = $db;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setAnio($anio) {
        $this->anio = $anio;
    }

    function setStudent($student) {
        $this->student = $student;
    }

    function setContable($contable) {
        $this->contable = $contable;
    }

    function setIdcentrodecosto($idcentrodecosto) {
        $this->idcentrodecosto = $idcentrodecosto;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setPerson($person) {
        $this->person = $person;
    }

    function setIdestadopp($idestadopp) {
        $this->idestadopp = $idestadopp;
    }

    function setTipoitem($tipoitem) {
        $this->tipoitem[] = $tipoitem;
    }

    function setFechavencimiento($fechavencimiento) {
        $this->fechavencimiento[] = $fechavencimiento;
    }

    function setImporte($importe) {
        $this->importe[] = $importe;
    }

    function setRecargo($recargo) {
        $this->recargo[] = $recargo;
    }

    function setCuotadesde($cuotadesde) {
        $this->cuotadesde[] = $cuotadesde;
    }

    function setCuotahasta($cuotahasta) {
        $this->cuotahasta[] = $cuotahasta;
    }

    function setNrocuota($nrocuota) {
        $this->nrocuota[] = $nrocuota;
    }


    
    
}

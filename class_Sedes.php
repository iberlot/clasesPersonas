<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * Obtiene datos de las sedes .
 * 
 * Datos obtenidos de la tabla studentc.branch
 * 
 * Datos Tabla :
 * CODE -DESCRIP-SDESC-ACTIVE-COUNTRY-POLDIV-CITY-ACTIVO_PREINGRESO
 *
 *
 * @author lquiroga
 */

/**
 * Description of class_Sedes
 *
 * @author lquiroga
 */
class Sedes {
           
    protected $db;
    protected $code ;
    protected $descrip;
    protected $sdesc;
    protected $active;
    protected $country;
    protected $poldiv;
    protected $city;
    protected $activo_preingreso;
    
    
    public function __construct($db, $code = null){
            
        $this->db = $db;
        
        if($code != null && $code != ''){
            
            $query = 'SELECT * FROM studentc.BRANCH WHERE CODE = :code';

			$parametros = array (
					$code
			);

			$result = $this->db->query ($query, true, $parametros);

			$this->loadData ($this->db->fetch_array ($result));
            
        }
        
    }
        
    
    /**
    * Carga datos traidos de db en objeto
    */
    protected function loadData($fila){
        
            $this->setCode      ($fila['CODE']);
            $this->setDescrip   ($fila['DESCRIP']);
            $this->setSdesc     ($fila['SDESC']);            
            $this->setActive    ($fila['ACTIVE']);                        
            $this->setCountry   ($fila['COUNTRY']);            
            $this->setPoldiv    ($fila['POLDIV']);
            $this->setCity      ($fila['CITY']);
            
            $this->setActivo_preingreso ($fila['ACTIVO_PREINGRESO']);
    }

    
    /**********GETTERS***************/
      function getDb() {
        return $this->db;
    }

    function getCode() {
        return $this->code;
    }

    function getDescrip() {
        return $this->descrip;
    }

    function getSdesc() {
        return $this->sdesc;
    }

    function getActive() {
        return $this->active;
    }

    function getCountry() {
        return $this->country;
    }

    function getPoldiv() {
        return $this->poldiv;
    }

    function getCity() {
        return $this->city;
    }

    function getActivo_preingreso() {
        return $this->activo_preingreso;
    }

    
    /**********SETTERS***************/
    
    function setDb($db) {
        $this->db = $db;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setDescrip($descrip) {
        $this->descrip = $descrip;
    }

    function setSdesc($sdesc) {
        $this->sdesc = $sdesc;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setPoldiv($poldiv) {
        $this->poldiv = $poldiv;
    }

    function setCity($city) {
        $this->city = $city;
    }

    function setActivo_preingreso($activo_preingreso) {
        $this->activo_preingreso = $activo_preingreso;
    }
    
}

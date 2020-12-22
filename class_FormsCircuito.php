<?php

/**
 * Description of class_materias
 *
 * @author lquiroga
 */
class FormsCircuito {

    protected $db;
    protected $person;
    protected $idtoken;
    protected $estadocir;
    protected $personusuario;
    protected $fechaecha;
    protected $comentario;

    /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $code
     */
    public function __construct($db, $person = null ,$idtoken = null) {

        $this->db = $db;

        if ($person != null && $idtoken != null) {

             $parametros = array(
                $person,
                $idtoken
            );
                     
            $query = "SELECT PERSON , IDTOKEN ,ESTADOCIR , PERSONUSUARIO,"
                    . " TO_CHAR(FECHA,'yyyy/mm/dd HH24#MI#SS') AS fechaformat ,COMENTARIO "
                    . "FROM PREINGRESO.circuito where person = :person and idtoken = :idtoken";

            $result = $this->db->query($query,true,$parametros);

            $this->loadData($this->db->fetch_array($result));
            
        }
    }
    
    /**
     * getAll
     * Obtenemos todos los pedidos de informacion que hay en el sistema     
     * @return array
     */
     function getAll($anio = null){ 
        
        $query = "select PREINGRESO.circuito.* ,PREINGRESO.person.* , PREINGRESO.perdoc.* ,
            TO_CHAR(PREINGRESO.circuito.FECHA,'yyyy/mm/dd HH24#MI#SS') as FECHAFORMAT  from PREINGRESO.circuito 
        JOIN PREINGRESO.PERSON ON CIRCUITO.PERSON = PERSON.PERSON
        JOIN PREINGRESO.PERDOC ON CIRCUITO.PERSON = PERDOC.PERSON ";
         
        if($anio == null){             
            $anio   =date("Y");
        }
        
        $query.=" where  to_char(circuito.FECHA ,'YYYY') = :anio ";
            
        $parametros =array($anio);                   
         
        $circuito   = $this->db->query($query, true, $parametros);
        
        $salida=array();
        
        while ($fila = $this->db->fetch_array($circuito)) {                
            $salida[] = $fila;           
        }
        
        return $salida;
        
     }
    /**
     * getAll
     * Obtenemos todos los pedidos de informacion que hay en el sistema   segmentados por unidad  
     * @return array
     */
     function getByUnidad($unidad , $anio=null ){ 
        
        $query = "select PREINGRESO.circuito.* ,PREINGRESO.person.* , PREINGRESO.perdoc.* ,
            TO_CHAR(PREINGRESO.circuito.FECHA,'yyyy/mm/dd HH24#MI#SS') as FECHAFORMAT  from PREINGRESO.circuito 
        JOIN PREINGRESO.PERSON ON CIRCUITO.PERSON = PERSON.PERSON
        JOIN PREINGRESO.PERDOC ON CIRCUITO.PERSON = PERDOC.PERSON ";
        
        if($anio == null){             
            $anio   =date("Y");
        }
        
        $query.=" where  to_char(circuito.FECHA ,'YYYY') = :anio AND IDUNIDAD = :unidad";
         
       // echo($query);
        
        $parametros =array($anio,$unidad);                   
         
        $circuito   = $this->db->query($query, true, $parametros);
        
        $salida=array();
        
        while ($fila = $this->db->fetch_array($circuito)) {                
            $salida[] = $fila;           
        }
        
        return $salida;
        
     }
    /**
     * getAll
     * Obtenemos todos los pedidos de informacion que hay en el sistema     
     * @return array
     */
     public static function getEstados($estados ,$linkOracle_class = null){ 
        
        //$query = "select * from preingreso.estadocir where idestadocir IN(:estado  , :estado2 )";
        $query = "select * from preingreso.estadocir ";
         
        /*$e  =$estados+1;
        $e2 =$estados-1;
*/
        $parametros =array($e,$e2);                   

        //$estados    = $linkOracle_class->query($query, true, $parametros);
        $estados    = $linkOracle_class->query($query);
                
         while ($fila = $linkOracle_class->fetch_array($estados)) {     
            $salida[]           =$fila;                              
        }
        
        return $salida;
        
     }
    /**
     * SAVE FORM CIRCUITO
     * @param ARRAY $datos
     * @return BOOL
     */
     function saveformCircuito($datos ,$insertHist = null){ 
       
        /* si mando el email dejo la variable de session */
        $query = "INSERT INTO PREINGRESO.CIRCUITO (PERSON, IDTOKEN ,ESTADOCIR ,PERSONUSUARIO ,COMENTARIO) 
            VALUES (
            :person,
            :idtoken,
            :estadocir,
            :personusuario,            
            :comentario
        )";        

        $parametros = array(
            $datos['person'],
            $datos['idtoken'],
            $datos['estadocir'],
            $datos['personusuario'],
            $datos['comentario']
        );

        $circuito_insert = $this->db->query($query, true, $parametros);
        
        if($circuito_insert){
            return 1;
        }else{
            return 0;
        }
    
    } 
    
    /**
     * updaye FORM CIRCUITO
     * @param ARRAY $datos
     * @return BOOL
     */
     function UpdateFormCircuito($usuarioModifica , $nuevoestado = null ,$comentario = null ,$unidad=0 ){ 
       
        $person = $this->getPerson();
        $token  = $this->getIdtoken();
        
        if($comentario == null){
            $comentario = "Cambio de estado del pedido.";
        }
        
        if($nuevoestado == null ){
            $nuevoestado = $this->getEstadocir()+1;
        }
        
        $parametros = array($nuevoestado);
        /* si mando el email dejo la variable de session */
        $query = "UPDATE PREINGRESO.CIRCUITO SET ESTADOCIR = :estadocir  ";
        
        if($unidad != 0){
            $query.=",IDUNIDAD = :unidad ";       
            $parametros[]=$unidad;
        }
        
        $query.="WHERE person = :person AND idtoken = :idtoken";                
        
            $parametros[]=$person;
            $parametros[]=$token;
        

        $circuito_update = $this->db->query($query, true, $parametros);
        
        if($circuito_update){
            return 1;
        }else{
            return 0;
        }
    
    } 
    
    
    /**
     * SAVE FORM CIRCUITO
     * @param ARRAY $datos
     * @return BOOL
     */
     function get_historial($person=null , $idtoken=null){ 
       
        $query="SELECT PREINGRESO.CIRCUITOHIST.* ,  TO_CHAR(circuitohist.fecha,'yyyy/mm/dd HH24#MI#SS ') AS fechaformat , PERSON.LNAME , PERSON.FNAME ,ESTADOCIR.DESCRIPCION FROM PREINGRESO.CIRCUITOHIST 
        LEFT JOIN PREINGRESO.PERSON ON CIRCUITOHIST.PERSON =  PERSON.PERSON
        LEFT JOIN PREINGRESO.ESTADOCIR ON CIRCUITOHIST.ESTADOCIR = ESTADOCIR.IDESTADOCIR
        WHERE PREINGRESO.CIRCUITOHIST.PERSON = :person and CIRCUITOHIST.IDTOKEN = :token";
        
          $parametros = array(
              $this->getPerson(),
              $this->getIdtoken()
        );

        $circuito_hist = $this->db->query($query, true, $parametros);
        
        while ($fila = $this->db->fetch_array($circuito_hist)) {     
            $salida[]           =$fila;                              
        }
        
        return $salida;
    } 
    
  /**
     * SAVE HISTORIAL
     * @param ARRAY $datos
     * @return BOOL
     */
     function InsertHistorialCircuito($datos ){ 
       
        /* si mando el email dejo la variable de session */
        $query = "INSERT INTO PREINGRESO.CIRCUITOHIST (PERSON,IDTOKEN,ESTADOCIR,PERSONUSUARIO,COMENTARIO) 
            VALUES (
            :person,
            :idtoken,
            :estadocir,
            :personusuario,
            :comentario
        )";
        

        $parametros = array(
            $datos['person'],
            $datos['idtoken'],
            $datos['estadocir'],
            $datos['personusuario'],
            $datos['comentario']
        );

        $circuito_insert = $this->db->query($query, true, $parametros);
        
        if($circuito_insert){
            return  date("d-m-Y H:i:s");
        }else{
            return 0;
        }
    
    } 

     protected function loadData($fila) {
        
        if (isset($fila['PERSON'])) {
            $this->setPerson($fila['PERSON']);
        }
        
        if (isset($fila['IDTOKEN'])) {
            $this->setIdtoken($fila['IDTOKEN']);
        }  
        
        if (isset($fila['ESTADOCIR'])) {
            $this->setEstadocir($fila['ESTADOCIR']);
        }
        
        if (isset($fila['PERSONUSUARIO'])) {
            $this->setPersonusuario($fila['PERSONUSUARIO']);
        }  
        
        if (isset($fila['FECHAFORMAT'])) {
            $this->setFecha($fila['FECHAFORMAT']);
        }
        
        if (isset($fila['COMENTARIO'])) {
            $this->setComentario($fila['COMENTARIO']);
        }     
    }
    
    /**GETERS**/
    function getPerson() {
        return $this->person;
    }
    
    function getIdtoken() {
        return $this->idtoken;
    }

    function getEstadocir() {
        return $this->estadocir;
    }

    function getPersonusuario() {
        return $this->personusuario;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getComentario() {
        return $this->comentario;
    }

    /**SETERS**/
    
    function setIdtoken($idtoken) {
        $this->idtoken = $idtoken;
    }
    
    function setPerson($person) {
        $this->person = $person;
    }

    function setEstadocir($estadocir) {
        $this->estadocir = $estadocir;
    }

    function setPersonusuario($personusuario) {
        $this->personusuario = $personusuario;
    }

    function setFecha($fecha) {
        $this->fecha =  str_replace('#',':',$fecha);
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }

}

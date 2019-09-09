<?php

require_once ("/web/html/classesUSAL/class_Personas.php");
require_once ("/web/html/classesUSAL/class_derechos_varios.php");
require_once ("/web/html/classesUSAL/class_alumnos.php");
require_once ("/web/html/classesUSAL/class_carreras.php");

class Transacciones{
    
    protected $db;
    protected $idtransaccion;
    protected $idformulario;
    protected $canal;
    protected $idcentrodecosto;
    protected $monto;
    protected $estadotransaccion;
    protected $fechatransaccion;
    protected $fechaproceso;
    protected $estadoproceso;
    protected $nro_transaccion;       
    
    public function __construct($db, $idtransaccion = null) {
        
        $this->db = $db;
        
        if($idtransaccion != null){            
            
            $parametros = array (
                $idtransaccion
            );

            $query  = "select * from TESORERIA.TRANSACCIONES WHERE IDTRANSACCION = :idtransac";

            $result = $this->db->query ($query, true, $parametros);

            if ($result){

                    $arr_asoc = $db->fetch_array ($result);

                    $this->loadData ($arr_asoc);
            }
            
        }
        
    }
    
    /**
     * Salva datos en la tabla transacciones
     * insertTransac
     *
     * tabla :
     * IDTRANSACCION-IDFORMULARIO-CANAL-IDCENTRODECOSTO-MONTO-ESTADOTRANSACCION
     * FECHATRANSACCION-FECHAPROCESO-ESTADOPROCESO-NRO_TRANSACCION
     *
     * @param array $datos
     * @return bool
     *
     */
    public function insertTransac($datos){

            // $db = Conexion::openConnection();
            $datos['IDTRANSACCION'] = 'tesoreria.idtransaccion_seq.nextval';

            $insercion = $this->db->realizarInsert ($datos, 'tesoreria.TRANSACCIONES');

            return $insercion;
    }
        
    /**
    * Hace un inserta de los campos que pasemos y la tabla
    *
    * @param
    *        	array data_update -->El array de datos se maneja con indices y valores ej:$datos['PERSON'] ='alumno'
    * @param
    *        	strin tabla -->tabla a actualizar
    * @param
    *        	string where -->Condicion para actualizar
    *
    * @return bool
    */
	public function updateTransaccion($data_update, $tabla, $where ){
            
            // $db = Conexion::openConnection();
            $insercion = $this->db->realizarUpdate ($data_update, $tabla, $where);

            /* SI SE REALIZO LA CORRECTAMENTE UPDATE DE HISTORIAL */
            if ($insercion){
               echo('OK'); 
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
    */
	public function loadData($fila)
	{
		if (isset ($fila['idtransaccion'])){
                    
			$this->setIdtransaccion($fila['idtransaccion']);
		}

		if (isset ($fila['idformulario'])){
                    
			$this->setIdformulario($fila['idformulario']);
		}


		if (isset ($fila['canal'])){
                    
			$this->setCanal($fila['canal']);
		}

		if (isset ($fila['idcentrodecosto'])){
                    
			$this->setIdcentrodecosto($fila['idcentrodecosto']);
		}

		if (isset ($fila['monto'])){
                    
			$this->setMonto($fila['monto']);
		}

		if (isset ($fila['estadotransaccion'])){
                    
			$this->setEstadotransaccion($fila['estadotransaccion']);
		}

		if (isset ($fila['fechatransaccion'])){
                    
			$this->setFechatransaccion($fila['fechatransaccion']);
		}

		if (isset ($fila['estadoproceso'])){
                    
			$this->setEstadoproceso($fila['estadoproceso']);
		}

		if (isset ($fila['nro_transaccion'])){
                    
			$this->setNro_transaccion ($fila['nro_transaccion']);
		}

	}
        
        
        
    /****GETTERS******/
    function getDb(){
        return $this->db;
    }

    /**
     *
     * @return string el dato de la variable $idtransaccion
     */
    function getIdtransaccion(){
        return $this->idtransaccion;
    }
    
    /**
     *
     * @return string el dato de la variable idformulario
    */
    function getIdformulario(){
        return $this->idformulario;
    }

    /**
     *
     * @return string el dato de la variable canal
     */
    function getCanal(){
        return $this->canal;
    }

    /**
     *
     * @return string el dato de la variable idcentrodecosto
     */
    function getIdcentrodecosto(){
        return $this->idcentrodecosto;
    }

    /**
     *
     * @return string el dato de la variable monto
     */
    function getMonto(){
        return $this->monto;
    }

    /**
     *
     * @return string el dato de la variable estadotransaccion
     */
    function getEstadotransaccion(){
        return $this->estadotransaccion;
    }

    /**
     *
     * @return string el dato de la variable fechatransaccion
     */
    function getFechatransaccion(){
        return $this->fechatransaccion;
    }

    /**
     *
     * @return string el dato de la variable fechaproceso
     */
    function getFechaproceso() {
        return $this->fechaproceso;
    }

    /**
     *
     * @return string el dato de la variable estadoproceso
     */
    function getEstadoproceso() {
        return $this->estadoproceso;
    }

    /**
     *
     * @return string el dato de la variable nro_transaccion
     */
    function getNro_transaccion() {
        return $this->nro_transaccion;
    }
    
    /****SETTERS******/

    function setDb($db) {
        $this->db = $db;
    }

    /**
    * Setter del parametro $idtransaccion de la clase.
    *
    * @param number $idtransaccion
    *        	dato a cargar en la variable.
    */
    function setIdtransaccion($idtransaccion) {
        $this->idtransaccion = $idtransaccion;
    }

        
    /**
    * Setter del parametro $idformulario de la clase.
    *
    * @param number $idformulario
    *        	dato a cargar en la variable.
    */
    function setIdformulario($idformulario) {
        $this->idformulario = $idformulario;
    }
    

    /**
    * Setter del parametro $canal de la clase.
    *
    * @param string $canal
    *        	dato a cargar en la variable.
    */
    function setCanal($canal) {
        $this->canal = $canal;
    }

    /**
    * Setter del parametro $idcentrodecosto de la clase.
    *
    * @param number $idcentrodecosto
    *        	dato a cargar en la variable.
    */
    function setIdcentrodecosto($idcentrodecosto) {
        $this->idcentrodecosto = $idcentrodecosto;
    }

        
    /**
    * Setter del parametro $monto de la clase.
    *
    * @param number $monto
    *        	dato a cargar en la variable.
    */
    function setMonto($monto) {
        $this->monto = $monto;
    }

    
    /**
    * Setter del parametro $estadotransaccion de la clase.
    *
    * @param string $estadotransaccion
    *        	dato a cargar en la variable.
    */
    function setEstadotransaccion($estadotransaccion) {
        $this->estadotransaccion = $estadotransaccion;
    }

    
    /**
    * Setter del parametro $fechatransaccion de la clase.
    *
    * @param string $fechatransaccion
    *        	dato a cargar en la variable.
    */
    function setFechatransaccion($fechatransaccion) {
        $this->fechatransaccion = $fechatransaccion;
    }

        /**
    * Setter del parametro $fechaproceso de la clase.
    *
    * @param string $fechaproceso
    *        	dato a cargar en la variable.
    */
    function setFechaproceso($fechaproceso) {
        $this->fechaproceso = $fechaproceso;
    }

        /**
    * Setter del parametro $estadoproceso de la clase.
    *
    * @param number $estadoproceso
    *        	dato a cargar en la variable.
    */
    function setEstadoproceso($estadoproceso) {
        $this->estadoproceso = $estadoproceso;
    }

        /**
    * Setter del parametro $nro_transaccion de la clase.
    *
    * @param number $nro_transaccion
    *        	dato a cargar en la variable.
    */
    function setNro_transaccion($nro_transaccion) {
        $this->nro_transaccion = $nro_transaccion;
    }

               
        
        
        
}

?>
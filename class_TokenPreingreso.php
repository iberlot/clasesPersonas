<?php

/**
 * Description of class_materias
 *
 * @author lquiroga
 */
class TokenPreinscripcion {

    protected $db;
    protected $idToken;
    protected $token;
    protected $email;
    protected $validado;
    protected $validadoFecha;
    protected $fechaAlta;
    protected $typDoc;
    protected $idPasoPreingreso;
    protected $docNro;

    /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $code
     */
    public function __construct($db, $token = null) {

        $this->db = $db;
         
        if ($token != null) {
            
        $token=strtoupper($token);
            
        $query = "SELECT TOKEN, TO_CHAR(FECHAALTA, 'YYYY-MM-DD HH24:MI:SS') FECHAALTA ,TYPDOC, DOCNO, EMAIL, IDPASOPREINGRESO  "
                . "FROM PREINGRESO.TOKEN  where TOKEN = UPPER('$token')";

        $result = $this->db->query($query ,false);
        
        $data=$this->db->fetch_array($result);
        
        $this->loadData($data);
        
                if($this->getIdToken()){
                    return 1;
                }else{
                    return 0;
                }
        
        }
    }
    
    
    /**
     * Funtion generaToken Genera un token que se asocia a un aspirante a la hora de preinscribirse
     * NO SALVA EN LA DB EL TOKEN SOLO LO GENERA LUEGO DEBEMOS SALVARLO
     * 
     * @param int $cantcaract -->cantidad de caracteres que queremos para el token
     * @return type string
     */
    public function generaToken($cantcaract){ 
      $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $token=NULL; 
      
          for($x=1;$x<=$cantcaract;$x++){ 
              $Posicao = rand(0,$QuantidadeCaracteres); 
              $token .= substr($Caracteres,$Posicao,1); 
          } 

      return $token; 
    } 
    
    /**
     * Funtion generaToken Genera un token que se asocia a un aspirante a la hora de preinscribirse
     * 
     * @param array $datos-->array con campos para hacer insert
     * @return bool
     */
    function saveToken($datos){ 
       
        /* si mando el email dejo la variable de session */
        $query = "INSERT INTO PREINGRESO.TOKEN (IDTOKEN, TOKEN, EMAIL, VALIDADO,TYPDOC, DOCNO) VALUES (
            :idtoken,
            :token,
            :email,
            :validado,
            :typdoc,
            :docno
        )";
        
        $idtoken=$this->lastToken(1);
        
        $parametros = array(
            $idtoken,
            $datos['token'],
            $datos['email'],
            $datos['validate'],
            $datos['doctipo'],
            $datos['docnro']
        );

        $token_insertado = $this->db->query($query, true, $parametros);
        
        if($token_insertado){
            return $idtoken;
        }    
    
    } 
    
     /**
     * Funtion generaToken Genera un token que se asocia a un aspirante a la hora de preinscribirse
     * 
     * @param array $datos-->array con campos para hacer insert
     * @return bool
     */
    function UpdateEstadoToken($nuevoestado){ 
       
        /* Chequeao si el nuevo estado es mayor al actual , si no es asi no actualizo */
        if($nuevoestado >= $this->getIdPasoPreingreso()){
            
             $upd = "UPDATE PREINGRESO.TOKEN SET
                IDPASOPREINGRESO  = :nuevoestado 
                WHERE TOKEN = :email_token";
      
        $parametros = array(
            $nuevoestado,
            $this->getToken()
        );

        $token_act = $this->db->query($upd, true,$parametros);     
     
        return true;
        }else {
            return false;
        }
    
    } 
    
    /**
     * Obtengo el ultimo id de token generado en la tabla preingreso.token
     * $db--> conezion a la base , ya que el metodo es publico y se puede invocar sin iniciar la clase
     * $siguiente int --> si esta aen uno devolvemos siguiente id
     * @return type
     */
    public function lastToken($siguiente){            

        $idtoken    = '';
        $lasttoken  = '';

        /* obtengo el ultimo id*/
        $sel        = "SELECT MAX(IDTOKEN) ID FROM PREINGRESO.TOKEN";
        $stmt       = $this->db->query($sel);
        $row        = $this->db->fetch_array($stmt);
        $idtoken    = $row['ID'];
        
        if($siguiente == 1){
            $lasttoken = $idtoken+1;
        }else{
            $lasttoken = $idtoken;
        }
        return $lasttoken;
    }
    
    
    /**
     * Activo el token enviado     
     * @param string $token-->token que vamos a activar
     * @return bool
     */
    public function activarToken($token){            

        $query = "UPDATE PREINGRESO.TOKEN
                SET VALIDADO      = 1,
                    VALIDADOFECHA = CURRENT_TIMESTAMP
                WHERE TOKEN = '$token'";
         /*
        $parametros = array(
            $token
        );
*/
        $token_act = $this->db->query($query, false);
        
       if($token_act ){
           return 1;
       }else{
           return 0;
       }
    }
    
    /**
     * Html del email para validar el token
     * @return type
     */
     function htmlEmailValidateToken($http_cgi , $email_token,$urlvalidacion ){            

          $mensaje = '<html>
                <head>
                <style>
                  @import url("https://fonts.googleapis.com/css?family=Roboto");
                    *{
                      padding: 0;
                      margin: 0;
                    }
                    body {
                      font-family: "Roboto", sans-serif;
                      font-size:12px;
                    }
                  .preinscripcion-title{
                    width: 90%;
                    margin: 0 auto;
                    background: #4caf50;
                    padding: 15px ;
                    border-radius: 5px;
                    color: white;
                    line-height: 2em;
                    text-align: center;
                    box-sizing: border-box; 

                  }
                  .preinscripcion-title span{
                    font-size: 2em;
                  }
                  .preinscripcion-title h3{
                    color: white;
                  }
                  .preinscripcion-docs{
                    width: 90%;
                    padding: 15px;
                    border:1px solid #CCC;
                    border-radius: 5px;
                    margin: 15px auto;
                    line-height: 2em;
                    box-sizing: border-box; 

                  }
                  .preinscripcion-docs ul{
                    list-style-position: inside;
                    text-align: left;
                    width: 300px;
                  }
                  .preinscripcion-docs span{
                    color:#4caf50;
                    font-weight: bold;
                  }
                  .descargar{
                    background:#ff9800;
                    color: white!important;
                    margin:15px auto; 
                    text-decoration: none;
                    padding: 10px;
                    display: block;
                    width: 150px;
                  }
                </style>
                </head>
                <body>';

    $mensaje .= '<img srg="http://www.usal.edu.ar/sites/all/themes/agrov/archivos/usal2017.jpg" width="250" style="margin:0 auto;"/>';
    
    $mensaje .= '<div class="preinscripcion-title">
                    <h2>Muchas gracias por solicitar informaci&oacute;n.</h2>                    
                    <p>Para continuar con la solicitud <br>hac&eacute; clic en el bot&oacute;n para confirmar tu email. </p>
                    <p><a href="'.$http_cgi.'/'.$urlvalidacion.'.php?token='.$email_token.'" class="descargar">Continuar</a></p>
                    <p>Ten&eacute; en cuenta que el siguiente link es v&aacute;lido en el lapso de 1 hora.</p>
                  </div>
                </body>
                </html>';
    
        return $mensaje;
    }


    /**
     * Carga datos traidos de db en objeto
     */
    protected function loadData($fila) {
 
        if (isset($fila['IDTOKEN'])) {
            $this->setIdToken($fila['IDTOKEN']);
        }
        
        if (isset($fila['TOKEN'])) {
            $this->setToken($fila['TOKEN']);
        }
        
        if (isset($fila['EMAIL'])) {
            $this->setEmail($fila['EMAIL']);
        }
        
        if (isset($fila['VALIDADO'])) {
            $this->setValidado($fila['VALIDADO']);
        }
        
        if (isset($fila['VALIDADOFECHA'])) {
            $this->setValidadoFecha($fila['VALIDADOFECHA']);
        }
        
        if (isset($fila['FECHAALTA'])) {
            $this->setFechaAlta($fila['FECHAALTA']);
        }
        
        if (isset($fila['TYPDOC'])) {
            $this->setTypDoc($fila['TYPDOC']);
        }
        
        if (isset($fila['DOCNRO'])) {
            $this->setDocNro($fila['DOCNRO']);
        } 
        
        if (isset($fila['IDPASOPREINGRESO'])) {
            $this->setIdPasoPreingreso($fila['IDPASOPREINGRESO']);
        }     
        
        
    }
    
    
    
    /*
     ********* GETERS ********
     */
    function getDb() {
        return $this->db;
    }

    function getIdToken() {
        return $this->idToken;
    }

    function getToken() {
        return $this->token;
    }

    function getEmail() {
        return $this->email;
    }

    function getValidado() {
        return $this->validado;
    }

    function getValidadoFecha() {
        return $this->validadoFecha;
    }

    function getFechaAlta() {
        return $this->fechaAlta;
    }

    function getTypDoc() {
        return $this->typDoc;
    }

    function getDocNro() {
        return $this->docNro;
    }
    
    function getIdPasoPreingreso() {
        return $this->idPasoPreingreso;
    }
    
    /*
    ********* SETERS ********
    */
    
    function setDb($db) {
        $this->db = $db;
    }

    function setIdToken($idToken) {
        $this->idToken = $idToken;
    }

    function setToken($token) {
        $this->token = $token;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setValidado($validado) {
        $this->validado = $validado;
    }

    function setValidadoFecha($validadoFecha) {
        $this->validadoFecha = $validadoFecha;
    }

    function setFechaAlta($fechaAlta) {
        $this->fechaAlta = $fechaAlta;
    }

    function setTypDoc($typDoc) {
        $this->typDoc = $typDoc;
    }

    function setDocNro($docNro) {
        $this->docNro = $docNro;
    }
    
    function setIdPasoPreingreso($idPasoPreingreso) {
        $this->idPasoPreingreso = $idPasoPreingreso;
    }

}

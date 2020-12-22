<?php

/**
 * Clase que se ocupa del manejo de todo lo referente a las credenciales.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 16 nov. 2018
 * @lenguage PHP
 * @name class_credenciales.php
 * @version 0.1 version inicial del archivo.
 *
 */
/*
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

/**
 * Clase que se ocupa del manejo de todo lo referente a las credenciales.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 28 Jun. 2019
 * @name Credenciales
 * @version 1.0 version inicial
 */
class Credenciales {

    /**
     * Estado en el que se encuenta la credencial
     *
     * @var integer
     */
    private $estadocredencialca = 0; // number(2,0)

    /**
     * Codigo de ISIC
     *
     * @var string
     */
    private $codigoisic = ""; // varchar2(8 byte)

    /**
     * Numero de credencial, es un entero de 10 digitos.
     *
     * @var integer
     */
    private $nrodechip = 0; // number(10,0)

    /**
     * Fecha en que se envia a ISIC la peticion de tarjeta.
     *
     * @var string
     */
    private $sca_fecha = ""; // date

    /**
     * Categoria en la que se encuentra la persona.
     *
     * @var integer
     */
    private $sca_categoria = 0; // number(38,0)

    /**
     * Lote de transaccion de la tarjeta.
     *
     * @var string
     */
    private $sca_lote = ""; // varchar2(21 byte)

    /**
     * Tipo de formulario en el cual se registro el pedido
     *
     * @var integer
     */
    private $tipo_formulario = 0; // number(38,0)

    /**
     * Fecha en que se emitio la tarjeta.
     *
     * @var string
     */
    private $fecha_chip = ""; // date

    /**
     * String de uso variable, normalmente para registrar el motivo por el cual no se envio la peticion de tarjeta o se rechazo la misma.
     *
     *
     * @var string
     */
    private $motivo = ""; // varchar2(200 byte)

    /**
     * Tipo de credencial, 1->ISIC,2->Blanca
     *
     * @var integer
     */
    private $tipo_credencial = 0; // number(1,0)

    /**
     * Objeto de coneccion a la base de datos
     *
     * @var class_db Class_db
     */
    private $db;

    /**
     * Constructor de la clase
     *
     * @param class_db $db
     * @param int $person
     *        	numero identificador de la persona.
     */
    public function __construct($db = null, $person = null) {
        if (!isset($db) or empty($db) or $db == null) {
            if (!$this->db = Sitios::openConnection()) {
                global $db;

                if (isset($db) and ! empty($db) and $db != null) {
                    $this->db = $db;
                }
            }
        } else {
            $this->db = $db;
        }

        if (isset($person) and $person != null) {
            $this->inicializar_con_person($person);
        }
    }

    /**
     * Constructor del la clase pasandole los datos por parametro.
     *
     * @param object $db
     * @param int $estadocredencialca
     * @param string $codigoisic
     * @param int $nrodechip
     * @param string $sca_fecha
     * @param int $sca_categoria
     * @param string $sca_lote
     * @param int $tipo_formulario
     * @param string $fecha_chip
     * @param string $motivo
     * @param int $tipo_credencial
     */
    public function constructo_con_datos($db = null, $estadocredencialca = null, $codigoisic = null, $nrodechip = null, $sca_fecha = null, $sca_categoria = null, $sca_lote = null, $tipo_formulario = null, $fecha_chip = null, $motivo = null, $tipo_credencial = null) {
        if (!isset($db) or empty($db)) {
            global $db;

            if (!isset($db) or empty($db)) {
                $this->db = Sitios::openConnection();
            } else {
                $this->db = $db;
            }
        } else {
            $this->db = $db;
        }

        if (isset($estadocredencialca) && $estadocredencialca != "" && $estadocredencialca != null) {
            $this->setEstadocredencialca($estadocredencialca);
        }
        if (isset($codigoisic) && $codigoisic != "" && $codigoisic != null) {
            $this->setCodigoisic($codigoisic);
        }
        if (isset($nrodechip) && $nrodechip != "" && $nrodechip != null) {
            $this->setNroDeChip($nrodechip);
        }
        if (isset($sca_fecha) && $sca_fecha != "" && $sca_fecha != null) {
            $this->setSca_fecha($sca_fecha);
        }
        if (isset($sca_categoria) && $sca_categoria != "" && $sca_categoria != null) {
            $this->setSca_categoria($sca_categoria);
        }
        if (isset($sca_lote) && $sca_lote != "" && $sca_lote != null) {
            $this->setSca_lote($sca_lote);
        }
        if (isset($tipo_formulario) && $tipo_formulario != "" && $tipo_formulario != null) {
            $this->setTipo_formulario($tipo_formulario);
        }
        if (isset($fecha_chip) && $fecha_chip != "" && $fecha_chip != null) {
            $this->setSca_fecha($fecha_chip);
        }
        if (isset($motivo) && $motivo != "" && $motivo != null) {
            $this->setMotivo($motivo);
        }
        if (isset($tipo_credencial) && $tipo_credencial != "" && $tipo_credencial != null) {
            $this->setTipo_credencial($tipo_credencial);
        }
    }

    /**
     * Constructor de la clase que inicializa los datos sacandolos de un array.
     *
     * @param object $db
     * @param array $datos
     *        	array con los parametros con los que inicializar la clase, La clave del array deber coincidir con el nombre de los parametros de la case, en minuscula.
     */
    public function constructo_con_array($db = null, $datos) {
        if (!isset($db) or empty($db)) {
            global $db;

            if (!isset($db) or empty($db)) {
                $this->db = Sitios::openConnection();
            } else {
                $this->db = $db;
            }
        } else {
            $this->db = $db;
        }

        if (isset($datos['estadocredencialca']) && $datos['estadocredencialca'] != "" && $datos['estadocredencialca'] != null) {
            $this->setEstadocredencialca($datos['estadocredencialca']);
        }
        if (isset($datos['codigoisic']) && $datos['codigoisic'] != "" && $datos['codigoisic'] != null) {
            $this->setCodigoisic($datos['codigoisic']);
        }
        if (isset($datos['nrodechip']) && $datos['nrodechip'] != "" && $datos['nrodechip'] != null) {
            $this->setNroDeChip($datos['nrodechip']);
        }
        if (isset($datos['sca_fecha']) && $datos['sca_fecha'] != "" && $datos['sca_fecha'] != null) {
            $this->setSca_fecha($datos['sca_fecha']);
        }
        if (isset($datos['sca_categoria']) && $datos['sca_categoria'] != "" && $datos['sca_categoria'] != null) {
            $this->setSca_categoria($datos['sca_categoria']);
        }
        if (isset($datos['sca_lote']) && $datos['sca_lote'] != "" && $datos['sca_lote'] != null) {
            $this->setSca_lote($datos['sca_lote']);
        }
        if (isset($datos['tipo_formulario']) && $datos['tipo_formulario'] != "" && $datos['tipo_formulario'] != null) {
            $this->setTipo_formulario($datos['tipo_formulario']);
        }
        if (isset($datos['fecha_chip']) && $datos['fecha_chip'] != "" && $datos['fecha_chip'] != null) {
            $this->setSca_fecha($datos['fecha_chip']);
        }
        if (isset($datos['motivo']) && $datos['motivo'] != "" && $datos['motivo'] != null) {
            $this->setMotivo($datos['motivo']);
        }
        if (isset($datos['tipo_credencial']) && $datos['tipo_credencial'] != "" && $datos['tipo_credencial'] != null) {
            $this->setTipo_credencial($datos['tipo_credencial']);
        }
    }

    /**
     * Busca una persona en personca, en caso de encontrarla devuelve los datos caso contrario retorna false.
     *
     * @param int $person
     *        	numero identificatorio de la persona
     * @return array|boolean
     */
    public function buscar_personca($person) {
        $sql = "SELECT * FROM appgral.personca WHERE person = :person";

        $parametros = array();
        $parametros[] = $person;

        $result = $this->db->query($sql, true, $parametros);

        if ($fila = $this->db->fetch_array($result)) {
            return $fila;
        } else {
            return false;
        }
    }

    /**
     * Actualiza los datos de una persona en personca se le pasa el person y usa el resto de los datos de los parametros de la clase.
     *
     * @param int $person
     *        	numero identificatorio de la persona
     * @throws Exception Mensaje de excepcion en caso de no poder realizar el update.
     * @return boolean Retorna true en caso de realizar el update correctamente.
     */
    public function update_personca_basic($person) {
        $sql = "UPDATE appgral.personca SET nrodechip = :tarjeta, tipo_credencial = :tipo WHERE person =:person";

        $parametros = array();
        $parametros[] = $this->nrodechip;
        $parametros[] = $this->tipo_credencial;
        $parametros[] = $person;

        if ($this->db->query($sql, true, $parametros)) {
            return true;
        } else {
            throw new Exception("No se pudo realizar el update en personca!.");
        }
    }

    /**
     * Realiza la comprobacion de que el numero de tarjeta no se encuentre registrado en la base.
     * En caso de estar disponible retorna TRUE.
     *
     * @param int $numTarjeta
     *        	numero de tarjeta a comprobar
     * @throws Exception En caso de que la tarjeta se encuentre registrada se retorna una excepcion.
     * @return boolean True en caso de que se encuentre disponible el numero de tarjeta.
     */
    public function comprobar_tarjeta_unica($numTarjeta) {
        $sql = "SELECT * FROM appgral.personca WHERE nrodechip = :tarjeta";

        $parametros = array();
        $parametros[] = $numTarjeta;

        $result = $this->db->query($sql, true, $parametros);

        $fila = $this->db->fetch_array($result);

        if (!empty($fila)) {
            throw new Exception("La tarjeta ya se encuentra asociada a otra persona!!!.");
        }

        return true;
    }

    /**
     * Busca el numero de la tarjeta en la base.
     *
     *
     * @param int $numTarjeta
     *        	numero de tarjeta a comprobar
     * @throws Exception En caso de que la tarjeta se encuentre registrada se retorna una excepcion.
     * @return boolean false en caso de que no se encuentre el numero de tarjeta y el person de la persona en caso de encontrarse.
     */
    public function buscar_tarjeta($numTarjeta) {
        $sql = "SELECT * FROM appgral.personca WHERE nrodechip = :tarjeta";

        $parametros = array();
        $parametros[] = $numTarjeta;

        $result = $this->db->query($sql, true, $parametros);

        $fila = $this->db->fetch_array($result);

        if (!empty($fila)) {
            return $fila['PERSON'];
        }

        return false;
    }

    /**
     * Inserta los datos de una persona en personca se le pasa el person y usa el resto de los datos de los parametros de la clase.
     *
     * @param int $person
     *        	numero identificatorio de la persona
     * @throws Exception Mensaje de excepcion en caso de no poder realizar el insert.
     * @return boolean Retorna true en caso de realizar la insercion correctamente.
     */
    public function insertar_personca_basic($person) {
        $sql = "INSERT INTO appgral.personca (person, nrodechip, tipo_credencial) VALUES (:person, :tarjeta,:tipo)";

        $parametros = array();
        $parametros[] = $person;
        $parametros[] = $this->nrodechip;
        $parametros[] = $this->tipo_credencial;

        if ($this->db->query($sql, true, $parametros)) {
            return true;
        } else {
            throw new Exception("No se pudo realizar el insert en personca!.");
        }
    }

    /**
     * Inicializa los valores de la clase con los datos en la base en caso de haberlos
     *
     * @param int $person
     * @return resource
     */
    public function inicializar_con_person($person) {
        $sql = "SELECT
				    person,
				    estadocredencialca,
				    codigoisic,
				    nrodechip,
				    TO_CHAR(sca_fecha,'dd/mm/yyyy') sca_fecha,
				    sca_categoria,
				    sca_lote,
				    tipo_formulario,
				    TO_CHAR(fecha_chip,'dd/mm/yyyy') fecha_chip,
				    motivo,
				    tipo_credencial
				FROM
				    appgral.personca
				WHERE
				    person =:person";

        $parametros = array();
        $parametros[] = $person;

        $result = $this->db->query($sql, true, $parametros);

        $fila = $this->db->fetch_array($result);

        if (!empty($fila)) {
            $this->setEstadocredencialca($fila['ESTADOCREDENCIALCA']);
            $this->setCodigoisic($fila['CODIGOISIC']);
            $this->set_Nrodechip($fila['NRODECHIP']);
            $this->setSca_fecha($fila['SCA_FECHA']);
            $this->setSca_categoria($fila['SCA_CATEGORIA']);
            $this->setSca_lote($fila['SCA_LOTE']);
            $this->setTipo_formulario($fila['TIPO_FORMULARIO']);
            $this->setSca_fecha($fila['FECHA_CHIP']);
            $this->setMotivo($fila['MOTIVO']);
            $this->setTipo_credencial($fila['TIPO_CREDENCIAL']);

            return $fila['PERSON'];
        }
    }

    /**
     * Retorna el dato del parametro $estadocredencialca.
     *
     * @return int $estadocredencialca
     */
    public function getEstadocredencialca() {
        return $this->estadocredencialca;
    }

    /**
     * Retorna el dato del parametro $codigoisic
     *
     * @return string $codigoisic el dato de la variable
     */
    public function getCodigoisic() {
        return $this->codigoisic;
    }

    /**
     * Retorna el dato del parametro $nrodechip
     *
     * @return number el dato de la variable $nrodechip
     */
    public function getNrodechip() {
        return $this->nrodechip;
    }

    /**
     * Retorna el dato del parametro $sca_fecha
     *
     * @return string el dato de la variable $sca_fecha
     */
    public function getSca_fecha() {
        return $this->sca_fecha;
    }

    /**
     * Retorna el dato del parametro $sca_categoria
     *
     * @return number el dato de la variable $sca_categoria
     */
    public function getSca_categoria() {
        return $this->sca_categoria;
    }

    /**
     * Retorna el dato del parametro $sca_lote
     *
     * @return string el dato de la variable $sca_lote
     */
    public function getSca_lote() {
        return $this->sca_lote;
    }

    /**
     * Retorna el dato del parametro $tipo_formulario
     *
     * @return number el dato de la variable $tipo_formulario
     */
    public function getTipo_formulario() {
        return $this->tipo_formulario;
    }

    /**
     * Retorna el dato del parametro $fecha_chip
     *
     * @return string el dato de la variable $fecha_chip
     */
    public function getFecha_chip() {
        return $this->fecha_chip;
    }

    /**
     * Retorna el dato del parametro $motivo
     *
     * @return string el dato de la variable $motivo
     */
    public function getMotivo() {
        return $this->motivo;
    }

    /**
     * Retorna el dato del parametro tipo_credencial
     *
     * @return number el dato de la variable $tipo_credencial
     */
    public function getTipo_credencial() {
        return $this->tipo_credencial;
    }

    /**
     * Retorna la descripcion del dato del parametro tipo_credencial
     *
     * @return string "ISIC", "Blanca", "Sin tarjeta";
     */
    public function getTipo_credencial_desc() {
        switch ($this->tipo_credencial) {
            case 1 :
                return "ISIC";
            case 2 :
                return "Blanca";
            default :
                return "Sin tarjeta";
        }
    }

    /**
     * Setter del atributo $estadocredencialca
     *
     * @param int $estadocredencialca
     *        	a cargar en la variable
     */
    public function setEstadocredencialca($estadocredencialca) {
        $this->estadocredencialca = $estadocredencialca;
    }

    /**
     * Setter del atributo $codigoisic
     *
     * @param string $codigoisic
     *        	a cargar en la variable
     */
    public function setCodigoisic($codigoisic) {
        $this->codigoisic = $codigoisic;
    }

    /**
     * Setter del atributo $nrodechip
     *
     * @param int $nrodechip
     *        	a cargar en la variable luego de comprobar que el dato no se encuentre en la base.
     */
    public function set_nuevo_Nrodechip($nrodechip) {
        if ($this->comprobar_tarjeta_unica($nrodechip)) {
            $this->nrodechip = $nrodechip;
        }
    }

    /**
     * Setter del atributo $nrodechip
     *
     * @param int $nrodechip
     *        	a cargar en la variable
     */
    public function set_Nrodechip($nrodechip) {
        $this->nrodechip = $nrodechip;
    }

    /**
     * Setter del atributo $sca_fecha
     *
     * @param string $sca_fecha
     *        	a cargar en la variable
     */
    public function setSca_fecha($sca_fecha) {
        $this->sca_fecha = $sca_fecha;
    }

    /**
     * Setter del atributo $sca_categoria
     *
     * @param int $sca_categoria
     *        	a cargar en la variable
     */
    public function setSca_categoria($sca_categoria) {
        $this->sca_categoria = $sca_categoria;
    }

    /**
     * Setter del atributo $sca_lote
     *
     * @param string $sca_lote
     *        	a cargar en la variable
     */
    public function setSca_lote($sca_lote) {
        $this->sca_lote = $sca_lote;
    }

    /**
     * Setter del atributo $tipo_formulario
     *
     * @param int $tipo_formulario
     *        	a cargar en la variable
     */
    public function setTipo_formulario($tipo_formulario) {
        $this->tipo_formulario = $tipo_formulario;
    }

    /**
     * Setter del atributo $fecha_chip
     *
     * @param string $fecha_chip
     *        	a cargar en la variable
     */
    public function setFecha_chip($fecha_chip) {
        $this->fecha_chip = $fecha_chip;
    }

    /**
     * Setter del atributo $motivo
     *
     * @param string $motivo
     *        	a cargar en la variable
     */
    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    /**
     * Setter del atributo $tipo_credencial
     *
     * @param int $tipo_credencial
     *        	a cargar en la variable
     */
    public function setTipo_credencial($tipo_credencial) {
        $this->tipo_credencial = $tipo_credencial;
    }

    /**
     * Setter del atributo $db
     *
     * @param class_db $db
     *        	dato a cargar en la variable
     */
    public function setDb($db) {
        $this->db = $db;
    }

}

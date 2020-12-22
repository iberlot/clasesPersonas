<?php

/**
 * Archivo principar de la clase.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @todo FechaC 19/2/2016 - Lenguaje PHP
 *
 * @name class_institucion.php
 *
 */

/**
 * Clase encargada del manejo de todos los datos referentes a la persona.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @name class_institucion
 *
 * @version 0.1 - Version de inicio
 *
 * @package Classes_USAL
 *
 * @category General
 *
 * @todo El usuario que se conecta a la base debe tener los siguientes permisos -
 *       - SELECT :
 *       - UPDATE :
 *       - INSERT :
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
 * totalHorasPerdidasAqui = 106
 *
 */
class class_institucion {

    /**
     * Se le pasara en caso de necesitar algun link para acceder
     *
     * @var string Por defecto vasio.
     */
    public $db_link = "";

    /**
     * Realiza un listado con todos las instituciones que cumplan una determinada condicion.
     *
     * @name listarInstituciones
     * @param object $db
     *        	- Objeto encargado de la interaccion con la base de datos.
     * @param mixed[] $datos
     *        	- Datos extra con los que realizar la busqueda, puede ser cualquier dato de la tabla, con el indice igual al nombre del campo en minuscula.
     * @param
     *        	string - Para agregar al funal del where
     * @return int[] - array con todos los ents resultantes
     */
    public function listarInstituciones($db, $datos = "", $extraWhere = "") {
        try {
            $parametros = array();

            if (isset($datos['ent']) and $datos['ent'] != "") {
                $where[] = " ent = :ent ";
                $parametros[] = $datos['ent'];
            }

            if (isset($datos['descrip']) and $datos['descrip'] != "") {
                $where[] = " descrip = :descrip ";
                $parametros[] = $datos['descrip'];
            }
            if (isset($datos['address']) and $datos['address'] != "") {
                $where[] = " address = :address ";
                $parametros[] = $datos['address'];
            }
            if (isset($datos['country']) and $datos['country'] != "") {
                $where[] = " country = :country ";
                $parametros[] = $datos['country'];
            }
            if (isset($datos['poldiv']) and $datos['poldiv'] != "") {
                $where[] = " poldiv = :poldiv ";
                $parametros[] = $datos['poldiv'];
            }
            if (isset($datos['city']) and $datos['city'] != "") {
                $where[] = " city = :city ";
                $parametros[] = $datos['city'];
            }
            if (isset($datos['relenti']) and $datos['relenti'] != "") {
                $where[] = " relenti = :relenti ";
                $parametros[] = $datos['relenti'];
            }
            if (isset($datos['stdate']) and $datos['stdate'] != "") {
                $where[] = " stdate = :stdate ";
                $parametros[] = $datos['stdate'];
            }
            if (isset($datos['duedate']) and $datos['duedate'] != "") {
                $where[] = " duedate = :duedate ";
                $parametros[] = $datos['duedate'];
            }
            if (isset($datos['shortdes']) and $datos['shortdes'] != "") {
                $where[] = " shortdes = :shortdes ";
                $parametros[] = $datos['shortdes'];
            }
            if (isset($datos['activity']) and $datos['activity'] != "") {
                $where[] = " activity = :activity ";
                $parametros[] = $datos['activity'];
            }
            if (isset($datos['telep']) and $datos['telep'] != "") {
                $where[] = " telep = :telep ";
                $parametros[] = $datos['telep'];
            }
            if (isset($datos['active']) and $datos['active'] != "") {
                $where[] = " active = :active ";
                $parametros[] = $datos['active'];
            }

            if (isset($where) and $where != "") {
                $where = implode(" AND ", $where);

                $where = " AND " . $where;
            } else {
                $where = "";
            }

            $sql = "SELECT ent FROM appgral.entity" . $this->db_link . " WHERE 1 = 1 " . $where . " " . $extraWhere;

            $result = $db->query($sql, true, $parametros);

            $rst = $db->fetch_all($result);

            return $rst;

            // if (1 == 1)
            // {
            // }
            // else
            // {
            // throw new Exception ('ERROR: No se pudo realizar la insercion en sueldos.valorremu.');
            // }
        } catch (Exception $e) {
            if ($db->debug == true) {
                return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage();
            } else {
                return $e->getMessage();
            }

            if ($db->dieOnError == true) {
                exit();
            }
        }
    }

    /**
     * Devuelve un array con los valores de aentity usando los SHORTDES como claves.
     *
     * @name buscarAentity
     * @param mixed[] $datosAUsar
     *        	- Requiere que dentro de los datos enviados este si o si el person de la persona
     *
     * @return Array
     */
    public function buscarAentity($datosAUsar) {
        global $db;
        try {
            if ($datosAUsar['ent'] != "") {
                $ent = $datosAUsar['ent'];

                $sql = "SELECT * FROM appgral.aentity" . $this->db_link . " WHERE ent = :ent";

                $parametros[0] = $ent;

                $result = $db->query($sql, true, $parametros);

                while ($recu = $db->fetch_array($result)) {
                    $instituto[$recu['SHORTDES']] = $recu['VAL'];
                }

                if (isset($instituto) and $instituto != "") {
                    return $instituto;
                }
            } else {
                throw new Exception('ERROR: El person es obligatorio.');
            }
        } catch (Exception $e) {
            if ($db->debug == true) {
                return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage();
            } else {
                return $e->getMessage();
            }

            if ($db->dieOnError == true) {
                exit();
            }
        }
    }

    /**
     * Devuelve el nombre de la institucion.
     *
     * @param object $db
     *        	- Objeto encargado de la interaccion con la base de datos.
     * @param mixed[] $datosAUsar
     *        	- Es impresindible que contenga el inice "ent" de lo contrario devolvera error.
     * @throws Exception - Tanto si no se pasa el person como si no se puede recuperar valor.
     * @return string[] - Con los campos LNAME y FNAME.
     */
    public function getNombreInstituto($db, $datosAUsar) {
        try {
            if (isset($datosAUsar['ent']) and $datosAUsar['ent'] != "") {
                $where[] = " ent = :ent ";
                $parametros[] = $datosAUsar['ent'];

                if ($where != "") {
                    $where = implode(" AND ", $where);

                    $where = " AND " . $where;
                }

                $sql = "SELECT descrip FROM appgral.entity WHERE 1=1 " . $where;

                if ($result = $db->query($sql, true, $parametros)) {
                    $rst = $db->fetch_array($result);

                    return $rst;
                } else {
                    throw new Exception('ERROR: No se pudo realizar la busqueda en appgral.entity.');
                }
            } else {
                throw new Exception('ERROR: El ent es obligatorio.');
            }
        } catch (Exception $e) {

            $this->errores($e);

            if ($db->debug == true) {
                return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage();
            } else {
                return $e->getMessage();
            }

            if ($db->dieOnError == true) {
                exit();
            }
        }
    }

}

?>
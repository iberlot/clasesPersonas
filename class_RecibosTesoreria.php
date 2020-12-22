<?php

/**
 * Archivo de la clase Caja
 *
 *
 * @author lquiroga - lquiroga@gmail.com
 *
 * @since 25 jun. 2019
 * @lenguage PHP
 * @name class_caja.php
 * @version 0.1 version inicial del archivo.
 *
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
 *
 * Manejo de caja del sistema tesoreria
 */
class RecibosTesoreria {

    protected $db;
    protected $nrorecibo;
    protected $person;
    protected $idfaesca;
    protected $estado;
    protected $fecha;
    protected $nrotipo;
    protected $nrofactura;
    protected $tipopago;
    protected $tipotar;
    protected $nrotar;
    protected $nrocupon;
    protected $cuotas;
    protected $impreso;
    protected $importepago;
    protected $importetotal;
    protected $idcajeragenero;
    protected $nromovasociado;
    protected $idrecibos;
    protected $fechaduplicado;
    protected $idcajeraduplica;
    protected $fechacancela;
    protected $idcajeracancela;
    protected $formapago;
    protected $idcarrera;
    protected $idcaja;
    protected $montoefectivo;
    protected $idcheq1;
    protected $idcheq2;
    protected $tipotarj;
    protected $codigotarj;
    protected $ultdigitos;
    protected $importetarj;
    protected $movimientos = array();

    function __construct($db, $idrecibo = null) {

        $this->db = $db;

        if ($idrecibo != null) {

            $parametros = array(
                $idrecibo
            );

            $query = "SELECT RECIBOS_CAJA.* , PAGOSRECIBOS.* FROM RECIBOS_CAJA "
                    . "JOIN PAGOSRECIBOS ON RECIBOS_CAJA.NRORECIBO = PAGOSRECIBOS.NRORECIBO "
                    . "WHERE RECIBOS_CAJA.NRORECIBO = :idrecibo";

            $result = $this->db->query($query, true, $parametros);

            $this->loadData($this->db->fetch_array($result));
        }
    }

    /**
     * Devuelve un array con los movimientos pertenecientes al recibo que estamos buscando
     * 
     * @param int $nrorecibo
     */
    function GetMovimientosPorRecibo() {

        $query = "SELECT * FROM MOVIMIENTOS where NRO = " . $this->getNrorecibo();

        $parametros = array(
            $this->nrorecibo
        );

        $result = $this->db->query($query, true, $parametros);

        while ($fila2 = $this->db->fetch_array($result)) {

            $r[] = $fila2;
        }

        return($r);
    }

    /**
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * IDSESION
     * IDPERSON
     * FECHAAPERTURA
     * FECHACIERRE
     * NROCAJA
     * TURNO
     * FONDOAPERTURA
     * FONDOCIERRE
     * NOMBREUSUARIO
     * IDSESIONUSUARIO
     * DIFERENCIACIERRE
     * IDSUCURSAL
     *
     * @param array $fila
     *        	return objet alumno
     */
    public function loadData($fila) {

        //return $fila;

        $this->setNrorecibo($fila['NRORECIBO']);
        $this->setPerson($fila['PERSON']);
        $this->setIdfaesca($fila['IDFAESCA']);
        $this->setEstado($fila['ESTADO']);

        if (isset($fila['FECHA'])) {
            $this->setFecha($fila['FECHA']);
        }

        $this->setNrotipo($fila['NROTIPO']);
        $this->setNrofactura($fila['NROFACTURA']);
        $this->setTipopago($fila['TIPOPAGO']);
        $this->setTipotar($fila['TIPOTAR']);
        $this->setNrotar($fila['NROTAR']);
        $this->setNrocupon($fila['NROCUPON']);
        $this->setCuotas($fila['CUOTAS']);
        $this->setImpreso($fila['IMPRESO']);
        $this->setImportepago($fila['IMPORTEPAGO']);
        $this->setImportetotal($fila['IMPORTETOTAL']);
        $this->setIdcajeragenero($fila['IDCAJERAGENERO']);
        $this->setNromovasociado($fila['NROMOVASOCIADO']);
        $this->setIdrecibos($fila['IDRECIBOS']);
        $this->setFechaduplicado($fila['FECHADUPLICADO']);
        $this->setIdcajeraduplica($fila['IDCAJERADUPLICA']);
        $this->setFechacancela($fila['FECHACANCELA']);
        $this->setIdcajeracancela($fila['IDCAJERACANCELA']);
        $this->setFormapago($fila['FORMAPAGO']);
        $this->setIdcarrera($fila['IDCARRERA']);
        $this->setIdcaja($fila['IDCAJA']);
        $this->setMontoefectivo($fila['MONTOEFECTIVO']);
        $this->setIdcheq1($fila['IDCHEQ1']);
        $this->setIdcheq2($fila['IDCHEQ2']);
        $this->setTipotarj($fila['TIPOTARJ']);
        $this->setCodigotarj($fila['CODIGOTARJ']);
        $this->setUltdigitos($fila['ULTDIGITOS']);
        $this->setImportetarj($fila['IMPORTETARJ']);
    }

    /**
     * *************GETTERS*****************
     */
    function getDb() {
        return $this->db;
    }

    function setDb($db) {
        $this->db = $db;
    }

    function getNrorecibo() {
        return $this->nrorecibo;
    }

    function getPerson() {
        return $this->person;
    }

    function getIdfaesca() {
        return $this->idfaesca;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getNrotipo() {
        return $this->nrotipo;
    }

    function getNrofactura() {
        return $this->nrofactura;
    }

    function getTipopago() {
        return $this->tipopago;
    }

    function getTipotar() {
        return $this->tipotar;
    }

    function getNrotar() {
        return $this->nrotar;
    }

    function getNrocupon() {
        return $this->nrocupon;
    }

    function getCuotas() {
        return $this->cuotas;
    }

    function getImpreso() {
        return $this->impreso;
    }

    function getImportepago() {
        return $this->importepago;
    }

    function getImportetotal() {
        return $this->importetotal;
    }

    function getIdcajeragenero() {
        return $this->idcajeragenero;
    }

    function getNromovasociado() {
        return $this->nromovasociado;
    }

    function getIdrecibos() {
        return $this->idrecibos;
    }

    function getFechaduplicado() {
        return $this->fechaduplicado;
    }

    function getIdcajeraduplica() {
        return $this->idcajeraduplica;
    }

    function getFechacancela() {
        return $this->fechacancela;
    }

    function getIdcajeracancela() {
        return $this->idcajeracancela;
    }

    function getFormapago() {
        return $this->formapago;
    }

    function getIdcarrera() {
        return $this->idcarrera;
    }

    function getIdcaja() {
        return $this->idcaja;
    }

    function getMontoefectivo() {
        return $this->montoefectivo;
    }

    function getIdcheq1() {
        return $this->idcheq1;
    }

    function getIdcheq2() {
        return $this->idcheq2;
    }

    function getTipotarj() {
        return $this->tipotarj;
    }

    function getCodigotarj() {
        return $this->codigotarj;
    }

    function getUltdigitos() {
        return $this->ultdigitos;
    }

    function getImportetarj() {
        return $this->importetarj;
    }

    function getMovimientos() {
        return $this->movimientos;
    }

    /**
     * *************SETTERS*****************
     */
    function setMovimientos($movimientos) {
        $this->movimientos[] = $movimientos;
    }

    function setNrorecibo($nrorecibo) {
        $this->nrorecibo = $nrorecibo;
    }

    function setPerson($person) {
        $this->person = $person;
    }

    function setIdfaesca($idfaesca) {
        $this->idfaesca = $idfaesca;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setNrotipo($nrotipo) {
        $this->nrotipo = $nrotipo;
    }

    function setNrofactura($nrofactura) {
        $this->nrofactura = $nrofactura;
    }

    function setTipopago($tipopago) {
        $this->tipopago = $tipopago;
    }

    function setTipotar($tipotar) {
        $this->tipotar = $tipotar;
    }

    function setNrotar($nrotar) {
        $this->nrotar = $nrotar;
    }

    function setNrocupon($nrocupon) {
        $this->nrocupon = $nrocupon;
    }

    function setCuotas($cuotas) {
        $this->cuotas = $cuotas;
    }

    function setImpreso($impreso) {
        $this->impreso = $impreso;
    }

    function setImportepago($importepago) {
        $this->importepago = $importepago;
    }

    function setImportetotal($importetotal) {
        $this->importetotal = $importetotal;
    }

    function setIdcajeragenero($idcajeragenero) {
        $this->idcajeragenero = $idcajeragenero;
    }

    function setNromovasociado($nromovasociado) {
        $this->nromovasociado = $nromovasociado;
    }

    function setIdrecibos($idrecibos) {
        $this->idrecibos = $idrecibos;
    }

    function setFechaduplicado($fechaduplicado) {
        $this->fechaduplicado = $fechaduplicado;
    }

    function setIdcajeraduplica($idcajeraduplica) {
        $this->idcajeraduplica = $idcajeraduplica;
    }

    function setFechacancela($fechacancela) {
        $this->fechacancela = $fechacancela;
    }

    function setIdcajeracancela($idcajeracancela) {
        $this->idcajeracancela = $idcajeracancela;
    }

    function setFormapago($formapago) {
        $this->formapago = $formapago;
    }

    function setIdcarrera($idcarrera) {
        $this->idcarrera = $idcarrera;
    }

    function setIdcaja($idcaja) {
        $this->idcaja = $idcaja;
    }

    function setMontoefectivo($montoefectivo) {
        $this->montoefectivo = $montoefectivo;
    }

    function setIdcheq1($idcheq1) {
        $this->idcheq1 = $idcheq1;
    }

    function setIdcheq2($idcheq2) {
        $this->idcheq2 = $idcheq2;
    }

    function setTipotarj($tipotarj) {
        $this->tipotarj = $tipotarj;
    }

    function setCodigotarj($codigotarj) {
        $this->codigotarj = $codigotarj;
    }

    function setUltdigitos($ultdigitos) {
        $this->ultdigitos = $ultdigitos;
    }

    function setImportetarj($importetarj) {
        $this->importetarj = $importetarj;
    }

}

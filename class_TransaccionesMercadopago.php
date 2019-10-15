<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ("class_transacciones.php");

/**
 * Description of class_TransaccionesMercadopago
 * 
 * Carga datos de las transacciones realizadas con mercadopago en la tabla DATATRANSACCIONESMERCADOPAGO
 *
 * @property tabla donde trabaja DATATRANSACCIONESMERCADOPAGO 
 * Description:
 * ID
 * IDFORM
 * COLLECTOR_ID
 * DATECREATED
 * DATEAPPROVED
 * OPERATIONTYPE
 * PAYMENTMETHODID
 * ORDERID
 * ORDERTYPE
 * PAYERNAME
 * PAYEREMAIL
 * FEEMP
 * FEETYPE
 * TRANSACTIONAMOUNT
 * NET_RECEIVED_AMOUNT
 * CUOTAS_INSTALLMENT_AMOUNT
 * TOTAL_PAID_AMOUNT
 * 
 * 
 * @author lquiroga
 */
class TransaccionesMercadopago extends Transacciones {

    protected $db;
    protected $id;
    protected $idform;
    protected $collector_id;
    protected $datecreated;
    protected $dateapproved;
    protected $operationtype;
    protected $paymentmethodid;
    protected $orderid;
    protected $ordertype;
    protected $payername;
    protected $payeremail;
    protected $feemp;
    protected $feetype;
    protected $transactionamount;
    protected $net_received_amount;
    protected $cuotas_installment_amount;
    protected $total_paid_amount;

    public function __construct($db = null, $idform = null) {

        parent::__construct($db,$idform);

        if ($idform != null && trim($idform) != '') {

            $query = "SELECT * FROM TESORERIA.DATATRANSACCIONESMERCADOPAGO WHERE idform = :idform";

            $parametros = array();

            $parametros['idform'] = $idform;

            $result = $this->db->query($query, true, $parametros);

            $this->loadData($this->db->fetch_array($result));
        }
    }
    
    /**
    * Salva datos en la tabla transacciones
    * insertTransac
    *
    * tabla :
    * ID- IDFORM   COLLECTOR_ID- DATECREATED- DATEAPPROVED
    * OPERATIONTYPE    PAYMENTMETHODID    ORDERID    ORDERTYPE    PAYERNAME    PAYEREMAIL
    * FEEMP    FEETYPE    TRANSACTIONAMOUNT    NET_RECEIVED_AMOUNT    CUOTAS_INSTALLMENT_AMOUNT    TOTAL_PAID_AMOUNT
    *
    * @param array $datos
    * @return bool
    *
    */
    public function insertTransacMercado($datos){

            // $db = Conexion::openConnection();

            $datos['ID'] = 'tesoreria.TRANSACCIONESMERCADOPAGO_SEQ.nextval';

            $insercion = $this->db->realizarInsert ($datos, 'tesoreria.DATATRANSACCIONESMERCADOPAGO');

            return $insercion;
    }
    /**
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *        	return objet TRANSACCIONMERCADOPAGO
     */
    public function loadDataMercado($fila) {


        if (isset($fila['id'])) {
            $this->setId($fila['ID']);
        }

        if (isset($fila['collector_id'])) {
            $this->setCollector_id($fila['collector_id']);
        }

        if (isset($fila['datecreated'])) {
                    $this->setDatecreated($fila['datecreated']);
        }

        if (isset($fila['dateapproved'])) {
                    $this->setDateapproved($fila['dateapproved']);
        }

        if (isset($fila['operationtype'])) {
                    $this->setOperationtype($fila['operationtype']);
        }

        if (isset($fila['paymentmethodid'])) {
                    $this->setPaymentmethodid($fila['paymentmethodid']);
        }

        if (isset($fila['orderid'])) {
                    $this->setOrderid($fila['orderid']);
        }

        if (isset($fila['ordertype'])) {
                    $this->setOrdertype($fila['ordertype']);
        }

        if (isset($fila['payername'])) {
                    $this->setPayername($fila['payername']);
        }

        if (isset($fila['payeremail'])) {
                    $this->setPayeremail($fila['payeremail']);
        }

        if (isset($fila['feemp'])) {
                    $this->setFeemp($fila['feemp']);
        }

        if (isset($fila['feetype'])) {
                    $this->setFeetype($fila['feetype']);
        }

        if (isset($fila['transactionamount'])) {
                    $this->setTransactionamount($fila['transactionamount']);
        }

        if (isset($fila['net_received_amount'])) {
                    $this->setNet_received_amount($fila['net_received_amount']);
        }

        if (isset($fila['cuotas_installment_amount'])) {
                    $this->setCuotas_installment_amount($fila['cuotas_installment_amount']);
        }

        if (isset($fila['total_paid_amount'])) {
                    $this->setTotal_paid_amount($fila['total_paid_amount']);
        }
    }

    /*     * ******GETTERS*********** */

    function getId() {
        return $this->id;
    }

    function getIdform() {
        return $this->idform;
    }

    function getCollector_id() {
        return $this->collector_id;
    }

    function getDatecreated() {
        return $this->datecreated;
    }

    function getDateapproved() {
        return $this->dateapproved;
    }

    function getOperationtype() {
        return $this->operationtype;
    }

    function getPaymentmethodid() {
        return $this->paymentmethodid;
    }

    function getOrderid() {
        return $this->orderid;
    }

    function getOrdertype() {
        return $this->ordertype;
    }

    function getPayername() {
        return $this->payername;
    }

    function getPayeremail() {
        return $this->payeremail;
    }

    function getFeemp() {
        return $this->feemp;
    }

    function getFeetype() {
        return $this->feetype;
    }

    function getTransactionamount() {
        return $this->transactionamount;
    }

    function getNet_received_amount() {
        return $this->net_received_amount;
    }

    function getCuotas_installment_amount() {
        return $this->cuotas_installment_amount;
    }

    function getTotal_paid_amount() {
        return $this->total_paid_amount;
    }

    /*     * ******SETTERS*********** */

    function setId($id) {
        $this->id = $id;
    }

    function setIdform($idform) {
        $this->idform = $idform;
    }

    function setCollector_id($collector_id) {
        $this->collector_id = $collector_id;
    }

    function setDatecreated($datecreated) {
        $this->datecreated = $datecreated;
    }

    function setDateapproved($dateapproved) {
        $this->dateapproved = $dateapproved;
    }

    function setOperationtype($operationtype) {
        $this->operationtype = $operationtype;
    }

    function setPaymentmethodid($paymentmethodid) {
        $this->paymentmethodid = $paymentmethodid;
    }

    function setOrderid($orderid) {
        $this->orderid = $orderid;
    }

    function setOrdertype($ordertype) {
        $this->ordertype = $ordertype;
    }

    function setPayername($payername) {
        $this->payername = $payername;
    }

    function setPayeremail($payeremail) {
        $this->payeremail = $payeremail;
    }

    function setFeemp($feemp) {
        $this->feemp = $feemp;
    }

    function setFeetype($feetype) {
        $this->feetype = $feetype;
    }

    function setTransactionamount($transactionamount) {
        $this->transactionamount = $transactionamount;
    }

    function setNet_received_amount($net_received_amount) {
        $this->net_received_amount = $net_received_amount;
    }

    function setCuotas_installment_amount($cuotas_installment_amount) {
        $this->cuotas_installment_amount = $cuotas_installment_amount;
    }

    function setTotal_paid_amount($total_paid_amount) {
        $this->total_paid_amount = $total_paid_amount;
    }

}

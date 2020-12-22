<?php

/**
 * @author iberlot
 *
 */
class concepPersLiqui
{
	private $db;
	private $faesca = 0;
	private $mater = 0;
	private $cod_desc_cien = 0;
	private $desc_cien = 0;
	private $diavaca = 0;
	private $anticipo = 0.0;
	private $prestamo = 0.0;
	private $canasta = 0.0;
	private $esposa = false;
	private $cargas = 0;
	private $cuota_medica = 0.0;
	private $sepelio_con = 0.0;
	private $seguro_con = 0.0;
	private $liquida_con = false;
	private $otro_suel_jub = 0.0;
	private $ap_volun = 0.0;
	private $seg_retiro = 0.0;
	private $donaciones = 0.0;
	private $otrcarg = false;
	private $guar = false;
	private $asis_pun = false;
	private $seg_caja = false;
	private $seg_dinero = false;
	private $seg_per = false;
	private $tar_ries = false;
	private $tar_ries_prop = false;
	private $cobertura = 0.0;
	private $otro_suel_con = 0.0;

	/**
	 */
	public function __construct($db = null)
	{
		if (!isset ($db) or empty ($db) or $db == null)
		{
			if (!$this->db = Sitios::openConnection ())
			{
				global $db;

				if (isset ($db) and !empty ($db) and $db != null)
				{
					$this->db = $db;
				}
			}
		}
		else
		{
			$this->db = $db;
		}
	}

	public function recuperarValores(int $id_liquidacion, int $person)
	{
		$sql = "SELECT * FROM sueldos.concepto WHERE concepto.person = :person AND id_liquidacion = :id_liquidacion";

		$parametros = array ();
		$parametros[] = $person;
		$parametros[] = $id_liquidacion;

		$result = $this->db->query ($sql, true, $parametros);

		$concepto = $this->db->fetch_array ($result);

		$this->setFaesca ($concepto['FAESCA']);
		$this->setMater ($concepto['MATER']);
		$this->setCod_desc_cien ($concepto['COD_DESC_CIEN']);
		$this->setDesc_cien ($concepto['DESC_CIEN']);
		$this->setDiavaca ($concepto['DIAVACA']);
		$this->setAnticipo ($concepto['ANTICIPO']);
		$this->setPrestamo ($concepto['PRESTAMO']);
		$this->setCanasta ($concepto['CANASTA']);
		$this->setEsposa ($concepto['ESPOSA']);
		$this->setCargas ($concepto['CARGAS']);
		$this->setCuota_medica ($concepto['CUOTA_MEDICA']);
		$this->setSepelio_con ($concepto['SEPELIO_CON']);
		$this->setSeguro_con ($concepto['SEGURO_CON']);
		$this->setLiquida_con ($concepto['LIQUIDA_CON']);
		$this->setOtro_suel_jub ($concepto['OTRO_SUEL_JUB']);
		$this->setAp_volun ($concepto['AP_VOLUN']);
		$this->setSeg_retiro ($concepto['SEG_RETIRO']);
		$this->setDonaciones ($concepto['DONACIONES']);
		$this->setOtrcarg ($concepto['OTRCARG']);
		$this->setGuar ($concepto['GUAR']);
		$this->setAsis_pun ($concepto['ASIS_PUN']);
		$this->setSeg_caja ($concepto['SEG_CAJA']);
		$this->setSeg_dinero ($concepto['SEG_DINERO']);
		$this->setSeg_per ($concepto['SEG_PER']);
		$this->setTar_ries ($concepto['TAR_RIES']);
		$this->setTar_ries_prop ($concepto['TAR_RIES_PROP']);
		$this->setCobertura ($concepto['COBERTURA']);
		$this->setOtro_suel_con ($concepto['OTRO_SUEL_CON']);
	}

	/**
	 * Retorna el valor del atributo $faesca
	 *
	 * @return number $faesca el dato de la variable.
	 */
	public function getFaesca()
	{
		return $this->faesca;
	}

	/**
	 * Setter del parametro $faesca de la clase.
	 *
	 * @param number $faesca
	 *        	dato a cargar en la variable.
	 */
	public function setFaesca($faesca)
	{
		$this->faesca = $faesca;
	}

	/**
	 * Retorna el valor del atributo $mater
	 *
	 * @return number $mater el dato de la variable.
	 */
	public function getMater()
	{
		return $this->mater;
	}

	/**
	 * Setter del parametro $mater de la clase.
	 *
	 * @param number $mater
	 *        	dato a cargar en la variable.
	 */
	public function setMater($mater)
	{
		$this->mater = $mater;
	}

	/**
	 * Retorna el valor del atributo $cod_desc_cien
	 *
	 * @return number $cod_desc_cien el dato de la variable.
	 */
	public function getCod_desc_cien()
	{
		return $this->cod_desc_cien;
	}

	/**
	 * Setter del parametro $cod_desc_cien de la clase.
	 *
	 * @param number $cod_desc_cien
	 *        	dato a cargar en la variable.
	 */
	public function setCod_desc_cien($cod_desc_cien)
	{
		$this->cod_desc_cien = $cod_desc_cien;
	}

	/**
	 * Retorna el valor del atributo $desc_cien
	 *
	 * @return number $desc_cien el dato de la variable.
	 */
	public function getDesc_cien()
	{
		return $this->desc_cien;
	}

	/**
	 * Setter del parametro $desc_cien de la clase.
	 *
	 * @param number $desc_cien
	 *        	dato a cargar en la variable.
	 */
	public function setDesc_cien($desc_cien)
	{
		$this->desc_cien = $desc_cien;
	}

	/**
	 * Retorna el valor del atributo $diavaca
	 *
	 * @return number $diavaca el dato de la variable.
	 */
	public function getDiavaca()
	{
		return $this->diavaca;
	}

	/**
	 * Setter del parametro $diavaca de la clase.
	 *
	 * @param number $diavaca
	 *        	dato a cargar en la variable.
	 */
	public function setDiavaca($diavaca)
	{
		$this->diavaca = $diavaca;
	}

	/**
	 * Retorna el valor del atributo $anticipo
	 *
	 * @return number $anticipo el dato de la variable.
	 */
	public function getAnticipo()
	{
		return $this->anticipo;
	}

	/**
	 * Setter del parametro $anticipo de la clase.
	 *
	 * @param number $anticipo
	 *        	dato a cargar en la variable.
	 */
	public function setAnticipo($anticipo)
	{
		$this->anticipo = $anticipo;
	}

	/**
	 * Retorna el valor del atributo $prestamo
	 *
	 * @return number $prestamo el dato de la variable.
	 */
	public function getPrestamo()
	{
		return $this->prestamo;
	}

	/**
	 * Setter del parametro $prestamo de la clase.
	 *
	 * @param number $prestamo
	 *        	dato a cargar en la variable.
	 */
	public function setPrestamo($prestamo)
	{
		$this->prestamo = $prestamo;
	}

	/**
	 * Retorna el valor del atributo $canasta
	 *
	 * @return number $canasta el dato de la variable.
	 */
	public function getCanasta()
	{
		return $this->canasta;
	}

	/**
	 * Setter del parametro $canasta de la clase.
	 *
	 * @param number $canasta
	 *        	dato a cargar en la variable.
	 */
	public function setCanasta($canasta)
	{
		$this->canasta = $canasta;
	}

	/**
	 * Retorna el valor del atributo $esposa
	 *
	 * @return boolean $esposa el dato de la variable.
	 */
	public function getEsposa()
	{
		return $this->esposa;
	}

	/**
	 * Setter del parametro $esposa de la clase.
	 *
	 * @param boolean $esposa
	 *        	dato a cargar en la variable.
	 */
	public function setEsposa($esposa)
	{
		$this->esposa = $esposa;
	}

	/**
	 * Retorna el valor del atributo $cargas
	 *
	 * @return number $cargas el dato de la variable.
	 */
	public function getCargas()
	{
		return $this->cargas;
	}

	/**
	 * Setter del parametro $cargas de la clase.
	 *
	 * @param number $cargas
	 *        	dato a cargar en la variable.
	 */
	public function setCargas($cargas)
	{
		$this->cargas = $cargas;
	}

	/**
	 * Retorna el valor del atributo $cuota_medica
	 *
	 * @return number $cuota_medica el dato de la variable.
	 */
	public function getCuota_medica()
	{
		return $this->cuota_medica;
	}

	/**
	 * Setter del parametro $cuota_medica de la clase.
	 *
	 * @param number $cuota_medica
	 *        	dato a cargar en la variable.
	 */
	public function setCuota_medica($cuota_medica)
	{
		$this->cuota_medica = $cuota_medica;
	}

	/**
	 * Retorna el valor del atributo $sepelio_con
	 *
	 * @return number $sepelio_con el dato de la variable.
	 */
	public function getSepelio_con()
	{
		return $this->sepelio_con;
	}

	/**
	 * Setter del parametro $sepelio_con de la clase.
	 *
	 * @param number $sepelio_con
	 *        	dato a cargar en la variable.
	 */
	public function setSepelio_con($sepelio_con)
	{
		$this->sepelio_con = $sepelio_con;
	}

	/**
	 * Retorna el valor del atributo $seguro_con
	 *
	 * @return number $seguro_con el dato de la variable.
	 */
	public function getSeguro_con()
	{
		return $this->seguro_con;
	}

	/**
	 * Setter del parametro $seguro_con de la clase.
	 *
	 * @param number $seguro_con
	 *        	dato a cargar en la variable.
	 */
	public function setSeguro_con($seguro_con)
	{
		$this->seguro_con = $seguro_con;
	}

	/**
	 * Retorna el valor del atributo $liquida_con
	 *
	 * @return boolean $liquida_con el dato de la variable.
	 */
	public function getLiquida_con()
	{
		return $this->liquida_con;
	}

	/**
	 * Setter del parametro $liquida_con de la clase.
	 *
	 * @param boolean $liquida_con
	 *        	dato a cargar en la variable.
	 */
	public function setLiquida_con($liquida_con)
	{
		$this->liquida_con = $liquida_con;
	}

	/**
	 * Retorna el valor del atributo $otro_suel_jub
	 *
	 * @return number $otro_suel_jub el dato de la variable.
	 */
	public function getOtro_suel_jub()
	{
		return $this->otro_suel_jub;
	}

	/**
	 * Setter del parametro $otro_suel_jub de la clase.
	 *
	 * @param number $otro_suel_jub
	 *        	dato a cargar en la variable.
	 */
	public function setOtro_suel_jub($otro_suel_jub)
	{
		$this->otro_suel_jub = $otro_suel_jub;
	}

	/**
	 * Retorna el valor del atributo $ap_volun
	 *
	 * @return number $ap_volun el dato de la variable.
	 */
	public function getAp_volun()
	{
		return $this->ap_volun;
	}

	/**
	 * Setter del parametro $ap_volun de la clase.
	 *
	 * @param number $ap_volun
	 *        	dato a cargar en la variable.
	 */
	public function setAp_volun($ap_volun)
	{
		$this->ap_volun = $ap_volun;
	}

	/**
	 * Retorna el valor del atributo $seg_retiro
	 *
	 * @return number $seg_retiro el dato de la variable.
	 */
	public function getSeg_retiro()
	{
		return $this->seg_retiro;
	}

	/**
	 * Setter del parametro $seg_retiro de la clase.
	 *
	 * @param number $seg_retiro
	 *        	dato a cargar en la variable.
	 */
	public function setSeg_retiro($seg_retiro)
	{
		$this->seg_retiro = $seg_retiro;
	}

	/**
	 * Retorna el valor del atributo $donaciones
	 *
	 * @return number $donaciones el dato de la variable.
	 */
	public function getDonaciones()
	{
		return $this->donaciones;
	}

	/**
	 * Setter del parametro $donaciones de la clase.
	 *
	 * @param number $donaciones
	 *        	dato a cargar en la variable.
	 */
	public function setDonaciones($donaciones)
	{
		$this->donaciones = $donaciones;
	}

	/**
	 * Retorna el valor del atributo $otrcarg
	 *
	 * @return boolean $otrcarg el dato de la variable.
	 */
	public function getOtrcarg()
	{
		return $this->otrcarg;
	}

	/**
	 * Setter del parametro $otrcarg de la clase.
	 *
	 * @param boolean $otrcarg
	 *        	dato a cargar en la variable.
	 */
	public function setOtrcarg($otrcarg)
	{
		$this->otrcarg = $otrcarg;
	}

	/**
	 * Retorna el valor del atributo $guar
	 *
	 * @return boolean $guar el dato de la variable.
	 */
	public function getGuar()
	{
		return $this->guar;
	}

	/**
	 * Setter del parametro $guar de la clase.
	 *
	 * @param boolean $guar
	 *        	dato a cargar en la variable.
	 */
	public function setGuar($guar)
	{
		$this->guar = $guar;
	}

	/**
	 * Retorna el valor del atributo $asis_pun
	 *
	 * @return boolean $asis_pun el dato de la variable.
	 */
	public function getAsis_pun()
	{
		return $this->asis_pun;
	}

	/**
	 * Setter del parametro $asis_pun de la clase.
	 *
	 * @param boolean $asis_pun
	 *        	dato a cargar en la variable.
	 */
	public function setAsis_pun($asis_pun)
	{
		$this->asis_pun = $asis_pun;
	}

	/**
	 * Retorna el valor del atributo $seg_caja
	 *
	 * @return boolean $seg_caja el dato de la variable.
	 */
	public function getSeg_caja()
	{
		return $this->seg_caja;
	}

	/**
	 * Setter del parametro $seg_caja de la clase.
	 *
	 * @param boolean $seg_caja
	 *        	dato a cargar en la variable.
	 */
	public function setSeg_caja($seg_caja)
	{
		$this->seg_caja = $seg_caja;
	}

	/**
	 * Retorna el valor del atributo $seg_dinero
	 *
	 * @return boolean $seg_dinero el dato de la variable.
	 */
	public function getSeg_dinero()
	{
		return $this->seg_dinero;
	}

	/**
	 * Setter del parametro $seg_dinero de la clase.
	 *
	 * @param boolean $seg_dinero
	 *        	dato a cargar en la variable.
	 */
	public function setSeg_dinero($seg_dinero)
	{
		$this->seg_dinero = $seg_dinero;
	}

	/**
	 * Retorna el valor del atributo $seg_per
	 *
	 * @return boolean $seg_per el dato de la variable.
	 */
	public function getSeg_per()
	{
		return $this->seg_per;
	}

	/**
	 * Setter del parametro $seg_per de la clase.
	 *
	 * @param boolean $seg_per
	 *        	dato a cargar en la variable.
	 */
	public function setSeg_per($seg_per)
	{
		$this->seg_per = $seg_per;
	}

	/**
	 * Retorna el valor del atributo $tar_ries
	 *
	 * @return boolean $tar_ries el dato de la variable.
	 */
	public function getTar_ries()
	{
		return $this->tar_ries;
	}

	/**
	 * Setter del parametro $tar_ries de la clase.
	 *
	 * @param boolean $tar_ries
	 *        	dato a cargar en la variable.
	 */
	public function setTar_ries($tar_ries)
	{
		$this->tar_ries = $tar_ries;
	}

	/**
	 * Retorna el valor del atributo $tar_ries_prop
	 *
	 * @return boolean $tar_ries_prop el dato de la variable.
	 */
	public function getTar_ries_prop()
	{
		return $this->tar_ries_prop;
	}

	/**
	 * Setter del parametro $tar_ries_prop de la clase.
	 *
	 * @param boolean $tar_ries_prop
	 *        	dato a cargar en la variable.
	 */
	public function setTar_ries_prop($tar_ries_prop)
	{
		$this->tar_ries_prop = $tar_ries_prop;
	}

	/**
	 * Retorna el valor del atributo $cobertura
	 *
	 * @return number $cobertura el dato de la variable.
	 */
	public function getCobertura()
	{
		return $this->cobertura;
	}

	/**
	 * Setter del parametro $cobertura de la clase.
	 *
	 * @param number $cobertura
	 *        	dato a cargar en la variable.
	 */
	public function setCobertura($cobertura)
	{
		$this->cobertura = $cobertura;
	}

	/**
	 * Retorna el valor del atributo $otro_suel_con
	 *
	 * @return number $otro_suel_con el dato de la variable.
	 */
	public function getOtro_suel_con()
	{
		return $this->otro_suel_con;
	}

	/**
	 * Setter del parametro $otro_suel_con de la clase.
	 *
	 * @param number $otro_suel_con
	 *        	dato a cargar en la variable.
	 */
	public function setOtro_suel_con($otro_suel_con)
	{
		$this->otro_suel_con = $otro_suel_con;
	}
}


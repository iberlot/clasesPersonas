<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * Obtiene datos de carreras.Clase inicial ,completarla
 * con mas metodos y propuiedades
 *
 *
 * @author lquiroga
 */
class Carreras
{
	protected $code;
	protected $descrip;
	protected $sdesc;
	protected $cartype;
	protected $active;
	protected $facu;
	protected $acode;
	protected $db;

	/**
	 * Constructor de la clase
	 *
	 * @param class_db $db
	 * @param int $code
	 */
	public function __construct($db, $code = null)
	{
		$this->db = $db;

		if ($code != null && trim ($code) != '')
		{

			$query = 'SELECT * FROM studentc.CAREER WHERE CODE = :code';

			$parametros = array (
					$code
			);

			$result = $this->db->query ($query, true, $parametros);

			$this->loadData ($this->db->fetch_array ($result));
		}
	}

	/**
	 * getMateriasPorPlan
	 *
	 * @param int $career
	 *        	carrera que queremos las materias
	 * @param int $plan
	 *        	plan perteneciente a esa carrera
	 * @param int $notsubject
	 *        	podemos excluir materias de la lista
	 *
	 * @example :
	 *          $aprobadasporalumno = [8895,8896,9987,4484];
	 *          getMateriasPorPlan(301 ,16 , $aprobadasporalumno)
	 *
	 * @return array --> nos devuelve array de materias que no haya aprobado el alumno
	 *
	 * @return array
	 */
	public function getMateriasPorPlan($career, $plan, $notsubject = null)
	{
		$salida = '';

		$query = 'select CAREER ,PLAN,SUBJECT,SDESC ,YR ,ANNUAL ,MODULES from studentc.subxplan ' . 'where CAREER  = :career and plan = :plan';

		$parametros = array (
				$career,
				$plan
		);

		$result = $this->db->query ($query, true, $parametros);

		while ($fila = $this->db->fetch_array ($result))
		{

			// Si es una materia anual
			if ($fila['ANNUAL'] == 1)
			{

				if ($fila['CAREER'] == 401)
				{

					$fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 8;
				}
				else
				{

					$fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 9;
				}
			}
			else
			{

				if ($fila['CAREER'] == 401)
				{

					$fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 4;
				}
				else
				{

					$fila['CARGA_HORARIA'] = $fila['MODULES'] * 4 * 4.5;
				}
			}

			if ($notsubject != null)
			{

				if (in_array ($fila['SUBJECT'], $notsubject))
				{

					$salida[] = $fila;
				}
			}
			else
			{

				$salida[] = $fila;
			}
		}

		return $salida;
	}

	/**
	 * Carga datos traidos de db en objeto
	 */
	protected function loadData($fila)
	{
		$this->set_code ($fila['CODE']);
		$this->set_descrip ($fila['DESCRIP']);
		$this->set_sdesc ($fila['SDESC']);
		$this->set_cartype ($fila['CARTYPE']);
		$this->set_active ($fila['ACTIVE']);
		$this->set_facu ($fila['FACU']);
		$this->set_acode ($fila['ACODE']);
	}

	/**
	 * ***SETTERS****
	 */
	function get_code()
	{
		return $this->code;
	}

	function get_descrip()
	{
		return $this->descrip;
	}

	function get_sdesc()
	{
		return $this->sdesc;
	}

	function get_cartype()
	{
		return $this->cartype;
	}

	function get_active()
	{
		return $this->active;
	}

	function get_facu()
	{
		return $this->facu;
	}

	function get_acode()
	{
		return $this->acode;
	}

	/**
	 * ***GETTERS****
	 */
	function set_code($code)
	{
		$this->code = $code;
	}

	function set_descrip($descrip)
	{
		$this->descrip = $descrip;
	}

	function set_sdesc($sdesc)
	{
		$this->sdesc = $sdesc;
	}

	function set_cartype($cartype)
	{
		$this->cartype = $cartype;
	}

	function set_active($active)
	{
		$this->active = $active;
	}

	function set_facu($facu)
	{
		$this->facu = $facu;
	}

	function set_acode($acode)
	{
		$this->acode = $acode;
	}
}

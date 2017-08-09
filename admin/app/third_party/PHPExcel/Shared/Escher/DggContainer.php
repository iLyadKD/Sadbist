<?php

class PHPExcel_Shared_Escher_DggContainer
{
	/**
	 * Maximum shape index of all shapes in all drawings increased by one
	 *
	 * @var int
	 */
	private $_spIdMax;

	/**
	 * Total number of drawings saved
	 *
	 * @var int
	 */
	private $_cDgSaved;

	/**
	 * Total number of shapes saved (including group shapes)
	 *
	 * @var int
	 */
	private $_cSpSaved;

	/**
	 * BLIP Store Container
	 *
	 * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer
	 */
	private $_bstoreContainer;

	/**
	 * Array of options for the drawing group
	 *
	 * @var array
	 */
	private $_OPT = array();

	/**
	 * Array of identifier clusters containg information about the maximum shape identifiers
	 *
	 * @var array
	 */
	private $_IDCLs = array();

	/**
	 * Get maximum shape index of all shapes in all drawings (plus one)
	 *
	 * @return int
	 */
	public function getSpIdMax()
	{
		return $this->_spIdMax;
	}

	/**
	 * Set maximum shape index of all shapes in all drawings (plus one)
	 *
	 * @param int
	 */
	public function setSpIdMax($value)
	{
		$this->_spIdMax = $value;
	}

	/**
	 * Get total number of drawings saved
	 *
	 * @return int
	 */
	public function getCDgSaved()
	{
		return $this->_cDgSaved;
	}

	/**
	 * Set total number of drawings saved
	 *
	 * @param int
	 */
	public function setCDgSaved($value)
	{
		$this->_cDgSaved = $value;
	}

	/**
	 * Get total number of shapes saved (including group shapes)
	 *
	 * @return int
	 */
	public function getCSpSaved()
	{
		return $this->_cSpSaved;
	}

	/**
	 * Set total number of shapes saved (including group shapes)
	 *
	 * @param int
	 */
	public function setCSpSaved($value)
	{
		$this->_cSpSaved = $value;
	}

	/**
	 * Get BLIP Store Container
	 *
	 * @return PHPExcel_Shared_Escher_DggContainer_BstoreContainer
	 */
	public function getBstoreContainer()
	{
		return $this->_bstoreContainer;
	}

	/**
	 * Set BLIP Store Container
	 *
	 * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer $bstoreContainer
	 */
	public function setBstoreContainer($bstoreContainer)
	{
		$this->_bstoreContainer = $bstoreContainer;
	}

	/**
	 * Set an option for the drawing group
	 *
	 * @param int $property The number specifies the option
	 * @param mixed $value
	 */
	public function setOPT($property, $value)
	{
		$this->_OPT[$property] = $value;
	}

	/**
	 * Get an option for the drawing group
	 *
	 * @param int $property The number specifies the option
	 * @return mixed
	 */
	public function getOPT($property)
	{
		if (isset($this->_OPT[$property])) {
			return $this->_OPT[$property];
		}
		return null;
	}

	/**
	 * Get identifier clusters
	 *
	 * @return array
	 */
	public function getIDCLs()
	{
		return $this->_IDCLs;
	}

	/**
	 * Set identifier clusters. array(<drawingId> => <max shape id>, ...)
	 *
	 * @param array $pValue
	 */
	public function setIDCLs($pValue)
	{
		$this->_IDCLs = $pValue;
	}
}

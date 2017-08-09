<?php

class PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip
{
	/**
	 * The parent BSE
	 *
	 * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE
	 */
	private $_parent;

	/**
	 * Raw image data
	 *
	 * @var string
	 */
	private $_data;

	/**
	 * Get the raw image data
	 *
	 * @return string
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * Set the raw image data
	 *
	 * @param string
	 */
	public function setData($data)
	{
		$this->_data = $data;
	}

	/**
	 * Set parent BSE
	 *
	 * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE $parent
	 */
	public function setParent($parent)
	{
		$this->_parent = $parent;
	}

	/**
	 * Get parent BSE
	 *
	 * @return PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE $parent
	 */
	public function getParent()
	{
		return $this->_parent;
	}

}

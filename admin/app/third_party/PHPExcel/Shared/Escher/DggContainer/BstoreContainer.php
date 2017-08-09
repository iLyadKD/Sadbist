<?php

class PHPExcel_Shared_Escher_DggContainer_BstoreContainer
{
	/**
	 * BLIP Store Entries. Each of them holds one BLIP (Big Large Image or Picture)
	 *
	 * @var array
	 */
	private $_BSECollection = array();

	/**
	 * Add a BLIP Store Entry
	 *
	 * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE $BSE
	 */
	public function addBSE($BSE)
	{
		$this->_BSECollection[] = $BSE;
		$BSE->setParent($this);
	}

	/**
	 * Get the collection of BLIP Store Entries
	 *
	 * @return PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE[]
	 */
	public function getBSECollection()
	{
		return $this->_BSECollection;
	}

}

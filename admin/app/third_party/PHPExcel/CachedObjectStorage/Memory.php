<?php
class PHPExcel_CachedObjectStorage_Memory extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache {

    /**
     * Dummy method callable from CacheBase, but unused by Memory cache
     *
	 * @return	void
     */
	protected function _storeData() {
	}	//	function _storeData()

    /**
     * Add or Update a cell in cache identified by coordinate address
     *
     * @param	string			$pCoord		Coordinate address of the cell to update
     * @param	PHPExcel_Cell	$cell		Cell to update
	 * @return	PHPExcel_Cell
     * @throws	PHPExcel_Exception
     */
	public function addCacheData($pCoord, PHPExcel_Cell $cell) {
		$this->_cellCache[$pCoord] = $cell;

		//	Set current entry to the new/updated entry
		$this->_currentObjectID = $pCoord;

		return $cell;
	}	//	function addCacheData()


    /**
     * Get cell at a specific coordinate
     *
     * @param 	string 			$pCoord		Coordinate of the cell
     * @throws 	PHPExcel_Exception
     * @return 	PHPExcel_Cell 	Cell that was found, or null if not found
     */
	public function getCacheData($pCoord) {
		//	Check if the entry that has been requested actually exists
		if (!isset($this->_cellCache[$pCoord])) {
			$this->_currentObjectID = NULL;
			//	Return null if requested entry doesn't exist in cache
			return null;
		}

		//	Set current entry to the requested entry
		$this->_currentObjectID = $pCoord;

		//	Return requested entry
		return $this->_cellCache[$pCoord];
	}	//	function getCacheData()


	/**
	 * Clone the cell collection
	 *
	 * @param	PHPExcel_Worksheet	$parent		The new worksheet
	 * @return	void
	 */
	public function copyCellCollection(PHPExcel_Worksheet $parent) {
		parent::copyCellCollection($parent);

		$newCollection = array();
		foreach($this->_cellCache as $k => &$cell) {
			$newCollection[$k] = clone $cell;
			$newCollection[$k]->attach($parent);
		}

		$this->_cellCache = $newCollection;
	}


	/**
	 * Clear the cell collection and disconnect from our parent
	 *
	 * @return	void
	 */
	public function unsetWorksheetCells() {
		//	Because cells are all stored as intact objects in memory, we need to detach each one from the parent
		foreach($this->_cellCache as $k => &$cell) {
			$cell->detach();
			$this->_cellCache[$k] = null;
		}
		unset($cell);

		$this->_cellCache = array();

		//	detach ourself from the worksheet, so that it can then delete this object successfully
		$this->_parent = null;
	}	//	function unsetWorksheetCells()

}

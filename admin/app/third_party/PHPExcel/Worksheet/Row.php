<?php

class PHPExcel_Worksheet_Row
{
	/**
	 * PHPExcel_Worksheet
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_parent;

	/**
	 * Row index
	 *
	 * @var int
	 */
	private $_rowIndex = 0;

	/**
	 * Create a new row
	 *
	 * @param PHPExcel_Worksheet 		$parent
	 * @param int						$rowIndex
	 */
	public function __construct(PHPExcel_Worksheet $parent = null, $rowIndex = 1) {
		// Set parent and row index
		$this->_parent 		= $parent;
		$this->_rowIndex 	= $rowIndex;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		unset($this->_parent);
	}

	/**
	 * Get row index
	 *
	 * @return int
	 */
	public function getRowIndex() {
		return $this->_rowIndex;
	}

	/**
	 * Get cell iterator
	 *
	 * @return PHPExcel_Worksheet_CellIterator
	 */
	public function getCellIterator() {
		return new PHPExcel_Worksheet_CellIterator($this->_parent, $this->_rowIndex);
	}
}

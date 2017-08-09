<?php

class PHPExcel_Worksheet_RowIterator implements Iterator
{
	/**
	 * PHPExcel_Worksheet to iterate
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_subject;

	/**
	 * Current iterator position
	 *
	 * @var int
	 */
	private $_position = 1;

	/**
	 * Start position
	 *
	 * @var int
	 */
	private $_startRow = 1;


	/**
	 * Create a new row iterator
	 *
	 * @param	PHPExcel_Worksheet	$subject	The worksheet to iterate over
	 * @param	integer				$startRow	The row number at which to start iterating
	 */
	public function __construct(PHPExcel_Worksheet $subject = null, $startRow = 1) {
		// Set subject
		$this->_subject = $subject;
		$this->resetStart($startRow);
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		unset($this->_subject);
	}

	/**
	 * (Re)Set the start row and the current row pointer
	 *
	 * @param integer	$startRow	The row number at which to start iterating
	 */
	public function resetStart($startRow = 1) {
		$this->_startRow = $startRow;
		$this->seek($startRow);
	}

	/**
	 * Set the row pointer to the selected row
	 *
	 * @param integer	$row	The row number to set the current pointer at
	 */
	public function seek($row = 1) {
		$this->_position = $row;
	}

	/**
	 * Rewind the iterator to the starting row
	 */
	public function rewind() {
		$this->_position = $this->_startRow;
	}

	/**
	 * Return the current row in this worksheet
	 *
	 * @return PHPExcel_Worksheet_Row
	 */
	public function current() {
		return new PHPExcel_Worksheet_Row($this->_subject, $this->_position);
	}

	/**
	 * Return the current iterator key
	 *
	 * @return int
	 */
	public function key() {
		return $this->_position;
	}

	/**
	 * Set the iterator to its next value
	 */
	public function next() {
		++$this->_position;
	}

	/**
	 * Set the iterator to its previous value
	 */
	public function prev() {
		if ($this->_position > 1)
			--$this->_position;
	}

	/**
	 * Indicate if more rows exist in the worksheet
	 *
	 * @return boolean
	 */
	public function valid() {
		return $this->_position <= $this->_subject->getHighestRow();
	}
}

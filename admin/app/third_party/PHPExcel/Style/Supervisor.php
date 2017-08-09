<?php

abstract class PHPExcel_Style_Supervisor
{
	/**
	 * Supervisor?
	 *
	 * @var boolean
	 */
	protected $_isSupervisor;

	/**
	 * Parent. Only used for supervisor
	 *
	 * @var PHPExcel_Style
	 */
	protected $_parent;

	/**
	 * Create a new PHPExcel_Style_Alignment
	 *
	 * @param	boolean	$isSupervisor	Flag indicating if this is a supervisor or not
	 *									Leave this value at default unless you understand exactly what
	 *										its ramifications are
	 */
	public function __construct($isSupervisor = FALSE)
	{
		// Supervisor?
		$this->_isSupervisor = $isSupervisor;
	}

	/**
	 * Bind parent. Only used for supervisor
	 *
	 * @param PHPExcel $parent
	 * @return PHPExcel_Style_Supervisor
	 */
	public function bindParent($parent, $parentPropertyName=NULL)
	{
		$this->_parent = $parent;
		return $this;
	}

	/**
	 * Is this a supervisor or a cell style component?
	 *
	 * @return boolean
	 */
	public function getIsSupervisor()
	{
		return $this->_isSupervisor;
	}

	/**
	 * Get the currently active sheet. Only used for supervisor
	 *
	 * @return PHPExcel_Worksheet
	 */
	public function getActiveSheet()
	{
		return $this->_parent->getActiveSheet();
	}

	/**
	 * Get the currently active cell coordinate in currently active sheet.
	 * Only used for supervisor
	 *
	 * @return string E.g. 'A1'
	 */
	public function getSelectedCells()
	{
		return $this->getActiveSheet()->getSelectedCells();
	}

	/**
	 * Get the currently active cell coordinate in currently active sheet.
	 * Only used for supervisor
	 *
	 * @return string E.g. 'A1'
	 */
	public function getActiveCell()
	{
		return $this->getActiveSheet()->getActiveCell();
	}

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if ((is_object($value)) && ($key != '_parent')) {
				$this->$key = clone $value;
			} else {
				$this->$key = $value;
			}
		}
	}
}

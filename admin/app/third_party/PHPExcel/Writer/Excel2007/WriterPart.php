<?php

abstract class PHPExcel_Writer_Excel2007_WriterPart
{
	/**
	 * Parent IWriter object
	 *
	 * @var PHPExcel_Writer_IWriter
	 */
	private $_parentWriter;

	/**
	 * Set parent IWriter object
	 *
	 * @param PHPExcel_Writer_IWriter	$pWriter
	 * @throws PHPExcel_Writer_Exception
	 */
	public function setParentWriter(PHPExcel_Writer_IWriter $pWriter = null) {
		$this->_parentWriter = $pWriter;
	}

	/**
	 * Get parent IWriter object
	 *
	 * @return PHPExcel_Writer_IWriter
	 * @throws PHPExcel_Writer_Exception
	 */
	public function getParentWriter() {
		if (!is_null($this->_parentWriter)) {
			return $this->_parentWriter;
		} else {
			throw new PHPExcel_Writer_Exception("No parent PHPExcel_Writer_IWriter assigned.");
		}
	}

	/**
	 * Set parent IWriter object
	 *
	 * @param PHPExcel_Writer_IWriter	$pWriter
	 * @throws PHPExcel_Writer_Exception
	 */
	public function __construct(PHPExcel_Writer_IWriter $pWriter = null) {
		if (!is_null($pWriter)) {
			$this->_parentWriter = $pWriter;
		}
	}

}

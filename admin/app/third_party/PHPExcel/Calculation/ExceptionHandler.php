<?php
class PHPExcel_Calculation_ExceptionHandler {
	/**
	 * Register errorhandler
	 */
	public function __construct() {
		set_error_handler(array('PHPExcel_Calculation_Exception', 'errorHandlerCallback'), E_ALL);
	}

	/**
	 * Unregister errorhandler
	 */
	public function __destruct() {
		restore_error_handler();
	}
}

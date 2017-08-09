<?php
class PHPExcel_Calculation_Token_Stack {

	/**
	 *  The parser stack for formulae
	 *
	 *  @var mixed[]
	 */
	private $_stack = array();

	/**
	 *  Count of entries in the parser stack
	 *
	 *  @var integer
	 */
	private $_count = 0;


	/**
	 * Return the number of entries on the stack
	 *
	 * @return  integer
	 */
	public function count() {
		return $this->_count;
	}	//	function count()

	/**
	 * Push a new entry onto the stack
	 *
	 * @param  mixed  $type
	 * @param  mixed  $value
	 * @param  mixed  $reference
	 */
	public function push($type, $value, $reference = NULL) {
		$this->_stack[$this->_count++] = array('type'		=> $type,
											   'value'		=> $value,
											   'reference'	=> $reference
											  );
		if ($type == 'Function') {
			$localeFunction = PHPExcel_Calculation::_localeFunc($value);
			if ($localeFunction != $value) {
				$this->_stack[($this->_count - 1)]['localeValue'] = $localeFunction;
			}
		}
	}	//	function push()

	/**
	 * Pop the last entry from the stack
	 *
	 * @return  mixed
	 */
	public function pop() {
		if ($this->_count > 0) {
			return $this->_stack[--$this->_count];
		}
		return NULL;
	}	//	function pop()

	/**
	 * Return an entry from the stack without removing it
	 *
	 * @param   integer  $n  number indicating how far back in the stack we want to look
	 * @return  mixed
	 */
	public function last($n = 1) {
		if ($this->_count - $n < 0) {
			return NULL;
		}
		return $this->_stack[$this->_count - $n];
	}	//	function last()

	/**
	 * Clear the stack
	 */
	function clear() {
		$this->_stack = array();
		$this->_count = 0;
	}

}	//	class PHPExcel_Calculation_Token_Stack

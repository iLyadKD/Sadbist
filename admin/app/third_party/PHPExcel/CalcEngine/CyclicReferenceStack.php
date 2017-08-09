<?php
class PHPExcel_CalcEngine_CyclicReferenceStack {

	/**
	 *  The call stack for calculated cells
	 *
	 *  @var mixed[]
	 */
	private $_stack = array();


	/**
	 * Return the number of entries on the stack
	 *
	 * @return  integer
	 */
	public function count() {
		return count($this->_stack);
	}

	/**
	 * Push a new entry onto the stack
	 *
	 * @param  mixed  $value
	 */
	public function push($value) {
		$this->_stack[] = $value;
	}	//	function push()

	/**
	 * Pop the last entry from the stack
	 *
	 * @return  mixed
	 */
	public function pop() {
		return array_pop($this->_stack);
	}	//	function pop()

	/**
	 * Test to see if a specified entry exists on the stack
	 *
	 * @param  mixed  $value  The value to test
	 */
	public function onStack($value) {
		return in_array($value, $this->_stack);
	}

	/**
	 * Clear the stack
	 */
	public function clear() {
		$this->_stack = array();
	}	//	function push()

	/**
	 * Return an array of all entries on the stack
	 *
	 * @return  mixed[]
	 */
	public function showStack() {
		return $this->_stack;
	}

}	//	class PHPExcel_CalcEngine_CyclicReferenceStack

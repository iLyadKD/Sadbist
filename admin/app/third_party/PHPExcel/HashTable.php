<?php

class PHPExcel_HashTable
{
	/**
	 * HashTable elements
	 *
	 * @var array
	 */
	public $_items = array();

	/**
	 * HashTable key map
	 *
	 * @var array
	 */
	public $_keyMap = array();

	/**
	 * Create a new PHPExcel_HashTable
	 *
	 * @param	PHPExcel_IComparable[] $pSource	Optional source array to create HashTable from
	 * @throws	PHPExcel_Exception
	 */
	public function __construct($pSource = null)
	{
		if ($pSource !== NULL) {
			// Create HashTable
			$this->addFromSource($pSource);
		}
	}

	/**
	 * Add HashTable items from source
	 *
	 * @param	PHPExcel_IComparable[] $pSource	Source array to create HashTable from
	 * @throws	PHPExcel_Exception
	 */
	public function addFromSource($pSource = null) {
		// Check if an array was passed
		if ($pSource == null) {
			return;
		} else if (!is_array($pSource)) {
			throw new PHPExcel_Exception('Invalid array parameter passed.');
		}

		foreach ($pSource as $item) {
			$this->add($item);
		}
	}

	/**
	 * Add HashTable item
	 *
	 * @param	PHPExcel_IComparable $pSource	Item to add
	 * @throws	PHPExcel_Exception
	 */
	public function add(PHPExcel_IComparable $pSource = null) {
		$hash = $pSource->getHashCode();
		if (!isset($this->_items[$hash])) {
			$this->_items[$hash] = $pSource;
			$this->_keyMap[count($this->_items) - 1] = $hash;
		}
	}

	/**
	 * Remove HashTable item
	 *
	 * @param	PHPExcel_IComparable $pSource	Item to remove
	 * @throws	PHPExcel_Exception
	 */
	public function remove(PHPExcel_IComparable $pSource = null) {
		$hash = $pSource->getHashCode();
		if (isset($this->_items[$hash])) {
			unset($this->_items[$hash]);

			$deleteKey = -1;
			foreach ($this->_keyMap as $key => $value) {
				if ($deleteKey >= 0) {
					$this->_keyMap[$key - 1] = $value;
				}

				if ($value == $hash) {
					$deleteKey = $key;
				}
			}
			unset($this->_keyMap[count($this->_keyMap) - 1]);
		}
	}

	/**
	 * Clear HashTable
	 *
	 */
	public function clear() {
		$this->_items = array();
		$this->_keyMap = array();
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count() {
		return count($this->_items);
	}

	/**
	 * Get index for hash code
	 *
	 * @param	string	$pHashCode
	 * @return	int	Index
	 */
	public function getIndexForHashCode($pHashCode = '') {
		return array_search($pHashCode, $this->_keyMap);
	}

	/**
	 * Get by index
	 *
	 * @param	int	$pIndex
	 * @return	PHPExcel_IComparable
	 *
	 */
	public function getByIndex($pIndex = 0) {
		if (isset($this->_keyMap[$pIndex])) {
			return $this->getByHashCode( $this->_keyMap[$pIndex] );
		}

		return null;
	}

	/**
	 * Get by hashcode
	 *
	 * @param	string	$pHashCode
	 * @return	PHPExcel_IComparable
	 *
	 */
	public function getByHashCode($pHashCode = '') {
		if (isset($this->_items[$pHashCode])) {
			return $this->_items[$pHashCode];
		}

		return null;
	}

	/**
	 * HashTable to array
	 *
	 * @return PHPExcel_IComparable[]
	 */
	public function toArray() {
		return $this->_items;
	}

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
		}
	}
}

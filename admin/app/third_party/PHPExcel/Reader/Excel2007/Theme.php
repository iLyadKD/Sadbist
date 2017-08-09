<?php

class PHPExcel_Reader_Excel2007_Theme
{
	/**
	 * Theme Name
	 *
	 * @var string
	 */
	private $_themeName;

	/**
	 * Colour Scheme Name
	 *
	 * @var string
	 */
	private $_colourSchemeName;

	/**
	 * Colour Map indexed by position
	 *
	 * @var array of string
	 */
	private $_colourMapValues;


	/**
	 * Colour Map
	 *
	 * @var array of string
	 */
	private $_colourMap;


    /**
     * Create a new PHPExcel_Theme
	 *
     */
    public function __construct($themeName,$colourSchemeName,$colourMap)
    {
		// Initialise values
    	$this->_themeName			= $themeName;
		$this->_colourSchemeName	= $colourSchemeName;
		$this->_colourMap			= $colourMap;
    }

	/**
	 * Get Theme Name
	 *
	 * @return string
	 */
	public function getThemeName()
	{
		return $this->_themeName;
	}

    /**
     * Get colour Scheme Name
     *
     * @return string
     */
    public function getColourSchemeName() {
		return $this->_colourSchemeName;
    }

    /**
     * Get colour Map Value by Position
     *
     * @return string
     */
    public function getColourByIndex($index=0) {
    	if (isset($this->_colourMap[$index])) {
			return $this->_colourMap[$index];
		}
		return null;
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

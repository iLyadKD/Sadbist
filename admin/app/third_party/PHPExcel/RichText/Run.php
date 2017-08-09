<?php

class PHPExcel_RichText_Run extends PHPExcel_RichText_TextElement implements PHPExcel_RichText_ITextElement
{
	/**
	 * Font
	 *
	 * @var PHPExcel_Style_Font
	 */
	private $_font;

    /**
     * Create a new PHPExcel_RichText_Run instance
     *
     * @param 	string		$pText		Text
     */
    public function __construct($pText = '')
    {
    	// Initialise variables
    	$this->setText($pText);
    	$this->_font = new PHPExcel_Style_Font();
    }

	/**
	 * Get font
	 *
	 * @return PHPExcel_Style_Font
	 */
	public function getFont() {
		return $this->_font;
	}

	/**
	 * Set font
	 *
	 * @param	PHPExcel_Style_Font		$pFont		Font
	 * @throws 	PHPExcel_Exception
	 * @return PHPExcel_RichText_ITextElement
	 */
	public function setFont(PHPExcel_Style_Font $pFont = null) {
		$this->_font = $pFont;
		return $this;
	}

	/**
	 * Get hash code
	 *
	 * @return string	Hash code
	 */
	public function getHashCode() {
    	return md5(
    		  $this->getText()
    		. $this->_font->getHashCode()
    		. __CLASS__
    	);
    }

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			} else {
				$this->$key = $value;
			}
		}
	}
}

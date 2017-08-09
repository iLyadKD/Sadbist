<?php
class PHPExcel_Worksheet_HeaderFooter
{
	/* Header/footer image location */
	const IMAGE_HEADER_LEFT							= 'LH';
	const IMAGE_HEADER_CENTER						= 'CH';
	const IMAGE_HEADER_RIGHT						= 'RH';
	const IMAGE_FOOTER_LEFT							= 'LF';
	const IMAGE_FOOTER_CENTER						= 'CF';
	const IMAGE_FOOTER_RIGHT						= 'RF';

	/**
	 * OddHeader
	 *
	 * @var string
	 */
	private $_oddHeader			= '';

	/**
	 * OddFooter
	 *
	 * @var string
	 */
	private $_oddFooter			= '';

	/**
	 * EvenHeader
	 *
	 * @var string
	 */
	private $_evenHeader		= '';

	/**
	 * EvenFooter
	 *
	 * @var string
	 */
	private $_evenFooter		= '';

	/**
	 * FirstHeader
	 *
	 * @var string
	 */
	private $_firstHeader		= '';

	/**
	 * FirstFooter
	 *
	 * @var string
	 */
	private $_firstFooter		= '';

	/**
	 * Different header for Odd/Even, defaults to false
	 *
	 * @var boolean
	 */
	private $_differentOddEven	= false;

	/**
	 * Different header for first page, defaults to false
	 *
	 * @var boolean
	 */
	private $_differentFirst	= false;

	/**
	 * Scale with document, defaults to true
	 *
	 * @var boolean
	 */
	private $_scaleWithDocument	= true;

	/**
	 * Align with margins, defaults to true
	 *
	 * @var boolean
	 */
	private $_alignWithMargins	= true;

	/**
	 * Header/footer images
	 *
	 * @var PHPExcel_Worksheet_HeaderFooterDrawing[]
	 */
	private $_headerFooterImages = array();

    /**
     * Create a new PHPExcel_Worksheet_HeaderFooter
     */
    public function __construct()
    {
    }

    /**
     * Get OddHeader
     *
     * @return string
     */
    public function getOddHeader() {
    	return $this->_oddHeader;
    }

    /**
     * Set OddHeader
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setOddHeader($pValue) {
    	$this->_oddHeader = $pValue;
    	return $this;
    }

    /**
     * Get OddFooter
     *
     * @return string
     */
    public function getOddFooter() {
    	return $this->_oddFooter;
    }

    /**
     * Set OddFooter
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setOddFooter($pValue) {
    	$this->_oddFooter = $pValue;
    	return $this;
    }

    /**
     * Get EvenHeader
     *
     * @return string
     */
    public function getEvenHeader() {
    	return $this->_evenHeader;
    }

    /**
     * Set EvenHeader
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setEvenHeader($pValue) {
    	$this->_evenHeader = $pValue;
    	return $this;
    }

    /**
     * Get EvenFooter
     *
     * @return string
     */
    public function getEvenFooter() {
    	return $this->_evenFooter;
    }

    /**
     * Set EvenFooter
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setEvenFooter($pValue) {
    	$this->_evenFooter = $pValue;
    	return $this;
    }

    /**
     * Get FirstHeader
     *
     * @return string
     */
    public function getFirstHeader() {
    	return $this->_firstHeader;
    }

    /**
     * Set FirstHeader
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setFirstHeader($pValue) {
    	$this->_firstHeader = $pValue;
    	return $this;
    }

    /**
     * Get FirstFooter
     *
     * @return string
     */
    public function getFirstFooter() {
    	return $this->_firstFooter;
    }

    /**
     * Set FirstFooter
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setFirstFooter($pValue) {
    	$this->_firstFooter = $pValue;
    	return $this;
    }

    /**
     * Get DifferentOddEven
     *
     * @return boolean
     */
    public function getDifferentOddEven() {
    	return $this->_differentOddEven;
    }

    /**
     * Set DifferentOddEven
     *
     * @param boolean $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setDifferentOddEven($pValue = false) {
    	$this->_differentOddEven = $pValue;
    	return $this;
    }

    /**
     * Get DifferentFirst
     *
     * @return boolean
     */
    public function getDifferentFirst() {
    	return $this->_differentFirst;
    }

    /**
     * Set DifferentFirst
     *
     * @param boolean $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setDifferentFirst($pValue = false) {
    	$this->_differentFirst = $pValue;
    	return $this;
    }

    /**
     * Get ScaleWithDocument
     *
     * @return boolean
     */
    public function getScaleWithDocument() {
    	return $this->_scaleWithDocument;
    }

    /**
     * Set ScaleWithDocument
     *
     * @param boolean $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setScaleWithDocument($pValue = true) {
    	$this->_scaleWithDocument = $pValue;
    	return $this;
    }

    /**
     * Get AlignWithMargins
     *
     * @return boolean
     */
    public function getAlignWithMargins() {
    	return $this->_alignWithMargins;
    }

    /**
     * Set AlignWithMargins
     *
     * @param boolean $pValue
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setAlignWithMargins($pValue = true) {
    	$this->_alignWithMargins = $pValue;
    	return $this;
    }

    /**
     * Add header/footer image
     *
     * @param PHPExcel_Worksheet_HeaderFooterDrawing $image
     * @param string $location
     * @throws PHPExcel_Exception
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function addImage(PHPExcel_Worksheet_HeaderFooterDrawing $image = null, $location = self::IMAGE_HEADER_LEFT) {
    	$this->_headerFooterImages[$location] = $image;
    	return $this;
    }

    /**
     * Remove header/footer image
     *
     * @param string $location
     * @throws PHPExcel_Exception
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function removeImage($location = self::IMAGE_HEADER_LEFT) {
    	if (isset($this->_headerFooterImages[$location])) {
    		unset($this->_headerFooterImages[$location]);
    	}
    	return $this;
    }

    /**
     * Set header/footer images
     *
     * @param PHPExcel_Worksheet_HeaderFooterDrawing[] $images
     * @throws PHPExcel_Exception
     * @return PHPExcel_Worksheet_HeaderFooter
     */
    public function setImages($images) {
    	if (!is_array($images)) {
    		throw new PHPExcel_Exception('Invalid parameter!');
    	}

    	$this->_headerFooterImages = $images;
    	return $this;
    }

    /**
     * Get header/footer images
     *
     * @return PHPExcel_Worksheet_HeaderFooterDrawing[]
     */
    public function getImages() {
    	// Sort array
    	$images = array();
    	if (isset($this->_headerFooterImages[self::IMAGE_HEADER_LEFT])) 	$images[self::IMAGE_HEADER_LEFT] = 		$this->_headerFooterImages[self::IMAGE_HEADER_LEFT];
    	if (isset($this->_headerFooterImages[self::IMAGE_HEADER_CENTER])) 	$images[self::IMAGE_HEADER_CENTER] = 	$this->_headerFooterImages[self::IMAGE_HEADER_CENTER];
    	if (isset($this->_headerFooterImages[self::IMAGE_HEADER_RIGHT])) 	$images[self::IMAGE_HEADER_RIGHT] = 	$this->_headerFooterImages[self::IMAGE_HEADER_RIGHT];
    	if (isset($this->_headerFooterImages[self::IMAGE_FOOTER_LEFT])) 	$images[self::IMAGE_FOOTER_LEFT] = 		$this->_headerFooterImages[self::IMAGE_FOOTER_LEFT];
    	if (isset($this->_headerFooterImages[self::IMAGE_FOOTER_CENTER])) 	$images[self::IMAGE_FOOTER_CENTER] = 	$this->_headerFooterImages[self::IMAGE_FOOTER_CENTER];
    	if (isset($this->_headerFooterImages[self::IMAGE_FOOTER_RIGHT])) 	$images[self::IMAGE_FOOTER_RIGHT] = 	$this->_headerFooterImages[self::IMAGE_FOOTER_RIGHT];
    	$this->_headerFooterImages = $images;

    	return $this->_headerFooterImages;
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

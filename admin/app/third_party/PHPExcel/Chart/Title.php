<?php
class PHPExcel_Chart_Title
{

	/**
	 * Title Caption
	 *
	 * @var string
	 */
	private $_caption = null;

	/**
	 * Title Layout
	 *
	 * @var PHPExcel_Chart_Layout
	 */
	private $_layout = null;

	/**
	 * Create a new PHPExcel_Chart_Title
	 */
	public function __construct($caption = null, PHPExcel_Chart_Layout $layout = null)
	{
		$this->_caption = $caption;
		$this->_layout = $layout;
	}

	/**
	 * Get caption
	 *
	 * @return string
	 */
	public function getCaption() {
		return $this->_caption;
	}

	/**
	 * Set caption
	 *
	 * @param string $caption
	 */
	public function setCaption($caption = null) {
		$this->_caption = $caption;
	}

	/**
	 * Get Layout
	 *
	 * @return PHPExcel_Chart_Layout
	 */
	public function getLayout() {
		return $this->_layout;
	}

}

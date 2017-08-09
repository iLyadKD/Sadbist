<?php
class PHPExcel_Cell_Hyperlink
{
    /**
     * URL to link the cell to
     *
     * @var string
     */
    private $_url;

    /**
     * Tooltip to display on the hyperlink
     *
     * @var string
     */
    private $_tooltip;

    /**
     * Create a new PHPExcel_Cell_Hyperlink
     *
     * @param  string  $pUrl      Url to link the cell to
     * @param  string  $pTooltip  Tooltip to display on the hyperlink
     */
    public function __construct($pUrl = '', $pTooltip = '')
    {
        // Initialise member variables
        $this->_url         = $pUrl;
        $this->_tooltip     = $pTooltip;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * Set URL
     *
     * @param  string    $value
     * @return PHPExcel_Cell_Hyperlink
     */
    public function setUrl($value = '') {
        $this->_url = $value;
        return $this;
    }

    /**
     * Get tooltip
     *
     * @return string
     */
    public function getTooltip() {
        return $this->_tooltip;
    }

    /**
     * Set tooltip
     *
     * @param  string    $value
     * @return PHPExcel_Cell_Hyperlink
     */
    public function setTooltip($value = '') {
        $this->_tooltip = $value;
        return $this;
    }

    /**
     * Is this hyperlink internal? (to another worksheet)
     *
     * @return boolean
     */
    public function isInternal() {
        return strpos($this->_url, 'sheet://') !== false;
    }

    /**
     * Get hash code
     *
     * @return string    Hash code
     */
    public function getHashCode() {
        return md5(
              $this->_url
            . $this->_tooltip
            . __CLASS__
        );
    }
}

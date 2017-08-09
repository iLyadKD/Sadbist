<?php
class PHPExcel_Chart_PlotArea
{
	/**
	 * PlotArea Layout
	 *
	 * @var PHPExcel_Chart_Layout
	 */
	private $_layout = null;

	/**
	 * Plot Series
	 *
	 * @var array of PHPExcel_Chart_DataSeries
	 */
	private $_plotSeries = array();

	/**
	 * Create a new PHPExcel_Chart_PlotArea
	 */
	public function __construct(PHPExcel_Chart_Layout $layout = null, $plotSeries = array())
	{
		$this->_layout = $layout;
		$this->_plotSeries = $plotSeries;
	}

	/**
	 * Get Layout
	 *
	 * @return PHPExcel_Chart_Layout
	 */
	public function getLayout() {
		return $this->_layout;
	}

	/**
	 * Get Number of Plot Groups
	 *
	 * @return array of PHPExcel_Chart_DataSeries
	 */
	public function getPlotGroupCount() {
		return count($this->_plotSeries);
	}

	/**
	 * Get Number of Plot Series
	 *
	 * @return integer
	 */
	public function getPlotSeriesCount() {
		$seriesCount = 0;
		foreach($this->_plotSeries as $plot) {
			$seriesCount += $plot->getPlotSeriesCount();
		}
		return $seriesCount;
	}

	/**
	 * Get Plot Series
	 *
	 * @return array of PHPExcel_Chart_DataSeries
	 */
	public function getPlotGroup() {
		return $this->_plotSeries;
	}

	/**
	 * Get Plot Series by Index
	 *
	 * @return PHPExcel_Chart_DataSeries
	 */
	public function getPlotGroupByIndex($index) {
		return $this->_plotSeries[$index];
	}

	/**
	 * Set Plot Series
	 *
	 * @param [PHPExcel_Chart_DataSeries]
	 */
	public function setPlotSeries($plotSeries = array()) {
		$this->_plotSeries = $plotSeries;
	}

	public function refresh(PHPExcel_Worksheet $worksheet) {
	    foreach($this->_plotSeries as $plotSeries) {
			$plotSeries->refresh($worksheet);
		}
	}

}

<?php

class PHPExcel_Shared_Escher
{
	/**
	 * Drawing Group Container
	 *
	 * @var PHPExcel_Shared_Escher_DggContainer
	 */
	private $_dggContainer;

	/**
	 * Drawing Container
	 *
	 * @var PHPExcel_Shared_Escher_DgContainer
	 */
	private $_dgContainer;

	/**
	 * Get Drawing Group Container
	 *
	 * @return PHPExcel_Shared_Escher_DgContainer
	 */
	public function getDggContainer()
	{
		return $this->_dggContainer;
	}

	/**
	 * Set Drawing Group Container
	 *
	 * @param PHPExcel_Shared_Escher_DggContainer $dggContainer
	 */
	public function setDggContainer($dggContainer)
	{
		return $this->_dggContainer = $dggContainer;
	}

	/**
	 * Get Drawing Container
	 *
	 * @return PHPExcel_Shared_Escher_DgContainer
	 */
	public function getDgContainer()
	{
		return $this->_dgContainer;
	}

	/**
	 * Set Drawing Container
	 *
	 * @param PHPExcel_Shared_Escher_DgContainer $dgContainer
	 */
	public function setDgContainer($dgContainer)
	{
		return $this->_dgContainer = $dgContainer;
	}

}

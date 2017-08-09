<?php

class PHPExcel_Shared_Escher_DgContainer_SpgrContainer
{
	/**
	 * Parent Shape Group Container
	 *
	 * @var PHPExcel_Shared_Escher_DgContainer_SpgrContainer
	 */
	private $_parent;

	/**
	 * Shape Container collection
	 *
	 * @var array
	 */
	private $_children = array();

	/**
	 * Set parent Shape Group Container
	 *
	 * @param PHPExcel_Shared_Escher_DgContainer_SpgrContainer $parent
	 */
	public function setParent($parent)
	{
		$this->_parent = $parent;
	}

	/**
	 * Get the parent Shape Group Container if any
	 *
	 * @return PHPExcel_Shared_Escher_DgContainer_SpgrContainer|null
	 */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
	 * Add a child. This will be either spgrContainer or spContainer
	 *
	 * @param mixed $child
	 */
	public function addChild($child)
	{
		$this->_children[] = $child;
		$child->setParent($this);
	}

	/**
	 * Get collection of Shape Containers
	 */
	public function getChildren()
	{
		return $this->_children;
	}

	/**
	 * Recursively get all spContainers within this spgrContainer
	 *
	 * @return PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer[]
	 */
	public function getAllSpContainers()
	{
		$allSpContainers = array();

		foreach ($this->_children as $child) {
			if ($child instanceof PHPExcel_Shared_Escher_DgContainer_SpgrContainer) {
				$allSpContainers = array_merge($allSpContainers, $child->getAllSpContainers());
			} else {
				$allSpContainers[] = $child;
			}
		}

		return $allSpContainers;
	}
}

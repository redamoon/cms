<?php

/**
 *
 */
class AssetContent extends BaseContentModel
{
	protected $model = 'Assets';
	protected $foreignKey = 'asset';

	/**
	 * Returns an instance of the specified model
	 *
	 * @access public
	 *
	 * @static
	 *
	 * @param string $class
	 *
	 * @return object The model instance
	 */
	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}
}

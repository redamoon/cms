<?php

/**
 * @abstract
 */
abstract class BaseContentModel extends BaseModel
{
	protected $foreignKey;
	protected $model;

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

	protected $attributes = array(
		'num'    => array('type' => AttributeType::Integer, 'required' => true),
		'label'  => array('type' => AttributeType::String, 'maxSize' => 150),
		'active' => array('type' => AttributeType::Boolean, 'required' => true),
		'type'   => array('type' => AttributeType::Enum, 'values' => 'published,draft,autosave', 'default' => 'draft', 'required' => true),
	);

	/**
	 * @access public
	 *
	 * @return array
	 */
	public function getBelongsTo()
	{
		return array(
			$this->foreignKey => $this->model,
			'content'         => 'Content'
		);
	}
}

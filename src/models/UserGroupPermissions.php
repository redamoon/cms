<?php

/**
 *
 */
class UserGroupPermissions extends BaseModel
{
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

	protected $belongsTo = array(
		'group' => array('model' => 'UserGroups', 'required' => true)
	);

	protected $attributes = array(
		'name' => array('type' => AttributeType::String, 'required' => true),
		'value' => array('type' => AttributeType::Integer, 'required' => true)
	);
}

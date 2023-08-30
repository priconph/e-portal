<?php
/**
 * @package     Sample.Library
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2013 Roberto Segura. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

/**
 * Sample list form field
 *
 * @package     Sample.Library
 * @subpackage  Field
 * @since       1.0
 */
class ConditionalFormFieldText extends JFormFieldText
{

	/**
	 * The form field type.
	 *
	 * @var  strings
	 */
	protected $type = 'conditional';
	
	protected $conditionalOn;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'conditionalOn':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'conditionalOn':
				$this->name = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	protected function getInput()
	{
		//ConditionalHelperAsset::load('conditional.js');
		$this->class = !empty($this->class) ? $this->class . ' hp_conditional' : 'hp_conditional';

		return parent::getInput();

		$conditionalOn = $this->conditionalOn;
	}

}

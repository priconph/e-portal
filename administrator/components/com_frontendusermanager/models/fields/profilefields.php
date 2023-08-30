<?php
/**
 * @package     Sample.Library
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2013 Roberto Segura. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_BASE.'/components/com_frontendusermanager/helpers/frontendusermanager.php';

JFormHelper::loadFieldClass('list');

/**
 * Sample list form field
 *
 * @package     Sample.Library
 * @subpackage  Field
 * @since       1.0
 */
class HeptaFormFieldProfilefields extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'hepta.profilefields';

	/**
	 * Cached array of the category items.
	 *
	 * @var  array
	 */
	protected static $options = array();


	/**
	 * Translate options labels ?
	 *
	 * @var  boolean
	 */
	protected $translate = true;

	/**
	 * Method to get the options to populate list
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Hash for caching
		$hash = md5($this->element);
		$type = strtolower($this->type);

		if (!isset(static::$options[$type][$hash]))
		{
			static::$options[$type][$hash] = parent::getOptions();

			$options = array();

			$profileFields = FrontendusermanagerHelper::getFieldsArray();

			FrontendusermanagerHelper::loadProfileStrings();

			$coreFields = array('id',
				'name',
				'username',
				'email',
				'groups',
				'registerDate',
				'lastvisitDate',
				'block'
				);

			foreach ($coreFields as $fieldName)
			{
					$options[] = (object) array(
						'value' => $fieldName,
						'text'  => $fieldName
					);
			}
			
			foreach ($profileFields as $fieldName => $field)
			{
					$options[] = (object) array(
						'value' => $fieldName,
						'text'  => JText::_($field->getAttribute('label'))
					);
			}

			static::$options[$type][$hash] = array_merge(static::$options[$type][$hash], $options);
		}

		return static::$options[$type][$hash];
	}
}

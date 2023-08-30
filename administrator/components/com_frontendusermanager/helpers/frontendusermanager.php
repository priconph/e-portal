<?php

/**
 * @version     1.0.0
 * @package     com_frontendusermanager
 * @copyright   Copyright (C) 2015. Joomla Design Studios Inc All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Carlos <carlos@joomladesigner.com> - http://www.joomladesignstudios.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Frontendusermanager helper.
 */
class FrontendusermanagerHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_FRONTENDUSERMANAGER_TITLE_USERMANAGERS'),
			'index.php?option=com_frontendusermanager&view=usermanagers',
			$vName == 'usermanagers'
		);

    }

	public static function createFilterQuery($field,$search)
	{
		$searchQuery = new stdClass();

			switch($field->type)
			{
				case 'Radio':
					$searchQuery->key =  $field->fieldname;
					if($search == 'Yes')
					{
						$searchQuery->value = " = Yes";
					}
					else
					{
						$searchQuery->value =  ' != "Yes"';
					}
					break;
				case 'List':
					if( ($search == '--Select--') || ($search == ' '))
					{
						break;
					}
					else
					{
						$searchQuery->key = $field->fieldname;
						$searchQuery->value = ' = \'"' . $search . '"\'';
					}
					break;
				case 'hepta.CustomDateRange':

						list($startDate,$endDate) = explode(' - ',$search);

						if( $startDate && $endDate )
						{
							$startDate = JFactory::getDate(strtotime($startDate))->toSql();
							$endDate = JFactory::getDate(strtotime($endDate))->toSql();
							$searchQuery->key = $field->fieldname;
							$searchQuery->value = ' BETWEEN "' . $startDate . '" AND "'. $endDate . '"';
						}

					break;
				default:
					$searchQuery->key = $field->fieldname;
					$searchQuery->value = ' LIKE \'"%'.$search.'%"\'';
					break;
			}
			

		return $searchQuery;
	}

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_frontendusermanager';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
	
	public static function getProfilePlugins()
	{
		$userPlugin = array();
      
      	$profilePlugins = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('folder AS type, element AS name, params')
			->from('#__extensions')
			->where('enabled = 1')
			->where('type =' . $db->quote('plugin'))
			->where('folder=' . $db->quote('user'))
			->where('state IN (0,1)')
			->order('ordering');
		$db->setQuery($query);

		$publishedUserPlugins =  $db->loadObjectList();

		foreach($publishedUserPlugins as $userPlugin)
		{
			$pluginPath = JPATH_PLUGINS . "/" . $userPlugin->type . "/" . $userPlugin->name . "/profiles";
			if( JFolder::exists($pluginPath) )
			{
				$profileXML = JFolder::files($pluginPath, ".xml", array(), true);
				if(!empty($profileXML))
				{
					$userPlugin->profileXML = $profileXML;
					$profilePlugins[] = $userPlugin;
				}
			}
		}

		return $profilePlugins;
	}

	public static function getFields($xmlFile)
	{
		$form = new JForm(basename($xmlFile));

		$form->loadFile($xmlFile);

		$fields = $form->getFieldset();

		return $fields;
	}

	public static function getFormXML($xmlFile)
	{
		$form = new JForm(basename($xmlFile));

		$form->loadFile($xmlFile);

		$fields = $form->getFieldset();

		return $form->getXml();
	}

	public static function getFieldsArray()
	{
      	$profileFields = array();
		$profilePlugins = static::getProfilePlugins();
		foreach($profilePlugins as $profilePlugin)
		{
			$pluginFields = static::getFields($profilePlugin->profileXML[0]);

			foreach($pluginFields as $field)
			{
				$profileFields[$field->fieldname] = $field;
			}
		}
		return $profileFields;
	}

	public static function getFilterForms()
	{
		$filters = array();
		$filtersFolder = JPATH_BASE . '/components/com_frontendusermanager/models/forms/';
		if( JFolder::exists($filtersFolder) )
		{
			$filters = JFolder::files($filtersFolder, "filter_(.*).xml", true, true);

		}

		return $filters;
	}

	public static function getFilters()
	{
		$filterFields = array();
		$filters = self::getFilterForms();

		foreach($filters as $filterFile)
		{
			$formFields = static::getFields($filterFile);

			foreach($formFields as $field)
			{
				$filterFields[$field->fieldname] = $field;
			}
		}
		
		return $filterFields;
	}

	public static function loadPluginLanguage($plugin)
	{

	}

	public static function loadProfileStrings()
	{
		// Get the dispatcher and load the users plugins.
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('user');

		// Trigger language load.
		$results = $dispatcher->trigger('loadLanguage');
	}


	public static function getFieldsXML($filterForms = null)
	{
		$fieldsXML = array();

		if(!$filterForms)
		{
			$filterForms = self::getFilterForms();
		}

		foreach($filterForms as $filterForm)
		{
			$formFields = self::getFields($filterForm);
			$formXML = self::getFormXML($filterForm);
			foreach($formFields as $field)
			{
				$fieldXML = $formXML->xpath('//field[@name="' . $field->fieldname . '"]');
				$fieldsXML[] = $fieldXML[0];
			}
		}

		return $fieldsXML;
	}

}

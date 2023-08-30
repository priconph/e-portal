<?php

/**
 * @version     1.0.0
 * @package     com_frontendusermanager
 * @copyright   Copyright (C) 2015. Hepta Technologies SL All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Carlos <carlos@hepta.es> - http://extensions.hepta.es
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.filesystem.folder');

/**
 * Methods supporting a list of Frontendusermanager records.
 */
class FrontendusermanagerModelUsermanagers extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
			);
		}
		parent::__construct($config);
	}

	public function getFilterForm($data = array(), $loadData = true)
	{
		$this->filterFormName = 'filter_' . 'usermanagerall';
		$form = null;

		$filterForms = FrontendusermanagerHelper::getFilterForms();

		$filterFieldsXML = FrontendusermanagerHelper::getFieldsXML($filterForms);
		
		$customForm = new JForm($this->filterFormName);
		$customForm->load('<?xml version="1.0" encoding="utf-8"?><form><fields name="filter"></fields></form>');
		foreach ($filterFieldsXML as $field)
		{
			$key = 'filter.' . $field->attributes()->name;
			if( property_exists($this->state, $key) )
			{
				$field->addAttribute('default',$this->state->$key);
			}
			$customForm->setField($field, 'filter');
		}
		
		return $customForm;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		FrontendusermanagerHelper::loadProfileStrings();
		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
		{
			$list['ordering'] = 'ordering';
		}

		if (empty($list['direction']))
		{
			$list['direction'] = 'asc';
		}

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);

	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/frontendusermanager.php';
      
		$app = JFactory::getApplication();
		$params = $app->getParams();		

		$activeFields = $this->filter_fields;
	
		$profileFields = array();
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

		$profileFields = FrontendusermanagerHelper::getFieldsArray();

		$profileGroup = "profile";

		$query->select('u.id, u.name, u.username, u.email, u.block, GROUP_CONCAT(DISTINCT gr.title ORDER BY gr.id DESC SEPARATOR \',\') AS groups, u.registerDate, u.lastvisitDate');
		foreach($profileFields as $field => $fieldObject)
		{
			$query->select('max(case when up.profile_key LIKE "%.' . $field . '" then up.profile_value end) AS '.str_replace('-', "", $field));
		}
		$query->from($db->quoteName('#__users') . ' AS u');
		$query->join('LEFT', $db->quoteName('#__user_profiles') . ' AS up ON up.' . $db->quoteName('user_id') . ' = u.'.$db->quoteName('id'));
		$query->join('LEFT', $db->quoteName('#__user_usergroup_map') . ' AS ug ON ug.' . $db->quoteName('user_id') . ' = u.'.$db->quoteName('id'));
		$query->join('LEFT', $db->quoteName('#__usergroups') . ' AS gr ON gr.' . $db->quoteName('id') . ' = ug.'.$db->quoteName('group_id'));
		$query->group('u.'.$db->quoteName('id'));

		// Filter by search in title
		$search = $this->getState('filter.name_username_search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('u.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( u.name LIKE '.$search.'  OR  u.username LIKE '.$search.' )');
			}
		}

		// Filter by search in title
		$search = $this->getState('filter.builtin-block');
		if (!empty($search) || $search === "0")
		{
				$activeFields['builtin-block'] =  'builtin-block';
				$search = $db->Quote($db->escape($search, true));
				$query->where('( u.block = '.$search.')');
		}

		$builtinBlocks = array("name_username_search", "builtin-block");
		// Filter by profile search
		$filterFields = FrontendusermanagerHelper::getFilters();
		$builtinSearch = array();
		$profileSearch = array();
		$whereClause = array();
		
		foreach($filterFields as $field)
		{
			if( in_array($field->fieldname, $builtinBlocks) )
			{
				continue;
			}
			$search = $this->getState('filter.'.$field->fieldname);

			if (!empty($search) || $search === "0")
			{
				$activeFields[$field->fieldname] =  $field->fieldname;
				$searchQuery = new stdClass();
				if (stripos($field->fieldname, 'builtin') === 0 )
				{
					$field->fieldname = str_replace('builtin-', "",$field->fieldname);

					$searchQuery = FrontendusermanagerHelper::createFilterQuery($field, $search);

					if(isset($searchQuery->key))
					{
						$builtinSearch[] = $searchQuery;
					}
				}
				else
				{					
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
						case 'jds.companyappliedto':
						case 'List':
							if( ($search == '--Select--') || !($search) )
							{
								unset($activeFields[$field->fieldname]);
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
									$searchQuery->value = ' BETWEEN ' . $db->quote($startDate) . ' AND '. $db->quote($endDate);
								}

							break;
						default:
							$searchQuery->key = $field->fieldname;
							$searchQuery->value = ' LIKE \'"%'.$search.'%"\'';
							break;
					}
					if(isset($searchQuery->key))
					{
						$profileSearch[] = $searchQuery;
					}
				}
			}
		}

		foreach( $builtinSearch as $index => $searchQuery)
		{
			$query->where('u.'.$searchQuery->key . $searchQuery->value);
		}

		foreach( $profileSearch as $index => $searchQuery )
		{
			$tableId = "up" . $index;

			$query->join('LEFT', $db->quoteName('#__user_profiles') . ' AS ' . $tableId .' ON ' . $tableId . '.' . $db->quoteName('user_id') . ' = u.'.$db->quoteName('id'));

			$keyWhere = $tableId .'.profile_key = "profile.' . $searchQuery->key . '"';
			$valueWhere = $tableId .'.profile_value' . $searchQuery->value;

			$whereClause[] = $keyWhere . " AND " . $valueWhere ;
		}

		$excludedGroups = $params->get('excludedgroups',array());
		$groupsToInclude = $params->get('groups_filter',array());
        
        if( $excludedGroups && !$groupsToInclude  )
        {
			$query->where( "(group_id NOT IN (" . implode(",",$excludedGroups) . ") )");
        }
        
        
        
        if( $groupsToInclude )
        {
			$query->where( "(group_id IN (" . implode(",",$groupsToInclude) . ") )");
        }
      
		if(!empty($whereClause))
		{
			$query->where( "(" . implode(" ) AND (", $whereClause) . ")" );
		}

		$this->filter_fields = $activeFields;
		
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_FRONTENDUSERMANAGER_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

	

}

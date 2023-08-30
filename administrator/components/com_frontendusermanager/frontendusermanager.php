<?php
/**
 * @version     1.0.0
 * @package     com_frontendusermanager
 * @copyright   Copyright (C) 2015. Joomla Design Studios Inc All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Carlos <carlos@joomladesigner.com> - http://www.joomladesignstudios.com
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_frontendusermanager')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
JLoader::import('hepta.formfields');

$controller	= JControllerLegacy::getInstance('Frontendusermanager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

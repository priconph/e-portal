<?php

/**
 * @version     1.0.0
 * @package     com_frontendusermanager
 * @copyright   Copyright (C) 2015. Joomla Design Studios Inc All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Carlos <carlos@joomladesigner.com> - http://www.joomladesignstudios.com
 */
defined('_JEXEC') or die;

class FrontendusermanagerFrontendHelper {
    public static function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}

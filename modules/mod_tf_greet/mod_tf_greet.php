<?php
 /*
 * @package		TF Greet Module
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$type = ModTfGreetHelper::getType();
$user = JFactory::getUser();

require JModuleHelper::getLayoutPath('mod_tf_greet');
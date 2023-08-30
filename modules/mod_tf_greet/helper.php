<?php
 /*
 * @package		TF Greet Module
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access
defined('_JEXEC') or die;

    class ModTfGreetHelper
        {
            // Returns the current user type
            public static function getType()
            {
                $user = JFactory::getUser();
                if ($user->guest) {
                    return 'login';
                }
                else if (!$user->guest) {
                    return 'logout';
                }
            }
        }
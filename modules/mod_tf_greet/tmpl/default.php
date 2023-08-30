<?php
 /*
 * @package		TF Greet Module
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
    // No direct access
    defined('_JEXEC') or die;

    // If the user is not logged in
    if ($type == 'login') {
        echo 'Hi! Guest, please login';
    } 
    
    // If the user is logged in
    else if ($type == 'logout') {
        echo $params->get('greetMessage') . '<br />';
        
        if ($params->get('dispId')) {
            echo $user->id . '<br />';
        }
        
        if ($params->get('dispName')) {
            echo $user->name . '<br />';
        }
        
        if ($params->get('dispUsername')) {
            echo $user->username . '<br />';
        }
        
        if ($params->get('dispEmail')) {
            echo $user->email . '<br />';
        }
        
        if ($params->get('dispRegisterDate')) {
            echo $user->registerDate . '<br />';
        }
        
        if ($params->get('dispLastvisitDate')) {
            echo $user->lastvisitDate . '<br />';
        }        
    }
?>
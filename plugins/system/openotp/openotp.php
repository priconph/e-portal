<?php
/**
 * @version    	$Id: openotp.php 20240 2013-25-08 05:46:24Z RCDevs $
 * @package    	Joomla.Plugin
 * @subpackage  Authentication.openotp
 * 
 * @copyright   Copyright (C) 2010 - 2016 RCDevs SA, All rights reserved.
*  @license     GNU General Public License version 2 or later; see LICENSE.txt.
*/

// No direct access
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

class plgSystemOpenOTP extends JPlugin {
	public  $openotp_auth = NULL;

    /**
     * This method should handle any authentication and report back to the subject
     *
     * @access	public
     * @param   array	$credentials Array holding the user credentials
     * @param	array   $options	Array of extra options
     * @param	object	$response	Authentication response object
     * @return	object	boolean
     * @since 1.5
     */	  
    function plgSystemOpenOTP(&$subject, $config)  {
        parent::__construct($subject, $config);
    }

    function onAfterDispatch(){
		// for JLog
		jimport('joomla.log.log');
		
		$remote_addr = $_SERVER["REMOTE_ADDR"];
		$current_plugin_edit = false;
		$openotp_auth_plg_id = NULL;
	
		$uri = JFactory::getURI();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();	
		$jinput = $app->input;	
		// disable plugin for non-HTML interface and Offline
		if ( $document->getType() != 'html' ) return true; 
        if ( $app->getCfg('offline') ) return true;	

		$openotp_auth_plg_enabled = JPluginHelper::isEnabled('authentication', 'openotp');	
		
		if( $openotp_auth_plg_enabled ){
			$plugin = JPluginHelper::getPlugin('authentication', 'openotp');
			$pluginParams = new JRegistry();
			$pluginParams->loadString($plugin->params);
			$openotp_scope = $pluginParams->get('openotp_scope', 'Both'); 
			
			if( $openotp_scope == 0 && $app->getName() != 'site') return true;
			if( $openotp_scope == 1 && $app->getName() == 'site') return true;
		}
		
		$openotp_sys_plg_id = $this->getId('system', 'openotp');
		
		$current_id = $jinput->get->get('extension_id');
		$option = $jinput->get->get('option');
		$view = $jinput->get->get('view');
		$layout = $jinput->get->get('layout');		
			
		$is_openotp_sys_plg = $current_id == $openotp_sys_plg_id  ? true : false;
		
		if( $option == "com_plugins" && $view == "plugin" && $layout == "edit" &&  $is_openotp_sys_plg ) $current_plugin_edit = true;


		if( !JFile::exists(JPATH_PLUGINS.DIRECTORY_SEPARATOR."authentication/openotp/openotp.class.php") || !$openotp_auth_plg_enabled ){
			JLog::add('OpenOTP Authentication plugin and OpenOTP system plugin must be both installed and enabled', JLog::ERROR, 'openotp');
			if($current_plugin_edit && $is_openotp_sys_plg && !$openotp_auth_plg_enabled) $app->enqueueMessage(JText::_('Please make sure openOTP Authentication plugin is installed and enabled'), 'error');

		}else{
			require_once(JPATH_PLUGINS.DIRECTORY_SEPARATOR."authentication/openotp/openotp.class.php");
		
			$params = $app->getUserState('openotp_response');
			$disable_OpenOTP = $app->getUserState('disable_OpenOTP');

			if(is_array($params) && !$disable_OpenOTP){
				$task = $app->getName() == 'site' ? "user.login" : "login"; 
				$formToken = JSession::getFormToken();
				$O_params = array("formToken" => $formToken, "task" => $task, "option" => $option);
				$script = openotp::getOverlay($params['response']['otpChallenge'], $params['response']['u2fChallenge'], $params['response']['message'],$params['credentials']['username'],$params['response']['session'],$params['response']['timeout'],$params['credentials']['password'], str_replace("administrator/","",$uri->base())."plugins/system/openotp", $O_params, $params['response']['domain']);

				// Add Javascript
				/*if(window.MooTools) or if(window.jQuery)*/
				//$document->addScript('chrome-extension://pfboblefjcgdjicmnffhdgionmgcdmne/u2f-api.js'); 
				$document->addScript(JURI::root( true ) ."/plugins/system/openotp/fidou2f.js");
				$document->addScriptDeclaration('
					if(window.MooTools){
						window.addEvent("domready", function() {
							'.$script.'
						});
					}else if(window.jQuery){
						jQuery(document).ready(function (){
							'.$script.'
						});
					}
				');
			}
			$app->setUserState('openotp_response', "");
		}

    }
	
	
	function getId($folder,$name){
		// Get the list of components.
		// for JLog
		jimport('joomla.log.log');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where('type = ' . $db->q('plugin'));
		$query->where('folder = ' . $db->q($folder));
		$query->where('element = ' . $db->q($name));
		//$query->where('enabled = 1');
		$db->setQuery($query);
		
		if(!($plg=$db->loadObject())){
			$app->enqueueMessage(JText::_('JFatal: Plugin is not installed or your SQL server is NUTS.'), 'error');
		}else{ 
			//JLog::add('plugin Id:' . (int)$plg->extension_id, JLog::ERROR, $remote_addr);
			return (int)$plg->extension_id;
		}

	}	


	

	
}

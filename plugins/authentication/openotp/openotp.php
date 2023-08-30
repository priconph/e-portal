<?php
/**
 * @package    	Joomla.Plugin
 * @subpackage  Authentication.openotp
 * 
 * @copyright   Copyright (C) 2010 - 2016 RCDevs SA, All rights reserved.
*  @license     GNU General Public License version 2 or later; see LICENSE.txt.
*/

// No direct access
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

/**
* @params boolean to disable OTP authentication in case OpenOTP server not responding
**/
global $disable_OpenOTP;
$disable_OpenOTP = false;

class plgAuthenticationOpenOTP extends JPlugin {
	public  $openotp_auth = NULL;
    private $state = NULL;
    private $message = NULL;
    private $timeout = NULL;
    private $domain = NULL;
    private $username = NULL;
    private $password = NULL;
    private $u2f = NULL;

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param	array	Array holding the user credentials
	 * @param	array	Array of extra options
	 * @param	object	Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
    public function onUserAuthenticate($credentials, $options, &$response) {
		// initialise variables
		global $disable_OpenOTP;

		$userdetails = null;
		$success = 0;
		$userdetails = Array();
		$remote_addr = $_SERVER["REMOTE_ADDR"];

		// for JLog
		$response->type = 'OpenOTP';
		jimport('joomla.log.log');
		//JLog::addLogger(array(),JLog::ERROR, array('openotp'));
		
		
		$app = JFactory::getApplication();		
		$jinput = $app->input;
		$jcookie  = $jinput->cookie;

		$app->setUserState('openotp_response', "");
		$app->setUserState('disable_OpenOTP', $disable_OpenOTP);

		
		require_once("openotp.class.php");
		$this->openotp_auth = new openotp($this, $this->params, JPATH_PLUGINS.DIRECTORY_SEPARATOR.$this->_type.DIRECTORY_SEPARATOR.$this->_name );

		if( $this->openotp_auth->getScope() == 0 && $app->getName() != 'site') $disable_OpenOTP = true; 
		if( $this->openotp_auth->getScope() == 1 && $app->getName() == 'site') $disable_OpenOTP = true; 
		
		// check OpenOTP system plugin
		$openotp_sys_plg_enabled = JPluginHelper::isEnabled('system', 'openotp');	
		if( !file_exists(JPATH_PLUGINS.DIRECTORY_SEPARATOR."system/openotp/openotp.php") || !$openotp_sys_plg_enabled){
			$response->error_message = JText::_('OpenOTP system plugin must be installed and enabled');
			$response->status = JAuthentication::STATUS_FAILURE;
			return false;
		}
		
		// check OpenOTP WSDL file
		if (!$this->openotp_auth->checkFile('openotp.wsdl','Could not load OpenOTP WSDL file')){
			JLog::add('Could not find OpenOTP WSDL file', JLog::ERROR, 'openotp');
			$app->enqueueMessage(JText::_('An error has occurred while processing your request.'), 'error');
			$response->status = JAuthentication::STATUS_FAILURE;
			return false;
		}
		
		// Check SOAP extension is loaded
		if (!$this->openotp_auth->checkSOAPext()){
			JLog::add('Your PHP installation is missing the SOAP extension', JLog::ERROR, 'openotp');
			$app->enqueueMessage(JText::_('An error has occurred while processing your request.'), 'error');
			$response->status = JAuthentication::STATUS_FAILURE;
			return false;
		}
		// require server_url
		if (!$this->openotp_auth->getServer_url()) {
			JLog::add('OpenOTP server URL is not configured', JLog::ERROR, 'openotp');
			$app->enqueueMessage(JText::_('An error has occurred while processing your request.'), 'error');
			$response->status = JAuthentication::STATUS_FAILURE;
			return false;
		}

		// Get context cookie
		$context_name = $this->openotp_auth->getContext_name();
		$context_size = $this->openotp_auth->getContext_size();
		$context_time = $this->openotp_auth->getContext_time();

		$context = $jcookie->get( $context_name, null );

		$this->username = $jinput->post->get('openotp_username') != NULL ? $jinput->post->get('openotp_username') : $credentials['username'];
		$this->password = $jinput->post->get('openotp_password') != NULL ? $jinput->post->get('openotp_password') : $credentials['password'];
		$this->u2f = $jinput->post->get('openotp_u2f', NULL, 'RAW' ) != NULL ? $jinput->post->get('openotp_u2f', NULL, 'RAW') : "";

		$state = $jinput->post->get('openotp_state');

		// forbid blank username & passwords
		if (empty($this->username)) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_INCORRECT');                                             
			return false;
		}
		// Could be an array or string domain
		$t_domain = $this->openotp_auth->getDomain($this->username);
		if (is_array($t_domain)){
			$credentials['username'] = $t_domain['username'];
			$this->username = $t_domain['username'];
			$this->domain = $t_domain['domain'];
		}elseif( $jinput->post->get('openotp_domain', NULL, 'RAW') != NULL) $this->domain = $jinput->post->get('openotp_domain', NULL, 'RAW');
		else $this->domain = $t_domain;
		
		if(!$disable_OpenOTP){
			if ($state != NULL) {
				// OpenOTP Challenge
				$resp = $this->openotp_auth->openOTPChallenge($this->username, $this->domain, $state, $this->password, $this->u2f);
			} else {
				// OpenOTP Login
				$resp = $this->openotp_auth->openOTPSimpleLogin($this->username, $this->domain, utf8_encode($this->password), $context);
			}

			if (!$resp || !isset($resp['code'])) {
				JLog::add('Invalid OpenOTP response for user '.$this->username, JLog::ERROR, 'openotp');
				$app->enqueueMessage(JText::_('An error has occurred while processing your request.'), 'error');
				//$response->status = JAuthentication::STATUS_FAILURE;
				return false;
			}
		}else{
			$resp['code'] = 1;
		}

		switch ($resp['code']) {
			 case 0:
				if ($resp['message']) $msg = $resp['message'];
				else $msg = JText::_('JGLOBAL_AUTH_FAIL');
	
				$app->setUserState('openotp_response', "");
				$response->error_message = $msg;
				$response->status = JAuthentication::STATUS_FAILURE;
				break;
			 case 1:
				// check user exists in database
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true);
		
				$query->select('id, password');
				$query->from('#__users');
				$query->where('username=' . $db->Quote($this->username));
		
				$db->setQuery($query);
				$result = $db->loadObject();

				if(!$disable_OpenOTP){
					if ($result) {
							$user = JUser::getInstance($result->id); 
							$response->email = $user->email;
							$response->fullname = $user->name;
							$response->username = $this->username;
							if (JFactory::getApplication()->isAdmin()) {
								$response->language = $user->getParam('admin_language');
							}
							else {
								$response->language = $user->getParam('language');
							}
							$response->status = JAuthentication::STATUS_SUCCESS;
							$response->error_message = '';
							$app->setUserState('openotp_response', "");
							return true;
	
					} else {
						$response->status = JAuthentication::STATUS_FAILURE;
						$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
						return false;
					}
				}else{
					if ($result) {
						$parts	= explode(':', $result->password);
						$crypt	= $parts[0];
						$salt	= @$parts[1];
						$testcrypt = JUserHelper::getCryptedPassword($this->password, $salt);

						if ($crypt == $testcrypt) {
							$user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
							$response->email = $user->email;
							$response->fullname = $user->name;
							if (JFactory::getApplication()->isAdmin()) {
								$response->language = $user->getParam('admin_language');
							}
							else {
								$response->language = $user->getParam('language');
							}
							$response->status = JAuthentication::STATUS_SUCCESS;
							$response->error_message = '';
						} else {
							$response->status = JAuthentication::STATUS_FAILURE;
							$response->error_message = JText::_('JGLOBAL_AUTH_INVALID_PASS');
						}
					} else {
						$response->status = JAuthentication::STATUS_FAILURE;
						$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
					}					
				}
				
				// set context cookie
				if (extension_loaded('openssl')) {			
					if (strlen($context) != $context_size) $context = bin2hex(openssl_random_pseudo_bytes($context_size/2));
					$jcookie->set($context_name, $context, time()+$context_time, '/', NULL, true, true);
				}
				
				break;
			 case 2:
				// hide the default Joomla login failure message 
				$resp['domain'] = $this->domain;
				$app->setUserState('openotp_response', array("response"=>$resp, "credentials"=>$credentials));
				break;
			 default:
			 	JLog::add("Invalid OpenOTP response code ".$resp['code']." for user ".$credentials['username'], JLog::ERROR, 'openotp');
				$app->setUserState('openotp_response', "");
				$response->error_message = JText::_('An error has occurred while processing your request.');
				$response->status = JAuthentication::STATUS_FAILURE;
				break;
		}
		
		return false;
    }
	

	
}

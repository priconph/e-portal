<?php

defined('_JEXEC') or die('Restricted access');

class PlgSystemLimitLoginAttempts extends JPlugin
{
	protected $ip_address;
	protected $failed_login_attempts;
	protected $whitelist_ip_addresses;
	protected $blacklist_ip_addresses;

	function __construct(&$subject, $config)
	{
		if(!JFactory::getUser()->guest)
		{
			return;
		}
		
		// Get the parameters.
		if (isset($config['params']))
		{
			if ($config['params'] instanceof JRegistry)
			{
				$this->params = $config['params'];
			}
			else
			{
				$this->params = new JRegistry;
				$this->params->loadString($config['params']);
			}
		}		
		$activated_on = $this->params->get('activated_on', 'both');
		if(strtolower($activated_on)=='frontend') 
		{
			if (JFactory::getApplication()->isAdmin())
			{
				return;
			}
		}
		if(strtolower($activated_on)=='backend') 
		{
			if (JFactory::getApplication()->isSite())
			{
				return;
			}
		}
		
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_system_limitloginattempts', JPATH_ADMINISTRATOR);

		$this->ip_address = $this->getIpAddress();
		$this->failed_login_attempts = $this->getLogIpAddress($this->ip_address);
		$this->whitelist_ip_addresses = $this->getWhiteIpAddresses();
		$this->blacklist_ip_addresses = $this->getBlockIpAddresses();
	}

	/**
	 * Determines correct IP address (correct usage also with a proxy)
	 *
	 * @return mixed
	 */
	private function getIpAddress()
	{
		$headers = $_SERVER;

		if(function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}

		$ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

		if($this->params->get('ip_address_type', true))
		{
			// Get the forwarded IP if it exists
			if(array_key_exists('X-Forwarded-For', $headers) AND filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6))
			{
				$ip_address = $headers['X-Forwarded-For'];
			}
			elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) AND filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6))
			{
				$ip_address = $headers['HTTP_X_FORWARDED_FOR'];
			}
		}

		return $ip_address;
	}

	/**
	 * Loads log file for a specific IP address
	 *
	 * @param $ip_address
	 *
	 * @return array
	 */
	private function getLogIpAddress($ip_address)
	{
		$log_content_array = array('retries' => 0, 'total_retries'=>0, 'lockouts' => 0, 'time' => 0);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('retries', 'total_retries', 'lockouts', 'time')));
		$query->from($db->quoteName('#__jt_limitloginattempts'));
		$query->where($db->quoteName('ip') . ' = '. $db->quote($ip_address));
		$db->setQuery($query);
		$results = $db->loadObject();
		if(!empty($results)) {
			$log_content_array = array('retries' => $results->retries, 'total_retries' => $results->total_retries, 'lockouts' => $results->lockouts, 'time' => $results->time);
		}
		return $log_content_array;
	}
	
	/**
	 * Loads white listed IP address
	 *
	 * @return array
	 */
	private function getWhiteIpAddresses()
	{
		$whitelist_ips = $this->params->get('whitelist_ip');
		if(!empty($whitelist_ips))
		 {
			 $whitelist = array();
			 $whitelist_ips_arr = explode(',', $whitelist_ips);
			 foreach($whitelist_ips_arr as $key => $value)
			 {
				 $whitelist[$key] = trim($value);
			 }
			 return $whitelist;

		 }
		 else
		 {
			return array();			 
		 }
	}

	/**
	 * Loads block listed IP address
	 *
	 * @return array
	 */
	private function getBlockIpAddresses()
	{
		$blacklist_ips = $this->params->get('blacklist_ip');
		if(!empty($blacklist_ips))
		 {
			 $blacklist = array();
			 $blacklist_ips_arr = explode(',', $blacklist_ips);
			 foreach($blacklist_ips_arr as $key => $value)
			 {
				 $blacklist[$key] = trim($value);
			 }
			 return $blacklist;
		 }
		 else
		 {
			return array();			 
		 }
	}

	/**
	 * Checks are executed in the system trigger onAfterInitialise
	 *
	 * @throws Exception
	 */
	function onAfterInitialise()
	{
		//JFactory::getApplication()->close(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_IP_ADDRESS_BLOCKED'), $this->ip_address));
		if(!empty($this->blacklist_ip_addresses)) {
			if(in_array(trim($this->ip_address), $this->blacklist_ip_addresses)) 
			{
				$this->resetLoginData();
				
				/**
				 * Clear the already enqued messages and notices 
				 *
				 * Enqueue the IP Address Blocked error message
				 */
				$session = JFactory::getSession();
                $sessionQueue = $session->get('application.queue');
                if (count($sessionQueue)) {
                        $session->set('application.queue', null);
                }
				
				JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_IP_ADDRESS_BLOCKED'), $this->ip_address), 'error');
				return;
			}
		}

		/**
		 * Remove the plugin operation for the whitelisted IP Addresses 
		 */
		if(!empty($this->whitelist_ip_addresses)) {
			if(in_array(trim($this->ip_address), $this->whitelist_ip_addresses)) 
			{
				$this->clearIpAddresses();
				return;
			}
		}

		$allowed_retries = $this->params->get('allowed_retries');
		$lockout_duration = $this->params->get('lockout_duration');
		$allowed_lockouts = $this->params->get('allowed_lockouts');
		$long_duration = $this->params->get('long_duration');
		$reset_retries = $this->params->get('reset_retries');
		$time_diff_min = round(abs(time() - $this->failed_login_attempts['time']) / 60,2);
		$time_diff_hours = round(abs(time() - $this->failed_login_attempts['time']) / 3600,2);								
		
		if($this->failed_login_attempts['lockouts'] >= $allowed_lockouts && $time_diff_hours < $long_duration) {
			$this->resetLoginData();
		} else {
		
			if($this->failed_login_attempts['retries'] >= $allowed_retries && $time_diff_min < $lockout_duration) 
			{
				$this->resetLoginData();
			}
		
		}
		
	}

	/**
	 * Unset the login data
	 */
	private function resetLoginData()
	{
		JFactory::getApplication()->input->set('username', '');
		JFactory::getApplication()->input->set('passwd', '');
		JFactory::getApplication()->input->set('secretkey', '');

		// Reset the global $_POST variable else login is still possible with correct credentials
		// Not possible to go through JInput because only $_REQUEST is processed as a reference
		$_POST['username'] = '';
		$_POST['passwd'] = 1;
		$_POST['secretkey'] = '';
	}

	/**
	 * Removes lock of all outdated IP addresses
	 */
	private function clearIpAddresses()
	{
		// First remove the lock of the specific IP address
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('ip') . ' = ' . $db->quote($this->ip_address)
		);
		$query->delete($db->quoteName('#__jt_limitloginattempts'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();

		// Get all outdated locks and remove them
		$outdated_ips = $this->getOutdatedIpAddresses();
		if(!empty($outdated_ips))
		{
			$outdated_ip_list = implode(",",$outdated_ips);
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__jt_limitloginattempts'));
			$query->where($db->quoteName('ip').' IN ('.$db->quote($outdated_ip_list).')');
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Loads all locked ips which are outdated
	 *
	 * @return array
	 */
	private function getOutdatedIpAddresses()
	{
		$outdated_addresses = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('retries', 'lockouts', 'time', 'ip')));
		$query->from($db->quoteName('#__jt_limitloginattempts'));
		$query->where($db->quoteName('ip') . ' != ""');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		if(!empty($results)) {
			
			$allowed_retries = $this->params->get('allowed_retries');
			$lockout_duration = $this->params->get('lockout_duration');
			$allowed_lockouts = $this->params->get('allowed_lockouts');
			$long_duration = $this->params->get('long_duration');
			$reset_retries = $this->params->get('reset_retries');
			
			foreach($results as $result)
			{
				$lock_ip_info = $this->getLogIpAddress($result->ip);
				$time_diff_min = round(abs(time() - $lock_ip_info['time']) / 60,2);								
				$time_diff_hours = round(abs(time() - $lock_ip_info['time']) / 3600,2);								
				if($lock_ip_info['retries'] >= $allowed_retries && $lock_ip_info['lockouts'] == 0 && ($time_diff_min > $lockout_duration))
				{
					$outdated_addresses[] = $result->ip;
				}
				
				if($lock_ip_info['retries'] >= $allowed_retries && $lock_ip_info['lockouts'] == 0 && $time_diff_hours > $reset_retries && $reset_retries > 0)
				{
					$outdated_addresses[] = $result->ip;
				}
				
				if($lock_ip_info['lockouts'] > 0 && $time_diff_hours > $long_duration)
				{
					$outdated_addresses[] = $result->ip;
				}
			}
		}

		return array_unique($outdated_addresses);
	}

	/**
	 * Detects whether the plugin routine has to be loaded and call the checks in onAfterRender
	 */
	public function onAfterRender()
	{
	}

	/**
	 * Saves amount of failed login attempts and execution time in the trigger onUserLoginFailure
	 */
	public function onUserLoginFailure()
	{
		if(!empty($this->blacklist_ip_addresses)) {
			if(in_array(trim($this->ip_address), $this->blacklist_ip_addresses)) 
			{
				JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_IP_ADDRESS_BLOCKED'), $this->ip_address), 'error');
				return;
			}
		}
		
		if(!empty($this->whitelist_ip_addresses)) {
			if(in_array(trim($this->ip_address), $this->whitelist_ip_addresses)) 
			{
				$this->clearIpAddresses();
				return;
			}
		}
		
		$allowed_retries = (null !== $this->params->get('allowed_retries') && $this->params->get('allowed_retries')!=0)?$this->params->get('allowed_retries'):1;
		$allowed_lockouts = (null !== $this->params->get('allowed_lockouts') && $this->params->get('allowed_lockouts')!=0)?$this->params->get('allowed_lockouts'):1;
		$allowed_total_retries = $allowed_retries * $allowed_lockouts;
		$lockout_duration = $this->params->get('lockout_duration');
		$long_duration = $this->params->get('long_duration');
		$time_diff_min = round(abs(time() - $this->failed_login_attempts['time']) / 60,2);								
		$time_diff_hours = round(abs(time() - $this->failed_login_attempts['time']) / 3600,2);
		$retries = $this->failed_login_attempts['retries'] + 1;
		
		if($this->failed_login_attempts['lockouts'] >= $allowed_lockouts && $time_diff_hours <= $long_duration) {
			/* Condition  - lockouts >= allowed lockouts and time reached the lockout hours */
			$long_diff = ceil($long_duration - $time_diff_hours);
			JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_MANY_ATTEMPTS'), $long_diff), 'warning');
			return;
		}
		else if($this->failed_login_attempts['lockouts'] >= $allowed_lockouts && $time_diff_hours > $long_duration)
		{
			/* Condition  - lockouts >= allowed lockouts and not reached the lockout hours */
			$this->failed_login_attempts['lockouts'] = 0;
			$this->failed_login_attempts['retries'] = 1;
			$this->failed_login_attempts['total_retries'] = 1;
			$this->failed_login_attempts['time'] = time();
			$this->setLogIpAddress();
			$remaining_attempts = $allowed_retries - 1;
			JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_LIMITS_REMAINING'), $remaining_attempts), 'warning');
			return;
		}
		else if($this->failed_login_attempts['lockouts'] < $allowed_lockouts)
		{
			if($retries < $allowed_retries) {
				$this->failed_login_attempts['retries'] = $this->failed_login_attempts['retries'] + 1;
				$this->failed_login_attempts['total_retries'] = $this->failed_login_attempts['total_retries'] + 1;
				$this->failed_login_attempts['time'] = time();
				$this->setLogIpAddress();
				
				$remaining_attempts = $allowed_retries - $this->failed_login_attempts['retries'];
				if($remaining_attempts==1) {
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_LIMIT_REMAINING'), $remaining_attempts), 'warning');
					return;
				} else if($remaining_attempts==0) {
					$lockout_diff = ceil($lockout_duration - $time_diff_min);
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_ATTEMPTS'), $lockout_diff), 'warning');
					return;
				} else {
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_LIMITS_REMAINING'), $remaining_attempts), 'warning');
					return;
				}
			} else if($retries > $allowed_retries && $time_diff_min > $lockout_duration) {
				
				if($this->failed_login_attempts['total_retries'] == $allowed_total_retries-1) {
					
					$this->failed_login_attempts['lockouts'] = $allowed_lockouts;
					$this->failed_login_attempts['retries'] = $allowed_retries;
					$this->failed_login_attempts['total_retries'] = $allowed_total_retries;
					$this->failed_login_attempts['time'] = time();
					$this->setLogIpAddress();
					
					if($this->params->get('email_lockouts')==1)
					{
						$this->sendLockoutEmail();
					}
							
					$long_diff = ceil($long_duration - $time_diff_hours);
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_MANY_ATTEMPTS'), $long_diff), 'warning');
					return;
					
				} else if($this->failed_login_attempts['total_retries'] >= $allowed_total_retries) {
					
					$long_diff = ceil($long_duration - $time_diff_hours);
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_MANY_ATTEMPTS'), $long_diff), 'warning');
					return;
					
				} else {

					$this->failed_login_attempts['retries'] = 1;
					$this->failed_login_attempts['time'] = time();
					$this->setLogIpAddress();
					
					$remaining_attempts = $allowed_retries - 1;
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_LIMITS_REMAINING'), $remaining_attempts), 'warning');
					return;
				}

			} else if($retries > $allowed_retries && $time_diff_min <= $lockout_duration) {
				$lockout_diff = ceil($lockout_duration - $time_diff_min);
				JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_ATTEMPTS'), $lockout_diff), 'warning');
				return;
			} else if($retries == $allowed_retries) {
				$this->failed_login_attempts['retries'] = $allowed_retries;
				$this->failed_login_attempts['total_retries'] = $this->failed_login_attempts['total_retries'] + 1;
				$this->failed_login_attempts['lockouts'] = $this->failed_login_attempts['lockouts'] + 1;
				$this->failed_login_attempts['time'] = time();
				$this->setLogIpAddress();
				
				if($this->failed_login_attempts['lockouts'] >= $allowed_lockouts)
				{
					if($this->params->get('email_lockouts')==1)
					{
						$this->sendLockoutEmail();
					}
					
					$long_diff = ceil($long_duration - $time_diff_hours);
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_MANY_ATTEMPTS'), $long_diff), 'warning');
					return;
				} else {				
					$lockout_diff = ceil($lockout_duration - $time_diff_min);
					JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_LIMITLOGINATTEMPTS_TOO_MANY_ATTEMPTS'), $lockout_diff), 'warning');
					return;
				}
			}
		}
			

	}

	/**
	 * Saves information to the content file of a specific IP address
	 */
	private function setLogIpAddress()
	{
		$log_content =$this->failed_login_attempts;
		$ip_address = $this->ip_address;
		$username = isset($_POST['username'])?$_POST['username']:'';

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__jt_limitloginattempts'));
		$query->where($db->quoteName('ip') . ' = '. $db->quote($ip_address));
		$db->setQuery($query);
		$result_count = $db->loadResult();
		if($result_count > 0)
		{
			$query = $db->getQuery(true);
			if($username!='')
			{
				$fields = array(
					$db->quoteName('username') . ' = ' . $db->quote($username),
					$db->quoteName('retries') . ' = ' . $log_content['retries'],
					$db->quoteName('total_retries') . ' = ' . $log_content['total_retries'],
					$db->quoteName('lockouts') . ' = ' . $db->quote($log_content['lockouts']),
					$db->quoteName('time') . ' = ' . $db->quote($log_content['time'])
				);
			} else {
				$fields = array(
					$db->quoteName('retries') . ' = ' . $log_content['retries'],
					$db->quoteName('total_retries') . ' = ' . $log_content['total_retries'],
					$db->quoteName('lockouts') . ' = ' . $db->quote($log_content['lockouts']),
					$db->quoteName('time') . ' = ' . $db->quote($log_content['time'])
				);
			}
			$conditions = array(
				$db->quoteName('ip') . ' = ' . $db->quote($ip_address)
			);
			$query->update($db->quoteName('#__jt_limitloginattempts'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$result = $db->execute();
			
			
		} 
		else
		{
			$query = $db->getQuery(true);
			if($username!='')
			{
				$columns = array('ip', 'username', 'retries', 'total_retries', 'lockouts', 'time');
				$values = array($db->quote($ip_address), $db->quote($username), $db->quote($log_content['retries']), $db->quote($log_content['total_retries']), $db->quote($log_content['lockouts']), $db->quote($log_content['time']));
			} 
			else
			{
				$columns = array('ip', 'retries', 'total_retries', 'lockouts', 'time');
				$values = array($db->quote($ip_address), $db->quote($log_content['retries']), $db->quote($log_content['total_retries']), $db->quote($log_content['lockouts']), $db->quote($log_content['time']));
			}
			$query
				->insert($db->quoteName('#__jt_limitloginattempts'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	private function sendLockoutEmail()
	{
		$ip_address = $this->ip_address;
		$allowed_retries = (null !== $this->params->get('allowed_retries') && $this->params->get('allowed_retries')!=0)?$this->params->get('allowed_retries'):1;
		$allowed_lockouts = (null !== $this->params->get('allowed_lockouts') && $this->params->get('allowed_lockouts')!=0)?$this->params->get('allowed_lockouts'):1;
		$allowed_total_retries = $allowed_retries * $allowed_lockouts;
		$lockout_duration = $this->params->get('lockout_duration');
		$long_duration = $this->params->get('long_duration');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__jt_limitloginattempts'));
		$query->where($db->quoteName('ip') . ' = '. $db->quote($ip_address));
		$db->setQuery($query);
		$result = $db->loadObjectList()[0];
		$username = $result->username;
		$lockouts = $result->lockouts;
		
		$config = JFactory::getConfig();
		$site_name = $config->get( 'sitename' );
		
		$email_subject = '['.$site_name.'] Too many failed login attempts';
		$email_message = '';
		$email_message .= $allowed_total_retries.' failed login attempts ('.$lockouts.' lockout(s)) from IP: '.$ip_address.'<br />Last user attempted: '.$username.'<br />IP was blocked for '.$long_duration.' hours';
		
		$sender = array( 
			$config->get( 'mailfrom' ),
			$config->get( 'fromname' ) 
		);
		$recipient = $config->get( 'mailfrom' );
		
		$mailer = JFactory::getMailer();
		$mailer->setSender($sender);
		$mailer->addRecipient($recipient);
		$mailer->setSubject($email_subject);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($email_message);

		$send = $mailer->Send();
	}

	/**
	 * Clears information file of a specific IP address after a successful login
	 */
	public function onUserLogin()
	{
		$this->clearIpAddresses();
	}
}

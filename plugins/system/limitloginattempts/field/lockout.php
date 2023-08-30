<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldLockout extends JFormField {
 
	protected $type = 'Lockout';

	private function getRetriesLockouts()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('ip', 'username', 'retries', 'lockouts', 'time')));
		$query->from($db->quoteName('#__jt_limitloginattempts'));
		$query->order('time DESC');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;	
	}
	
	public function getLabel() 
	{
		$lockouts = $this->getRetriesLockouts();
		$lockout_data = '<div class="span12"><table class="table table-bordered table-striped table-hover"><tr><th width="180">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_IP_ADDRESS').'</th><th width="200">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_USER_NAME').'</th><th width="100">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_RETRIES').'</th><th width="100">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_LOCKOUTS').'</th><th width="150">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_TIME').'</th></tr>';
		if(!empty($lockouts))
		{
			foreach($lockouts as $lockout) 
			{
				$username = isset($lockout->username)?$lockout->username:'--';
				$lockout_data .= '<tr><td>'.$lockout->ip.'</td><td>'.$username.'</td><td>'.$lockout->retries.'</td><td>'.$lockout->lockouts.'</td><td>'.date('d-m-Y h:i:s', $lockout->time).'</td></tr>';
			}
		}
		else 
		{
			$lockout_data .= '<tr><td colspan="5">'.JText::_('PLG_LIMITLOGINATTEMPTS_LOCKOUTS_EMPTY').'</td></tr>';
			
		}
		$lockout_data .= '</table></div>';
		return $lockout_data;
	}
	
	public function getInput() 
	{
		return '';
	}
}
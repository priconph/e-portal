<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Fsdb
 * @author     Carlos Cámara <carlos@joomladesigner.com>
 * @copyright  2016 Joomla Design Studios INC
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports a value from an external table
 *
 * @since  1.6
 */
class HeptaFormFieldCustomDateRange extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.6
	 */
	protected $type = 'hepta.CustomDateRange';

	private $table;

	private $key_field;

	private $value_field;

	public function getInput() {

		//Get properties
		$requestedRanges = $this->getAttribute('ranges');
		$dateLimit = $this->getAttribute('days_span');
		$dateFormat = $this->getAttribute('date_format','YYYY/MM/DD');
		
		JHtml::script('hepta/formfields/vendor/momentjs/moment.min.js', true, true, false, false, false);
		JHtml::script('hepta/formfields/vendor/daterangepicker/daterangepicker.js', true, true, false, false, true);
		JHtml::stylesheet('hepta/formfields/vendor/daterangepicker/daterangepicker.css', array(), true, false, false, true);

		$html = '<input type="text" name="' . $this->name . '" value="' . $this->value .'" placeholder="' . JText::_($this->hint) . '" />';

		
		$ranges = $this->getRanges($requestedRanges);

		$script[] = 'jQuery(document).ready(function(){';
		$script[] = 'jQuery(\'input[name="' . $this->name . '"]\').daterangepicker({';
		
		if($dateLimit)
		{
			
		    $script[] = '"dateLimit": {
		        "days": ' . $dateLimit .'
		    },';
		}
		$script[] = '"ranges": {
	';
	foreach($ranges as $range)
	{
			$script[] = '"' . $range->label . '": [ ' . $range->start . ', ' . $range->end . '],';
	}
    $script[] = '},
    "locale": {
        "format": "' . $dateFormat . '",
        "separator": " - ",
        "applyLabel": "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_APPLY") . '",
        "cancelLabel": "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_CLEAR") . '",
        "fromLabel": "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_FROM") . '",
        "toLabel": "To",
        "customRangeLabel": "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_CUSTOM") . '",
        "daysOfWeek": [
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_SUNDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_MONDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_TUESDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_WEDNESDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_THURSDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_FRIDAY_SHORT") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_SATURDAY_SHORT") . '"
        ],
        "monthNames": [
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_JANUARY") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_FEBRUARY") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_MARCH") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_APRIL") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_MAY") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_JUNE") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_JULY") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_AUGUST") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_SEPTEMBER") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_OCTOBER") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_NOVEMBER") . '",
            "' . JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_DECEMBER") . '"
        ],
        "firstDay": 1
    },
    "linkedCalendars": false,
    "autoUpdateInput": false,
    "opens": "center",
    "buttonClasses": "button btn uk-button",
    "applyClass": "button btn btn-primary uk-button uk-button-primary"
});';

		

		$script[] = 'jQuery(\'input[name="' . $this->name . '"]\').on(\'cancel.daterangepicker\', function(ev, picker) {
      jQuery(this).val(\'\');
  });';
		$script[] = 'jQuery(\'input[name="' . $this->name . '"]\').on(\'apply.daterangepicker\', function(ev, picker) {
      jQuery(this).val(picker.startDate.format(\'YYYY/MM/DD/\') + " - " + picker.endDate.format(\'YYYY/MM/DD\'));
  });';
$script[] = '});';
		JFactory::getDocument()->addScriptDeclaration(implode(' ',$script));

		return $html;

	}

	private function getRanges($requested="")
	{
		$ranges['today'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_TODAY"), 'moment()','moment()');
		$ranges['yesterday'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_YESTERDAY"), 'moment().subtract(1, \'days\')','moment().subtract(1, \'days\')');
		$ranges['pastweek'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_PAST_WEEK"), 'moment().subtract(1, \'weeks\').startOf(\'isoWeek\'),','moment().subtract(1, \'weeks\').endOf(\'isoWeek\')');
		$ranges['pastmonth'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_PAST_MONTH"), 'moment().subtract(1, \'month\').startOf(\'month\'),','moment().subtract(1, \'month\').endOf(\'month\')');
		$ranges['priormonth'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_PRIOR_MONTH"), 'moment().subtract(2, \'month\').startOf(\'month\'),','moment().subtract(2, \'month\').endOf(\'month\')');
		$ranges['pastquarter'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_PAST_MONTH"), 'moment().subtract(1, \'quarter\').startOf(\'quarter\'),','moment().subtract(1, \'quarter\').endOf(\'quarter\')');
		$ranges['priorquarter'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_PRIOR_MONTH"), 'moment().subtract(2, \'quarter\').startOf(\'quarter\'),','moment().subtract(2, \'quarter\').endOf(\'quarter\')');

		$ranges['nextmonth'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_NEXT_MONTH"), 'moment()','moment().add(1, \'month\')');
		$ranges['next3months'] = $this->createRange(JText::_("LIB_HEPTA_FORM_FIELDS_CUSTOMDATE_NEXT_3_MONTHS"), 'moment()','moment().add(3, \'months\')');
		
		if($requested)
		{
			$keys = explode(',',$requested);
/*
			foreach($keys as $key)
			{
				$requestedRanges[$key] = $ranges[$key];
			}*/

			$requestedRanges = array_intersect_key($ranges, array_flip($keys));
		}
		else
		{
			$requestedRanges = $ranges;
		}

		return $requestedRanges;
	}

	private function createRange($label, $start, $end)
	{
		$range = new stdClass();

		$range->label = $label;
		$range->start = $start;
		$range->end = $end;

		return $range;
	}

	private function getConfiguration()
	{
		$configuration = array();
		
		return $configuration;
	}

	/**
	 * Wrapper method for getting attributes from the form element
	 *
	 * @param   string  $attr_name  Attribute name
	 * @param   mixed   $default    Optional value to return if attribute not found
	 *
	 * @return mixed The value of the attribute if it exists, null otherwise
	 */
	public function getAttribute($attr_name, $default = null)
	{
		if (!empty($this->element[$attr_name]))
		{
			return $this->element[$attr_name];
		}
		else
		{
			return $default;
		}
	}
}

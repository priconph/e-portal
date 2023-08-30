<?php
/**
 * @version     1.0.0
 * @package     com_paqueteurgente
 * @copyright   Copyright (C) 2014 - Hepta Technologies SL . Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Carlos Cámara <carlos@heptatechnologies.com> - http://www.heptatechnologies.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JqueryUiDatePicker extends JFormField {

 /**
  * field type
  * @var string
  */
 protected $type = 'JqueryUiDatePicker';

 /**
  * Method to get the field input markup
  */
 protected function getInput() {
	$js = 'jQuery(document).ready(function() {
		jQuery("#'.$this->id.'").datepicker({

		 showButtonPanel: true,
		 showOn: "both",
		 dateFormat: "'.$this->element['format'].'",
		 defaultDate: "'.$this->element['defaultDate'].'",
		 changeYear: true
		  });
		});';

	JFactory::getDocument()->addScriptDeclaration($js);

	// The input field
	// class='required' for client side validation
	$class = "";

	if ($this->required) {

	   $class = 'class="required"';
	}

	$html = array();
	$html[] = '<input ' . $class . ' type="text" name="' . $this->name . '" id="' . $this->id . '" size="30" value="' . $this->value . '" />';

	return implode("\n", $html);
 }

}
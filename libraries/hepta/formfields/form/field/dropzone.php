<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Esubcontratacion
 * @author     Carlos <carlos@heptatechnologies.com>
 * @copyright  Copyright (C) 2015. Todos los derechos reservados.
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.filesystem.file');

/**
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class HeptaFormFieldDropzone extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'hepta.Dropzone';

	public function getInput()
	{
		JHtml::stylesheet('hepta/formfields/vendor/dropzone/dropzone.css', array(), true);
		JHtml::stylesheet('hepta/formfields/dropzone-field.css', array(), true);
		JHtml::script("hepta/formfields/vendor/dropzone/dropzone.js", false, true, false,false, true);
		JHtml::script("hepta/formfields//dropzonefield.js", false, true, false,false, true);

		// Initialize some field attributes.
		$accept    = !empty($this->accept) ? ' accept="' . $this->accept . '"' : '';
		$size      = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$class     = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$multiple  = $this->multiple ? ' multiple' : '';

		$html = $this->getUploadZone();
		return $html;
	}

	protected function getUploadZone()
	{
		$zone = '';

		$zone .= '<div id="' . $this->id .'-dz" class="filezone dropzone"></div>';
		

		$dropzoneOptions = $this->getDropzoneOptions();

		$gwejsonParams = $dropzoneOptions['gwejsonParams'];
		unset($dropzoneOptions['gwejsonParams']);

		foreach($dropzoneOptions as $option => $optionValue)
		{
			$jsOptions [] = $option . ":" . "'$optionValue'";
		}

		$accept = "";
		if($this->getAttribute('accept'))
		{
			$accept = 'accept: function(file,done) {'
							. 'var acceptMessage = ' .$this->getAttribute('accept') .';'
							.' if(acceptMessage.length=0)done();else done(acceptMessage);'
						.'}'
					. ',';
		}

		$script[] = 'if(hepta === undefined) {
					  var hepta = {};
					}';
		$script[] = 'hepta.baseURL = "' . JUri::root() .'";';

		if($this->value)
		{
			$dzFiles = $this->value;
			if (!is_array($dzFiles)) {
				$dzFiles = (array) $dzFiles;
			}
			foreach($dzFiles as $key => $file)
			{
				if($size = $this->checkSize($file))
				{
					$dzFiles[$key]->size = $size;
				}
				else
				{
					unset($dzFiles[$key]);
				}
			}

			$this->value = $dzFiles;
			
			$script[] = 'hepta.dzfield.dzFiles = ' . json_encode($this->value).';';
		}
		else
		{
			$script[] = 'hepta.dzfield.dzFiles = {};';
		}

		if($this->value) {
			$inputValue = htmlspecialchars(json_encode($this->value));
		}
		else
		{
			$inputValue = "";
		}
		$zone .= '<input type="hidden" id="' . $this->id .'" name="' . $this->name . '" value="' . $inputValue . '"/>';

		$script[] = 'jQuery(document).ready(function(){'
					. 'Dropzone.autoDiscover = false;'
					. ' hepta.Dropzone = new Dropzone( "div#' . $this->id .'-dz", {'
						. $accept
						. 'params: {'
							. '"json": JSON.stringify(' . json_encode($gwejsonParams) . '),'
						. '},'
						. 'init: function() {'
						.	'this.on("success", function(file, responseText) {'
						.		'dzSuccessfulUpload(file,responseText);'
						.	'});'
						.	'this.on("removedfile", function(file) {'
						.		'dzRemoveFile(file, "'. $this->id .'");'
						.	 '});'
						.	'this.on("addedfile", function(file) {'
						.		""
						.	'});'
						. '},	'
						. implode(',',$jsOptions) . ','
						. 'maxfilesexceeded: function(file){this.removeFile(file);alert("max files");},'
					. '});'
				. 'addServerFiles("#' . $this->id .'");'
				. '});';

		if($onload = $this->getAttribute('onload'))
		{
			$script[]= $onload;
		}

		JFactory::getDocument()->addScriptDeclaration(implode($script));

		return $zone;
	}

	private function checkSize($file)
	{
		$path = JPATH_SITE . '/' . $file->path;
		if(file_exists($path))
		{
			return filesize($path);
		}

		return false;
	}

	private function getDropzoneOptions()
	{
		$jinput = JFactory::getApplication()->input;
		$context = $jinput->get('option');

		//check
		$uploadFolder = $this->getAttribute('upload_folder');
		$fileTypes = $this->getAttribute('filetypes');
		$options['maxFiles'] = $this->getAttribute('max_uploads','');
		$options['uploadMultiple'] = "true";
		$options['addRemoveLinks'] = "dictRemoveFile";
		$options['paramName'] = "$this->name";
		

		$options['url'] = "index.php?task=gwejson&path=library&folder=hepta/formfields/actions&file=gwejson_dropzone&token=" . JSession::getFormToken();


		//If no XML attribute we check component parameters
		if( $context && (!$uploadFolder || !$fileTypes || !$options['maxFiles']) )
		{
			$prefixParts[] = 'dropzone';
			$prefixParts[] = $this->name;

			$prefix = implode('_',$prefixParts);

			if(!$uploadFolder)
			{
				$uploadFolder = JComponentHelper::getParams($context)->get($prefix.'_upload_folder','images');
			}

			if(!$fileTypes)
			{
				$fileTypes = JComponentHelper::getParams($context)->get($prefix.'_filetypes','*');
			}

			if(!$options['maxFiles'])
			{
				$options['maxFiles'] = JComponentHelper::getParams($context)->get($prefix.'_max_uploads','2');
			}
		}

		if(!$uploadFolder)
		{
			$uploadFolder = 'images';
		}
		if($fileTypes)
		{
			$fileTypes = str_replace('.', '', $fileTypes);
			$fileTypes = str_replace(' ', '', $fileTypes);

			$acceptedFiles = explode(',',  strtolower($fileTypes));

			$options['acceptedFiles'] = '.'.implode(',.',$acceptedFiles);
		}

		if(!$options['maxFiles'])
		{
			$options['maxFiles'] = 2;
		}

		$params['uploadFolder'] = $uploadFolder;
		$params['fieldName'] = $this->fieldname;
		$params['fieldId'] = $this->id;
		$options['gwejsonParams'] = $params;

		$options['dictDefaultMessage'] = JText::_('LIB_HEPTA_FORM_FIELDS_DROPZONE_DEFAULT_MESSAGE',true);
		$options['dictFallbackMessage'] = JText::_('LIB_HEPTA_FORM_FIELDS_DROPZONE_FALLBACK_MESSAGE',true);
		$options['dictFallbackText'] = JText::_('LIB_HEPTA_FORM_FIELDS_DROPZONE_FALLBACK_TEXT',true);
		$options['dictInvalidFileType'] = JText::sprintf('LIB_HEPTA_FORM_FIELDS_DROPZONE_INVALID_FILE_TYPE',$options['acceptedFiles'],true);
		$options['dictFileTooBig'] = JText::sprintf('LIB_HEPTA_FORM_FIELDS_DROPZONE_FILE_TOO_BIG',true);
		$options['dictResponseError'] = JText::sprintf('LIB_HEPTA_FORM_FIELDS_DROPZONE_RESPONSE_ERROR',true);
		$options['dictRemoveFile'] = JText::sprintf('LIB_HEPTA_FORM_FIELDS_DROPZONE_REMOVE_FILE',true);
		$options['dictMaxFilesExceeded'] = JText::sprintf('LIB_HEPTA_FORM_FIELDS_DROPZONE_MAX_FILES_EXCEEDED',true);

		return $options;
	}
	
}

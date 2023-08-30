<?php

/* 
 *  @author    Carlos Cámara - Hepta Technologies
 *  @copyright Copyright (C) Hepta Technologies SL
 *  @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *  @link http://www.heptatechnologies.com
 */
defined('JPATH_BASE') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

function ProcessJsonRequest(&$requestObject, $returnData)
{
	$returnData->result = "ERROR";
	$returnData->error = 1;

	$uploadFolder = JPATH_SITE . "/images";

	$fieldName = $requestObject->fieldName;

	if( $requestObject->uploadFolder )
	{
		$uploadFolder = $requestObject->uploadFolder;
		$uploadFolder = JPATH_SITE . "/". $uploadFolder;
	}

	$uploadFolder = JFolder::makeSafe($uploadFolder);

	if(!file_exists($uploadFolder))
	{
		JFolder::create($uploadFolder);
	}

	$input = JFactory::getApplication()->input;
	$files = $input->files->get('jform');

	if($files[$fieldName])
	{
		$uploadedFiles = array();
		$files = $files[$fieldName];

		foreach($files as $file)
		{
			$temp = $file['tmp_name'];
			$dest = $uploadFolder . '/' . JFile::makeSafe($file['name']);
			if(JFile::upload($temp, $dest))
			{
				$uploadedFile = new stdClass();
				$uploadedFile->path = str_replace(JPATH_SITE, '', $dest);
				$uploadedFile->name = basename($dest,JFile::stripExt($dest));
				$uploadedFile->hash = hash('md5',$uploadedFile->name);
				$uploadedFiles[] = $uploadedFile;
			}
		}

		if($uploadedFiles)
		{
			$returnData->fieldId = $requestObject->fieldId;
			$returnData->result = "SUCCESS";
			$returnData->error = 0;
			$returnData->uploadedFiles = $uploadedFiles;
		}
	}

	return $returnData;
}


<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace HBAgency\ContentElement;

use HBAgency\Model\VideoArchive as VideoArchiveModel;


/**
 * Content element "Video".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Video extends \ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['CTE']['video'][0]) . ($this->video ? ' (ID '.$this->video.')' : '') . ' ###';
			$objTemplate->id = $this->id;
			$objTemplate->href = 'contao/main.php?do=article&amp;table=tl_content&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && ($this->invisible || ($this->start != '' && $this->start > time()) || ($this->stop != '' && $this->stop < time())))
		{
			return '';
		}
		
		$objModule 							= new \ModuleModel();
		$objModule->type 					= 'videoreader';
		$objModule->video 					= $this->video;
		$objModule->video_template			= $this->video_template;
		$objModule->video_archives			= serialize(static::getAllArchives());

		$strClass = \Module::findClass($objModule->type);

		if (!class_exists($strClass))
		{
			return '';
		}

		$objModule->typePrefix = 'ce_';
		$objModule = new $strClass($objModule, $this->strColumn);

		// Overwrite spacing and CSS ID
		$objModule->origSpace = $objModule->space;
		$objModule->space = $this->space;
		$objModule->origCssID = $objModule->cssID;
		$objModule->cssID = $this->cssID;

		$strTemp = \Input::get('videos');
		\Input::setGet('videos', $this->video);
		$strBuffer = $objModule->generate();
		\Input::setGet('videos', $strTemp);
		return $strBuffer;
	}


	/**
	 * Generate the content element
	 */
	protected static function getAllArchives()
	{
		$arrArchives = array();
		$objArchives = VideoArchiveModel::findAll();
		
		if ($objArchives !== null)
		{
			while ($objArchives->next())
			{
				$arrArchives[] = $objArchives->current()->id;
			}
		}
		
		return $arrArchives;
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}

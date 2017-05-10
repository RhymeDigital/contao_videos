<?php

/**
 * Video management for Contao Open Source CMS
 *
 * Copyright (C) 2014-2015 HB Agency
 *
 * @package    Video_Management
 * @link       http://www.hbagency.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace HBAgency\Backend\ContentElement\Video;

use HBAgency\Model\Video as VideoModel;

/**
 * Class Callbacks
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    Video_Management
 */
class Callbacks extends \Backend
{


	/**
	 * Get videos
	 *
	 * @access		public
	 * @return		array
	 */
	public function getVideos()
	{
		$arrReturn = array();
		$t = VideoModel::getTable();
		$objVideos = VideoModel::findAll(array('order'=>"$t.title"));
		
		if ($objVideos === null)
		{
			return $arrReturn;
		}
		
		while ($objVideos->next())
		{
			$arrReturn[strval($objVideos->current()->id)] = $objVideos->current()->title;
		}
		
		return $arrReturn;
	}
}

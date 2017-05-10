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
namespace HBAgency\Backend\Module\Video;

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
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		
		\System::loadLanguageFile(VideoModel::getTable());
		\Controller::loadDataContainer(VideoModel::getTable());
	}
	

	/**
	 * Get filterable video fields
	 *
	 * @access		public
	 * @return		array
	 */
	public function getVideoFilterFields()
	{
		$arrReturn = array('body'=>'Body');
		
		foreach ($GLOBALS['TL_DCA'][VideoModel::getTable()]['fields'] as $key=>$data)
		{
			if ($data['attributes'] && $data['attributes']['fe_filter'])
			{
				$arrReturn[$key] = $data['label'][0];
			}
		}
		
		return $arrReturn;
	}


	/**
	 * Get video lister modules
	 *
	 * @access		public
	 * @return		array
	 */
	public function getVideoListModules()
	{
		$arrReturn = array();
		
		$objModules = \ModuleModel::findAll();
		
		if ($objModules === null)
		{
			return $arrReturn;
		}
		
		while ($objModules->next())
		{
			if (stripos($objModules->current()->type, 'video') !== false)
			{
				$arrReturn[strval($objModules->current()->id)] = $objModules->current()->name;
			}
		}
		
		return $arrReturn;
	}


	/**
	 * Get all video archives and return them as array
	 * @return array
	 */
	public function getVideoArchives()
	{
		if (!$this->User->isAdmin && !is_array($this->User->video))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_video_archive ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->hasAccess($objArchives->id, 'video'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}


	/**
	 * Get all video reader modules and return them as array
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='videoreader' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}


	/**
	 * Hide the start day drop-down if not applicable
	 * @return string
	 */
	public function hideStartDay()
	{
		return '
  <script>
    var enableStartDay = function() {
      var e1 = $("ctrl_video_startDay").getParent("div");
      var e2 = $("ctrl_video_order").getParent("div");
      if ($("ctrl_video_format").value == "video_day") {
        e1.setStyle("display", "block");
        e2.setStyle("display", "none");
	  } else {
        e1.setStyle("display", "none");
        e2.setStyle("display", "block");
	  }
    };
    window.addEvent("domready", function() {
      if ($("ctrl_video_startDay")) {
        enableStartDay();
        $("ctrl_video_format").addEvent("change", enableStartDay);
      }
    });
  </script>';
	}


	/**
	 * Return all video templates as array
	 * @return array
	 */
	public function getVideoTemplates()
	{
		return $this->getTemplateGroup('video_');
	}
}

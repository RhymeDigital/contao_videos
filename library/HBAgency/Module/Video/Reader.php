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
 
namespace HBAgency\Module\Video;

use HBAgency\Module\Video as Video_Module;
use HBAgency\Model\VideoArchive as VideoArchiveModel;
use HBAgency\Model\Video as VideoModel;

/**
 * Class Reader
 *
 * Front end module "video reader".
 * @copyright  HB Agency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    Video_Management
 */
class Reader extends Video_Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_videoreader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['videoreader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		// Set the item from the auto_item parameter
		if (!isset($_GET['videos']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
		{
			\Input::setGet('videos', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no news item has been specified
		if (!\Input::get('videos'))
		{
			return '';
		}

		$this->video_archives = $this->sortOutProtected(deserialize($this->video_archives));

		// Return if there are no archives
		if (!is_array($this->video_archives) || empty($this->video_archives))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->videos = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		// Get the video item
		$objVideo = VideoModel::findPublishedByParentAndIdOrAlias(\Input::get('videos'), $this->video_archives);

		if ($objVideo === null)
		{
			$this->Template->videos = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('videos')) . '</p>';
			return;
		}

		$arrVideo = $this->parseVideo($objVideo);
		$this->Template->videos = $arrVideo;

		// Overwrite the page title (see #2853 and #4955)
		if ($objVideo->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objVideo->title));
		}

		// Overwrite the page description
		if ($objVideo->description != '')
		{
			$objPage->description = $this->prepareMetaDescription($objVideo->description);
		}
	}
}

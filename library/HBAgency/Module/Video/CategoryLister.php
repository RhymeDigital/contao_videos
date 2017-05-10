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
use HBAgency\Model\VideoCategory as VideoCategoryModel;
use HBAgency\Model\VideoCategoryVideo as VideoCategoryVideoModel;

/**
 * Class CategoryLister
 *
 * Front end module "video category filter".
 * @copyright  HB Agency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    Video_Management
 */
class CategoryLister extends Video_Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_videocategorylist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['videocategorylist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
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
		$arrAllVideos = array();
		$arrCategories = array();
		
		foreach ($this->video_archives as $archive)
		{
			$c = VideoCategoryModel::getTable();
			$objCategories = VideoCategoryModel::findByPid($archive, array('order'=>"$c.sorting"));
			
			if ($objCategories !== null)
			{
				while ($objCategories->next())
				{
					$v = VideoCategoryVideoModel::getTable();
					$objCategoryVideos = VideoCategoryVideoModel::findByPid($objCategories->current()->id, array('order'=>"$v.sorting"));
					
					if ($objCategoryVideos !== null)
					{
						$arrVideoIds = array();
						
						while ($objCategoryVideos->next())
						{
							$arrVideoIds[] = $objCategoryVideos->current()->video;
						}
						
						$objVideos = VideoModel::findPublishedByIds($arrVideoIds);
						
						if ($objVideos !== null)
						{
							$arrCategories[] = $this->parseVideos($objVideos);
							
							$objVideos->reset();
							
							while ($objVideos->next())
							{
								$arrAllVideos[] = array
								(
									'video'			=> $objVideos->current(),
									'category'		=> $objCategories->current(),
									'categoryvideo'	=> $objCategoryVideos->current(),
									'html'			=> $this->parseVideo($objVideos->current())
								);
							}
						}
					}
				}
			}
		}
		
		if (!empty($arrCategories))
		{
			$this->Template->categories = $arrCategories;
		}
		
		// todo: create a module setting that allows you to show by category or all videos
		if (!empty($arrAllVideos))
		{
			$this->Template->videos = $arrAllVideos;
		}

		$this->Template->archives = $this->video_archives;
	}
}

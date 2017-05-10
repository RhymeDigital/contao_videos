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
 * Class List
 *
 * Front end module "video list".
 * @copyright  HB Agency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    Video_Management
 */
class Lister extends Video_Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_videolist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['videolist'][0]) . ' ###';
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
		$offset = intval($this->skipFirst);
		$limit = null;

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Handle featured video
		if ($this->video_featured == 'featured')
		{
			$blnFeatured = true;
		}
		elseif ($this->video_featured == 'unfeatured')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$this->Template->videos = array();
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyVideoList'];

		// Get the total number of items
		$intTotal = VideoModel::countPublishedByPids($this->video_archives, $blnFeatured);
                
		if ($intTotal < 1)
		{
			return;
		}

		$total = $intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_v' . $this->id;
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;
			$skip = intval($this->skipFirst);

			// Overall limit
			if ($offset + $limit > $total + $skip)
			{
				$limit = $total + $skip - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}
		
		// Get the items
		if (isset($limit))
		{
			$objVideos = VideoModel::findPublishedByPids($this->video_archives, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objVideos = VideoModel::findPublishedByPids($this->video_archives, $blnFeatured, 0, $offset);
		}
                
		// Add the articles
		if ($objVideos !== null)
		{
			$this->Template->videos = $this->parseVideos($objVideos);
		}

		$this->Template->archives = $this->video_archives;
	}
}

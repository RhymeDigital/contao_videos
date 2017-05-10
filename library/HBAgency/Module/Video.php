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
 
namespace HBAgency\Module;

use HBAgency\Model\VideoArchive as VideoArchiveModel;
use HBAgency\Model\Video as VideoModel;

/**
 * Class Video
 *
 * Base class for video modules
 * @copyright  HB Agency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    Video_Management
 */
abstract class Video extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();
	
	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrDownloadCache = array();


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		// Store this ID so the NewsModel class and accompanying
		// hooks know which module is currently being generated.
		$GLOBALS['VIDEO']['LAST_GENERATED_MODULE'] = $this->id;
		
		return parent::generate();
	}

	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || empty($arrArchives))
		{
			return $arrArchives;
		}

		$this->import('FrontendUser', 'User');
		$objArchive = VideoArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				if ($objArchive->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objArchive->groups);

					if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
					{
						continue;
					}
				}

				$arrArchives[] = $objArchive->id;
			}
		}

		return $arrArchives;
	}


	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseVideo($objVideo, $strClass='', $intCount=0)
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->video_template);
		$objTemplate->setData($objVideo->row());

		$objTemplate->class = (($objVideo->cssClass != '') ? ' ' . $objVideo->cssClass : '') . $strClass;
		$objTemplate->videoHeadline = $objVideo->title;
		$objTemplate->subHeadline = $objVideo->subheadline;
		$objTemplate->hasSubHeadline = $objVideo->subheadline ? true : false;
		$objTemplate->link = $this->generateVideoUrl($objVideo);
		$objTemplate->archive = $objVideo->getRelated('pid');
		$objTemplate->count = $intCount; // see #5708
        $objTemplate->embed = $objVideo->current()->getEmbedCode(array());
        $objTemplate->cdnThumb = $objVideo->current()->getCDNThumbnail(array());
        
		// Clean the RTE output
		if ($objVideo->teaser != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$objTemplate->teaser = \String::toXhtml($objVideo->teaser);
			}
			else
			{
				$objTemplate->teaser = \String::toHtml5($objVideo->teaser);
			}

			$objTemplate->teaser = \String::encodeEmail($objTemplate->teaser);
		}

		$arrMeta = $this->getMetaFields($objVideo);

		// Add the meta information
		$objTemplate->hasMetaFields = !empty($arrMeta);
		$objTemplate->author = $arrMeta['author'];

		// Add the video
		if ($objVideo->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objVideo->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objVideo->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrVideo = $objVideo->row();

				// Override the default image size
				if ($this->imgSize != '')
				{
					$size = deserialize($this->imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrVideo['size'] = $this->imgSize;
					}
				}

				$arrVideo['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrVideo);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['parseVideos']) && is_array($GLOBALS['TL_HOOKS']['parseVideos']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseVideos'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objTemplate, $objVideo->row(), $this);
			}
		}

		return $objTemplate->parse();
	}


	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseVideos($objVideos)
	{
		$limit = $objVideos->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrVideos = array();

		while ($objVideos->next())
		{
			$arrVideos[] = $this->parseVideo($objVideos, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		}

		return $arrVideos;
	}


	/**
	 * Return the meta fields of a video as array
	 * @param object
	 * @return array
	 */
	protected function getMetaFields($objVideo)
	{
		$meta = deserialize($this->video_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		global $objPage;
		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'author':
					if (($objAuthor = $objVideo->getRelated('author')) !== null)
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
					}
					break;
			}
		}

		return $return;
	}


	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @return string
	 */
	protected function generateVideoUrl($objItem)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Link to the jumpTo page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objPage = \PageModel::findByPk($this->jumpTo);

			if ($objPage === null)
			{
				$objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpTo);
				
				if ($objPage === null)
				{
					global $objPage;
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
				}
				else
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
				}
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}
			
		}

		return self::$arrUrlCache[$strCacheKey];
	}
	
}

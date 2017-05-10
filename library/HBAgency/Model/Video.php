<?php

/**
 * Video management for Contao Open Source CMS
 *
 * Copyright (C) 2014-2015 HB Agency
 *
 * @package    Videos
 * @link       http://www.hbagency.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace HBAgency\Model;

use HBAgency\Interfaces\BaseVideo;

/**
 * Reads and writes videos
 *
 * @package   Models
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    Video_Management
 */
class Video extends \Model implements BaseVideo
{
	
	/**
	 * Current method being called
	 * @var string
	 */
	protected static $strCurrentMethod = '';

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_video';
	
	
	/**
	 * Cache of video models
	 * @var array
	 */
	private static $arrModelCache = array();
	

	/**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getEmbedCode(array $arrConfig)
    {
        $strEmbed = '';
        $varVideoModel = $this->getVideoModel();

		if($varVideoModel)
		{
    		$strEmbed = $varVideoModel->getEmbedCode($arrConfig);
		}
		
		return $strEmbed;
    }
    
    /**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getCDNThumbnail(array $arrConfig)
    {
        $strThumb = '';
        
        $varVideoModel = $this->getVideoModel();

		if($varVideoModel)
		{
    		$strThumb = $varVideoModel->getCDNThumbnail($arrConfig);
		}
		
		return $strThumb;
    }
    
    /**
     * Return a video model variation
     * @return  string
     */
    protected function getVideoModel()
    {
        $strCacheKey = 'videoid_' . $this->id;
        
        if(!isset(self::$arrModelCache[$strCacheKey]))
        {
            $strVideoClass = $GLOBALS['VIDEOS'][$this->type];
            if (class_exists($strVideoClass))
            {
	            $objVideoModel = new $strVideoClass();
	    		$objVideoModel->setRow($this->row());
	    		self::$arrModelCache[$strCacheKey] = $objVideoModel;
            }
        }
        
        return self::$arrModelCache[$strCacheKey] ?: false;
    }


	/**
	 * Find records and return the model or model collection
	 *
	 * Supported options:
	 *
	 * * column: the field name
	 * * value:  the field value
	 * * limit:  the maximum number of rows
	 * * offset: the number of rows to skip
	 * * order:  the sorting order
	 * * eager:  load all related records eagerly
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return \Model|\Model\Collection|null A model, model collection or null if the result is empty
	 */
	protected static function find(array $arrOptions)
	{
        // !HOOK: custom actions
        if (isset($GLOBALS['TL_HOOKS']['findVideos']) && is_array($GLOBALS['TL_HOOKS']['findVideos'])) {
            foreach ($GLOBALS['TL_HOOKS']['findVideos'] as $callback) {
                $objCallback = \System::importStatic($callback[0]);
                $objCallback->$callback[1]($arrOptions, static::$strCurrentMethod);
            }
        }
        
        return parent::find($arrOptions);
	}


	/**
	 * Return the number of records matching certain criteria
	 *
	 * @param mixed $strColumn  An optional property name
	 * @param mixed $varValue   An optional property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return integer The number of matching rows
	 */
	public static function countBy($strColumn=null, $varValue=null, array $arrOptions=array())
	{
        // !HOOK: custom actions
        if (isset($GLOBALS['TL_HOOKS']['countByVideos']) && is_array($GLOBALS['TL_HOOKS']['countByVideos'])) {
            foreach ($GLOBALS['TL_HOOKS']['countByVideos'] as $callback) {
                $objCallback = \System::importStatic($callback[0]);
                $objCallback->$callback[1]($strColumn, $varValue, $arrOptions, static::$strCurrentMethod);
            }
        }
        
		return parent::countBy($strColumn, $varValue, $arrOptions);
	}
    

	/**
	 * Find published video items by their parent ID and ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The NewsModel or null if there are no video
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		static::$strCurrentMethod = 'findPublishedByParentAndIdOrAlias';
		$varBuffer = static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published video items by their parent ID
	 *
	 * @param array   $arrPids     An array of video archive IDs
	 * @param boolean $blnFeatured If true, return only featured video, if false, return only unfeatured video
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no video
	 */
	public static function findPublishedByPids($arrPids, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		// Never return unpublished elements in the back end, so they don't end up in the RSS feed
		if (!BE_USER_LOGGED_IN || TL_MODE == 'BE')
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.date DESC, $t.time DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		static::$strCurrentMethod = 'findPublishedByPids';
		$varBuffer = static::findBy($arrColumns, null, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Count published video items by their parent ID
	 *
	 * @param array   $arrPids     An array of video archive IDs
	 * @param boolean $blnFeatured If true, return only featured video, if false, return only unfeatured video
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of video items
	 */
	public static function countPublishedByPids($arrPids, $blnFeatured=null, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		static::$strCurrentMethod = 'countPublishedByPids';
		$varBuffer = static::countBy($arrColumns, null, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published video items with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The video archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no video
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting ASC";
		}

		static::$strCurrentMethod = 'findPublishedDefaultByPid';
		$varBuffer = static::findBy($arrColumns, $intPid, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find published video items by their parent ID
	 *
	 * @param integer $intId      The video archive ID
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no video
	 */
	public static function findPublishedByPid($intId, $intLimit=0, array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting ASC";
		}

		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		static::$strCurrentMethod = 'findPublishedByPid';
		$varBuffer = static::findBy($arrColumns, $intId, $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find all published video items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of video archive IDs
	 * @param integer $intLimit   An optional limit
	 * @param integer $intOffset  An optional offset
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no video
	 */
	public static function findPublishedFromToByPids($intFrom, $intTo, $arrPids, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.sorting ASC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		static::$strCurrentMethod = 'findPublishedFromToByPids';
		$varBuffer = static::findBy($arrColumns, array($intFrom, $intTo), $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}


	/**
	 * Find all published video items of a certain period of time by their parent ID
	 *
	 * @param array   $arrPids    An array of video IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no video
	 */
	public static function findPublishedByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.title ASC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		static::$strCurrentMethod = 'findPublishedByIds';
		$varBuffer = static::findBy($arrColumns, array($intFrom, $intTo), $arrOptions);
		static::$strCurrentMethod = '';
		return $varBuffer;
	}
}

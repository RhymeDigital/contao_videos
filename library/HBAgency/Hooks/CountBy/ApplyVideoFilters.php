<?php

/**
 * Copyright (C) 2014 HB Agency
 * 
 * @author		Blair Winans <bwinans@hbagency.com>
 * @author		Adam Fisher <afisher@hbagency.com>
 * @link		http://www.hbagency.com
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace HBAgency\Hooks\CountBy;

use HBAgency\Model\Video as VideoModel;


class ApplyVideoFilters extends \Frontend
{

	/**
	 * Store the IDs of items that have been obtained by searching for their body text
	 * @var array
	 */
	protected static $arrCachedBodyIds = array();
	

	/**
	 * Apply filter values to the custom video model
	 * 
	 * Namespace:	HBAgency\Model
	 * Class:		VideoModel
	 * Method:		countBy
	 * Hook:		$GLOBALS['TL_HOOKS']['countByVideos']
	 *
	 * @access		public
	 * @param		array
	 * @param		string
	 * @return		void
	 */
	public static function run($strColumn, $varValue, &$arrOptions, $strCurrentMethod)
	{
		if (!static::validateFilterAndLister())
		{
			return;
		}
		
		$arrFilters = static::getFilters();
		
		if (!empty($arrFilters))
		{
			$t = VideoModel::getTable();
			
			\System::loadLanguageFile($t);
			\Controller::loadDataContainer($t);
			
			foreach ($arrFilters as $key=>$val)
			{
				$strFilterType = $GLOBALS['TL_DCA'][$t]['fields'][$key]['attributes']['fe_filter_type'] ?: 'rgxp';
				
				if ($key == 'body')
				{
					$arrOptions['column'][] = "$t.teaser REGEXP ?";
					$arrOptions['value'][] = $val;
					continue;
				}
				
				if (is_array($val) && !empty($val))
				{
					$strWhere = "(";
					foreach ($val as $i=>$opt)
					{
						if ($i != 0)
						{
							$strWhere .= " OR ";
						}
						
						list($key, $val, $strWhere, $strFilterType, $arrOptions) = static::getWhere($key, $val, $strWhere, $strFilterType, $arrOptions);
						
						$arrOptions['value'][] = $opt;
					}
					$strWhere .= ")";
					
					$arrOptions['column'][] = $strWhere;
					
				}
				else
				{	
					$strWhere = "";
					list($key, $val, $strWhere, $strFilterType, $arrOptions) = static::getWhere($key, $val, $strWhere, $strFilterType, $arrOptions);
					$arrOptions['column'][] = $strWhere;
					$arrOptions['value'][] = $val;
				}
			}
		}
	}
	
	protected static function getWhere($key, $val, $strWhere, $strFilterType, $arrOptions)
	{
		$t = VideoModel::getTable();
		$strWhere .= "$t.$key ";
		switch ($strFilterType)
		{
			// todo: add things like gte, lte, etc.
			case 'equals':
				$strWhere .= "=?";
				break;
				
			case 'rgxp':
			case 'rgxpstr':
				$strWhere .= "REGEXP ?";
				break;
				
			case 'rgxpint':
				$strWhere .= "REGEXP CONCAT(':', ?, ';')";
				break;
				
			case 'rgxpintstr':
				$strWhere .= "REGEXP CONCAT('\"', ?, '\"')";
				break;
			
			default:
		        // !HOOK: custom...
		        if (isset($GLOBALS['TL_HOOKS']['videoFiltersGetWhere']) && is_array($GLOBALS['TL_HOOKS']['videoFiltersGetWhere'])) {
		            foreach ($GLOBALS['TL_HOOKS']['videoFiltersGetWhere'] as $callback) {
		                $objCallback = \System::importStatic($callback[0]);
		                list($key, $val, $strWhere, $strFilterType, $arrOptions) = $objCallback->$callback[1]($key, $val, $strWhere, $strFilterType, $arrOptions);
		            }
		        }
				break;
		}
		
		return array($key, $val, $strWhere, $strFilterType, $arrOptions);
	}
	
	
	protected static function getFilters()
	{
		$arrFilters = array();
		$arrGetKeys = array_keys((array)$_GET);
		
		foreach ((array)$arrGetKeys as $key)
		{
			if ((\Database::getInstance()->fieldExists($key, VideoModel::getTable()) || $key == 'body') && \Input::get($key))
			{
				if (is_array(\Input::get($key)))
				{
					$arrValues = array();
					
					foreach (\Input::get($key) as $val)
					{
						if ($val)
						{
							$arrValues[] = $val;
						}
					}
					
					if (!empty($arrValues))
					{
						$arrFilters[$key] = $arrValues;
					}
				}
				else
				{
					$arrFilters[$key] = \Input::get($key);
				}
			}
		}
		
		return $arrFilters;
	}
	
	
	protected static function validateFilterAndLister()
	{
		// See if we have a "last generated module" ID, lists in the GET params, and that the last generated module is one of the lists
		if (!isset($GLOBALS['VIDEO']['LAST_GENERATED_MODULE']) ||
			!$GLOBALS['VIDEO']['LAST_GENERATED_MODULE'] || 
			!\Input::get('lists') || 
			!in_array($GLOBALS['VIDEO']['LAST_GENERATED_MODULE'], trimsplit(',', \Input::get('lists')))
		)
		{
			return false;
		}

		$objRow = \ModuleModel::findByPk($GLOBALS['VIDEO']['LAST_GENERATED_MODULE']);

		// See if we have a row, and check the visibility (see #6311)
		if ($objRow === null || !\Controller::isVisibleElement($objRow))
		{
			return false;
		}
		
		return true;
	}
	
}

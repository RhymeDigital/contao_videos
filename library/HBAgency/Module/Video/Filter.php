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
 * Class Filter
 *
 * Front end module "video filter".
 * @copyright  HB Agency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    Video_Management
 */
class Filter extends Video_Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_videofilter';

	/**
	 * Selected filters
	 * @var array
	 */
	protected $arrFilters = array();


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['videofilter'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		\System::loadLanguageFile(VideoModel::getTable());
		\Controller::loadDataContainer(VideoModel::getTable());

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;
		$arrFilters = array();
		$arrFields = deserialize($this->video_filterfields, true);
		
		if (!empty($arrFields))
		{
			foreach ($arrFields as $strField)
			{
				$varValue = \Input::get($strField) ?: null;
				
				if ($strField == 'body')
				{
					$objWidget = new \FormTextField(array
					(
						'name'			=> $strField,
						'id'			=> $strField,
						'value'			=> $varValue,
					));
				}
				else
				{
					$GLOBALS['TL_DCA'][VideoModel::getTable()]['fields'][$strField]['eval']['mandatory'] = false;
					$GLOBALS['TL_DCA'][VideoModel::getTable()]['fields'][$strField]['eval']['required'] = false;
					$GLOBALS['TL_DCA'][VideoModel::getTable()]['fields'][$strField]['eval']['includeBlankOption'] = true;
					
					$objWidget = static::getFrontendWidgetFromDca(VideoModel::getTable(), $strField, $varValue);
				}
				
		        // !HOOK: custom actions
		        if (isset($GLOBALS['TL_HOOKS']['getVideoFilterWidget']) && is_array($GLOBALS['TL_HOOKS']['getVideoFilterWidget'])) {
		            foreach ($GLOBALS['TL_HOOKS']['getVideoFilterWidget'] as $callback) {
		                $objCallback = \System::importStatic($callback[0]);
		                $objWidget = $objCallback->$callback[1]($objWidget, static::$strCurrentMethod);
		            }
		        }
				
				$strBuffer = $objWidget->generate();
				
				$arrFilters[$strField] = $strBuffer;
				$this->Template->{'filter_'.$strField} = $strBuffer;
			}
		}
		
		$objJumpTo = $this->jumpTo ? \PageModel::findByPk($this->jumpTo) : \PageModel::findByPk($objPage->id);
		
		$this->Template->action					= $this->generateFrontendUrl($objJumpTo->row());
		$this->Template->filters				= $arrFilters;
		$this->Template->targetlistmodules 		= implode(',', deserialize($this->video_targetlistmodules, true));
		$this->Template->submit_label			= $GLOBALS['TL_LANG']['MSC']['videofilter_submit'];
	}
	
	
	
	/**
	 * Create a widget using the table, field, and optional current value
	 */
	protected static function getFrontendWidgetFromDca($strTable, $strField, $varValue=null)
	{
		$strClass = $GLOBALS['TL_FFL'][$GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType']];

		if (class_exists($strClass))
		{
			return new $strClass(\Widget::getAttributesFromDca($GLOBALS['TL_DCA'][$strTable]['fields'][$strField], $strField, $varValue, $strField, $strTable));
		}
		
		return null;
	}
}

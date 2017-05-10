<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace HBAgency\Backend\VideoCategoryVideo;

use HBAgency\Model\VideoArchive;
use HBAgency\Model\VideoCategory;
use HBAgency\Model\VideoCategoryVideo;

class FieldOptions extends \Backend
{

	/**
	 * Get video for "Category videos" field options
	 *
	 * @access		public
	 * @return		array
	 */
	public function getCategoryVideoOptions($dc=null)
	{
		$intPid = 0;
		
		if ($dc && $dc->activeRecord && $dc->activeRecord->pid)
		{
			$objCategory = VideoCategory::findByPk($dc->activeRecord->pid);
			
			if ($objCategory !== null)
			{
				$objArchive = VideoArchive::findByPk($objCategory->pid);
				
				if ($objArchive !== null)
				{
					$intPid = $objArchive->id;
				}
			}
		}
		$arrReturn = array();
		$objResult = \Database::getInstance()->prepare("SELECT id, title FROM tl_video".($intPid ? " WHERE pid=".$intPid : "")." ORDER BY title")->executeUncached();
		
		if ($objResult->numRows)
		{
			while ($objResult->next())
			{
				$arrReturn[$objResult->id] = $objResult->title;
			}
		}
		
		return $arrReturn;
	}
	
}

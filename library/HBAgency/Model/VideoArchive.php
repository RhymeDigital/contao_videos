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


/**
 * Reads and writes video archives
 *
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    Video_Management
 */
class VideoArchive extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_video_archive';

}

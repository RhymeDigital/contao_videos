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
namespace HBAgency\Model\Video;

use HBAgency\Interfaces\BaseVideo;
use HBAgency\Model\Video as BaseVideoModel;

/**
 * Reads and writes videos
 *
 * @package   Models
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    Video_Management
 */
class Vimeo extends BaseVideoModel implements BaseVideo
{
    
    /**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getEmbedCode(array $arrConfig)
    {
        return '<iframe width="560" height="315" src="//player.vimeo.com/video/'.$this->video_id.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }
    
    /**
     * Return the URL for the default CDN hosted Thumbnail
     * @param   array
     * @return  string
     */
    public function getCDNThumbnail(array $arrConfig)
    {
    	// Use the XML method: http://vimeo.com/api/v2/video/<video_id>.xml
    	// As well as doctrine/cache (Doctrine/Common/Cache/FileCache.php)
    	//<videos> 
		//	<video> 
		//		[skipped]
		//			<thumbnail_small>http://ts.vimeo.com.s3.amazonaws.com/235/662/23566238_100.jpg</thumbnail_small> 
		//			<thumbnail_medium>http://ts.vimeo.com.s3.amazonaws.com/235/662/23566238_200.jpg</thumbnail_medium> 
		//			<thumbnail_large>http://ts.vimeo.com.s3.amazonaws.com/235/662/23566238_640.jpg</thumbnail_large> 
		//		[skipped]
		//	<video> 
		//</videos>
        return '';//'//img.youtube.com/vi/'.$this->video_id.'/default.jpg';
    }

}
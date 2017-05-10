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
class Viddler extends BaseVideoModel implements BaseVideo
{
    
    /**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getEmbedCode(array $arrConfig)
    {
        return '<iframe width="560" height="315" src="//www.viddler.com/embed/'.$this->video_id.'/?f=1&player=full" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>';
    }
    
    /**
     * Return the URL for the default CDN hosted Thumbnail
     * @param   array
     * @return  string
     */
    public function getCDNThumbnail(array $arrConfig)
    {
    	// todo: set this up
        return '';
    }

}
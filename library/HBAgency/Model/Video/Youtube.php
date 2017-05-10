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
class Youtube extends BaseVideoModel implements BaseVideo
{
    
    /**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getEmbedCode(array $arrConfig)
    {
        return '<iframe width="560" height="315" src="//www.youtube.com/embed/'.$this->video_id.'" frameborder="0" allowfullscreen></iframe>';
    }
    
    /**
     * Return the URL for the default CDN hosted Thumbnail
     * @param   array
     * @return  string
     */
    public function getCDNThumbnail(array $arrConfig)
    {
        return '//img.youtube.com/vi/'.$this->video_id.'/default.jpg';
    }

}
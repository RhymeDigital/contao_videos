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

namespace HBAgency\Interfaces;


/**
 * BaseVideo is the interface for a video object
 */
interface BaseVideo
{
    /**
     * Generate a video embed script
     * @param   array
     * @return  string
     */
    public function getEmbedCode(array $arrConfig);
    
    
    /**
     * Return the URL for the default CDN hosted Thumbnail
     * @param   array
     * @return  string
     */
    public function getCDNThumbnail(array $arrConfig);
    
}

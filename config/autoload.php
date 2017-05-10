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
 * Register PSR-0 namespace
 */
NamespaceClassLoader::add('HBAgency', 'system/modules/videos/library');


/**
 * Register classes outside the namespace folder
 */
NamespaceClassLoader::addClassMap(array
(
    // TBD
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    //Modules
    'mod_videolist'       		=> 'system/modules/videos/templates/modules',
    'mod_videoreader'     		=> 'system/modules/videos/templates/modules',
    'mod_videofilter'     		=> 'system/modules/videos/templates/modules',
    'mod_videocategorylist'		=> 'system/modules/videos/templates/modules',
    
    //Videos
    'video_full'          		=> 'system/modules/videos/templates/video',
    'video_short'         		=> 'system/modules/videos/templates/video',
    'video_only'         		=> 'system/modules/videos/templates/video',
));

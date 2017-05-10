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
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 10, array
(
	'video' => array
	(
		'tables'      => array('tl_video_archive', 'tl_video', 'tl_video_config', 'tl_video_category', 'tl_video_category_video'),
		'icon'        => 'system/modules/videos/assets/img/icon.png',
		'javascript'  => 'system/modules/videos/assets/js/videos.js'
	)
));


/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'video' => array
	(
		'videolist'    			=> 'HBAgency\Module\Video\Lister',
		'videoreader'  			=> 'HBAgency\Module\Video\Reader',
		'videofilter'			=> 'HBAgency\Module\Video\Filter',
		'videocategorylist'    	=> 'HBAgency\Module\Video\CategoryLister',
	)
));


/**
 * Content elements
 */
$GLOBALS['TL_CTE']['videos']['video'] 		= 'HBAgency\ContentElement\Video';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['findVideos'][]		= array('HBAgency\Hooks\Find\ApplyVideoFilters', 'run');
$GLOBALS['TL_HOOKS']['countByVideos'][]		= array('HBAgency\Hooks\CountBy\ApplyVideoFilters', 'run');


/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_video']            		= 'HBAgency\Model\Video';
$GLOBALS['TL_MODELS']['tl_video_archive']    		= 'HBAgency\Model\VideoArchive';
$GLOBALS['TL_MODELS']['tl_video_category'] 	 		= 'HBAgency\Model\VideoCategory';
$GLOBALS['TL_MODELS']['tl_video_category_video'] 	= 'HBAgency\Model\VideoCategoryVideo';


/**
 * Video types
 */
$GLOBALS['VIDEOS'] = array
(
    'youtube'   => 'HBAgency\Model\Video\Youtube',
    'viddler'   => 'HBAgency\Model\Video\Viddler',  
    'vimeo'     => 'HBAgency\Model\Video\Vimeo', 
);


/**
 * Register hook to add video items to the indexer
 */
//$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('HBAgency\Frontend\Video', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['executePostActions'][] = array('HBAgency\Hooks\ExecutePostActions\ToggleFeaturedVideo', 'run');


/**
 * Register the auto_item keywords
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'videos';


/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'video';
$GLOBALS['TL_PERMISSIONS'][] = 'videop';

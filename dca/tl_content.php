<?php

/**
 * Document management for Contao Open Source CMS
 *
 * Copyright (C) 2014-2015 HB Agency
 *
 * @package    Document_Management
 * @link       http://www.hbagency.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['video'] = str_replace(array(',type',',module'), array(',type,headline',',video,video_template'), $GLOBALS['TL_DCA']['tl_content']['palettes']['module']);



/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['video'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['video'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('HBAgency\Backend\ContentElement\Video\Callbacks', 'getVideos'),
	'eval'                    => array('tl_class'=>'w50','includeBlankOption'=>true, 'chosen'=>true),
	'sql'                     => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['video_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['video_template'],
	'default'                 => 'video_latest',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getVideoTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
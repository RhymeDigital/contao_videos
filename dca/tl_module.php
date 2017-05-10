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


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['videocategorylist']    = '{title_legend},name,headline,type;{config_legend},jumpTo,video_archives;{template_legend:hide},video_metaFields,video_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['videolist']    = '{title_legend},name,headline,type;{config_legend},jumpTo,video_archives,numberOfItems,video_featured,perPage,skipFirst;{template_legend:hide},video_metaFields,video_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['videoreader']  = '{title_legend},name,headline,type;{config_legend},video_archives;{template_legend:hide},video_metaFields,video_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['videofilter']	= '{title_legend},name,headline,type;{config_legend},video_filterfields,video_targetlistmodules;{redirect_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['video_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getVideoArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_featured'],
	'default'                 => 'all_items',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_items', 'featured', 'unfeatured'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_jumpToCurrent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_jumpToCurrent'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('hide_module', 'show_current', 'all_items'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_readerModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_readerModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getReaderModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_metaFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_metaFields'],
	'default'                 => array('date', 'author'),
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('date'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_template'],
	'default'                 => 'video_latest',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getVideoTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_format'],
	'default'                 => 'video_month',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('video_day', 'video_month', 'video_year'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'wizard' => array
	(
		array('HBAgency\Backend\Module\Video\Callbacks', 'hideStartDay')
	),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_startDay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_startDay'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 1, 2, 3, 4, 5, 6),
	'reference'               => &$GLOBALS['TL_LANG']['DAYS'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_order'],
	'default'                 => 'descending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_showQuantity'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_showQuantity'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_filterfields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_filterfields'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getVideoFilterFields'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['video_targetlistmodules'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['video_targetlistmodules'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('HBAgency\Backend\Module\Video\Callbacks', 'getVideoListModules'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);


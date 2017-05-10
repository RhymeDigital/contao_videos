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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_video']['title']        = array('Title', 'Please enter the video title.');
$GLOBALS['TL_LANG']['tl_video']['type']         = array('Video type', 'Select the video type for this video.');
$GLOBALS['TL_LANG']['tl_video']['alias']        = array('Video alias', 'The video alias is a unique reference to the video which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_video']['video_id']     = array('Video ID', 'Enter the unique ID for this video.');
$GLOBALS['TL_LANG']['tl_video']['subheadline']  = array('Subheadline', 'Please enter the video subheadline.');
$GLOBALS['TL_LANG']['tl_video']['date']         = array('Date', 'Please enter the date according to the global date format.');
$GLOBALS['TL_LANG']['tl_video']['time']         = array('Time', 'Please enter the time according to the global time format.');
$GLOBALS['TL_LANG']['tl_video']['description']  = array('Video text', 'Here you can enter the video text.');
$GLOBALS['TL_LANG']['tl_video']['cssClass']     = array('CSS class', 'Here you can enter one or more classes.');
$GLOBALS['TL_LANG']['tl_video']['featured']     = array('Feature item', 'Show the video item in a featured video list.');
$GLOBALS['TL_LANG']['tl_video']['published']    = array('Publish item', 'Make the video item publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_video']['start']        = array('Show from', 'Do not show the video item on the website before this day.');
$GLOBALS['TL_LANG']['tl_video']['stop']         = array('Show until', 'Do not show the video item on the website on and after this day.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_video']['title_legend']     = 'Title and author';
$GLOBALS['TL_LANG']['tl_video']['date_legend']      = 'Date and time';
$GLOBALS['TL_LANG']['tl_video']['teaser_legend']    = 'Subheadline and teaser';
$GLOBALS['TL_LANG']['tl_video']['text_legend']      = 'Document text';
$GLOBALS['TL_LANG']['tl_video']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_video']['source_legend']    = 'Source settings';
$GLOBALS['TL_LANG']['tl_video']['publish_legend']   = 'Publish settings';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_video']['new']        = array('New video', 'Create a new video.');
$GLOBALS['TL_LANG']['tl_video']['show']       = array('Document details', 'Show the details of video ID %s');
$GLOBALS['TL_LANG']['tl_video']['edit']       = array('Edit video', 'Edit video ID %s');
$GLOBALS['TL_LANG']['tl_video']['copy']       = array('Duplicate video', 'Duplicate video ID %s');
$GLOBALS['TL_LANG']['tl_video']['cut']        = array('Move video', 'Move video ID %s');
$GLOBALS['TL_LANG']['tl_video']['delete']     = array('Delete video', 'Delete video ID %s');
$GLOBALS['TL_LANG']['tl_video']['toggle']     = array('Publish/unpublish video', 'Publish/unpublish video ID %s');
$GLOBALS['TL_LANG']['tl_video']['feature']    = array('Feature/unfeature video', 'Feature/unfeature video ID %s');
$GLOBALS['TL_LANG']['tl_video']['editheader'] = array('Edit archive settings', 'Edit the archive settings');
$GLOBALS['TL_LANG']['tl_video']['pasteafter'] = array('Paste into this archive', 'Paste after video ID %s');
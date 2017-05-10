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
 
namespace HBAgency\Hooks\ExecutePostActions;

/**
 * Class ToggleFeaturedDoc
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    Video_Management
 */
class ToggleFeaturedVideo extends \Backend
{
    
    public function run($strAction, $dc)
    {
        if($strAction=='toggleFeaturedVideo')
        {
            $this->import('HBAgency\Backend\Video\Callbacks', 'Callbacks');
            $this->Callbacks->toggleFeatured(\Input::post('id'), ((\Input::post('state') == 1) ? true : false));
        }
    }
    
}
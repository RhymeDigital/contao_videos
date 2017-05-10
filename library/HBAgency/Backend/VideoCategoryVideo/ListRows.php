<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace HBAgency\Backend\VideoCategoryVideo;

use HBAgency\Model\Video as VideoModel;


class ListRows extends \Backend
{

    /**
     * Add an image to each record
     * @param array
     * @param string
     * @return string
     */
    public function generate($row)
    {
        $objVideo = VideoModel::findByPk($row['video']);
        if ($objVideo === null) return '';
        
        $strVideo = $objVideo->title;

        $strBuffer = '<div class="cte_type" style="color:#666966"><strong>' . $strVideo . '</strong>&nbsp;<em>[ID ' . $objVideo->id . ']</em></div>';

        return $strBuffer;
    }
}
<?php

/**
 * Copyright (C) 2015 Rhyme Digital, LLC.
 * 
 * @author		Blair Winans <blair@rhyme.digital>
 * @author		Adam Fisher <adam@rhyme.digital>
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace HBAgency\Backend\VideoCategory;

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
        $strBuffer = '<div class="" style="color:#666966; font-size:13px;">' . $row['title'] . '</div>';

        return $strBuffer;
    }
}
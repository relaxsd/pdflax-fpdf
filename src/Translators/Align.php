<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Align
{

    /**
     * @param Style|null $style
     * @param string     $default
     *
     * @return string
     */
    public static function translate($style, $default = '')
    {
        switch ($style->getValue('align')) {
            case 'left':
                return 'L';
            case 'center':
                return 'C';
            case 'right':
                return 'R';
            default:
                return $default;
        }
    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Align
{

    /**
     * @param Style|null $style
     *
     * @return string
     */
    public static function translate($style)
    {
        switch (Style::value($style, 'align')) {
            case 'left':
                return 'L';
            case 'center':
                return 'C';
            case 'right':
                return 'R';
            default:
                return '';
        }
    }

}

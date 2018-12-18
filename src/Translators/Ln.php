<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Ln
{

    /**
     * @param Style $style
     * @param int   $default  E.g. 0 for Cell, 2 for MultiCell
     *
     * @return int
     */
    public static function translate($style, $default = 0)
    {
        return Style::value($style, 'ln', $default);
    }

}

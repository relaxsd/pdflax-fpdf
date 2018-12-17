<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Ln
{

    /**
     * @param Style $style
     *
     * @return int
     */
    public static function translate($style)
    {
        return Style::value($style, 'ln', 0);
    }

}

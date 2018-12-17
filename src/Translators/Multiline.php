<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Multiline
{

    /**
     * @param Style   $style
     *
     * @return boolean
     */
    public static function translate($style)
    {
        return !!Style::value($style, 'multiline', false);
    }

}

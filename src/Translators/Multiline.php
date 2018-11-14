<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Multiline
{

    /**
     * @param Style   $style
     * @param boolean $default
     *
     * @return boolean
     */
    public static function translate($style, $default = false)
    {
        return !!$style->getValue('multiline', $default);
    }

}

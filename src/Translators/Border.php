<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Style;

class Border
{

    /**
     * @param Style $style
     * @param float $default
     *
     * @return float
     */
    public static function translate($style, $default = 0.0)
    {
        return $style->getValue('border', $default);
    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Multiline;
use Relaxsd\Stylesheets\Style;

class MultilineTranslator
{

    /**
     * @param Style $style
     *
     * @return boolean
     */
    public static function translate($style)
    {
        return !!Style::value($style, Multiline::ATTRIBUTE, false);
    }

}

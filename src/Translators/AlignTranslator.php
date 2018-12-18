<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Align;
use Relaxsd\Stylesheets\Style;

class AlignTranslator
{

    /**
     * @param Style|null $style
     *
     * @return string
     */
    public static function translate($style)
    {
        switch (Style::value($style, Align::ATTRIBUTE)) {
            case Align::LEFT:
                return 'L';
            case Align::CENTER:
                return 'C';
            case Align::RIGHT:
                return 'R';
            default:
                return '';
        }
    }

}

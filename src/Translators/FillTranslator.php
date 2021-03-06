<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\Fill;
use Relaxsd\Stylesheets\Attributes\FillColor;
use Relaxsd\Stylesheets\Style;

class FillTranslator
{

    /**
     * @param Style $style
     *
     * @return integer
     */
    public static function translate($style)
    {
        return Style::value($style, Fill::ATTRIBUTE) ? 1 : 0;
    }

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style)
    {

        if (self::translate($style)) {

            // Black is the FPdf default
            list($r, $g, $b) = Color::toRGB(Style::value($style, FillColor::ATTRIBUTE, 'black'));
            $fpdf->SetFillColor($r, $g, $b);

        }

    }

}

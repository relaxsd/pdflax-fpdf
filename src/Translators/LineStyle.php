<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\BorderColor;
use Relaxsd\Stylesheets\Attributes\BorderWidth;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Style;

class LineStyle
{

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        list($r, $g, $b) = Color::toRGB(Style::value($style, BorderColor::ATTRIBUTE, 'black'));
        $fpdf->SetDrawColor($r, $g, $b);

        // TODO: Convert string to float etc
        // 0.2 mm is the FPdf default
        $fpdf->SetLineWidth(Style::value($style, BorderWidth::ATTRIBUTE, 0.2));

    }

}

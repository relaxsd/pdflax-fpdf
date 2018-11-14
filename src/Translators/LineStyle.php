<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Color;
use Relaxsd\Stylesheets\Style;

class LineStyle
{

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        if (!isset($style)) return;

        if ($style->hasValue('line-width')) {
            // TODO: Convert string to float etc
            $fpdf->SetLineWidth($style->getValue('line-width'));
        }

        if ($style->hasValue('line-color')) {
            list($r, $g, $b) = Color::toRGB($style->getValue('line-color'));
            $fpdf->SetDrawColor($r, $g, $b);
        }

    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Style;

class RectStyle
{

    /**
     * @param Style|null $style
     *
     * @return string
     */
    public static function translate($style)
    {
        if (!isset($style)) {
            return '';
        }

        $result = '';

        if ($style->hasValue('border-color') || $style->hasValue('border-size')) {
            $result .= 'D';
        }

        if ($style->hasValue('fill-color')) {
            $result .= 'F';
        }

        return $result;
    }

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        $rectStyle = self::translate($style);

        // FPdf draws a border for 'D' (draw) and '' (the default)
        if ($rectStyle == '' || str_contains($rectStyle, 'D')) {

            list($r, $g, $b) = Color::toRGB(Style::value($style, 'border-color', 'black'));
            $fpdf->SetDrawColor($r, $g, $b);

            // TODO: Convert string to float etc
            // 0.2 mm is the FPdf default
            $fpdf->SetLineWidth(Style::value($style, 'border-width', 0.2));

        }

        // FPdf draws a border for 'F' only
        if (str_contains($rectStyle, 'F')) {

            // Black is the FPdf default
            list($r, $g, $b) = Color::toRGB(Style::value($style, 'fill-color', 'black'));
            $fpdf->SetFillColor($r, $g, $b);

        }

    }

}

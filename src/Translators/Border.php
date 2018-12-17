<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Color;
use Relaxsd\Stylesheets\Style;

class Border
{

    /**
     * @param Style $style
     *
     * @return int|string 0 (no border), 1 (border), or "L?T?B?R?"
     */
    public static function translate($style)
    {
        // Support border-left, border-right, border-top, border-bottom

        if (isset($style)) {

            $result = '';

            if ($style->hasValue('border-bottom') && self::parseBorder($style->getValue('border-bottom'))) {
                $result .= 'B';
            }
            if ($style->hasValue('border-left') && self::parseBorder($style->getValue('border-left'))) {
                $result .= 'L';
            }
            if ($style->hasValue('border-right') && self::parseBorder($style->getValue('border-right'))) {
                $result .= 'R';
            }
            if ($style->hasValue('border-top') && self::parseBorder($style->getValue('border-top'))) {
                $result .= 'T';
            }

            if ($result)
                return $result;

        }

        // Else support border (1) / no border (0)
        return self::parseBorder(Style::value($style, 'border'));
    }

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        $border = self::translate($style);

        if ($border) {

            // TODO: Convert string to float etc
            // 0.2 mm is the FPdf default
            $fpdf->SetLineWidth(Style::value($style, 'border-width', 0.2));

            // Black is the FPdf default
            list($r, $g, $b) = Color::toRGB(Style::value($style, 'border-color', 'black'));
            $fpdf->SetDrawColor($r, $g, $b);

        }

    }

    /**
     * @param $value
     *
     * @return int  0 (no border) or 1 (border)
     */
    private static function parseBorder($value)
    {
        return $value ? 1 : 0;
    }

}

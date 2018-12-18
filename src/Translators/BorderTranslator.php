<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Border;
use Relaxsd\Stylesheets\Attributes\BorderColor;
use Relaxsd\Stylesheets\Attributes\BorderWidth;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Style;

class BorderTranslator
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

            if ($style->hasValue(Border::BORDER_BOTTOM) && self::parseBorder($style->getValue(Border::BORDER_BOTTOM))) {
                $result .= 'B';
            }
            if ($style->hasValue(Border::BORDER_LEFT) && self::parseBorder($style->getValue(Border::BORDER_LEFT))) {
                $result .= 'L';
            }
            if ($style->hasValue(Border::BORDER_RIGHT) && self::parseBorder($style->getValue(Border::BORDER_RIGHT))) {
                $result .= 'R';
            }
            if ($style->hasValue(Border::BORDER_TOP) && self::parseBorder($style->getValue(Border::BORDER_TOP))) {
                $result .= 'T';
            }

            if ($result)
                return $result;

        }

        // Else support border (1) / no border (0)
        return self::parseBorder(Style::value($style, Border::BORDER));
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
            $fpdf->SetLineWidth(Style::value($style, BorderWidth::ATTRIBUTE, 0.2));

            // Black is the FPdf default
            list($r, $g, $b) = Color::toRGB(Style::value($style, BorderColor::ATTRIBUTE, 'black'));
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

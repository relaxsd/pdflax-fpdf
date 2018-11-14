<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Color;
use Relaxsd\Stylesheets\Style;

class RectStyle
{

    /**
     * @param Style|null $style
     * @param string     $default
     *
     * @return string
     */
    public static function translate($style, $default = '')
    {
        if (!isset($style)) {
            return $default;
        }

        $result = '';

        if ($style->hasValue('border-color')) {
            $result .= 'F';
        }

        if ($style->hasValue('fill-color')) {
            $result .= 'D';
        }

        return $result;
    }

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        if (!isset($style)) return;

        if ($style->hasValue('border-color')) {
            list($r, $g, $b) = Color::toRGB($style->getValue('border-color'));
            $fpdf->SetDrawColor($r, $g, $b);
        }

        if ($style->hasValue('fill-color')) {
            list($r, $g, $b) = Color::toRGB($style->getValue('fill-color'));
            $fpdf->SetFillColor($r, $g, $b);
        }

    }

}

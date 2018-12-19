<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\FontFamily;
use Relaxsd\Stylesheets\Attributes\FontSize;
use Relaxsd\Stylesheets\Attributes\FontStyle;
use Relaxsd\Stylesheets\Style;

class FontStyleTranslator
{

    /**
     * @param string $pdflaxFontStyle
     *
     * @return string
     */
    public static function translate($pdflaxFontStyle)
    {
        $result = '';

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, FontStyle::FONT_STYLE_BOLD)) {
            $result .= 'B';
        }

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, FontStyle::FONT_STYLE_ITALIC)) {
            $result .= 'I';
        }

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, FontStyle::FONT_STYLE_UNDERLINE)) {
            $result .= 'U';
        }

        return $result;
    }

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        list($r, $g, $b) = Color::toRGB(Style::value($style, 'text-color', 'black'));
        $fpdf->SetTextColor($r, $g, $b);

        $fpdf->SetFont(
            Style::value($style, FontFamily::ATTRIBUTE, ''),
            self::translate(Style::value($style, FontStyle::ATTRIBUTE, FontStyle::FONT_STYLE_NORMAL)),
            Style::value($style, FontSize::ATTRIBUTE, 0)
        );

    }

}

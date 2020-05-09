<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Illuminate\Support\Str;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\FontColor;
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

        if ($pdflaxFontStyle && Str::contains($pdflaxFontStyle, FontStyle::BOLD)) {
            $result .= 'B';
        }

        if ($pdflaxFontStyle && Str::contains($pdflaxFontStyle, FontStyle::ITALIC)) {
            $result .= 'I';
        }

        if ($pdflaxFontStyle && Str::contains($pdflaxFontStyle, FontStyle::UNDERLINE)) {
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

        list($r, $g, $b) = Color::toRGB(Style::value($style, FontColor::ATTRIBUTE, Color::BLACK));
        $fpdf->SetTextColor($r, $g, $b);

        $fpdf->SetFont(
            Style::value($style, FontFamily::ATTRIBUTE, ''),
            self::translate(Style::value($style, FontStyle::ATTRIBUTE, FontStyle::NORMAL)),
            // TODO: Use FontSize::DEFAULT (16pt?) instead of 0 (which means 'leave unchanged' in FPDF)
            Style::value($style, FontSize::ATTRIBUTE, 0)
        );

    }

}

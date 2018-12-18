<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\PdfView;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Style;

class FontStyle
{

    /**
     * @param string $pdflaxFontStyle
     *
     * @return string
     */
    public static function translate($pdflaxFontStyle)
    {
        $result = '';

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, PdfView::FONT_STYLE_BOLD)) {
            $result .= 'B';
        }

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, PdfView::FONT_STYLE_ITALIC)) {
            $result .= 'I';
        }

        if ($pdflaxFontStyle && str_contains($pdflaxFontStyle, PdfView::FONT_STYLE_UNDERLINE)) {
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
            Style::value($style, 'font-family', ''),
            self::translate(Style::value($style, 'font-style', PdfView::FONT_STYLE_NORMAL)),
            Style::value($style, 'font-size', 0)
        );

    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Color;
use Relaxsd\Pdflax\PdfView;

class FontStyle
{

    /**
     * @param \Anouar\Fpdf\Fpdf $fpdf
     * @param Style|null        $style
     */
    public static function applyStyle($fpdf, $style = null)
    {

        if (!isset($style)) return;

        if ($style->hasValue('text-color')) {
            list($r, $g, $b) = Color::toRGB($style->getValue('text-color'));
            $fpdf->SetTextColor($r, $g, $b);
        }

        $fpdf->SetFont(
            $style->getValue('font-family', ''),
            self::translate($style->getValue('font-style', PdfView::FONT_STYLE_NORMAL)),
            $style->getValue('font-size', 0)
        );

    }

    /**
     * @param int $pdflaxFontStyle
     *
     * @return string
     */
    public static function translate($pdflaxFontStyle)
    {
        $result = '';

        if ($pdflaxFontStyle & PdfView::FONT_STYLE_BOLD) {
            $result .= 'B';
        }

        if ($pdflaxFontStyle & PdfView::FONT_STYLE_ITALIC) {
            $result .= 'I';
        }

        if ($pdflaxFontStyle & PdfView::FONT_STYLE_UNDERLINE) {
            $result .= 'U';
        }

        return $result;
    }

}

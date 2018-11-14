<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\PdfView;

class FontStyle
{

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

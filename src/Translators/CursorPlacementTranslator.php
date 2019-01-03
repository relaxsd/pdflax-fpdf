<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;
use Relaxsd\Stylesheets\Attributes\CursorPlacement;
use Relaxsd\Stylesheets\Style;

class CursorPlacementTranslator
{

    /**
     * @param Style $style
     * @param int   $default E.g. 0 for Cell, 2 for MultiCell
     *
     * @return int
     */
    public static function translate($style, $default = 0)
    {
        switch ($cursorPlacement = Style::value($style, CursorPlacement::ATTRIBUTE)) {
            case CursorPlacement::TOP_RIGHT:
                return 0;
            case CursorPlacement::NEWLINE:
                return 1;
            case CursorPlacement::BOTTOM_LEFT:
                return 2;
            case null:
                return $default;
            default:
                throw new UnsupportedFeatureException("Unsupported cursor placement: ${$cursorPlacement}");
        }
    }

}

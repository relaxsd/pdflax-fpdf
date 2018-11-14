<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;

class Orientation
{

    /**
     * @param int $pdflaxOrientation
     *
     * @return string
     * @throws Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public static function translate($pdflaxOrientation)
    {
        if (!$pdflaxOrientation) {
            return '';
        } elseif ($pdflaxOrientation == PdfCreatorOptionsInterface::ORIENTATION_LANDSCAPE) {
            return 'L';
        } elseif ($pdflaxOrientation == PdfCreatorOptionsInterface::ORIENTATION_PORTRAIT) {
            return 'P';
        }

        throw new UnsupportedFeatureException("Unsupported orientation value: '{$pdflaxOrientation}'");
    }

}


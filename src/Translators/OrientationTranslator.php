<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;
use Relaxsd\Stylesheets\Attributes\PageOrientation;

class OrientationTranslator
{

    /**
     * @param int $pdflaxOrientation
     *
     * @return string
     * @throws \Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public static function translate($pdflaxOrientation)
    {
        if (!$pdflaxOrientation) {
            return '';
        } elseif ($pdflaxOrientation == PageOrientation::LANDSCAPE) {
            return 'L';
        } elseif ($pdflaxOrientation == PageOrientation::PORTRAIT) {
            return 'P';
        }

        throw new UnsupportedFeatureException("Unsupported orientation value: '{$pdflaxOrientation}'");
    }

}


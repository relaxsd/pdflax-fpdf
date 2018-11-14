<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;

class Size
{

    /**
     * @param array|string|null $size
     *
     * @return array|string|null
     * @throws Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public static function translate($size)
    {
        if (!$size) {
            return '';

        } elseif (is_array($size)) {
            return $size;

        } elseif ($size == PdfCreatorOptionsInterface::SIZE_A4) {
            return 'A4';

        }

        throw new UnsupportedFeatureException("Unsupported size value: '{$size}'");
    }

}

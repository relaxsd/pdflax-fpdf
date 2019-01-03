<?php

namespace Relaxsd\Pdflax\Fpdf\Translators;

use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;
use Relaxsd\Stylesheets\Attributes\PageSize;

class PageSizeTranslator
{

    /**
     * @param array|string|null $size
     *
     * @return array|string|null
     */
    public static function translate($size)
    {
        if (!$size) {
            return '';

        } elseif (is_array($size)) {
            return $size;

        } elseif ($size == PageSize::A4) {
            return 'A4';

        }

        throw new UnsupportedFeatureException("Unsupported size value: '{$size}'");
    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf\Extensions\Utf8;

use Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException;

class Utf8SupportFactory
{

    public static function create($type)
    {

        switch ($type) {

            case 'iconv':
                return new IconvTextFilter();

            case 'utf8_decode':
                return new Utf8DecodeTextFilter();

            default:
                throw new UnsupportedFeatureException("Cannot create UTF-8 support for type '{$type}'.");

        }

    }
}

<?php

namespace Relaxsd\Pdflax\Fpdf\Extensions\Utf8;

use Relaxsd\Pdflax\Contracts\TextFilterInterface;

/**
 * Class Utf8DecodeTextFilter
 *
 * Converts strings with ISO-8859-1 characters encoded with UTF-8 to single-byte ISO-8859-1.
 *
 * Known issues:
 * - Does not correctly convert euro symbols (€)
 *
 * @package Relaxsd\Pdflax\Fpdf\Extensions\Utf8
 */
class Utf8DecodeTextFilter implements TextFilterInterface
{

    /**
     * Filters/translates a string.
     *
     * @param string $str The input string
     *
     * @return string The output string
     */
    public function filter($str)
    {
        return utf8_decode($str);
    }

}

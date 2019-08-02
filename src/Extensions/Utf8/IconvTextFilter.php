<?php

namespace Relaxsd\Pdflax\Fpdf\Extensions\Utf8;

use Relaxsd\Pdflax\Contracts\TextFilterInterface;

/**
 * Class IconvTextFilter
 *
 * Convert strings to the requested character encoding using iconv().
 *
 * Known issues:
 * - Does not correctly convert euro symbols (€)
 *
 * @package Relaxsd\Pdflax\Fpdf\Extensions\Utf8
 */
class IconvTextFilter implements TextFilterInterface
{
    /**
     * When a character can't be represented in the target charset,
     * it can be approximated through one or several similarly looking characters,
     * e.g. 'EUR' instead of '€'
     */
    const ON_FAIL_TRANSLITERATE = 'translit';

    /** When a character can't be represented in the target charset, they are silently discarded. */
    const ON_FAIL_IGNORE = 'ignore';

    /** When a character can't be represented in the target charset, an E_NOTICE is generated. */
    const ON_FAIL_NOTICE = 'notice';

    /**
     * IconvTextFilter constructor.
     */
    public function __construct($inCharset = "UTF-8", $outCharset = "windows-1252", $onFail = self::ON_FAIL_IGNORE)
    {
        $this->inCharset = $inCharset;

        if ($onFail == self::ON_FAIL_TRANSLITERATE) {

            $this->outCharset = "{$outCharset}//TRANSLIT";

        } elseif ($onFail == self::ON_FAIL_IGNORE) {

            $this->outCharset = "{$outCharset}//IGNORE";

        } else {

            $this->outCharset = $outCharset;

        }

    }

    /**
     * Filters/translates a string.
     *
     * @param string $str The input string
     *
     * @return string The output string
     */
    public function filter($str)
    {
        return iconv($this->inCharset, $this->outCharset, $str);
    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf;

use Relaxsd\Pdflax\Helpers\CurrencyFormatter;

class FpdfCurrencyFormatter extends CurrencyFormatter
{

    public function __construct(array $options = [])
    {
        parent::__construct(array_merge([
            self::OPTION_EURO_SYMBOL => chr(128),
        ], $options));
    }

}

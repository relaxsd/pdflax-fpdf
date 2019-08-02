<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Fpdf\Extensions\Utf8\Utf8DecodeTextFilter;

class Utf8DecodeTextFilterTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Relaxsd\Pdflax\Fpdf\Extensions\Utf8\Utf8DecodeTextFilter
     */
    protected $textFilter;

    /**
     * @test
     */
    public function it_translates_correctly_to_windows_1252()
    {
        $textFilter = new Utf8DecodeTextFilter();

        $this->assertEquals("Zee\xEBgel", $textFilter->filter("Zeeëgel"));
    }

    /**
     * @test
     */
    public function it_translates_problematic_symbols_to_questionmarks()
    {
        $textFilter = new Utf8DecodeTextFilter();

        $this->assertEquals("This is the Euro symbol '?'", $textFilter->filter("This is the Euro symbol '€'"));
    }

}

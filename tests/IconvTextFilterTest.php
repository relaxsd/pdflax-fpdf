<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Fpdf\Extensions\Utf8\IconvTextFilter;

class IconvTextFilterTest extends TestCase
{

    /**
     * The test subject
     *
     * @var \Relaxsd\Pdflax\Fpdf\Extensions\Utf8\IconvTextFilter
     */
    protected $textFilter;

    /**
     * @test
     */
    public function it_translates_correctly_to_windows_1252()
    {
        $textFilter = new IconvTextFilter();

        $this->assertEquals("This is the Euro symbol '\x80'", $textFilter->filter("This is the Euro symbol '€'"));
        $this->assertEquals("Zee\xEBgel", $textFilter->filter("Zeeëgel"));
    }

    /**
     * @test
     */
    public function it_transliterates_problematic_characters()
    {
        $textFilter = new IconvTextFilter("UTF-8", "ISO-8859-1", IconvTextFilter::ON_FAIL_TRANSLITERATE);

        $this->assertEquals("This is the Euro symbol 'EUR'", $textFilter->filter("This is the Euro symbol '€'"));
    }

    /**
     * @test
     */
    public function it_ignores_problematic_characters()
    {
        $textFilter = new IconvTextFilter("UTF-8", "ISO-8859-1", IconvTextFilter::ON_FAIL_IGNORE);

        $this->assertEquals("This is the Euro symbol ''", $textFilter->filter("This is the Euro symbol '€'"));
    }

    /**
     * @test
     * TODO: NOT @ expectedException PHPUnit_Framework_Error_Notice
     */
    public function it_fails_on_problematic_characters()
    {
        $textFilter = new IconvTextFilter("UTF-8", "ISO-8859-1", IconvTextFilter::ON_FAIL_NOTICE);

        $this->assertFalse($textFilter->filter("This is the Euro symbol '€'"));
    }

}

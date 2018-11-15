<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Color;
use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Fpdf\FpdfDocumentAdapter;
use Relaxsd\Pdflax\PdfView;

class FpdfDocumentAdapterTest extends TestCase
{

    /**
     * The test subject
     *
     * @var Relaxsd\Pdflax\Fpdf\FpdfDocumentAdapter
     */
    protected $fpdfDocumentAdapter;

    /**
     * A Stylesheet mock object
     *
     * @var \Anouar\Fpdf\Fpdf|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fpdfMock;

    protected function setUp()
    {
        parent::setUp();

        $this->fpdfMock            = $this->getMockBuilder('\Anouar\Fpdf\Fpdf')->getMock();
        $this->fpdfDocumentAdapter = new FpdfDocumentAdapter($this->fpdfMock);
    }

    /**
     * @test
     */
    public function it_sets_a_font()
    {
        $this->fpdfMock
            ->expects($this->exactly(5))
            ->method('SetFont')
            ->withConsecutive(
                ['FAMILY', '', 1],
                ['FAMILY', 'B', 2],
                ['FAMILY', 'I', 3],
                ['FAMILY', 'U', 4],
                ['FAMILY', 'BIU', 5]
            );

        $self = $this->fpdfDocumentAdapter->setFont('FAMILY', PdfView::FONT_STYLE_NORMAL, 1);
        $this->fpdfDocumentAdapter->setFont('FAMILY', PdfView::FONT_STYLE_BOLD, 2);
        $this->fpdfDocumentAdapter->setFont('FAMILY', PdfView::FONT_STYLE_ITALIC, 3);
        $this->fpdfDocumentAdapter->setFont('FAMILY', PdfView::FONT_STYLE_UNDERLINE, 4);
        $this->fpdfDocumentAdapter->setFont('FAMILY', PdfView::FONT_STYLE_BOLD . PdfView::FONT_STYLE_ITALIC . PdfView::FONT_STYLE_UNDERLINE, 5);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_sets_a_draw_color()
    {
        $this->fpdfMock
            ->expects($this->exactly(4))
            ->method('SetDrawColor')
            ->withConsecutive(
                [0, 0, 0],
                [127, 128, 129],
                [30, 30, 30],
                [255, 0, 0]
            );

        $self = $this->fpdfDocumentAdapter->setDrawColor(0, 0, 0);
        $this->fpdfDocumentAdapter->setDrawColor(127, 128, 129);
        $this->fpdfDocumentAdapter->setDrawColor(30);
        $this->fpdfDocumentAdapter->setDrawColor(Color::RED);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_sets_a_text_color()
    {
        $this->fpdfMock
            ->expects($this->exactly(4))
            ->method('SetTextColor')
            ->withConsecutive(
                [0, 0, 0],
                [127, 128, 129],
                [30, 30, 30],
                [255, 0, 0]
            );

        $self = $this->fpdfDocumentAdapter->setTextColor(0, 0, 0);
        $this->fpdfDocumentAdapter->setTextColor(127, 128, 129);
        $this->fpdfDocumentAdapter->setTextColor(30);
        $this->fpdfDocumentAdapter->setTextColor(Color::RED);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_sets_a_fill_color()
    {
        $this->fpdfMock
            ->expects($this->exactly(4))
            ->method('SetFillColor')
            ->withConsecutive(
                [0, 0, 0],
                [127, 128, 129],
                [30, 30, 30],
                [255, 0, 0]
            );

        $self = $this->fpdfDocumentAdapter->setFillColor(0, 0, 0);
        $this->fpdfDocumentAdapter->setFillColor(127, 128, 129);
        $this->fpdfDocumentAdapter->setFillColor(30);
        $this->fpdfDocumentAdapter->setFillColor(Color::RED);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_adds_a_new_page()
    {
        $this->fpdfMock
            ->expects($this->exactly(4))
            ->method('AddPage')
            ->withConsecutive(
                [],
                ['L'],
                [null, 'A4'],
                ['P', [100, 200]]
            );

        $self = $this->fpdfDocumentAdapter->addPage();
        $this->fpdfDocumentAdapter->addPage(PdfCreatorOptionsInterface::ORIENTATION_LANDSCAPE);
        $this->fpdfDocumentAdapter->addPage(null, PdfCreatorOptionsInterface::SIZE_A4);
        $this->fpdfDocumentAdapter->addPage(PdfCreatorOptionsInterface::ORIENTATION_PORTRAIT, [100, 200]);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     * @expectedException Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function it_excepts_when_adding_a_page_with_invalid_size()
    {
        $this->fpdfDocumentAdapter->addPage(null, '(invalid)');
    }

    /**
     * @test
     * @expectedException Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function it_excepts_when_adding_a_page_with_invalid_orientation()
    {
        $this->fpdfDocumentAdapter->addPage('(invalid)');
    }

    /**
     * @test
     */
    public function it_adds_a_newline()
    {
        $this->fpdfMock
            ->expects($this->once())
            ->method('Ln');

        $self = $this->fpdfDocumentAdapter->newLine();
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_adds_multiple_newlines()
    {
        $this->fpdfMock
            ->expects($this->exactly(3))
            ->method('Ln');

        $this->fpdfDocumentAdapter->newLine(3);
    }

    /**
     * @test
     */
    public function it_returns_the_current_page()
    {
        $this->fpdfMock->page = 10;

        $this->assertEquals(10, $this->fpdfDocumentAdapter->getPage());
    }

    /**
     * @test
     */
    public function it_returns_zero_for_x_and_y()
    {
        $this->assertEquals(0, $this->fpdfDocumentAdapter->getX());
        $this->assertEquals(0, $this->fpdfDocumentAdapter->getY());
    }

    /**
     * @test
     */
    public function it_returns_the_width_and_height()
    {
        $this->fpdfMock->w = 100;
        $this->fpdfMock->h = 200;

        $this->assertEquals(100, $this->fpdfDocumentAdapter->getWidth());
        $this->assertEquals(200, $this->fpdfDocumentAdapter->getHeight());
    }

    /**
     * @test
     */
    public function it_returns_the_cursor()
    {
        $this->fpdfMock
            ->expects($this->once())
            ->method('GetX')
            ->willReturn(100);
        $this->fpdfMock
            ->expects($this->once())
            ->method('GetY')
            ->willReturn(200);

        $this->assertEquals(100, $this->fpdfDocumentAdapter->getCursorX());
        $this->assertEquals(200, $this->fpdfDocumentAdapter->getCursorY());
    }

    /**
     * @test
     */
    public function it_sets_the_cursor()
    {
        $this->fpdfMock
            ->expects($this->once())
            ->method('SetX')
            ->with(100);
        $this->fpdfMock
            ->expects($this->once())
            ->method('SetY')
            ->with(200);
        $this->fpdfMock
            ->expects($this->once())
            ->method('SetXY')
            ->with(10, 20);

        $self = $this->fpdfDocumentAdapter->setCursorX(100);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorY(200);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorXY(10, 20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_returns_the_left_and_right_margins()
    {
        $this->fpdfMock->lMargin = 10;
        $this->fpdfMock->rMargin = 190;

        $this->assertEquals(10, $this->fpdfDocumentAdapter->getLeftMargin());
        $this->assertEquals(190, $this->fpdfDocumentAdapter->getRightMargin());
    }

    /**
     * @test
     */
    public function it_sets_the_left_and_right_margins()
    {
        $this->fpdfMock
            ->expects($this->once())
            ->method('SetLeftMargin')
            ->with(20);
        $this->fpdfMock
            ->expects($this->once())
            ->method('SetRightMargin')
            ->with(180);

        $self = $this->fpdfDocumentAdapter->setLeftMargin(20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setRightMargin(180);
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_returns_the_underlying_raw_implementation()
    {
        $this->assertSame($this->fpdfMock, $this->fpdfDocumentAdapter->raw());
    }

    /**
     * @test
     */
    public function it_returns_a_currency_converter()
    {
        $this->assertInstanceOf('Relaxsd\Pdflax\Fpdf\FpdfCurrencyFormatter', $this->fpdfDocumentAdapter->getCurrencyFormatter());
    }

    /**
     * @test
     */
    public function it_saves_to_a_file()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Output')
            ->with('FILENAME', 'F');

        $self = $this->fpdfDocumentAdapter->save('FILENAME');
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_saves_to_a_string()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Output')
            ->with('', 'S')
            ->willReturn('PDF');

        $this->assertSame('PDF', $this->fpdfDocumentAdapter->getPdfContent());

    }

    /**
     * @test
     */
    public function it_draws_a_cell()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Cell')
            ->with(10, 20, 'text', 0, 0, 'L', false, '');

        $this->fpdfDocumentAdapter->cell(10, 20, 'text');

    }

    /**
     * @test
     */
    public function it_draws_a_multicell()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('MultiCell')
            ->with(10, 20, 'text', 0, 'L', false);

        $this->fpdfDocumentAdapter->cell(10, 20, 'text', ['multiline' => true]);

    }

    /**
     * @test
     */
    public function it_draws_a_rect()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Rect')
            ->with(10,20,30,40);

        $this->fpdfDocumentAdapter->rectangle(10,20,30,40);

    }

    /**
     * @test
     */
    public function it_draws_a_line()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Line')
            ->with(10,20,30,40);

        $this->fpdfDocumentAdapter->line(10,20,30,40);

    }

    /**
     * @test
     */
    public function it_draws_a_image()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Image')
            ->with('file', 10,20,30,40, 'type', 'link');

        $this->fpdfDocumentAdapter->image('file', 10,20,30,40, 'type', 'link');

    }

    /**
     * @test
     */
    public function it_writes_text()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Write')
            ->with(10, 'text', 'link');

        $this->fpdfDocumentAdapter->write(10, 'text', 'link');

    }
}

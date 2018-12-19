<?php

use PHPUnit\Framework\TestCase;
use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Fpdf\FpdfDocumentAdapter;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\FontStyle;
use Relaxsd\Stylesheets\Attributes\Multiline;
use Relaxsd\Stylesheets\Attributes\PageOrientation;

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

        $this->fpdfMock = $this->getMockBuilder('\Anouar\Fpdf\Fpdf')->getMock();
        $this->setFPdfMargins(0, 0, 0, 0);

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

        $self = $this->fpdfDocumentAdapter->setFont('FAMILY', FontStyle::FONT_STYLE_NORMAL, 1);
        $this->fpdfDocumentAdapter->setFont('FAMILY', FontStyle::FONT_STYLE_BOLD, 2);
        $this->fpdfDocumentAdapter->setFont('FAMILY', FontStyle::FONT_STYLE_ITALIC, 3);
        $this->fpdfDocumentAdapter->setFont('FAMILY', FontStyle::FONT_STYLE_UNDERLINE, 4);
        $this->fpdfDocumentAdapter->setFont('FAMILY', FontStyle::FONT_STYLE_BOLD . FontStyle::FONT_STYLE_ITALIC . FontStyle::FONT_STYLE_UNDERLINE, 5);

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
        $this->fpdfDocumentAdapter->addPage(PageOrientation::LANDSCAPE);
        $this->fpdfDocumentAdapter->addPage(null, PdfCreatorOptionsInterface::SIZE_A4);
        $this->fpdfDocumentAdapter->addPage(PageOrientation::PORTRAIT, [100, 200]);

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
    public function it_returns_cursor_x_and_y()
    {
        // Without margins:
        $this->setFPdfMargins(0, 0, 0, 0);

        $this->fpdfMock->expects($this->once())->method('GetX')->willReturn(10);
        $this->fpdfMock->expects($this->once())->method('GetY')->willReturn(15);

        $this->assertEquals(10, $this->fpdfDocumentAdapter->getCursorX());
        $this->assertEquals(15, $this->fpdfDocumentAdapter->getCursorY());

    }

    /**
     * @test
     */
    public function it_returns_cursor_x_and_y_with_margins()
    {
        // With margins:
        $this->setFPdfMargins(1, 2, 3, 4);

        $this->fpdfMock->expects($this->once())->method('GetX')->willReturn(10);
        $this->fpdfMock->expects($this->once())->method('GetY')->willReturn(15);

        $this->assertEquals(10 - 1, $this->fpdfDocumentAdapter->getCursorX());
        $this->assertEquals(15 - 3, $this->fpdfDocumentAdapter->getCursorY());
    }

    /**
     * @test
     */
    public function it_sets_the_cursor()
    {
        // Without margins:
        $this->setFPdfMargins(0, 0, 0, 0);

        $this->fpdfMock->expects($this->exactly(2))->method('SetX')->with(10);
        $this->fpdfMock->expects($this->exactly(2))->method('SetY')->with(20);

        $self = $this->fpdfDocumentAdapter->setCursorX(10);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorY(20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorXY(10, 20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_sets_the_cursor_with_margins()
    {
        // With margins:
        $this->setFPdfMargins(1, 2, 3, 4);

        $this->fpdfMock->expects($this->exactly(2))->method('SetX')->with(10 + 1);
        $this->fpdfMock->expects($this->exactly(2))->method('SetY')->with(20 + 3);

        $self = $this->fpdfDocumentAdapter->setCursorX(10);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorY(20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setCursorXY(10, 20);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_returns_margins()
    {
        // With margins:
        $this->setFPdfMargins(1, 2, 3, 4);

        $this->assertEquals(1, $this->fpdfDocumentAdapter->getLeftMargin());
        $this->assertEquals(2, $this->fpdfDocumentAdapter->getRightMargin());
        $this->assertEquals(3, $this->fpdfDocumentAdapter->getTopMargin());
        $this->assertEquals(4, $this->fpdfDocumentAdapter->getBottomMargin());
    }

    /**
     * @test
     */
    public function it_sets_margins()
    {
        $this->fpdfMock->expects($this->once())->method('SetLeftMargin')->with(1);
        $this->fpdfMock->expects($this->once())->method('SetRightMargin')->with(2);
        $this->fpdfMock->expects($this->once())->method('SetTopMargin')->with(3);
        // FPdf has no SetBottomMargin(), the bottom margin can only be set through SetAutoPageBreak
        // $this->fpdfMock->expects($this->once())->method('SetBottomMargin')->with(4);

        $self = $this->fpdfDocumentAdapter->setLeftMargin(1);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setRightMargin(2);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setTopMargin(3);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        $self = $this->fpdfDocumentAdapter->setBottomMargin(4);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

        // FPdf has no SetBottomMargin(), the bottom margin can only be set through SetAutoPageBreak
        $this->assertEquals($this->fpdfMock->bMargin, 4);
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

        $self = $this->fpdfDocumentAdapter->cell(10, 20, 'text');
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_draws_a_cell_with_margins()
    {
        // With margins:
        $this->setFPdfMargins(1, 2, 3, 4);

        // Because the call draws a cell at the current cursor,
        // the margins will have no effect.
        $this->fpdfMock
            ->expects($this->once())
            ->method('Cell')
            ->with(10, 20, 'text', 0, 0, 'L', false, '');

        $self = $this->fpdfDocumentAdapter->cell(10, 20, 'text');
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_draws_a_cell_with_percentages()
    {
        // With margins:
        $this->setFPdfMargins(1, 2, 3, 4);

        $this->setFPdfCanvas(200 + 1 + 2, 300 + 3 + 4); // 200x300 and margins

        // Because the call draws a cell at the current cursor,
        // the margins will have no effect.
        // For the percentages, we expect 10% of the InnerWidth and 20% of the InnerHeight of the parent
        $this->fpdfMock
            ->expects($this->once())
            ->method('Cell')
            ->with(0.10 * 200, 0.20 * 300, 'text', 0, 0, 'L', false, '');

        $self = $this->fpdfDocumentAdapter->cell('10%', '20%', 'text');
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_draws_a_multicell()
    {

        // For Ln=2, these are called but irrelevant
        // $this->fpdfMock->expects($this->once())->method('GetX')->willReturn(20);
        // $this->fpdfMock->expects($this->once())->method('GetY')->willReturn(35);

        $this->fpdfMock
            ->expects($this->once())
            ->method('MultiCell')
            ->with(10, 20, 'text', 0, 'L', false);

        $self = $this->fpdfDocumentAdapter->cell(10, 20, 'text', [
            Multiline::ATTRIBUTE => true,
            'ln'                 => 2 // FPdf default, should be in 2 in all styles that use multiline
        ]);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_supports_newline_after_a_multicell()
    {

        // For Ln=2, these are called but irrelevant
        // $this->fpdfMock->expects($this->once())->method('GetX')->willReturn(20);
        // $this->fpdfMock->expects($this->once())->method('GetY')->willReturn(35);

        // For Ln=1, expect a newline (x=0)
        $this->fpdfMock->expects($this->once())->method('SetX')->with(0);

        $this->fpdfMock
            ->expects($this->once())
            ->method('MultiCell')
            ->with(10, 20, 'text', 0, 'L', false);

        $self = $this->fpdfDocumentAdapter->cell(10, 20, 'text', [
            Multiline::ATTRIBUTE => true,
            'ln'                 => 1
        ]);

        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_supports_top_right_after_a_multicell()
    {

        $this->fpdfMock->expects($this->once())->method('GetX')->willReturn(10);
        $this->fpdfMock->expects($this->once())->method('GetY')->willReturn(15);

        // For Ln=0, expect a move to top-right (20, 15)
        $this->fpdfMock->expects($this->once())->method('SetXY')->with(20, 15);

        $this->fpdfMock
            ->expects($this->once())
            ->method('MultiCell')
            ->with(10, 20, 'text', 0, 'L', false);

        $self = $this->fpdfDocumentAdapter->cell(10, 20, 'text', [
            Multiline::ATTRIBUTE => true,
            'ln'                 => 0
        ]);

        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_draws_a_rect()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Rect')
            ->with(10, 20, 30, 40);

        $self = $this->fpdfDocumentAdapter->rectangle(10, 20, 30, 40);
        $this->assertSame($this->fpdfDocumentAdapter, $self);

    }

    /**
     * @test
     */
    public function it_draws_a_rect_with_margins()
    {

        $this->setFPdfMargins(1, 2, 3, 4);

        $this->fpdfMock
            ->expects($this->once())
            ->method('Rect')
            ->with(10 + 1, 20 + 3, 30 + 1, 40 + 3);

        $self = $this->fpdfDocumentAdapter->rectangle(10, 20, 30, 40);
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_draws_a_line()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Line')
            ->with(10, 20, 30, 40);

        $self = $this->fpdfDocumentAdapter->line(10, 20, 30, 40);
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_draws_a_line_with_margins()
    {

        $this->setFPdfMargins(1, 2, 3, 4);

        $this->fpdfMock
            ->expects($this->once())
            ->method('Line')
            ->with(10 + 1, 20 + 3, 30 + 1, 40 + 3);

        $self = $this->fpdfDocumentAdapter->line(10, 20, 30, 40);
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_draws_a_image()
    {

        $this->fpdfMock
            ->expects($this->once())
            ->method('Image')
            ->with('file', 10, 20, 30, 40, 'type', 'link');

        $self = $this->fpdfDocumentAdapter->image('file', 10, 20, 30, 40, 'type', 'link');
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    /**
     * @test
     */
    public function it_draws_a_image_with_margins()
    {

        $this->setFPdfMargins(1, 2, 3, 4);

        $this->fpdfMock
            ->expects($this->once())
            ->method('Image')
            ->with('file', 10 + 1, 20 + 3, 30, 40, 'type', 'link');

        $self = $this->fpdfDocumentAdapter->image('file', 10, 20, 30, 40, 'type', 'link');
        $this->assertSame($this->fpdfDocumentAdapter, $self);
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

        $self = $this->fpdfDocumentAdapter->write(10, 'text', 'link');
        $this->assertSame($this->fpdfDocumentAdapter, $self);
    }

    protected function setFPdfMargins($left, $right, $top, $bottom)
    {
        $this->fpdfMock->lMargin = $left;
        $this->fpdfMock->rMargin = $right;
        $this->fpdfMock->tMargin = $top;
        $this->fpdfMock->bMargin = $bottom;
    }

    protected function setFPdfCanvas($width, $height)
    {
        $this->fpdfMock->w = $width;
        $this->fpdfMock->h = $height;
    }

    protected function getXY()
    {
        return [$this->fpdfMock->x, $this->fpdfMock->y];
    }

}

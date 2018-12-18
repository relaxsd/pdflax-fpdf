<?php

namespace Relaxsd\Pdflax\Fpdf;

use Anouar\Fpdf\Fpdf;
use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;
use Relaxsd\Pdflax\Fpdf\Translators\AlignTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\BorderTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\Fill;
use Relaxsd\Pdflax\Fpdf\Translators\FontStyleTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\LineStyle;
use Relaxsd\Pdflax\Fpdf\Translators\Ln;
use Relaxsd\Pdflax\Fpdf\Translators\Multiline;
use Relaxsd\Pdflax\Fpdf\Translators\Orientation;
use Relaxsd\Pdflax\Fpdf\Translators\RectStyle;
use Relaxsd\Pdflax\Fpdf\Translators\Size;
use Relaxsd\Pdflax\PdfDOMTrait;
use Relaxsd\Pdflax\PdfStyleTrait;
use Relaxsd\Stylesheets\Attributes\Align;
use Relaxsd\Stylesheets\Attributes\Border;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\FontStyle;
use Relaxsd\Stylesheets\Style;
use Relaxsd\Stylesheets\Stylesheet;

class FpdfDocumentAdapter implements PdfDocumentInterface
{

    use PdfDOMTrait;
    use PdfStyleTrait;

    /**
     * @var Fpdf
     */
    protected $fpdf;

    /**
     * FpdfDocumentAdapter constructor.
     *
     * @param Fpdf $fpdf
     */
    public function __construct(Fpdf $fpdf)
    {
        $this->fpdf = $fpdf;

        $this->addStylesheet(new Stylesheet([

            // Inherited by all other styles
            'body'         => [
                'font-family'        => 'Arial',
                FontStyle::ATTRIBUTE => '',
                'font-size'          => 11,
                'text-color'         => [0, 0, 0],
            ],
            // Adds to 'body'. Used for all cells, including p, h1, h2
            'cell'         => [
                // FPdf default for cell()
                Align::ATTRIBUTE => Align::LEFT,
                Border::BORDER   => 0,
                'fill'           => 0,
                'link'           => '',
                'multiline'      => false, // Uses Cell, not MultiCell
                'ln'             => 0,
            ],

            // The paragraph type.
            // Adds to 'body' and 'cell'
            'p'            => [
                Align::ATTRIBUTE => Align::LEFT,
                'multiline'      => true, // Uses MultiCell, not Cell
                // By default, FPdf always uses Ln=2 for MultiCell.
                // TODO: 1 or 2 is important for correctly recognizing page breaks
                'ln'             => 2,
            ],
            // Heading 1 type
            // Adds to 'body' and 'cell'
            'h1'           => [
                FontStyle::ATTRIBUTE => 'bold',
                'font-size'          => 14,
                'ln'                 => 2,
                Align::ATTRIBUTE     => Align::LEFT,
            ],
            // Heading 2 type
            // Adds to 'body' and 'cell'
            'h2'           => [
                FontStyle::ATTRIBUTE => 'bold',
                'font-size'          => 12,
                'ln'                 => 2,
                Align::ATTRIBUTE     => Align::LEFT,
            ],
            '.align-right' => [
                Align::ATTRIBUTE => Align::RIGHT,
            ],
        ]));

    }

    /**
     * @param string $family
     * @param string $style
     * @param int    $size
     *
     * @return self
     */
    public function setFont($family, $style = FontStyle::FONT_STYLE_NORMAL, $size = 0)
    {
        $this->fpdf->SetFont(
            $family ?: '',
            FontStyleTranslator::translate($style),
            $size ?: 0
        );

        return $this;
    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setDrawColor($r, $g = null, $b = null)
    {
        list ($r, $g, $b) = Color::toRGB($r, $g, $b);

        $this->fpdf->SetDrawColor($r, $g, $b);

        return $this;
    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setTextColor($r, $g = null, $b = null)
    {
        list ($r, $g, $b) = Color::toRGB($r, $g, $b);

        $this->fpdf->SetTextColor($r, $g, $b);

        return $this;
    }

    /**
     * @param string|int|array $r Red value (with $g and $b) or greyscale value ($g and $b null) or color name or [r,g,b] array
     * @param int|null         $g Green value
     * @param int|null         $b Blue value
     *
     * @return mixed
     */
    public function setFillColor($r, $g = null, $b = null)
    {
        list ($r, $g, $b) = Color::toRGB($r, $g, $b);

        $this->fpdf->SetFillColor($r, $g, $b);

        return $this;
    }

    /**
     * @param     $auto
     * @param int $margin
     *
     * @return self
     */
    public function setAutoPageBreak($auto, $margin = 0)
    {
        $this->fpdf->SetAutoPageBreak($auto, $margin);

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setFontPath($path)
    {
        $this->fpdf->fontpath = $path;

        return $this;
    }

    /**
     * @param string  $family
     * @param integer $style
     * @param string  $filename
     *
     * @return $this
     */
    public function registerFont($family, $style, $filename)
    {
        $this->fpdf->AddFont(
            $family,
            FontStyleTranslator::translate($style),
            $filename
        );

        return $this;
    }

    /**
     * @param string|null $orientation
     * @param string|null $size
     *
     * @return self
     * @throws \Relaxsd\Pdflax\Exceptions\UnsupportedFeatureException
     */
    public function addPage($orientation = null, $size = null)
    {
        $this->fpdf->AddPage(
            Orientation::translate($orientation),
            Size::translate($size)
        );

        return $this;
    }

    /**
     * @param string $name
     * @param string $dest
     *
     * @return PdfDocumentInterface|string
     */
    public function output($name = '', $dest = '')
    {
        $result = $this->fpdf->Output($name, $dest);
        // TODO: Get rid of this stupid Fpdf interface (returning string or object)
        return ($dest == 'S') ? $result : $this;
    }

    /**
     * @param int        $n
     * @param array|null $options
     *
     * @return mixed
     */
    public function newLine($n = 1, $options = null)
    {
        // TODO: h as option!
        for ($i = 0; $i < $n; $i++) {
            $this->fpdf->Ln(/* h */);
        }

        return $this;
    }

    /**
     * Get the current page number.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->fpdf->page;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return 0;
    }

    /**
     * Get the total width of this document, including its left and right margins
     *
     * @return float
     */
    public function getWidth()
    {
        return $this->fpdf->w;
    }

    /**
     * Get the total height of this document, including its top and bottom margins
     *
     * @return float
     */
    public function getHeight()
    {
        return $this->fpdf->h;
    }

    /**
     * Get the inner width of this document, between its left and right margins
     *
     * @return float
     */
    public function getInnerWidth()
    {
        return $this->fpdf->w - $this->fpdf->lMargin - $this->fpdf->rMargin;
    }

    /**
     * Get the inner height of this document, between its top and bottom margins
     *
     * @return float
     */
    public function getInnerHeight()
    {
        return $this->fpdf->h - $this->fpdf->tMargin - $this->fpdf->bMargin;
    }

    /**
     * @return float
     */
    public function getCursorX()
    {
        return $this->fpdf->GetX() - $this->fpdf->lMargin;
    }

    /**
     * @return float
     */
    public function getCursorY()
    {
        return $this->fpdf->GetY() - $this->fpdf->tMargin;
    }

    /**
     * Position the 'cursor' at a given X
     *
     * @param float|string $x Local X-coordinate
     *
     * @return PdfDocumentInterface
     */
    public function setCursorX($x)
    {
        // TODO: fpdf->SetX moves the cursor from the right for negative values
        $this->fpdf->SetX(
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x)
        );

        return $this;
    }

    /**
     * Position the 'cursor' at a given Y
     *
     * @param float|string $y Local Y-coordinate
     *
     * @return PdfDocumentInterface
     */
    public function setCursorY($y)
    {
        // TODO: fpdf->SetY moves the cursor from the bottom for negative values
        $this->fpdf->SetY(
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y)
        );

        return $this;
    }

    /**
     * Position the 'cursor' at a given X,Y
     *
     * @param float|string $x Local X-coordinate
     * @param float|string $y Local Y-coordinate
     *
     * @return PdfDocumentInterface
     */
    public function setCursorXY($x, $y)
    {
        $this->setCursorX($x);
        $this->setCursorY($y);

        return $this;
    }

    /**
     * @param $leftMargin
     *
     * @return $this
     */
    public function setLeftMargin($leftMargin)
    {
        $this->fpdf->SetLeftMargin($leftMargin);
        return $this;
    }

    /**
     * @return float
     */
    public function getLeftMargin()
    {
        return $this->fpdf->lMargin;
    }

    /**
     * @param $rightMargin
     *
     * @return $this
     */
    public function setRightMargin($rightMargin)
    {
        $this->fpdf->SetRightMargin($rightMargin);

        return $this;
    }

    /**
     * @return float
     */
    public function getRightMargin()
    {
        return $this->fpdf->rMargin;
    }

    /**
     * @param $topMargin
     *
     * @return $this
     */
    public function setTopMargin($topMargin)
    {
        $this->fpdf->SetTopMargin($topMargin);
        return $this;
    }

    /**
     * @return float
     */
    public function getTopMargin()
    {
        return $this->fpdf->tMargin;
    }

    /**
     * @param $bottomMargin
     *
     * @return $this
     */
    public function setBottomMargin($bottomMargin)
    {
        // FPdf has no SetBottomMargin(), the bottom margin can only be set through SetAutoPageBreak
        // $this->fpdf->SetBottomMargin($bottomMargin);
        $this->fpdf->bMargin = $bottomMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getBottomMargin()
    {
        return $this->fpdf->bMargin;
    }

    /**
     * @return Fpdf
     */
    public function raw()
    {
        return $this->fpdf;
    }

    /**
     * @param string $fileName
     *
     * @return PdfDocumentInterface
     */
    public function save($fileName)
    {
        $this->fpdf->Output($fileName, 'F');

        return $this;
    }

    /**
     * @return string
     */
    public function getPdfContent()
    {
        return $this->fpdf->Output('', 'S');
    }

    /**
     * @param array|null $options
     *
     * @return \Relaxsd\Pdflax\Contracts\CurrencyFormatterInterface
     */
    public function getCurrencyFormatter($options = [])
    {
        return new FpdfCurrencyFormatter($options);
    }

    /**
     * Move the 'cursor' horizontally
     *
     * @param float|string $d distance
     *
     * @return self
     */
    public function moveCursorX($d)
    {
        // TODO: Implement moveCursorX() method.
    }

    /**
     * Move the 'cursor' vertically
     *
     * @param float|string $d distance
     *
     * @return self
     */
    public function moveCursorY($d)
    {
        // TODO: Implement moveCursorY() method.
    }

    /**
     * @param float|string                          $w
     * @param float|string                          $h
     * @param string                                $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return $this
     */
    public function cell($w, $h = 0.0, $txt = '', $style = null)
    {
        $style = Style::merged($this->getStyle('body'), $this->getStyle('cell'), $style);

        $w = $this->parseGlobalValue_h($w);
        $h = $this->parseGlobalValue_v($h);

        FontStyleTranslator::applyStyle($this->fpdf, $style);
        BorderTranslator::applyStyle($this->fpdf, $style);
        Fill::applyStyle($this->fpdf, $style);

        if (Multiline::translate($style)) {

            // For Ln=0, we need to restore the cursor
            $oldX = $this->fpdf->GetX();
            $oldY = $this->fpdf->GetY();

            $this->fpdf->MultiCell(
                $w,
                $h,
                $txt,
                BorderTranslator::translate($style),
                AlignTranslator::translate($style),
                Fill::translate($style)
            );

            $ln = Ln::translate($style, 2);

            // MultiCell uses ln=2 (bottom left) by default
            if ($ln == 1) {
                // Next line
                $this->fpdf->SetX($this->fpdf->lMargin);
            } else if ($ln == 0) {
                // Top right
                $this->fpdf->SetXY($oldX + $w, $oldY);
            }

        } else {

            $this->fpdf->Cell(
                $w,
                $h,
                $txt,
                BorderTranslator::translate($style),
                Ln::translate($style),
                AlignTranslator::translate($style),
                Fill::translate($style),
                ''
            );

        }

        return $this;
    }

    /**
     * @param float|string                          $x
     * @param float|string                          $y
     * @param float|string                          $w
     * @param float|string                          $h
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return self
     */
    public function rectangle($x, $y, $w, $h, $style = null)
    {
        // Set border (color, width) and fill styles
        RectStyle::applyStyle($this->fpdf, $style);

        $this->fpdf->Rect(
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y),
            $this->fpdf->lMargin + $this->parseGlobalValue_h($w),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($h),
            RectStyle::translate($style)
        );

        return $this;
    }

    /**
     * @param float|string                          $x1
     * @param float|string                          $y1
     * @param float|string                          $x2
     * @param float|string                          $y2
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return self
     */
    public function line($x1, $y1, $x2, $y2, $style = null)
    {
        // Set border (color, width) styles
        LineStyle::applyStyle($this->fpdf, $style);

        $this->fpdf->Line(
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x1),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y1),
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x2),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y2)
        );

        return $this;
    }

    /**
     * @param string                                $file
     * @param float|string                          $x
     * @param float|string                          $y
     * @param float|string                          $w
     * @param float|string                          $h
     * @param string                                $type
     * @param string                                $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return self
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '', $style = null)
    {
        $this->fpdf->Image(
            $file,
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y),
            $this->parseGlobalValue_h($w),
            $this->parseGlobalValue_v($h),
            $type,
            $link
        );

        // TODO: Draw rect if we want a border!

        return $this;
    }

    /**
     * @param float|string                          $h
     * @param string                                $text
     * @param string                                $link
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     *
     * @return self
     */
    public function write($h, $text, $link = '', $style = null)
    {
        FontStyleTranslator::applyStyle($this->fpdf, $style);

        $this->fpdf->Write(
            $this->parseGlobalValue_v($h),
            $text,
            $link
        );

        return $this;
    }

    // ======================================

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function parseGlobalValue_h($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getInnerWidth() * floatval($globalValue) / 100
            : $globalValue;
    }

    /**
     * @param float|string|null $globalValue
     *
     * @return float|null
     */
    protected function parseGlobalValue_v($globalValue)
    {
        return (is_string($globalValue))
            ? $this->getInnerHeight() * floatval($globalValue) / 100
            : $globalValue;
    }

}

<?php

namespace Relaxsd\Pdflax\Fpdf;

use Anouar\Fpdf\Fpdf;
use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;
use Relaxsd\Pdflax\Fpdf\Translators\AlignTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\BorderTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\CursorPlacementTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\FillTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\FontStyleTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\LineStyleTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\MultilineTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\OrientationTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\PageSizeTranslator;
use Relaxsd\Pdflax\Fpdf\Translators\RectStyleTranslator;
use Relaxsd\Pdflax\PdfDOMTrait;
use Relaxsd\Pdflax\PdfStyleTrait;
use Relaxsd\Stylesheets\Attributes\Align;
use Relaxsd\Stylesheets\Attributes\Border;
use Relaxsd\Stylesheets\Attributes\Color;
use Relaxsd\Stylesheets\Attributes\CursorPlacement;
use Relaxsd\Stylesheets\Attributes\Fill;
use Relaxsd\Stylesheets\Attributes\FontFamily;
use Relaxsd\Stylesheets\Attributes\FontSize;
use Relaxsd\Stylesheets\Attributes\FontStyle;
use Relaxsd\Stylesheets\Attributes\Multiline;
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
                Align::ATTRIBUTE      => Align::LEFT,
                FontFamily::ATTRIBUTE => 'Arial',
                FontStyle::ATTRIBUTE  => '',
                FontSize::ATTRIBUTE   => 11,
                'text-color'          => [0, 0, 0],
            ],
            // Adds to 'body'. Used for all cells, including p, h1, h2
            'cell'         => [
                // FPdf default for cell()
                Border::BORDER             => false,
                Fill::ATTRIBUTE            => false,
                'link'                     => '',
                Multiline::ATTRIBUTE       => false, // Uses Cell, not MultiCell
                CursorPlacement::ATTRIBUTE => CursorPlacement::CURSOR_TOP_RIGHT,
            ],

            // Adds to 'body'.
            'text'         => [],

            // The paragraph type.
            // Adds to 'body' and 'cell'
            'p'            => [
                CursorPlacement::ATTRIBUTE => CursorPlacement::CURSOR_BOTTOM_LEFT,
                Multiline::ATTRIBUTE       => true, // Uses MultiCell, not Cell
            ],
            // Heading 1 type
            // Adds to 'body' and 'cell'
            'h1'           => [
                CursorPlacement::ATTRIBUTE => CursorPlacement::CURSOR_BOTTOM_LEFT,
                FontSize::ATTRIBUTE        => 14,
                FontStyle::ATTRIBUTE       => 'bold',
                Multiline::ATTRIBUTE       => true, // Uses MultiCell, not Cell
            ],
            // Heading 2 type
            // Adds to 'body' and 'cell'
            'h2'           => [
                CursorPlacement::ATTRIBUTE => CursorPlacement::CURSOR_BOTTOM_LEFT,
                FontSize::ATTRIBUTE        => 12,
                FontStyle::ATTRIBUTE       => 'bold',
                Multiline::ATTRIBUTE       => true, // Uses MultiCell, not Cell
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
     */
    public function addPage($orientation = null, $size = null)
    {
        $this->fpdf->AddPage(
            OrientationTranslator::translate($orientation),
            PageSizeTranslator::translate($size)
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
        // Don't use SetX() (see setCursorY())
        // $this->fpdf->SetX($this->fpdf->lMargin + $this->parseGlobalValue_h($x));
        $this->fpdf->x = $this->fpdf->lMargin + $this->parseGlobalValue_h($x);

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
        // Don't use SetY():
        //  - This also resets fpdf->x !!!
        //  - fpdf->SetY moves the cursor from the bottom for negative values
        // $this->fpdf->SetY($this->fpdf->tMargin + $this->parseGlobalValue_v($y));
        $this->fpdf->y = $this->fpdf->tMargin + $this->parseGlobalValue_v($y);

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

    // ======================= output

    /**
     * @param float|string|null                     $x X position (may be percentage). If null, use current cursor position.
     * @param float|string|null                     $y Y position (may be percentage). If null, use current cursor position.
     * @param float|string|null                     $w Cell width  (may be percentage). If null, use right margin.
     * @param float|string|null                     $h Cell height (may be percentage). If null, use bottom margin
     * @param string                                $txt
     * @param \Relaxsd\Stylesheets\Style|array|null $style
     * @param array                                 $options
     *
     * @return $this
     */
    public function cell($x = null, $y = null, $w = null, $h = null, $txt = '', $style = null, $options = [])
    {
        if (!isset($x)) $x = $this->getCursorX();
        if (!isset($y)) $y = $this->getCursorY();
        if (!isset($w)) $w = $this->getInnerWidth() - $x;
        if (!isset($h)) $h = $this->getInnerHeight() - $y;

        $style = Style::merged($this->getStyle('body'), $this->getStyle('cell'), $style);

        $w = $this->parseGlobalValue_h($w);
        $h = $this->parseGlobalValue_v($h);

        FontStyleTranslator::applyStyle($this->fpdf, $style);
        BorderTranslator::applyStyle($this->fpdf, $style);
        FillTranslator::applyStyle($this->fpdf, $style);

        $this->setCursorXY($x, $y);

        if (MultilineTranslator::translate($style)) {

            $this->fpdf->MultiCell(
                $w,
                $h,
                $txt,
                BorderTranslator::translate($style),
                AlignTranslator::translate($style),
                FillTranslator::translate($style)
            );

            $ln = CursorPlacementTranslator::translate($style, 2);

            // MultiCell always uses ln==2 (bottom left) by default, correct for ln==0 or ln==1:
            if ($ln == 1) {
                // Move cursor to left margin
                $this->setCursorX(0);
            } else if ($ln == 0) {
                // Move cursor to top right of the cell
                $this->setCursorXY($x + $w, $y);
            }

        } else {

            // FPds has hyperlink support for cells
            $link = array_key_exists('href', $options) ? $options['href'] : '';

            $this->fpdf->Cell(
                $w,
                $h,
                $txt,
                BorderTranslator::translate($style),
                CursorPlacementTranslator::translate($style),
                AlignTranslator::translate($style),
                FillTranslator::translate($style),
                $link
            );

        }

        return $this;
    }

    public function text($h = null, $txt = '', $style = null, $options = [])
    {
        $style = Style::merged($this->getStyle('body'), $this->getStyle('text'), $style);

        FontStyleTranslator::applyStyle($this->fpdf, $style);

        $link = array_key_exists('href', $options) ? $options['href'] : '';

        $this->fpdf->Write(
            $this->parseGlobalValue_v($h),
            $txt,
            $link
        );

        return $this;

    }


    // ===========================================

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
        RectStyleTranslator::applyStyle($this->fpdf, $style);

        $this->fpdf->Rect(
            $this->fpdf->lMargin + $this->parseGlobalValue_h($x),
            $this->fpdf->tMargin + $this->parseGlobalValue_v($y),
            $this->parseGlobalValue_h($w),
            $this->parseGlobalValue_v($h),
            RectStyleTranslator::translate($style)
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
        LineStyleTranslator::applyStyle($this->fpdf, $style);

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

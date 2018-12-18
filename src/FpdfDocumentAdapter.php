<?php

namespace Relaxsd\Pdflax\Fpdf;

use Anouar\Fpdf\Fpdf;
use Relaxsd\Pdflax\Color;
use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;
use Relaxsd\Pdflax\Fpdf\Translators\Align;
use Relaxsd\Pdflax\Fpdf\Translators\Border;
use Relaxsd\Pdflax\Fpdf\Translators\Fill;
use Relaxsd\Pdflax\Fpdf\Translators\FontStyle;
use Relaxsd\Pdflax\Fpdf\Translators\LineStyle;
use Relaxsd\Pdflax\Fpdf\Translators\Ln;
use Relaxsd\Pdflax\Fpdf\Translators\Multiline;
use Relaxsd\Pdflax\Fpdf\Translators\Orientation;
use Relaxsd\Pdflax\Fpdf\Translators\RectStyle;
use Relaxsd\Pdflax\Fpdf\Translators\Size;
use Relaxsd\Pdflax\PdfDOMTrait;
use Relaxsd\Pdflax\PdfStyleTrait;
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
                'font-family' => 'Arial',
                'font-style'  => '',
                'font-size'   => 11,
                'text-color'  => [0, 0, 0],
            ],
            // Adds to 'body'. Used for all cells, including p, h1, h2
            'cell'         => [
                // FPdf default for cell()
                'align'     => 'left',
                'border'    => 0,
                'fill'      => 0,
                'link'      => '',
                'multiline' => false, // Uses Cell, not MultiCell
                'ln'        => 0,
            ],

            // The paragraph type.
            // Adds to 'body' and 'cell'
            'p'            => [
                'align'     => 'left',
                // By default, FPdf always uses Ln=2 for MultiCell.
                // TODO: 1 or 2 is important for correctly recognizing page breaks
                'ln'        => 2,
                'multiline' => true, // Uses MultiCell, not Cell
            ],
            // Heading 1 type
            // Adds to 'body' and 'cell'
            'h1'           => [
                'font-style' => 'bold',
                'font-size'  => 14,
                'ln'         => 2,
                'align'      => 'left',
            ],
            // Heading 2 type
            // Adds to 'body' and 'cell'
            'h2'           => [
                'font-style' => 'bold',
                'font-size'  => 12,
                'ln'         => 2,
                'align'      => 'left',
            ],
            '.align-right' => [
                'align' => 'right',
            ],
        ]));

    }

    /**
     * @param string $family
     * @param string    $style
     * @param int    $size
     *
     * @return self
     */
    public function setFont($family, $style = self::FONT_STYLE_NORMAL, $size = 0)
    {
        $this->fpdf->SetFont(
            $family ?: '',
            FontStyle::translate($style),
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
     * @param string $family
     * @param integer $style
     * @param string $filename
     *
     * @return $this
     */
    public function registerFont($family, $style, $filename) {
        $this->fpdf->AddFont(
            $family,
            FontStyle::translate($style),
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
        return $this->fpdf->x;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->fpdf->y;
    }

    /**
     * Get the width of this document in it parent.
     *
     * @return float
     */
    public function getWidth()
    {
        return $this->fpdf->w;
    }

    /**
     * Get the height of this document in it parent.
     *
     * @return float
     */
    public function getHeight()
    {
        return $this->fpdf->h;
    }

    /**
     * @return float
     */
    public function getCursorX()
    {
        return $this->fpdf->GetX();
    }

    /**
     * @return float
     */
    public function getCursorY()
    {
        return $this->fpdf->GetY();
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
        $this->fpdf->SetX($x);

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
        $this->fpdf->SetY($y);

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
        $this->fpdf->setXY($x, $y);

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

        FontStyle::applyStyle($this->fpdf, $style);
        Border::applyStyle($this->fpdf, $style);
        Fill::applyStyle($this->fpdf, $style);

        if (Multiline::translate($style)) {

            $oldX = $this->fpdf->x;
            $oldY = $this->fpdf->y;

            $this->fpdf->MultiCell(
                $w,
                $h,
                $txt,
                Border::translate($style),
                Align::translate($style),
                Fill::translate($style)
            );

            $ln = Ln::translate($style);

            // MultiCell uses ln=2 (bottom left) by default
            if ($ln == 1) {
                // Next line
                $this->fpdf->x = $this->fpdf->lMargin;
            } else if ($ln == 0) {
                // Top right
                $this->fpdf->x = $oldX + $w;
                $this->fpdf->y = $oldY;
            }

        } else {

            $this->fpdf->Cell(
                $w,
                $h,
                $txt,
                Border::translate($style),
                Ln::translate($style),
                Align::translate($style),
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
            $x,
            $y,
            $w,
            $h,
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

        $this->fpdf->Line($x1, $y1, $x2, $y2);

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
     *
     * @return self
     */
    public function image($file, $x, $y, $w, $h, $type = '', $link = '', $style = null)
    {
        $this->fpdf->Image(
            $file,
            $x,
            $y,
            $w,
            $h,
            $type,
            $link
        );

        // TODO: Draw rect if we want a border!
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
        FontStyle::applyStyle($this->fpdf, $style);

        $this->fpdf->Write(
            $h,
            $text,
            $link
        );

    }

}

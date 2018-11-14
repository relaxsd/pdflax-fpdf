<?php

namespace Relaxsd\Pdflax\Fpdf;

use Anouar\Fpdf\Fpdf;
use Relaxsd\Pdflax\Contracts\PdfCreatorOptionsInterface;
use Relaxsd\Pdflax\Contracts\PdfDocumentInterface;
use Relaxsd\Pdflax\Creator\PdfCreator;

class FPdfPdfCreator extends PdfCreator
{

    // Maps PdfCreator options to the corresponding FPdf values
    private static $OPTION_MAPPINGS = [
        // Orientation
        PdfCreatorOptionsInterface::ORIENTATION_LANDSCAPE => 'L',
        PdfCreatorOptionsInterface::ORIENTATION_PORTRAIT  => 'P',

        // Size
        PdfCreatorOptionsInterface::SIZE_A4               => 'A4',

        // Units
        PdfCreatorOptionsInterface::UNIT_CM               => 'cm',
        PdfCreatorOptionsInterface::UNIT_INCH             => 'in',
        PdfCreatorOptionsInterface::UNIT_MM               => 'mm',
        PdfCreatorOptionsInterface::UNIT_PT               => 'pt',
    ];

    protected static $DEFAULTS = [
        'orientation' => PdfCreatorOptionsInterface::ORIENTATION_PORTRAIT,
        'unit'        => PdfCreatorOptionsInterface::UNIT_MM,
        'size'        => PdfCreatorOptionsInterface::SIZE_A4,
    ];

    /**
     * Create a PDF document
     *
     * @param array|null $options
     *
     * @return PdfDocumentInterface
     */
    public function create($options = [])
    {
        // Merge defaults, pre-set options and given options
        $options = array_merge(self::$DEFAULTS, $this->options, $options);

        // Convert from Pdflax to FPdf
        $options = self::convertOptions($options);

        // Create the FPdf instance
        $fpdf = new Fpdf($options['orientation'], $options['unit'], $options['size']);

        // Set leftmargin if given in the options
        if (array_key_exists('margin-left', $options)) {
            $fpdf->SetLeftMargin($options['margin-left']);
        }

        // Additional settings can be put on the 'raw' object later on.

        return new FpdfDocumentAdapter($fpdf);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private static function convertOptions(array $options)
    {
        return array_map(function ($optionValue) {
            return self::convertOption($optionValue);
        }, $options);
    }

    /**
     * @param mixed $optionValue
     *
     * @return mixed
     */
    private static function convertOption($optionValue)
    {
        if (is_string($optionValue) && array_key_exists($optionValue, self::$OPTION_MAPPINGS)) {
            return self::$OPTION_MAPPINGS[$optionValue];
        }

        return $optionValue;
    }
}

<?php

namespace Ooga\PdfToImage;

use Ooga\PdfToImage\Exception\PdfNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * execute command: pdftoppm [options] PDF-file PPM-root
 *
 * @package Ooga\PdfToImage
 */
class Pdf
{
    private $pdf;
    private $bin_path;

    /**
     * PPM-root
     * @var string
     */
    private $out_root_path;

    /**
     * PdfToImage constructor.
     * @param string|null $bin_path
     */
    public function __construct(string $bin_path = null)
    {
        $this->bin_path = $bin_path ?? '/usr/bin/pdftoppm';
    }

    /**
     * set a pdf file path
     * @param string $pdf
     * @param string|null $out_root_path
     * @return $this
     * @throws PdfNotFoundException
     */
    public function setPdf(string $pdf, string $out_root_path = './out')
    {
        if (!file_exists($pdf)) {
            throw new PdfNotFoundException("could not find pdf {$pdf}");
        }

        $this->pdf = $pdf;
        $this->out_root_path = $out_root_path;

        return $this;
    }

    /**
     * generate image from pdf
     * @return string
     */
    public function image(): string
    {
        $file_name = escapeshellarg($this->pdf);
        $out_prefix = escapeshellarg($this->out_root_path);
        $process = new Process("{$this->bin_path} -png " . $file_name . " {$out_prefix}");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return trim($process->getOutput());
    }

    /**
     * simple generate image from pdf.
     * @param string $pdf
     * @param string|null $binPath
     * @return string
     * @throws PdfNotFoundException
     */
    public static function getSimpleImage(string $pdf, string $binPath = null): string
    {
        return (new static($binPath))
            ->setPdf($pdf)
            ->image();
    }
}
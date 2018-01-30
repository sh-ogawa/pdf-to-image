<?php

namespace Ooga\PdfToImage;


use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{

    /**
     * @test
     */
    public function イメージファイル変換()
    {
        Pdf::getSimpleImage('pdf-to-image.pdf');
        $this->assertTrue(true);
    }

}
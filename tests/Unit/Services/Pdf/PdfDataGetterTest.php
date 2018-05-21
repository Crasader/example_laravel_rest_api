<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use Tests\TestCase;
use App\Services\Pdf\PdfDataGetter;
use App\User;
use App\Structs\PdfData;

class PdfDataGetterTest extends TestCase
{
    const PDF_TYPES_NUMBER = 3;

    /**
     * @var PdfDataGetter
     */
    private $pdfDataGetter;

    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $this->pdfDataGetter = new PdfDataGetter();
        $this->user = new User;
    }

    public function testGet_PdfDataHas3CorrectElements()
    {
        $this->user->name = 'John Doe';
        $this->user->email = 'john.doe@email.com';
        $customTexts = [
            'text_short' => 'abcde1',
            'text_full' => 'abcde2',
            'text_advanced' => 'abcde3',
        ];
        $pdfDataArray = $this->pdfDataGetter->get($this->user, $customTexts);

        $this->assertEquals(self::PDF_TYPES_NUMBER, count($pdfDataArray));

        foreach ($pdfDataArray as $pdfType => $pdfData) {
            $this->assertInstanceOf(PdfData::class, $pdfData);
            $this->assertEquals($pdfData->email, $this->user->email);
            $this->assertEquals($pdfData->name, $this->user->name);
            $title = PdfTypes::$titles[$pdfType];
            $fieldName = sprintf(PdfDataGetter::TEXT_FIELD_PATTERN, $title);
            $text = $customTexts[$fieldName];
            $this->assertEquals($pdfData->text, $text);
        }
    }

    public function testGet_PdfDataArrayIsEmpty()
    {
        $customTexts = ['test_assdq' => 'wrong test'];
        $pdfDataArray = $this->pdfDataGetter->get($this->user, $customTexts);
        $this->assertEquals([], $pdfDataArray);
    }

    public function testGet_PdfDataArrayHasOnly1Element()
    {
        $customTexts = [
            'test_assdq' => 'wrong test',
            'text_short' => 'abcde1',
        ];
        $pdfDataArray = $this->pdfDataGetter->get($this->user, $customTexts);
        $this->assertEquals(1, count($pdfDataArray));
    }
}

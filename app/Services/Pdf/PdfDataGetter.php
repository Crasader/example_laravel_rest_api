<?php

namespace App\Services\Pdf;

use App\Structs\PdfData;
use App\User;
use App\Constants\PdfTypes;

class PdfDataGetter
{
    const TEXT_FIELD_PATTERN = 'text_%s';

    /**
     * @param  User $user
     * @param  string[] $customTexts
     * @return PdfData[]|array
     */
    public function get(User $user, $customTexts)
    {
        $pdfDataArray = [];
        $pdfTypeTitles = $this->getPdfTypeTitles();

        foreach ($pdfTypeTitles as $pdfType) {
            $fieldName = sprintf(self::TEXT_FIELD_PATTERN, $pdfType);
            if (array_key_exists($fieldName, $customTexts)) {
                $pdfData = new PdfData;
                $pdfData->text = $customTexts[$fieldName];
                $pdfData->name = $user->name;
                $pdfData->email = $user->email;

                $pdfDataArray[$pdfType] = $pdfData;
            }
        }

        return $pdfDataArray;
    }

    /**
     * @return string[]
     */
    private function getPdfTypeTitles()
    {
        return [
            PdfTypes::$titles[PdfTypes::SHORT],
            PdfTypes::$titles[PdfTypes::FULL],
            PdfTypes::$titles[PdfTypes::ADVANCED],
        ];
    }
}

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

        foreach ($this->getPdfTypes() as $pdfType) {
            $pdfTitle = PdfTypes::$titles[$pdfType];
            $fieldName = sprintf(self::TEXT_FIELD_PATTERN, $pdfTitle);
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
     * @return int[]
     */
    private function getPdfTypes()
    {
        return [
            PdfTypes::SHORT,
            PdfTypes::FULL,
            PdfTypes::ADVANCED,
        ];
    }
}

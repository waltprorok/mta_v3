<?php

namespace App\Services;

class PhoneNumberService
{
    /**
     * @param String|null $phoneNumber
     * @return string|null
     */
    public function stripPhoneNumber(?string $phoneNumber): ?string
    {
        if($phoneNumber !== null) {
            return preg_replace('/[^[:digit:]]/', '', $phoneNumber);
        } else {
            return null;
        }
    }

    /**
     * @param $phoneNumber
     * @return string|null
     */
    public function getPhoneNumberFormat($phoneNumber): ?string
    {
        if ($phoneNumber !== null) {
            $cleaned = $this->stripPhoneNumber($phoneNumber);

            switch (strlen($cleaned)) {
                case 10:
                    preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                    return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
                case 11:
                    preg_match('/(\d{1})(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                    return "{$matches[1]}-{$matches[2]}-{$matches[3]}-{$matches[4]}";
                default:
                    return $phoneNumber;
            }
        }

        return null;
    }
}

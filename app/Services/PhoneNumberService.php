<?php

namespace App\Services;

class PhoneNumberService
{
    /**
     * @param String|null $phoneNumber
     * @return array|string|string[]|null
     */
    public function stripPhoneNumber(?String $phoneNumber)
    {
        if($phoneNumber !== null) {
            return preg_replace('/\D+/', '', $phoneNumber);
        } else {
            return null;
        }
    }

    /**
     * @param $phoneNumber
     * @return mixed|string|null
     */
    public function getPhoneNumberFormat($phoneNumber)
    {
        if ($phoneNumber !== null) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $phoneNumber);

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

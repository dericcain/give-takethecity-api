<?php

namespace App\Helpers;

class Phone
{
    /**
     * When we store phone numbers, we only want the numbers and not
     * @param  string $phoneNumber
     * @return string
     */
    public static function onlyNumbers($phoneNumber)
    {
        return preg_replace("/[^0-9]/", "", $phoneNumber);
    }

    /**
     * We will format the number to make it look pretty for when we display it.
     *
     * @param  string $phoneNumber
     * @return string
     */
    public static function format($phoneNumber)
    {
        return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phoneNumber);
    }
}

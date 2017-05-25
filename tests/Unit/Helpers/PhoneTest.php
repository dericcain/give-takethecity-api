<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Phone;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    /** @test */
    function a_phone_number_only_returns_numbers()
    {
        $number1 = '(205) 902-3961';
        $number2 = '205 902-3961';
        $number3 = '205-902-3961';
        $number4 = '205.902.3961';

        $convertedNumber1 = Phone::onlyNumbers($number1);
        $convertedNumber2 = Phone::onlyNumbers($number2);
        $convertedNumber3 = Phone::onlyNumbers($number3);
        $convertedNumber4 = Phone::onlyNumbers($number4);

        $this->assertEquals('2059023961', $convertedNumber1);
        $this->assertEquals('2059023961', $convertedNumber2);
        $this->assertEquals('2059023961', $convertedNumber3);
        $this->assertEquals('2059023961', $convertedNumber4);
    }

    /** @test */
    function a_phone_number_is_properly_formatted()
    {
        $expected = '(205) 902-3961';
        $number = Phone::format('2059023961');

        $this->assertEquals($expected, $number);
    }
}

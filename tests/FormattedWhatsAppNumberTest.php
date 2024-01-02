<?php
require_once DRRB . DS . 'app/helper/common.php';
use PHPUnit\Framework\TestCase;

class FormattedWhatsAppNumberTest extends TestCase
{
    // Helper variable - Replace this with your phone number
    private $phoneNumberCase = [
        [
            '0Prefix' => '0899340282972',
            '62Prefix' => '62899340282972',
            '+62Prefix' => '+62899340282972',
            'withHypen' => '+62-8993-4028-2972',
            'withSpace' => '+62 8993 4028 2972',
            'withoutPrefix' => '899340282972'
        ],

        [
            '0Prefix' => '085801932475',
            '62Prefix' => '6285801932475',
            '+62Prefix' => '+6285801932475',
            'withHypen' => '+62-858-0193-2475',
            'withSpace' => '+62 858 0193 2475',
            'withoutPrefix' => '85801932475'
        ],

        [
            '0Prefix' => '08157483059',
            '62Prefix' => '628157483059',
            '+62Prefix' => '+628157483059',
            'withHypen' => '+62-815-748-3059',
            'withSpace' => '+62 815 748 3059',
            'withoutPrefix' => '8157483059'
        ],

        [
            '0Prefix' => '0811567890',
            '62Prefix' => '62811567890',
            '+62Prefix' => '+62811567890',
            'withHypen' => '+62-811-567-890',
            'withSpace' => '+62 811 567 890',
            'withoutPrefix' => '811567890'
        ],
    ];

    // Test case for 13 digits default number (0prefix)
    public function testPhoneNumber_13Digits_on12Characters_withoutPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['withoutPrefix']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_13Digits_on13Characters_withZeroPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['0Prefix']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_13Digits_on14Characters_with62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['62Prefix']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_13Digits_on15Characters_withPlus62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['+62Prefix']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_13Digits_on18Characters_plus62Prefix_withHypen()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['withHypen']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_13Digits_on18Characters_plus62Prefix_withSpace()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[0]['withSpace']);

        $expectedOutput = $this->phoneNumberCase[0]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    // Test case for 12 digits default number (0prefix)
    public function testPhoneNumber_12Digits_on11Characters_withoutPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['withoutPrefix']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_12Digits_on12Characters_withZeroPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['0Prefix']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_12Digits_on13Characters_with62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['62Prefix']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_12Digits_on14Characters_withPlus62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['+62Prefix']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_12Digits_on15Characters_plus62Prefix_withHypen()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['withHypen']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_12Digits_on15Characters_plus62Prefix_withSpace()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[1]['withSpace']);

        $expectedOutput = $this->phoneNumberCase[1]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    // Test case for 11 digits default number (0prefix)
    public function testPhoneNumber_11Digits_on10Characters_withoutPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['withoutPrefix']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_11Digit_on11Characterss_withZeroPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['0Prefix']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_11Digits_on12Characters_with62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['62Prefix']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_11Digits_on13Characters_withPlus62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['+62Prefix']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_11Digits_on15Characters_plus62Prefix_withHypen()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['withHypen']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_11Digits_on15Characters_plus62Prefix_withSpace()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[2]['withSpace']);

        $expectedOutput = $this->phoneNumberCase[2]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    // Test case for 10 digits default number (0prefix)
    public function testPhoneNumber_10Digits_on9Characters_withoutPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['withoutPrefix']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_10Digits_on10Characters_withZeroPrefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['0Prefix']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_10Digits_on11Characters_with62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['62Prefix']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_10Digits_on12Characters_withPlus62Prefix()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['+62Prefix']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_10Digits_on14Characters_plus62Prefix_withHypen()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['withHypen']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPhoneNumber_10Digits_on14Characters_plus62Prefix_withSpace()
    {
        $result = formatWhatsAppNumberInto62Format($this->phoneNumberCase[3]['withSpace']);

        $expectedOutput = $this->phoneNumberCase[3]['62Prefix'];

        $this->assertEquals($expectedOutput, $result);
    }

    public function testInvalidPhoneNumber_10Digits_on10Characters()
    {
        $result = formatWhatsAppNumberInto62Format('2312617222');

        $expectedOutput = '2312617222';

        $this->assertEquals($expectedOutput, $result);
    }

    public function testInvalidPhoneNumber_11Digits_on11Characters()
    {
        $result = formatWhatsAppNumberInto62Format('23123617222');

        $expectedOutput = '23123617222';

        $this->assertEquals($expectedOutput, $result);
    }

    public function testInvalidPhoneNumber_12Digits_on12Characters()
    {
        $result = formatWhatsAppNumberInto62Format('231236172223');

        $expectedOutput = '231236172223';

        $this->assertEquals($expectedOutput, $result);
    }
}

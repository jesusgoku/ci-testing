<?php

namespace Linets\Util;

use PHPUnit_Framework_TestCase;

class RutUtilTest extends PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $rutList = array(
            '11.111.111-1' => true,
            '111111111' => true,
            '11.111.111-2' => false,
            '11.111.1112' => false,
            '11111111-1' => true,
            '11.111111-1' => true,
        );

        foreach ($rutList as $rut => $expected) {
            if ($expected) {
                $this->assertTrue(RutUtil::validate($rut));
            } else {
                $this->assertFalse(RutUtil::validate($rut));
            }
        }
    }

    public function testVerificationDigit()
    {
        $rutList = array(
            '11.111.111' => 1,
            '11.111111' => 1,
            '11111.111' => 1,
            '11111111' => 1,
        );

        foreach ($rutList as $rutPart => $verificationDigit) {
            $this->assertEquals($verificationDigit, RutUtil::getVerificationDigit($rutPart));
        }
    }

    public function testFormat()
    {
        $rutList = array(
            '11.111.111-1' => '11.111.111-1',
            '11111.111-1' => '11.111.111-1',
            '11.111111-1' => '11.111.111-1',
            '11111111-1' => '11.111.111-1',
            '11.111.1111' => '11.111.111-1',
            '111111111' => '11.111.111-1',
        );

        foreach ($rutList as $rut => $expected) {
            $this->assertEquals($expected, RutUtil::format($rut));
        }
    }
}

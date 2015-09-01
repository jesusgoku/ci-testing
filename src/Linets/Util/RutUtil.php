<?php

namespace Linets\Util;

/**
 * Collection of work with RUT.
 *
 * @author  Jesús Urrutia <jesus.urrutia@gmail.com>
 */
class RutUtil
{
    /**
     * Array key for rut part
     */
    const RUT_PART = 0;

    /**
     * Array key for verification digit part
     */
    const VD_PART = 1;

    /**
     * Verification digit when digit is equal to 10
     */
    const VD_K_CHARACTER = 'k';

    /**
     * Verification digit when digit is equal to k
     */
    const VD_K_NUMBER = 10;

    /**
     * Validate verification.
     *
     * @param  string $rawRut
     * @return boolean
     */
    public static function validate($rawRut)
    {
        $parts = self::split(self::sanity($rawRut));

        return $parts[self::VD_PART]  == self::getVerificationDigit($parts[self::RUT_PART]);
    }

    /**
     * Get verification digit from rut part.
     *
     * @param  string $rawRutPart
     * @return string
     */
    public static function getVerificationDigit($rawRutPart)
    {
        $rutPart = self::sanityRutPart($rawRutPart);
        for ($sum = 0, $factor = 2; 0 < $rutPart; ++$factor) {
            $sum += $factor * ($rutPart % 10);
            $rutPart /= 10;

            if (7 === $factor) {
                $factor = 1;
            }
        }

        return (string) self::normalizeVerificationDigit(11 - ($sum % 11));
    }

    /**
     * Format rut.
     *
     * @param  string $rawRut
     * @return string
     */
    public static function format($rawRut)
    {
        $parts = self::split(self::sanity($rawRut));

        return self::formatRutPart($parts[self::RUT_PART]) . '-' . $parts[self::VD_PART];
    }

    /**
     * Format rut part.
     *
     * @param  int $rawRutPart
     * @return string
     */
    private static function formatRutPart($rawRutPart)
    {
        return self::addThousandSeparators($rawRutPart);
    }

    private static function addThousandSeparators($numberRaw)
    {
        $number = '';
        for ($i = 0; 0 < $numberRaw; ++$i) {
            if (0 !== $i && 0 === $i % 3) {
                $number = '.' . $number;
            }

            $number = ($numberRaw % 10) . $number;
            $numberRaw = (int) floor($numberRaw / 10);
        }

        return $number;
    }

    /**
     * Normalize verification digit to letter if necessary.
     *
     * @param  int $rawVerificationDigit
     * @return string
     */
    private static function normalizeVerificationDigit($rawVerificationDigit)
    {
        return (string) (self::VD_K_NUMBER === $rawVerificationDigit ? self::VD_K_CHARACTER : $rawVerificationDigit);
    }

    /**
     * Clear rut part of contain only digits.
     *
     * @param  string $rutPart
     * @return int
     */
    private static function sanityRutPart($rutPart)
    {
        return (int) preg_replace('/[^\d]/', '', $rutPart);
    }

    /**
     * Sanity raw rut input for contains only valid digits.
     *
     * @param  string $rawRut
     * @return string
     */
    private static function sanity($rawRut)
    {
        return preg_replace('/[^k\d]/', '', strtolower($rawRut));
    }

    /**
     * Split sanity rut in rut part and digit part.
     *
     * @param  string $sanityRut
     * @return array
     */
    private static function split($sanityRut)
    {
        if (preg_match('/[^k0-9]/', $sanityRut)) {
            throw new \InvalidArgumentException('Before sanity rut');
        }

        return array(
            self::RUT_PART => (int) substr($sanityRut, 0, -1),
            self::VD_PART => (string) substr($sanityRut, -1),
        );
    }
}

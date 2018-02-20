<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Utils\Text;

/**
 * Class Normalize.
 *
 * @package YoannBlot\Framework\Utils\Text
 */
class Normalize
{

    /**
     * Remove accents.
     *
     * @param string $sText
     *            text
     *
     * @return string text without accents.
     */
    public static function removeAccents(string $sText): string
    {
        $aAccents = [
            'À',
            'Â',
            'Ä',
            'Á',
            'Ã',
            'Å',
            'Î',
            'Ï',
            'Ì',
            'Í',
            'Ô',
            'Ö',
            'Ò',
            'Ó',
            'Õ',
            'Ø',
            'Ù',
            'Û',
            'Ü',
            'Ú',
            'É',
            'È',
            'Ê',
            'Ë',
            'Ç',
            'Ÿ',
            'Ñ',
            'Œ',
            '&OElig;',
            'à',
            'â',
            'ä',
            'á',
            'ã',
            'å',
            'î',
            'ï',
            'ì',
            'í',
            'ô',
            'ö',
            'ò',
            'ó',
            'õ',
            'ø',
            'ù',
            'û',
            'ü',
            'ú',
            'é',
            'è',
            'ê',
            'ë',
            'ç',
            'ÿ',
            'ý',
            'ñ',
            'œ',
            '&oelig;',
            'š'
        ];
        $aReplaces = [
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'I',
            'I',
            'I',
            'I',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'U',
            'U',
            'U',
            'U',
            'E',
            'E',
            'E',
            'E',
            'C',
            'Y',
            'N',
            'OE',
            'OE',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'i',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'e',
            'e',
            'e',
            'e',
            'c',
            'y',
            'y',
            'n',
            'oe',
            'oe',
            's'
        ];
        $sText = str_replace($aAccents, $aReplaces, $sText);

        return $sText;
    }

    /**
     * Remove all spaces of a text.
     *
     * @param string $sText text.
     *
     * @return string cleaned text.
     */
    public static function removeSpaces(string $sText): string
    {
        $sText = htmlentities($sText);
        $sText = str_replace('&nbsp;', '', $sText);
        $sText = html_entity_decode($sText);
        $sText = str_replace([" ", "\n", "\t", "\r", "\0", "\x0B"], '', trim($sText));
        $sText = trim($sText);
        return $sText;
    }
}
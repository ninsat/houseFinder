<?php

namespace YoannBlot\Framework\Validator;

/**
 * Class Boolean
 *
 * @package YoannBlot\Framework\Validator
 * @author  Yoann Blot
 */
abstract class Boolean {

    /**
     * Get a boolean value.
     *
     * @param mixed $mValue        value.
     * @param bool  $bDefaultValue default value if $mValue is not valid.
     *
     * @return bool converted value.
     */
    public static function getValue ($mValue, $bDefaultValue = false): bool {
        $bReturn = $bDefaultValue;
        if (is_bool($mValue)) {
            $bReturn = (bool)$mValue;
        } elseif (is_numeric($mValue)) {
            $mValue = intval($mValue);
            if (1 === $mValue) {
                $bReturn = true;
            } elseif (0 === $mValue) {
                $bReturn = false;
            }
        } elseif (is_string($mValue)) {
            $mValue = strtolower($mValue);
            if ("true" === $mValue) {
                $bReturn = true;
            } elseif ("false" === $mValue) {
                $bReturn = false;
            }
        }

        return $bReturn;
    }
}
<?php

namespace YoannBlot\Framework\Helper;

/**
 * Class Reflection Helper.
 *
 * @package Framework\Helper
 */
abstract class Reflection {

    /**
     * @param object $oObject
     * @param string $sPropertyName property name
     * @param mixed  $mValue
     *
     * @return \ReflectionProperty $oProperty
     */
    public static function setProperty ($oObject, $sPropertyName, $mValue) {
        $oReflection = new \ReflectionClass($oObject);
        $oProperty = $oReflection->getProperty($sPropertyName);
        $oProperty->setAccessible(true);
        $oProperty->setValue($oObject, $mValue);

        return $oProperty;
    }

    /**
     * @param object $oObject
     * @param string $sMethodName method name to invoke.
     *
     * @return \ReflectionMethod method reflection
     */
    public static function getMethod ($oObject, $sMethodName): \ReflectionMethod {
        $oReflection = new \ReflectionClass($oObject);
        $oMethod = $oReflection->getMethod($sMethodName);
        $oMethod->setAccessible(true);

        return $oMethod;
    }

    /**
     * @param object $oObject     object to invoke method.
     * @param string $sMethodName method name to invoke.
     *
     * @return mixed method result value.
     */
    public static function getValue ($oObject, $sMethodName) {
        $oMethodGetViewDirectory = static::getMethod($oObject, $sMethodName);
        $sResult = $oMethodGetViewDirectory->invoke($oObject);

        return $sResult;
    }

}
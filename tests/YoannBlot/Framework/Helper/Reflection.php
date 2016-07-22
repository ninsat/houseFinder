<?php

namespace YoannBlot\Framework\Helper;

/**
 * Class Reflection Helper.
 *
 * @package Framework\Helper
 * @author  Yoann Blot
 */
abstract class Reflection {

    /**
     * Set a property value which can be private / protected.
     *
     * @param object $oObject       object to set property on.
     * @param string $sPropertyName property name
     * @param mixed  $mValue        value to set
     *
     * @return \ReflectionProperty $oProperty
     */
    public static function setProperty ($oObject, string $sPropertyName, $mValue): \ReflectionProperty {
        $oReflection = new \ReflectionClass($oObject);
        $oProperty = $oReflection->getProperty($sPropertyName);
        $oProperty->setAccessible(true);
        $oProperty->setValue($oObject, $mValue);

        return $oProperty;
    }

    /**
     * Get a private / protected method on the given object.
     *
     * @param object $oObject
     * @param string $sMethodName method name to invoke.
     *
     * @return \ReflectionMethod method reflection
     */
    public static function getMethod ($oObject, string $sMethodName): \ReflectionMethod {
        $oReflection = new \ReflectionClass($oObject);
        $oMethod = $oReflection->getMethod($sMethodName);
        $oMethod->setAccessible(true);

        return $oMethod;
    }

    /**
     * Call a private / protected method on th given object, and get the value.
     * This method can only be used without parameters, use getMethod() and invoke if you need to pass parameters.
     *
     * @param object $oObject     object to invoke method.
     * @param string $sMethodName method name to invoke.
     *
     * @return mixed method result value.
     */
    public static function getValue ($oObject, string $sMethodName) {
        $oMethodGetViewDirectory = static::getMethod($oObject, $sMethodName);
        $mResult = $oMethodGetViewDirectory->invoke($oObject);

        return $mResult;
    }

}
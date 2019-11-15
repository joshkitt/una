<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Service calls to modules' methods.
 *
 * The class has one static method to make service calls to the module's methods
 *
 *
 * Example of usage:
 * @code
 * $isSpam = BxDolService::call('bx_antispam', 'is_spam', array($sText));
 * @endcode
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolService extends BxDol
{
    static protected $_aMemoryCache = array();

    /**
     * Perform serice call
     * @param $mixed module name or module id
     * @param $sMethod service method name in format 'method_name', corresponding class metod is serviceMethodName
     * @param $aParams params to pass to service method
     * @param $sClass class to search for service method, by default it is main module class
     * @return service call result
     */
    public static function call($mixed, $sMethod, $aParams = array(), $sClass = 'Module', $bIgnoreCache = false)
    {
        $oDb = BxDolModuleQuery::getInstance();

        $aModule = array();
        if(is_string($mixed))
            $aModule = $oDb->getModuleByName($mixed);
        else
            $aModule = $oDb->getModuleById($mixed);

        if (empty($aModule))
            return '';

        $sKey = md5($mixed . $sMethod . print_r($aParams, true) . $sClass);
        if (!$bIgnoreCache && isset(self::$_aMemoryCache[$sKey]))
            return self::$_aMemoryCache[$sKey];

        self::$_aMemoryCache[$sKey] = BxDolRequest::processAsService($aModule, $sMethod, $aParams, $sClass);

        return self::$_aMemoryCache[$sKey];
    }

    /**
     * Perform serice call by accepting serialized array of service call parameters:
     * @code
     *     array (
     *         'module' => 'system', // module name
     *         'method' => 'test', // service method name
     *         'params' => array(), // array of parameters to pass to service method
     *         'class' => 'Module', // class to search service method in
     *     )
     * @endcode
     *
     * @param $s serialized array of serice call
     * @param $aMarkers service method name in format 'method_name', corresponding class metod is serviceMethodName
     * @param $sReplaceIn params to pass to service method
     * @return service call result
     */
    public static function callSerialized($s, $aMarkers = array(), $sReplaceIn = 'params')
    {
        $a = @unserialize($s);
        if (false === $a || !is_array($a))
            return '';

        if (isset($a[$sReplaceIn]) && $aMarkers)
            $a[$sReplaceIn] = bx_replace_markers($a[$sReplaceIn], $aMarkers);

        return self::call($a['module'], $a['method'], isset($a['params']) ? $a['params'] : array(), isset($a['class']) ? $a['class'] : 'Module', isset($a['ignore_cache']) ? $a['ignore_cache'] : false);
    }

    public static function callMacro($s, $aMarkers = array(), $sReplaceIn = 'params')
    {
        if ($aMarkers)
            $s = bx_replace_markers($s, $aMarkers);

        if (!preg_match("/^([a-zA-Z0-9_\:]+)(.*)$/", $s, $aMatches))
            return '<b></b>';

        $a = explode(":", $aMatches[1]);
        if (!isset($a[0]) || !isset($a[1]))
            return '';

        $aParams = array();
        if (!empty($aMatches[2]))
            $aParams = json_decode($aMatches[2]);
        if (!$aParams)
            $aParams = array();

        return self::call($a[0], $a[1], $aParams, isset($a[3]) ? $a[3] : 'Module', isset($a[4]) && 'ignore_cache' == $a[4] ? true : false);
    }

    /**
     * Check if string is serialized array
     */
    public static function isSerializedService($s)
    {
        return preg_match('/^a:[\d+]:\{/', $s);
    }

    /**
     * Serialized service call array
     */
    public static function getSerializedService($mixedModule, $sMethod, $aParams = array(), $sClass = '')
    {
		$aService = array(
			'module' => $mixedModule,
			'method' => $sMethod,
		);

		if(!empty($aParams))
			$aService['params'] = $aParams;

		if(!empty($sClass))
			$aService['class'] = $sClass;

		return serialize($aService);
    }
}

/** @} */

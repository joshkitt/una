<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPersonsPrivacy extends BxBaseModProfilePrivacy
{
    function __construct($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_persons';
        parent::__construct($aOptions, $oTemplate);
    }
}

/** @} */

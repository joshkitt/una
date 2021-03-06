<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioModules extends BxDol
{
    protected $sJsObject;
    protected $sLangPrefix;
    protected $sTemplPrefix;

    function __construct()
    {
        parent::__construct();

        $this->oDb = new BxDolStudioModulesQuery();
        $this->sJsObject = 'oBxDolStudioModules';
        $this->sLangPrefix = 'mod';
        $this->sTemplPrefix = 'mod';
        $this->sParamPrefix = 'mod';
    }

    function serviceGetActions($aWidget)
    {
        return array(
            array (
                'caption' => _t('_adm_txt_uninstall'),
                'link' => '',
                'click' => $this->sJsObject . ".uninstall(" . $aWidget['id'] . ", '" . $aWidget['page_name'] . "', 0)",
                'icon' => 'times'
            )
        );
    }

    function processActions()
    {
        if(($sAction = bx_get($this->sParamPrefix . '_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_' . $this->sLangPrefix . '_err_cannot_process_action'));
            switch($sAction) {
                case 'uninstall':
                    $sPageName = bx_process_input(bx_get($this->sParamPrefix . '_page_name'));
                    if(empty($sPageName))
                        break;

                    $aModule = BxDolModuleQuery::getInstance()->getModuleByName($sPageName);
                    if(empty($aModule) || !is_array($aModule))
                        break;

                    if(($iWidgetId = (int)bx_get($this->sParamPrefix . '_widget_id')) != 0 && (int)bx_get($this->sParamPrefix . '_confirmed') != 1) {
                        $aResult['message'] = $this->getPopupConfirm($iWidgetId, $aModule);
                        break;
                    }

                    $aResult = BxDolStudioInstallerUtils::getInstance()->perform($aModule['path'], 'uninstall', array('html_response' => true));
                    if(!empty($aResult['message']))
                        $aResult['message'] = $this->getPopupResult($aResult['message']);

                    if($aResult['code'] == BX_DOL_STUDIO_IU_RC_SUCCESS)
                        BxTemplStudioMenuTop::historyDelete($sPageName);
                    break;
            }

            if(!empty($aResult['message']))
                $aResult['message'] = BxTemplStudioFunctions::getInstance()->transBox('', $aResult['message']);

            echo json_encode($aResult);
            exit;
        }
    }
}

/** @} */

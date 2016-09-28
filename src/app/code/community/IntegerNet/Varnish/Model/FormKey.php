<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * since magento version 1.8 form key is used on product view page for cart request
 * in most case product view page is cached by varnish with wrong form key
 *
 * Class IntegerNet_Varnish_Model_FormKey
 */
class IntegerNet_Varnish_Model_FormKey extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * form key param name
     * see template app/design/frontend/base/default/template/core/formkey.phtml
     */
    const FORM_KEY_PARAM_NAME = 'form_key';


    /**
     * @return void
     */
    public function updateFormKey()
    {
        if ($this->getConfig()->isEnabled()
            && $this->getRequest()->getParam(self::FORM_KEY_PARAM_NAME)
            && $this->_isInjectFormKeyRoute()
        ) {

            /** @var Mage_Core_Model_Session $session */
            $session = Mage::getSingleton('core/session');

            $this->getRequest()->setParam(self::FORM_KEY_PARAM_NAME, $session->getFormKey());
        }
    }


    /**
     * @return boolean
     */
    protected function _isInjectFormKeyRoute()
    {
        $route = $this->getRequest()->getRequestedRouteName();
        $controller = $this->getRequest()->getRequestedControllerName();
        $action = $this->getRequest()->getRequestedActionName();

        foreach ($this->getConfig()->getInjectFormKeyRouts() as $injectFormKeyRoute) {

            if ($injectFormKeyRoute['route'] == $route) {

                if ($injectFormKeyRoute['controller'] == $controller || $injectFormKeyRoute['controller'] == '*') {

                    if ($injectFormKeyRoute['action'] == $action || $injectFormKeyRoute['action'] == '*') {

                        return true;
                    }
                }
            }
        }

        return false;
    }
}

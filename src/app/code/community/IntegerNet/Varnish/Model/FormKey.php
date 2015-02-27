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
     * @var string
     */
    protected $_formKey;


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_formKey = Mage::getSingleton('core/session')->getFormKey();
    }


    /**
     * @return void
     */
    public function updateFormKey()
    {
        if ($this->_config->isEnabled()
            && $this->_request->getParam(self::FORM_KEY_PARAM_NAME)
            && $this->isInjectFormKeyRoute()
        ) {

            $this->_request->setParam(self::FORM_KEY_PARAM_NAME, $this->_formKey);
        }
    }


    /**
     * @return boolean
     */
    public function isInjectFormKeyRoute()
    {
        $route = $this->_request->getRequestedRouteName();
        $controller = $this->_request->getRequestedControllerName();
        $action = $this->_request->getRequestedActionName();

        foreach ($this->_config->getInjectFormKeyRouts() as $injectFormKeyRoute) {

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

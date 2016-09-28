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
 * Class IntegerNet_Varnish_Model_Abstract
 */
class IntegerNet_Varnish_Model_Abstract
{

    /**
     *
     */
    const DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM = 'dynamicblock';
    

    /**
     * @return IntegerNet_Varnish_Model_Config
     */
    public function getConfig()
    {
        return Mage::getSingleton('integernet_varnish/config');
    }


    /**
     * @return IntegerNet_Varnish_Model_Validate
     */
    public function getValidate()
    {
        return Mage::getSingleton('integernet_varnish/validate');
    }


    /**
     * @return IntegerNet_Varnish_Model_Index_Helper
     */
    public function getIndex()
    {
        return Mage::getSingleton('integernet_varnish/index_helper');
    }


    /**
     * @return Mage_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return Mage::app()->getRequest();
    }


    /**
     * @return Zend_Controller_Response_Http
     */
    public function getResponse()
    {
        return Mage::app()->getResponse();
    }


    /**
     * @return Mage_Core_Model_Cookie
     */
    public function getCookies()
    {
        return Mage::getSingleton('core/cookie');
    }
}

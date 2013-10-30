<?php
/**
 * integer_net Magento Module
 *
 * @category IntegerNet
 * @package IntegerNet_<Module>
 * @copyright  Copyright (c) 2012-2013 integer_net GmbH (http://www.integer-net.de/)
 * @author Viktor Franz <vf@integer-net.de>
 */

/**
 * Enter description here ...
 */
class IntegerNet_Varnish_Model_Invalidate_Response_Customer implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'customer';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Customer';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Customer is logged in';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::helper('customer')->getCustomer()->getId();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
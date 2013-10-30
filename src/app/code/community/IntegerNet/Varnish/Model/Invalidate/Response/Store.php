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
class IntegerNet_Varnish_Model_Invalidate_Response_Store implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'store';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Store';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'None default store view';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return Mage::app()->getDefaultStoreView()->getId() != Mage::app()->getStore()->getId();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
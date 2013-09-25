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
class IntegerNet_Varnish_Model_Invalidate_Currency implements IntegerNet_Varnish_Model_Invalidate_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'currency';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Currency';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'None default currency';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return Mage::app()->getStore()->getDefaultCurrencyCode() != Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
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
class IntegerNet_Varnish_Model_Invalidate_Checkout implements IntegerNet_Varnish_Model_Invalidate_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'checkout';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Checkout';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Items in cart';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::helper('checkout')->getQuote()->hasItems();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
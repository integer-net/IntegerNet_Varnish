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
class IntegerNet_Varnish_Model_Invalidate_Compare implements IntegerNet_Varnish_Model_Invalidate_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'compare';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Compare';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Products on compare list';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::helper('catalog/product_compare')->hasItems();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
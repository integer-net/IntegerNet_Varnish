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
class IntegerNet_Varnish_Model_Invalidate_Response_RecentlyCompared implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'recently_compared';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Recently Compared';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Products on recently compared list';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::app()->getLayout()->createBlock('reports/product_compared')->getCount();
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
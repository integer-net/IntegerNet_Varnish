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
 * Class IntegerNet_Varnish_Model_Invalidate_Response_Store
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
     * @return bool
     */
    public function hasData()
    {
        return Mage::app()->getDefaultStoreView()->getId() != Mage::app()->getStore()->getId();
    }
}

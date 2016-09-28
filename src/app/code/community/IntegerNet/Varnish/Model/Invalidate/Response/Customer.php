<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Model_Invalidate_Response_Customer
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
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::helper('customer')->getCustomer()->getId();
    }
}

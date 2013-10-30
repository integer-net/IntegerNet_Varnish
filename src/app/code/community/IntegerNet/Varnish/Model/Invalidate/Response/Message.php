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
class IntegerNet_Varnish_Model_Invalidate_Response_Message implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return 'message';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Message';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Message';
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        $sessionModelNames = array(
            'customer/session',
            'catalog/session',
            'checkout/session',
            'wishlist/session',
            'review/session',
            'tag/session',
            'paypal/session'
        );

        foreach ($sessionModelNames as $sessionModelName) {

            /** @var $sessionModel Mage_Core_Model_Session */
            $sessionModel = Mage::getSingleton($sessionModelName);
            if($sessionModel->getMessages()->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasChange()
    {
        return false;
    }
}
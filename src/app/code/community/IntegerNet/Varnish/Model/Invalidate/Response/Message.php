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
 * Class IntegerNet_Varnish_Model_Invalidate_Response_Message
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
     * @return bool
     */
    public function hasData()
    {
        $sessionModelNames = array(
            'core/session',
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
            if ($sessionModel->getMessages()->count()) {
                return true;
            }
        }

        return false;
    }
}

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
class IntegerNet_Varnish_Model_Control_Varnish implements Mage_PageCache_Model_Control_Interface
{
    /**
     * Clean zend server page cache
     *
     * @return void
     */
    public function clean()
    {
        $purged = array();

        /** @var $store Mage_Core_Model_Store */
        foreach(Mage::app()->getStores() as $store) {

            $url = $store->getBaseUrl();

            if(!in_array($url, $purged)) {

                $clint = new Zend_Http_Client(sprintf('%s*', $url));
                $clint->setMethod('PURGE');
                $clint->request();

                $purged[] = $url;
            }
        }
    }
}

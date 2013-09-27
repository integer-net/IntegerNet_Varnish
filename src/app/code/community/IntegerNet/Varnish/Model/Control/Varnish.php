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
     * Clean varnish cache
     *
     * @return void
     */
    public function clean()
    {
        $purged = array();

        /** @var $store Mage_Core_Model_Store */
        foreach(Mage::app()->getStores() as $store) {

            $uri = $store->getBaseUrl();
            $uri = Zend_Uri::factory($uri);
            $host =  $uri->getHost();

            if(!in_array($host, $purged)) {
                $purged[] = $host;

                $clint = new Zend_Http_Client(Mage::helper('integernet_varnish/config')->getPurgeUri($store->getId()));
                $clint->setHeaders('Host', $host);
                $clint->setMethod('PURGE');

                /** @var $response Zend_Http_Response */
                $response = $clint->request();

                if($response->isSuccessful()) {
                    Mage::getModel('integernet_varnish/index')->setAllExpire();
                }

                $message = Mage::helper('integernet_varnish')->__('Varnish PURGE Response for %s: <i>%s (%s)</i>', $host, $response->getMessage(), $response->getStatus());
                Mage::getSingleton('adminhtml/session')->addNotice($message);
            }
        }
    }
}

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
class IntegerNet_Varnish_Model_Purge
{
    /**
     * Clean varnish cache
     *
     * @return void
     */
    public function fullPurge()
    {
        $purged = array();

        /** @var $store Mage_Core_Model_Store */
        foreach(Mage::app()->getStores() as $store) {

            $uri = $store->getBaseUrl();
            $uri = Zend_Uri::factory($uri);
            $host =  $uri->getHost();

            if(!in_array($host, $purged)) {
                $purged[] = $host;

                /** @var $uri Zend_Uri_Http */
                $uri = Zend_Uri_Http::factory();
                $uri->setHost(Mage::helper('integernet_varnish/config')->getPurgeServer($store->getId()));
                $uri->setPath(Mage::helper('integernet_varnish/config')->getPurgeUrl($store->getId()));

                $clint = new Zend_Http_Client($uri);
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

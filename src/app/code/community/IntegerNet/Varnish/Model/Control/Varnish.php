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

            // If the port is not default, add it
            if (! (($uri->getScheme() == 'http' && $uri->getPort() == 80) ||
                ($uri->getScheme() == 'https' && $uri->getPort() == 443))) {
                $host .= ':' . $uri->getPort();
            }

            if(!in_array($host, $purged)) {
                $purged[] = $host;

                $clint = new Zend_Http_Client('http://127.0.0.1/*');
                $clint->setHeaders('Host', $host);
                $clint->setMethod('PURGE');

                /** @var $response Zend_Http_Response */
                $response = $clint->request();

                $message = Mage::helper('integernet_varnish')->__('Varnish PURGE Response for %s <i>%s (%s)</i>', $host, $response->getMessage(), $response->getStatus());
                Mage::getSingleton('adminhtml/session')->addNotice($message);
            }
        }
    }
}

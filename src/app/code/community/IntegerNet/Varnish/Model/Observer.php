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
class IntegerNet_Varnish_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductSaveAfter(Varien_Event_Observer $observer)
    {
        if (Mage::helper('integernet_varnish')->isEnabled()) {
            Mage::getModel('integernet_varnish/index')->observerProductUrls($observer->getProduct());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function cataloginventoryStockItemSaveAfter(Varien_Event_Observer $observer)
    {
        if (Mage::helper('integernet_varnish')->isEnabled()) {
            Mage::getModel('integernet_varnish/index')->observerProductUrls($observer->getItem()->getProductId());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer)
    {
        if (Mage::helper('integernet_varnish')->isEnabled()) {
            Mage::helper('integernet_varnish')->addLayoutHandle();
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function coreBlockAbstractToHtmlBefore(Varien_Event_Observer $observer)
    {
        if (Mage::helper('integernet_varnish')->isEnabled()) {
            Mage::helper('integernet_varnish')->wrapBlock($observer->getData('block'));
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        /** @var $helper IntegerNet_Varnish_Helper_Data */
        $helper = Mage::helper('integernet_varnish');

        if ($helper->isEnabled()) {

            /** @var $response Mage_Core_Controller_Response_Http */
            $response = $observer->getControllerAction()->getResponse();

            $lifetime = $helper->getLifetime();

            if ($lifetime) {

                $invalidate = $helper->isInvalidate();

                if (count($invalidate) == 0) {

                    if ($helper->hasNoCacheCookie()) {

                        $helper->unsetNoCacheCookie();
                        $helper->debug(false, 'unset external_no_cache');

                    } else {
                        $response->setHeader('aoestatic', 'cache', true);
                        $response->setHeader('Cache-Control', sprintf('max-age=%s', $lifetime), true);

                        Mage::getModel('integernet_varnish/index')->addUrlByRequest($observer->getControllerAction()->getRequest(), $lifetime);

                        $helper->debug(true, 'lifetime', $lifetime);
                    }
                } else {
                    $helper->setNoCacheCookie();
                    $helper->debug(false, 'invalidate', $invalidate);
                }
            } else {
                $helper->debug(false, 'lifetime', 0);
            }
        } else {
            Mage::getSingleton('pagecache/observer')->processPreDispatch($observer);
        }
    }
}
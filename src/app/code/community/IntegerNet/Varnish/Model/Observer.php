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
     * Prevent set external_no_cache cookie by Mage_PageCache module.
     *
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        /** @var $helper IntegerNet_Varnish_Helper_Data */
        $helper = Mage::helper('integernet_varnish');

        if (!$helper->isEnabled()) {
            Mage::getSingleton('pagecache/observer')->processPreDispatch($observer);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPostdispatch(Varien_Event_Observer $observer)
    {
        /** @var $helper IntegerNet_Varnish_Helper_Data */
        $helper = Mage::helper('integernet_varnish');

        /**
         * isModuleRoute prevent execution if request processed by fetch
         * controller.
         */
        if ($helper->isEnabled() && !$helper->isModuleRoute()) {

            if ($bypass = $helper->isBypass()) {

                //if(!$helper->hasNoCacheCookie()) {
                //    Mage::app()->getResponse()->setRedirect(Mage::helper('core/url')->getCurrentUrl(), 200);
                //}

                $helper->setNoCacheCookie();
                $helper->debug(false, 'external_no_cache', $bypass);

            } elseif ($helper->hasNoCacheCookie()) {

                $helper->unsetNoCacheCookie();
                $helper->debug(false, 'external_no_cache', '-');

            } else {

                $disqualified = $helper->isDisqualified();
                $lifetime = $helper->getLifetime();

                if (!$disqualified && $lifetime) {

                    Mage::app()->getResponse()->setHeader('aoestatic', 'cache', true);
                    Mage::app()->getResponse()->setHeader('Cache-Control', sprintf('max-age=%s', $lifetime), true);

                    Mage::getModel('integernet_varnish/index')->addUrl($lifetime);

                    $helper->debug(true, 'lifetime', $lifetime);

                } elseif($disqualified) {
                    $helper->debug(false, 'disqualified', $disqualified);
                } else {
                    $helper->debug(true, 'lifetime', '0');
                }
            }
        }
    }
}
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
        if (Mage::helper('integernet_varnish/config')->isEnabled()) {
            Mage::getModel('integernet_varnish/index')->observerProductUrls($observer->getProduct());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function cataloginventoryStockItemSaveAfter(Varien_Event_Observer $observer)
    {
        if (Mage::helper('integernet_varnish/config')->isEnabled()) {
            Mage::getModel('integernet_varnish/index')->observerProductUrls($observer->getItem()->getProductId());
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer)
    {
        $enable = Mage::helper('integernet_varnish/config')->isEnabled();
        $holePunching = Mage::helper('integernet_varnish/config')->isHolePunching();

        if ($enable && $holePunching) {
            Mage::helper('integernet_varnish')->addLayoutHandle();
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function coreBlockAbstractToHtmlBefore(Varien_Event_Observer $observer)
    {
        $enable = Mage::helper('integernet_varnish/config')->isEnabled();
        $holePunching = Mage::helper('integernet_varnish/config')->isHolePunching();

        if ($enable && $holePunching) {
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

        /** @var $helperConfig IntegerNet_Varnish_Helper_Config */
        $helperConfig = Mage::helper('integernet_varnish/config');

        /**
         * isModuleRoute prevent execution if request processed by fetch
         * controller.
         */
        if ($helperConfig->isEnabled() && !$helper->isModuleRoute() && Mage::app()->getResponse()->canSendHeaders()) {

            if ($bypass = $helper->isBypass()) {

                //if(!$helper->hasNoCacheCookie()) {
                //    Mage::app()->getResponse()->setRedirect(Mage::helper('core/url')->getCurrentUrl(), 200);
                //}

                $helper->setNoCacheCookie();
                $helper->debug('external_no_cache', $bypass);

            } elseif ($helper->hasNoCacheCookie()) {

                $helper->unsetNoCacheCookie();
                $helper->debug('external_no_cache', '0');

            } else {

                $disqualified = $helper->isDisqualified();
                $lifetime = $helper->getLifetime();

                if (!$disqualified && $lifetime) {

                    if (function_exists('header_remove')) {
                        header_remove('Set-Cookie');
                    }

                    Mage::app()->getResponse()->setHeader('aoestatic', 'cache', true);
                    Mage::app()->getResponse()->setHeader('Cache-Control', sprintf('max-age=%s', $lifetime), true);

                    Mage::getModel('integernet_varnish/index')->addUrl(Mage::helper('core/url')->getCurrentUrl(), $lifetime);

                    $helper->debug('lifetime', $lifetime);

                } elseif ($disqualified) {
                    $helper->debug('disqualified', $disqualified);
                } else {
                    $helper->debug('No-Cache');
                }
            }
        } elseif (!$helperConfig->isEnabled()) {
            Mage::getSingleton('pagecache/observer')->processPreDispatch($observer);
        }
    }
}
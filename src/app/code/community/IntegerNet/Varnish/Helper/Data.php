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
class IntegerNet_Varnish_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param null $message
     * @param null $additional
     * @return $this
     */
    public function debug($message = null, $additional = null)
    {
        if (Mage::helper('integernet_varnish/config')->isDebugMode() && Mage::app()->getResponse()->canSendHeaders()) {

            if ($additional) {
                $additional = is_array($additional) ? implode(',', $additional) : (string)$additional;
                $message = sprintf('%s (%s)', $message, $additional);
            }

            Mage::app()->getResponse()->setHeader('X-Magento-Varnish', $message, true);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime()
    {
        $cacheRoutes = Mage::helper('integernet_varnish/config')->getCacheRoutes();

        $route = $this->_getRequest()->getRequestedRouteName();
        $controller = $this->_getRequest()->getRequestedControllerName();
        $action = $this->_getRequest()->getRequestedActionName();

        foreach ($cacheRoutes as $cacheRoute) {
            if($cacheRoute->getRoute() == $route) {
                if($cacheRoute->getController() == $controller || $cacheRoute->getController() == '*') {
                    if($cacheRoute->getAction() == $action || $cacheRoute->getAction() == '*') {
                        return $cacheRoute->getLifetime();
                    }
                }
            }
        }

        return 0;
    }

    /**
     * @return bool
     */
    public function isModuleRoute()
    {
        return $this->_getRequest()->getRequestedRouteName() == 'integernet_varnish';
    }

    /**
     * @return array
     */
    public function isDisqualified()
    {
        $invalidateList = array();

        $disqualifiedParams = Mage::helper('integernet_varnish/config')->getDisqualifiedParams();


        foreach ($disqualifiedParams as $disqualifiedParam) {
            if ($this->_getRequest()->has($disqualifiedParam)) {
                if (Mage::helper('integernet_varnish/config')->isDebugMode()) {
                    $invalidateList[] = $disqualifiedParam;
                } else {
                    return array(true);
                }
            }
        }

        $invalidateModels = Mage::helper('integernet_varnish/config')->getInvalidateModels();
        $disqualifiedStates = Mage::helper('integernet_varnish/config')->getDisqualifiedStates();

        foreach ($invalidateModels as $invalidate) {
            if (in_array($invalidate->getCode(), $disqualifiedStates) && $invalidate->hasData()) {
                if (Mage::helper('integernet_varnish/config')->isDebugMode()) {
                    $invalidateList[] = $invalidate->getCode();
                } else {
                    return array(true);
                }
            }
        }

        return $invalidateList;
    }

    /**
     * @return array
     */
    public function isBypass()
    {
        $invalidateModels = Mage::helper('integernet_varnish/config')->getInvalidateModels();
        $bypassStates = Mage::helper('integernet_varnish/config')->getBypassStates();

        $invalidateList = array();
        foreach ($invalidateModels as $invalidate) {
            if (in_array($invalidate->getCode(), $bypassStates) && $invalidate->hasData()) {
                if (Mage::helper('integernet_varnish/config')->isDebugMode()) {
                    $invalidateList[] = $invalidate->getCode();
                } else {
                    return array(true);
                }
            }
        }

        return $invalidateList;
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return self
     */
    public function setNoCacheCookie()
    {
        Mage::helper('pagecache')->setNoCacheCookie();

        return $this;
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return mixed
     */
    public function hasNoCacheCookie()
    {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getSingleton('core/cookie');

        return $cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return self
     */
    public function unsetNoCacheCookie()
    {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getSingleton('core/cookie');

        $noCache = $cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);

        if ($noCache) {
            $cookie->delete(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function addLayoutHandle()
    {
        Mage::app()->getLayout()->getUpdate()->addHandle('integernetvarnish');

        return $this;
    }

    /**
     * @param Mage_Core_Block_Abstract $block
     * @return self
     */
    public function wrapBlock(Mage_Core_Block_Abstract $block)
    {
        $blockWrapInfo = Mage::helper('integernet_varnish/config')->getBlockWrapInfo();

        if (array_key_exists($block->getNameInLayout(), $blockWrapInfo)) {
            $tagOpen = sprintf('div id="%s"', $this->getWrapId($block->getNameInLayout()));
            $block->setFrameTags($tagOpen, '/div');
        }

        return $this;
    }

    /**
     * @param $blockNameInLayout
     * @return string
     */
    public function getWrapId($blockNameInLayout)
    {
        return sprintf('varnishwrap_%s', md5($blockNameInLayout));
    }
}
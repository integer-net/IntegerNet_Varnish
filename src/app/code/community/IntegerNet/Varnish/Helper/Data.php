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
class IntegerNet_Varnish_Helper_Data extends Mage_PageCache_Helper_Data
{
    /**
     * Pathes to external cache config options
     */
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_DEBUG = 'system/external_page_cache/integernet_varnish_debug';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_ACTION = 'system/external_page_cache/integernet_varnish_action';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_DISQUALIFIED = 'system/external_page_cache/integernet_varnish_invalidate_disqualified';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_BYPASS = 'system/external_page_cache/integernet_varnish_invalidate_bypass';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_PURGE_URI = 'system/external_page_cache/integernet_varnish_purge_uri';

    /**
     *
     */
    const XML_PATH_GLOBAL_INTEGERNET_VARNISH_INVALIDATE = 'global/integernet_varnish/invalidate';

    /**
     * @var null
     */
    protected $_isEnabled = null;

    /**
     * @var null
     */
    protected $_wrapBlock = null;

    /**
     * @var null
     */
    protected $_invalidateModels = null;

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->_isEnabled === null) {
            $this->_isEnabled = parent::isEnabled() && parent::getCacheControlInstance() instanceof IntegerNet_Varnish_Model_Control_Varnish;
        }

        return $this->_isEnabled;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return (bool)Mage::getStoreConfig(self::XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_DEBUG);
    }

    /**
     * @param null $state
     * @param null $message
     * @param null $additional
     * @return $this
     */
    public function debug($state = null, $message = null, $additional = null)
    {
        if ($this->isDebug() && ($state === false || $state === true)) {
            $state = $state ? 'true' : 'false';

            if ($additional) {
                $additional = is_array($additional) ? implode(',', $additional) : (string)$additional;
                $message = sprintf('%s (%s)', $message, $additional);
            }

            Mage::app()->getResponse()->setHeader('X-Magento-FPC-State', $state, true);
            Mage::app()->getResponse()->setHeader('X-Magento-FPC-Message', $message, true);

        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime()
    {
        $actions = unserialize(Mage::getStoreConfig(self::XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_ACTION));

        $fullActionName = sprintf('%s_%s_%s',
            $this->_getRequest()->getRequestedRouteName(),
            $this->_getRequest()->getRequestedControllerName(),
            $this->_getRequest()->getRequestedActionName()
        );

        foreach ($actions as $actions) {
            if (strtolower(trim($actions['action'])) == $fullActionName) {
                return (int)$actions['lifetime'];
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
        $disqualifiedConfig = explode(',', Mage::getStoreConfig(self::XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_DISQUALIFIED));

        $invalidateList = array();
        foreach ($this->getInvalidateModels() as $invalidate) {
            if (in_array($invalidate->getCode(), $disqualifiedConfig) && $invalidate->hasData()) {
                if ($this->isDebug()) {
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
        $bypassConfig = explode(',', Mage::getStoreConfig(self::XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_BYPASS));

        $invalidateList = array();
        foreach ($this->getInvalidateModels() as $invalidate) {
            if (in_array($invalidate->getCode(), $bypassConfig) && $invalidate->hasData()) {
                if ($this->isDebug()) {
                    $invalidateList[] = $invalidate->getCode();
                } else {
                    return array(true);
                }
            }
        }

        return $invalidateList;
    }

    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Interface[]
     */
    public function getInvalidateModels()
    {
        if ($this->_invalidateModels === null) {
            $this->_invalidateModels = array();
            $invalidateConfig = Mage::app()->getConfig()->getNode(self::XML_PATH_GLOBAL_INTEGERNET_VARNISH_INVALIDATE);

            foreach ($invalidateConfig->asCanonicalArray() as $key => $class) {
                $model = Mage::getSingleton($class);
                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Interface) {
                    $this->_invalidateModels[$key] = $model;
                }
            }
        }

        return $this->_invalidateModels;
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return void
     */
    public function setNoCacheCookie()
    {
        Mage::helper('pagecache')->setNoCacheCookie();
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return void
     */
    public function hasNoCacheCookie()
    {
        $cookie = Mage::getSingleton('core/cookie');

        return $cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
    }

    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return void
     */
    public function unsetNoCacheCookie()
    {
        $cookie = Mage::getSingleton('core/cookie');

        $noCache = $cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);

        if ($noCache) {
            $cookie->delete(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
        }
    }

    /**
     * @return $this
     */
    public function addLayoutHandle()
    {
        Mage::app()->getLayout()->getUpdate()->addHandle('integernetvarnish');
        return $this;
    }

    /**
     * @return array|null
     */
    public function getBlockWrapInfo()
    {
        if ($this->_wrapBlock === null) {

            $this->_wrapBlock = array();
            $varnishWrap = Mage::app()->getLayout()->getXpath('//varnishwrap');

            if (is_array($varnishWrap)) {
                foreach ($varnishWrap as $node) {
                    $this->_wrapBlock[$node->getAttribute('name')] = $node->getAttribute('name');
                }
            }
        }

        return $this->_wrapBlock;
    }

    /**
     * @param $blockNameInLayout
     * @return string
     */
    public function getWrapId($blockNameInLayout)
    {
        return sprintf('varnishwrap_%s', $blockNameInLayout);
    }

    /**
     * @param Mage_Core_Block_Abstract $block
     * @return $this
     */
    public function wrapBlock(Mage_Core_Block_Abstract $block)
    {
        $blockWrapInfo = $this->getBlockWrapInfo();

        if (array_key_exists($block->getNameInLayout(), $blockWrapInfo)) {
            $tagOpen = sprintf('div id="%s"', $this->getWrapId($block->getNameInLayout()));
            $block->setFrameTags($tagOpen, '/div');
        }

        return $this;
    }
}



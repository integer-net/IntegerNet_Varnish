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
class IntegerNet_Varnish_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     *
     */
    const XML_PATH_GLOBAL_INTEGERNET_VARNISH_INVALIDATE = 'global/integernet_varnish/invalidate';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_ENABLE = 'system/integernet_varnish/enable';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_CACHE_ROUTES = 'system/integernet_varnish/cache_routes';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_HOLE_PUNCHING = 'system/integernet_varnish/hole_punching';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_HOLE_BLOCKS = 'system/integernet_varnish/hole_blocks';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_DISQUALIFIED_STATES = 'system/integernet_varnish/disqualified_states';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_DISQUALIFIED_PARAMS = 'system/integernet_varnish/disqualified_params';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_BYPASS_STATES = 'system/integernet_varnish/bypass_states';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_PURGE_SERVER = 'system/integernet_varnish/purge_server';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_PURGE_URL = 'system/integernet_varnish/purge_url';
    const XML_PATH_SYSTEM_INTEGERNET_VARNISH_DEBUG_MODE = 'system/integernet_varnish/debug_mode';

    /**
     * @var array
     */
    private $_objectCache = array();

    /**
     * @param null $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        $enable = Mage::getStoreConfigFlag(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_ENABLE, $store);
        $pageCache = Mage::helper('pagecache')->isEnabled();

        return $enable && !$pageCache;
    }

    /**
     * @param null $store
     * @return Varien_Object[]
     */
    public function getCacheRoutes($store = null)
    {
        if (!array_key_exists(__METHOD__ . $store, $this->_objectCache)) {

            $routes = array();
            $routesConfig = unserialize(Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_CACHE_ROUTES, $store));

            foreach ($routesConfig as $routeConfig) {
                $route = explode('/', $routeConfig['route']);

                $_route = array_key_exists(0, $route) ? (trim($route[0]) ? trim($route[0]) : '*') : '*';
                $_controller = array_key_exists(1, $route) ? (trim($route[1]) ? trim($route[1]) : '*') : '*';
                $_action = array_key_exists(2, $route) ? (trim($route[2]) ? trim($route[2]) : '*') : '*';
                $_lifetime = (int)$routeConfig['lifetime'];

                if ($_route !== '*' && $_lifetime) {
                    $routes[] = new Varien_Object(array(
                        'route' => $_route,
                        'controller' => $_controller,
                        'action' => $_action,
                        'lifetime' => $_lifetime,
                    ));
                }
            }
            $this->_objectCache[__METHOD__ . $store] = $routes;
        }
        return $this->_objectCache[__METHOD__ . $store];
    }

    /**
     * @return bool
     */
    public function isHolePunching()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_HOLE_PUNCHING, $store = null);
    }

    /**
     * @param null $store
     * @return array
     */
    public function getDisqualifiedStates($store = null)
    {
        return explode(',', Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_DISQUALIFIED_STATES, $store));
    }

    /**
     * @param null $store
     * @return array
     */
    public function getDisqualifiedParams($store = null)
    {
        if (!array_key_exists(__METHOD__ . $store, $this->_objectCache)) {

            $params = array();
            $paramsConfig = unserialize(Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_DISQUALIFIED_PARAMS, $store));

            foreach ($paramsConfig as $paramConfig) {
                if (trim($paramConfig['param'])) {
                    $params[] = $paramConfig['param'];
                }
            }
            $this->_objectCache[__METHOD__ . $store] = $params;
        }
        return $this->_objectCache[__METHOD__ . $store];
    }

    /**
     * @param null $store
     * @return array
     */
    public function getBypassStates($store = null)
    {
        return explode(',', Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_BYPASS_STATES, $store));
    }

    /**
     * @param null $store
     * @return string
     */
    public function getPurgeServer($store = null)
    {
        return (string)Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_PURGE_SERVER, $store);
    }

    /**
     * @param null $store
     * @return string
     */
    public function getPurgeUrl($store = null)
    {
        return (string)Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_PURGE_URL, $store);
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isDebugMode($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_DEBUG_MODE, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getBlockWrapInfo($store = null)
    {
        if (!array_key_exists(__METHOD__ . $store, $this->_objectCache)) {

            $wrapInfo = array();
            $blocksConfig = unserialize(Mage::getStoreConfig(self::XML_PATH_SYSTEM_INTEGERNET_VARNISH_HOLE_BLOCKS, $store));

            foreach ($blocksConfig as $blockConfig) {
                if (trim($blockConfig['name'])) {
                    $wrapInfo[trim($blockConfig['name'])] = array(
                        'name' => trim($blockConfig['name'])
                    );
                }
            }
            $this->_objectCache[__METHOD__ . $store] = $wrapInfo;
        }
        return $this->_objectCache[__METHOD__ . $store];
    }

    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Interface[]
     */
    public function getInvalidateModels()
    {
        if (!array_key_exists(__METHOD__, $this->_objectCache)) {
            $invalidateModels = array();
            $invalidateConfig = Mage::app()->getConfig()->getNode(self::XML_PATH_GLOBAL_INTEGERNET_VARNISH_INVALIDATE);

            foreach ($invalidateConfig->asCanonicalArray() as $key => $class) {
                $model = Mage::getSingleton($class);
                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Interface) {
                    $invalidateModels[$key] = $model;
                }
            }

            $this->_objectCache[__METHOD__] = $invalidateModels;
        }

        return $this->_objectCache[__METHOD__];
    }
}
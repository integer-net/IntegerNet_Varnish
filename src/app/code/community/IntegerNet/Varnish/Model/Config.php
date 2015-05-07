<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Model_Config
 *
 * IntegerNet_Varnish configuration
 */
class IntegerNet_Varnish_Model_Config
{


    /**
     * @var null|bool
     */
    protected $_isEnable;

    /**
     * @var null|array
     */
    protected $_headerAdd;

    /**
     * @var null|array
     */
    protected $_headerRemove;

    /**
     * @var null|array
     */
    protected $_cacheRoutes;

    /**
     * @var null|array
     */
    protected $_injectFormKeyRouts;

    /**
     * @var null|array
     */
    protected $_bypassStates;

    /**
     * @var null|array
     */
    protected $_disqualifiedStates;


    /**
     * @var null|array
     */
    protected $_disqualifiedPaths;

    /**
     * @var null|array
     */
    protected $_allowedParams;

    /**
     * @var null|bool
     */
    protected $_httpsAllowed;

    /**
     * @var null|bool
     */
    protected $_isDynamicBlock;

    /**
     * @var null|array
     */
    protected $_dynamicBlocks;

    /**
     * @var null|string
     */
    protected $_dynamicBlockJs;

    /**
     * @var null|string
     */
    protected $_purgeServer;

    /**
     * @var null|string
     */
    protected $_purgeUrl;

    /**
     * @var null|integer
     */
    protected $_purgeSize;

    /**
     * @var null|bool
     */
    protected $_isDebugMode;

    /**
     * @var null|bool
     */
    protected $_isBuild;

    /**
     * @var null|int
     */
    protected $_buildTimeout;

    /**
     * @var null|integer
     */
    protected $_buildShellLimit;

    /**
     * @var null|integer
     */
    protected $_buildPhpLimit;
    /**
     * @var null|integer
     */
    protected $_buildPhpTimeout;

    /**
     * @var null|string
     */
    protected $_buildUserAgent;

    /**
     * @var null|IntegerNet_Varnish_Model_Invalidate_Response_Interface[]
     */
    protected $_invalidateResponseModels;

    /**
     * @var null|IntegerNet_Varnish_Model_Invalidate_Resource_Interface[]
     */
    protected $_invalidateResourceModels;


    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->_isEnable === null) {

            /** @var Mage_PageCache_Helper_Data $pageCache */
            $pageCache = Mage::helper('pagecache');

            $enabled = $pageCache->isEnabled();
            $cacheControlInstance = $enabled ? $pageCache->getCacheControlInstance() : null;

            $this->_isEnable = $enabled && $cacheControlInstance instanceof IntegerNet_Varnish_Model_Control_Varnish;
        }

        return $this->_isEnable;
    }


    /**
     * @return array
     */
    public function getCacheRoutes()
    {
        if ($this->_cacheRoutes === null) {

            $this->_cacheRoutes = array();

            $routesConfig = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_cache_routes');
            $routesConfig = (array)unserialize($routesConfig);

            foreach ($routesConfig as $routeConfig) {

                $routeParts = explode('/', $routeConfig['route']);
                $lifetime = (int)$routeConfig['lifetime'];

                $route = array_key_exists(0, $routeParts) ? (trim($routeParts[0]) ? trim($routeParts[0]) : '*') : '*';
                $controller = array_key_exists(1, $routeParts) ? (trim($routeParts[1]) ? trim($routeParts[1]) : '*') : '*';
                $action = array_key_exists(2, $routeParts) ? (trim($routeParts[2]) ? trim($routeParts[2]) : '*') : '*';

                if ($route !== '*' && $lifetime > 0) {
                    $this->_cacheRoutes[] = array(
                        'route' => $route,
                        'controller' => $controller,
                        'action' => $action,
                        'lifetime' => $lifetime,
                    );
                }
            }
        }

        return $this->_cacheRoutes;
    }


    /**
     * @return array
     */
    public function getHeadersAdd()
    {
        if ($this->_headerAdd === null) {

            $this->_headerAdd = array();

            $headersAdd = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_headers_add');
            $headersAdd = (array)unserialize($headersAdd);

            foreach ($headersAdd as $headerAdd) {
                if ($headerAddTrim = trim($headerAdd['header'])) {
                    $this->_headerAdd[$headerAddTrim] = trim($headerAdd['value']);
                }
            }
        }

        return $this->_headerAdd;
    }


    /**
     * @return array
     */
    public function getHeadersRemove()
    {
        if ($this->_headerRemove === null) {

            $this->_headerRemove = array();

            $headersRemove = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_headers_remove');
            $headersRemove = (array)unserialize($headersRemove);

            foreach ($headersRemove as $headerRemove) {
                if ($headerRemoveTrim = trim($headerRemove['header'])) {
                    $this->_headerRemove[] = $headerRemoveTrim;
                }
            }
        }

        return $this->_headerRemove;
    }


    /**
     * @return array
     */
    public function getInjectFormKeyRouts()
    {
        if ($this->_injectFormKeyRouts === null) {

            $this->_injectFormKeyRouts = array();

            $injectFormKeyRoutes = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_inject_form_key_routes');
            $injectFormKeyRoutes = (array)unserialize($injectFormKeyRoutes);

            foreach ($injectFormKeyRoutes as $injectFormKeyRoute) {

                $routeParts = explode('/', $injectFormKeyRoute['route']);

                $route = array_key_exists(0, $routeParts) ? (trim($routeParts[0]) ? trim($routeParts[0]) : '*') : '*';
                $controller = array_key_exists(1, $routeParts) ? (trim($routeParts[1]) ? trim($routeParts[1]) : '*') : '*';
                $action = array_key_exists(2, $routeParts) ? (trim($routeParts[2]) ? trim($routeParts[2]) : '*') : '*';

                if ($route !== '*') {
                    $this->_injectFormKeyRouts[] = array(
                        'route' => $route,
                        'controller' => $controller,
                        'action' => $action,
                    );
                }
            }
        }

        return $this->_injectFormKeyRouts;
    }


    /**
     * @return array
     */
    public function getBypassStates()
    {
        if ($this->_bypassStates === null) {

            $this->_bypassStates = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_bypass_states');
            $this->_bypassStates = explode(',', $this->_bypassStates);
        }

        return $this->_bypassStates;
    }


    /**
     * @return array
     */
    public function getDisqualifiedStates()
    {
        if ($this->_disqualifiedStates === null) {

            $this->_disqualifiedStates = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_disqualified_states');
            $this->_disqualifiedStates = explode(',', $this->_disqualifiedStates);
        }

        return $this->_disqualifiedStates;
    }


    /**
     * @return array
     */
    public function getDisqualifiedPaths()
    {
        if ($this->_disqualifiedPaths === null) {

            $this->_disqualifiedPaths = array();

            $disqualifiedPaths = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_disqualified_paths');
            $disqualifiedPaths = (array)unserialize($disqualifiedPaths);

            foreach ($disqualifiedPaths as $disqualifiedParam) {
                if ($disqualifiedParamTrim = trim($disqualifiedParam['path'])) {
                    $this->_disqualifiedPaths[] = $disqualifiedParamTrim;
                }
            }
        }

        return $this->_disqualifiedPaths;
    }


    /**
     * @return array
     */
    public function getAllowedParams()
    {
        if ($this->_allowedParams === null) {

            $this->_allowedParams = array();

            $allowedParams = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_allowed_params');
            $allowedParams = (array)unserialize($allowedParams);

            foreach ($allowedParams as $allowedParam) {

                if (array_key_exists('param', $allowedParam) && trim($allowedParam['param'])) {

                    $values = array_key_exists('value', $allowedParam) ? $allowedParam['value'] : '';
                    $values = explode('|', $values);
                    $values = array_map('trim', $values);
                    $values = array_filter($values);

                    $this->_allowedParams[trim($allowedParam['param'])] = $values;
                }
            }
        }

        return $this->_allowedParams;
    }


    /**
     * @return bool
     */
    public function isHttpsAllowed()
    {
        if ($this->_httpsAllowed === null) {

            $this->_httpsAllowed = Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_https_allowed');
        }

        return $this->_httpsAllowed;
    }


    /**
     * @return bool
     */
    public function isDynamicBlock()
    {
        if ($this->_isDynamicBlock === null) {

            $this->_isDynamicBlock = Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_dynamic_block');
        }

        return $this->_isDynamicBlock;
    }


    /**
     * @return array
     */
    public function getDynamicBlocks()
    {
        if ($this->_dynamicBlocks === null) {

            $this->_dynamicBlocks = array();

            $dynamicBlocks = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_dynamic_blocks');
            $dynamicBlocks = (array)unserialize($dynamicBlocks);

            foreach ($dynamicBlocks as $dynamicBlock) {

                if ($holeBlockTrim = trim($dynamicBlock['name'])) {

                    $this->_dynamicBlocks[$holeBlockTrim] = array(
                        'type' => trim($dynamicBlock['type']),
                        'template' => trim($dynamicBlock['template']),
                    );
                }
            }
        }

        return $this->_dynamicBlocks;
    }


    /**
     * @return mixed
     */
    public function getDynamicBlockJs()
    {
        if ($this->_dynamicBlockJs === null) {

            $this->_dynamicBlockJs = trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_dynamic_block_js'));
        }

        return $this->_dynamicBlockJs;
    }


    /**
     * @return string
     */
    public function getPurgeServer()
    {
        if ($this->_purgeServer === null) {

            $this->_purgeServer = trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_server'));
        }

        return $this->_purgeServer;
    }


    /**
     * @return string
     */
    public function getPurgeUrl()
    {
        if ($this->_purgeUrl === null) {

            $this->_purgeUrl = trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_url'));
        }

        return $this->_purgeUrl;
    }


    /**
     * @return integer
     */
    public function getPurgeSize()
    {
        if ($this->_purgeSize === null) {

            $this->_purgeSize = (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_size'));
        }

        return $this->_purgeSize;
    }


    /**
     * @return int
     */
    public function getBuildType()
    {
        if ($this->_isBuild === null) {

            $this->_isBuild = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build');
        }

        return $this->_isBuild;
    }


    /**
     * @return bool
     */
    public function isBuildShell()
    {
        return $this->getBuildType() == IntegerNet_Varnish_Model_System_Config_Source_Build::BUILD_SHELL;
    }


    /**
     * @return bool
     */
    public function isBuildPhp()
    {
        return $this->getBuildType() == IntegerNet_Varnish_Model_System_Config_Source_Build::BUILD_PHP;
    }


    /**
     * @return int
     */
    public function getBuildTimeout()
    {
        if ($this->_buildTimeout === null) {

            $this->_buildTimeout = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_timeout');
        }

        return $this->_buildTimeout;
    }


    /**
     * @return integer
     */
    public function getBuildShellLimit()
    {
        if ($this->_buildShellLimit === null) {
            $this->_buildShellLimit = (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_shell_limit'));
        }

        return $this->_buildShellLimit;
    }


    /**
     * @return integer
     */
    public function getBuildPhpLimit()
    {
        if ($this->_buildPhpLimit === null) {
            $this->_buildPhpLimit = (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_php_limit'));
        }

        return $this->_buildPhpLimit;
    }


    /**
     * @return integer
     */
    public function getBuildPhpTimeout()
    {
        if ($this->_buildPhpTimeout === null) {
            $this->_buildPhpTimeout = (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_php_timeout'));
        }

        return $this->_buildPhpTimeout;
    }


    /**
     * @return string
     */
    public function getBuildUserAgent()
    {
        if ($this->_buildUserAgent === null) {

            $this->_buildUserAgent = trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_user_agent'));
        }

        return $this->_buildUserAgent;
    }


    /**
     * @return bool
     */
    public function isDebugMode()
    {
        if ($this->_isDebugMode === null) {

            $this->_isDebugMode = Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_debug_mode');
        }

        return $this->_isDebugMode;
    }


    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Response_Interface[]
     */
    public function getInvalidateResponseModels()
    {
        if ($this->_invalidateResponseModels === null) {

            $this->_invalidateResponseModels = array();

            $invalidateResponseModelsConfig = Mage::app()->getConfig()->getNode('global/integernet_varnish/invalidate/response');

            foreach ($invalidateResponseModelsConfig->asCanonicalArray() as $key => $class) {

                $model = Mage::getSingleton($class);

                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Response_Interface) {
                    $this->_invalidateResponseModels[$key] = $model;
                }
            }
        }

        return $this->_invalidateResponseModels;
    }


    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Resource_Interface[]
     */
    public function getInvalidateResourceModels()
    {
        if ($this->_invalidateResourceModels === null) {

            $this->_invalidateResourceModels = array();
            $invalidateResourceModelsConfig = Mage::app()->getConfig()->getNode('global/integernet_varnish/invalidate/resource');

            foreach ($invalidateResourceModelsConfig->asCanonicalArray() as $key => $class) {

                $model = Mage::getSingleton($class);

                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Resource_Interface) {
                    $this->_invalidateResourceModels[$key] = $model;
                }
            }
        }

        return $this->_invalidateResourceModels;
    }
}

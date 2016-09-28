<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Model_Config
 */
class IntegerNet_Varnish_Model_Config extends Varien_Object
{


    /**
     * @return bool
     */
    public function isEnabled()
    {
        if (!$this->hasData('enable')) {

            /** @var Mage_PageCache_Helper_Data $pageCache */
            $pageCache = Mage::helper('pagecache');

            $enabled = $pageCache->isEnabled();
            $cacheControlInstance = $enabled ? $pageCache->getCacheControlInstance() : null;

            $enabled = $enabled && $cacheControlInstance instanceof IntegerNet_Varnish_Model_Control_Varnish;

            $this->setData('enable', $enabled);
        }

        return $this->getData('enable');
    }


    /**
     * @return array
     */
    public function getCacheRoutes()
    {
        if (!$this->hasData('cache_routes')) {

            $cacheRoutes = array();

            $routesConfig = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_cache_routes');
            $routesConfig = (array)unserialize($routesConfig);

            foreach ($routesConfig as $routeConfig) {

                $routeParts = explode('/', $routeConfig['route']);
                $routeParts = array_map('trim', $routeParts);
                $lifetime = (int)$routeConfig['lifetime'];
                $priority = (int)$routeConfig['priority'];

                $route = (array_key_exists(0, $routeParts) && strlen($routeParts[0])) ? $routeParts[0] : '*';
                $controller = (array_key_exists(1, $routeParts) && strlen($routeParts[1])) ? $routeParts[1] : '*';
                $action = (array_key_exists(2, $routeParts) && strlen($routeParts[2])) ? $routeParts[2] : '*';

                if ($route !== '*' && $lifetime > 0) {
                    $cacheRoutes[] = array(
                        'route' => $route,
                        'controller' => $controller,
                        'action' => $action,
                        'lifetime' => $lifetime,
                        'priority' => $priority,
                    );
                }
            }

            $this->setData('cache_routes', $cacheRoutes);
        }

        return $this->getData('cache_routes');
    }


    /**
     * @return array
     */
    public function getHeadersAdd()
    {

        if (!$this->hasData('header_add')) {

            $_headerAdd = array();

            $headersAdd = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_headers_add');
            $headersAdd = (array)unserialize($headersAdd);

            foreach ($headersAdd as $headerAdd) {
                if ($headerAddTrim = trim($headerAdd['header'])) {
                    $_headerAdd[$headerAddTrim] = trim($headerAdd['value']);
                }
            }
            $this->setData('header_add', $_headerAdd);
        }

        return $this->getData('header_add');
    }


    /**
     * @return array
     */
    public function getHeadersRemove()
    {
        if (!$this->hasData('header_remove')) {

            $_headerRemove = array();

            $headersRemove = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_headers_remove');
            $headersRemove = (array)unserialize($headersRemove);

            foreach ($headersRemove as $headerRemove) {
                if ($headerRemoveTrim = trim($headerRemove['header'])) {
                    $_headerRemove[] = $headerRemoveTrim;
                }
            }

            $this->setData('header_remove', $_headerRemove);
        }

        return $this->getData('header_remove');
    }


    /**
     * @return array
     */
    public function getInjectFormKeyRouts()
    {
        if (!$this->hasData('inject_form_key_routs')) {

            $_injectFormKeyRouts = array();

            $injectFormKeyRoutes = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_inject_form_key_routes');
            $injectFormKeyRoutes = (array)unserialize($injectFormKeyRoutes);

            foreach ($injectFormKeyRoutes as $injectFormKeyRoute) {

                $routeParts = explode('/', $injectFormKeyRoute['route']);
                $routeParts = array_map('trim', $routeParts);

                $route = (array_key_exists(0, $routeParts) && strlen($routeParts[0])) ? $routeParts[0] : '*';
                $controller = (array_key_exists(1, $routeParts) && strlen($routeParts[1])) ? $routeParts[1] : '*';
                $action = (array_key_exists(2, $routeParts) && strlen($routeParts[2])) ? $routeParts[2] : '*';

                if ($route !== '*') {
                    $_injectFormKeyRouts[] = array(
                        'route' => $route,
                        'controller' => $controller,
                        'action' => $action,
                    );
                }
            }

            $this->setData('inject_form_key_routs', $_injectFormKeyRouts);
        }

        return $this->getData('inject_form_key_routs');
    }


    /**
     * @return array
     */
    public function getBypassStates()
    {
        if (!$this->hasData('bypass_states')) {

            $bypassStates = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_bypass_states');
            $bypassStates = explode(',', $bypassStates);

            $this->setData('bypass_states', $bypassStates);
        }

        return $this->getData('bypass_states');
    }


    /**
     * @return array
     */
    public function getDisqualifiedStates()
    {
        if (!$this->hasData('disqualified_states')) {

            $disqualifiedStates = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_disqualified_states');
            $disqualifiedStates = explode(',', $disqualifiedStates);

            $this->setData('disqualified_states', $disqualifiedStates);
        }

        return $this->getData('disqualified_states');
    }


    /**
     * @return array
     */
    public function getDisqualifiedPaths()
    {
        if (!$this->hasData('disqualified_paths')) {

            $_disqualifiedPaths = array();

            $disqualifiedPaths = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_disqualified_paths');
            $disqualifiedPaths = (array)unserialize($disqualifiedPaths);

            foreach ($disqualifiedPaths as $disqualifiedParam) {
                if ($disqualifiedParamTrim = trim($disqualifiedParam['path'])) {
                    $_disqualifiedPaths[] = $disqualifiedParamTrim;
                }
            }

            $this->setData('disqualified_paths', $_disqualifiedPaths);
        }

        return $this->getData('disqualified_paths');
    }


    /**
     * @return array
     */
    public function getAllowedParams()
    {
        if (!$this->hasData('allowed_params')) {

            $_allowedParams = array();

            $allowedParams = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_allowed_params');
            $allowedParams = (array)unserialize($allowedParams);

            foreach ($allowedParams as $allowedParam) {

                if (array_key_exists('param', $allowedParam) && trim($allowedParam['param'])) {

                    $values = array_key_exists('value', $allowedParam) ? $allowedParam['value'] : '';
                    $values = explode('|', $values);
                    $values = array_map('trim', $values);
                    $values = array_filter($values);

                    $priority = (int)array_key_exists('priority', $allowedParam) ? $allowedParam['priority'] : 0;

                    $_allowedParams[trim($allowedParam['param'])] = array(
                        'values' => $values,
                        'priority' => $priority,
                    );
                }
            }
            $this->setData('allowed_params', $_allowedParams);
        }

        return $this->getData('allowed_params');
    }


    /**
     * @return bool
     */
    public function isHttpsAllowed()
    {
        return Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_https_allowed');
    }


    /**
     * @return bool
     */
    public function isDynamicBlock()
    {
        return Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_dynamic_block');
    }


    /**
     * @return array
     */
    public function getDynamicBlocks()
    {
        if (!$this->hasData('dynamic_blocks')) {

            $_dynamicBlocks = array();

            $dynamicBlocks = Mage::getStoreConfig('system/external_page_cache/integernet_varnish_dynamic_blocks');
            $dynamicBlocks = (array)unserialize($dynamicBlocks);

            foreach ($dynamicBlocks as $dynamicBlock) {

                if ($holeBlockTrim = trim($dynamicBlock['name'])) {

                    $_dynamicBlocks[$holeBlockTrim] = array(
                        'type' => trim($dynamicBlock['type']),
                        'template' => trim($dynamicBlock['template']),
                    );
                }
            }

            $this->setData('dynamic_blocks', $_dynamicBlocks);
        }

        return $this->getData('dynamic_blocks');
    }


    /**
     * @return mixed
     */
    public function getDynamicBlockJs()
    {
        return trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_dynamic_block_js'));
    }


    /**
     * @return string
     */
    public function getPurgeServer()
    {
        return trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_server'));
    }

    /**
     * @return string
     */
    public function getPurgePort()
    {
        return trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_port'));
    }

    /**
     * @return string
     */
    public function getPurgeUrl()
    {
        return trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_url'));
    }


    /**
     * @return integer
     */
    public function getPurgeSize()
    {
        return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_purge_size'));
    }


    /**
     * @return int
     */
    public function getBuildType()
    {
        return (integer)Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build');
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
        return (integer)Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_timeout');
    }


    /**
     * @return int|null
     */
    public function getBuildPriority()
    {
        if ($this->isBuildPhp()) {

            return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_php_priority'));
        } 
        
        if ($this->isBuildShell()) {

            return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_shell_priority'));
        }

        return null;
    }


    /**
     * @return integer
     */
    public function getBuildLimit()
    {
        if ($this->isBuildPhp()) {

            return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_php_limit'));
        } 
        
        if ($this->isBuildShell()) {

            return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_shell_limit'));
        }

        return null;
    }
    

    /**
     * @return integer
     */
    public function getBuildPhpTimeout()
    {
        return (integer)trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_php_timeout'));
    }


    /**
     * @return string
     */
    public function getBuildUserAgent()
    {
        return trim(Mage::getStoreConfig('system/external_page_cache/integernet_varnish_build_user_agent'));
    }


    /**
     * @return bool
     */
    public function isDebugMode()
    {
        if (!$this->hasData('debug_mode')) {

            $isDebugMode = Mage::getStoreConfigFlag('system/external_page_cache/integernet_varnish_debug_mode');

            $this->setData('debug_mode', $isDebugMode);
        }

        return $this->getData('debug_mode');
    }


    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Response_Interface[]
     */
    public function getInvalidateResponseModels()
    {
        if (!$this->hasData('invalidate_response_models')) {

            $_invalidateResponseModels = array();

            $invalidateResponseModelsConfig = Mage::app()->getConfig()->getNode('global/integernet_varnish/invalidate/response');

            foreach ($invalidateResponseModelsConfig->asCanonicalArray() as $key => $class) {

                $model = Mage::getSingleton($class);

                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Response_Interface) {
                    $_invalidateResponseModels[$key] = $model;
                }
            }

            $this->setData('invalidate_response_models', $_invalidateResponseModels);
        }

        return $this->getData('invalidate_response_models');
    }


    /**
     * @return IntegerNet_Varnish_Model_Invalidate_Resource_Interface[]
     */
    public function getInvalidateResourceModels()
    {
        if (!$this->hasData('invalidate_resource_models')) {

            $_invalidateResourceModels = array();
            $invalidateResourceModelsConfig = Mage::app()->getConfig()->getNode('global/integernet_varnish/invalidate/resource');

            foreach ($invalidateResourceModelsConfig->asCanonicalArray() as $key => $class) {

                $model = Mage::getSingleton($class);

                if ($model instanceof IntegerNet_Varnish_Model_Invalidate_Resource_Interface) {
                    $_invalidateResourceModels[$key] = $model;
                }
            }

            $this->setData('invalidate_resource_models', $_invalidateResourceModels);
        }

        return $this->getData('invalidate_resource_models');
    }
}

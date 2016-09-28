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
 * Class IntegerNet_Varnish_Model_Validate
 */
class IntegerNet_Varnish_Model_Validate extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @return bool
     */
    public function isDynamicBlockRequest()
    {
        return $this->getRequest()->getParam(self::DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM);
    }


    /**
     * @return null|array
     */
    public function getCacheRoute()
    {
        $route = $this->getRequest()->getRequestedRouteName();
        $controller = $this->getRequest()->getRequestedControllerName();
        $action = $this->getRequest()->getRequestedActionName();

        foreach ($this->getConfig()->getCacheRoutes() as $cacheRoute) {

            if ($cacheRoute['route'] == $route) {

                if ($cacheRoute['controller'] == $controller || $cacheRoute['controller'] == '*') {

                    if ($cacheRoute['action'] == $action || $cacheRoute['action'] == '*') {

                        return $cacheRoute;
                    }
                }
            }
        }

        return null;
    }


    /**
     * @return int
     */
    public function getLifetime()
    {
        $cacheRoute = $this->getCacheRoute();
        
        $lifetime = $cacheRoute ? $cacheRoute['lifetime'] : 0;
        
        return $lifetime;
    }


    /**
     * @return array
     */
    public function getBypassStates()
    {
        $result = array();
        $invalidateModels = $this->getConfig()->getInvalidateResponseModels();
        $bypassStates = $this->getConfig()->getBypassStates();

        foreach ($invalidateModels as $invalidate) {

            if (in_array($invalidate->getCode(), $bypassStates) && $invalidate->hasData()) {

                $result[] = $invalidate->getCode();

                if(!$this->getConfig()->isDebugMode()) {
                    break;
                }
            }
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getDisqualifiedParams()
    {
        $result = array();
        $allowedParams = $this->getConfig()->getAllowedParams();

        foreach ($_GET as $param => $value) {
            
            if (!array_key_exists($param, $allowedParams) || array_key_exists($param, $allowedParams) && $allowedParams[$param]['values'] && !in_array($value, $allowedParams[$param]['values'])) {

                $result[] = sprintf('%s=%s', $param, $value);

                if(!$this->getConfig()->isDebugMode()) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDisqualifiedPaths()
    {
        $result = array();
        $disqualifiedPaths = $this->getConfig()->getDisqualifiedPaths();

        foreach ($disqualifiedPaths as $disqualifiedPath) {

            if (strpos($this->getRequest()->getOriginalPathInfo(), $disqualifiedPath) !== false) {

                $result[] = $disqualifiedPath;

                if(!$this->getConfig()->isDebugMode()) {
                    break;
                }
            }
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getDisqualifiedStates()
    {
        $result = array();
        $invalidateModels = $this->getConfig()->getInvalidateResponseModels();
        $disqualifiedStates = $this->getConfig()->getDisqualifiedStates();

        foreach ($invalidateModels as $invalidate) {

            if (in_array($invalidate->getCode(), $disqualifiedStates) && $invalidate->hasData()) {

                $result[] = $invalidate->getCode();

                if(!$this->getConfig()->isDebugMode()) {
                    break;
                }
            }
        }

        return $result;
    }


    /**
     * @return bool
     */
    public function isNoRoute()
    {
        $route = $this->getRequest()->getRequestedRouteName();
        $controller = $this->getRequest()->getRequestedControllerName();
        $action = $this->getRequest()->getRequestedActionName();

        if (strtolower($route . $controller . $action) == 'cmsindexnoroute') {
            return true;
        }

        return false;
    }


    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return boolean
     */
    public function hasNoCacheCookie()
    {
        return (boolean)$this->getCookies()->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
    }



    /**
     * @return bool
     */
    public function isHttpsAllowed()
    {
        if($this->getRequest()->isSecure()) {

            return $this->getConfig()->isHttpsAllowed();
        }

        return true;
    }
}

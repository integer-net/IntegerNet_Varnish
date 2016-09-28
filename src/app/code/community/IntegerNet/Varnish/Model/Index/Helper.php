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
 * Class IntegerNet_Varnish_Model_Index_Helper
 */
class IntegerNet_Varnish_Model_Index_Helper
{

    /**
     * @param int $lifetime
     * @param null|string $route
     * @param null|string $url
     * @param null|int $priority
     * @param null|boolean $count
     */
    public function add($lifetime, $route = null, $url = null, $priority = null, $count = null)
    {
        $route = is_null($route) ? $this->_getRoute() : $route;
        $url = is_null($url) ? $this->_getUrl() : $url;
        $priority = is_null($priority) ? $this->_getPriority() : $priority;
        $count = is_null($count) ? $this->_isCount() : $count;

        /** @var IntegerNet_Varnish_Model_Resource_Index $indexResource */
        $indexResource = Mage::getResourceModel('integernet_varnish/index');
        $indexResource->indexUrl($url, $route, $lifetime, $priority, $count);
    }


    /**
     * @param null|string $url
     */
    public function remove($url = null)
    {
        $url = is_null($url) ? $this->_getUrl() : $url;
        
        /** @var IntegerNet_Varnish_Model_Resource_Index $indexResource */
        $indexResource = Mage::getResourceModel('integernet_varnish/index');
        $indexResource->removeUrl($url);
    }


    /**
     * @return string
     */
    protected function _getRoute()
    {
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = Mage::app()->getRequest();

        $route = array(
            $request->getRequestedRouteName(),
            $request->getRequestedControllerName(),
            $request->getRequestedActionName()
        );

        $route = implode('/', $route);

        return $route;
    }


    /**
     * @return string
     */
    protected function _getUrl()
    {
        /** @var Mage_Checkout_Helper_Url $urlHelper */
        $urlHelper = Mage::helper('core/url');

        return $urlHelper->getCurrentUrl();
    }


    /**
     * @return string
     */
    protected function _getPriority()
    {
        /** @var IntegerNet_Varnish_Model_Config $config */
        $config = Mage::getSingleton('integernet_varnish/config');
        $allowedParams = $config->getAllowedParams();

        $params = array_intersect(array_keys($_GET), array_keys($allowedParams));
        $priorities = array();

        foreach ($params as $param) {

            $priorities[] = $allowedParams[$param]['priority'];
        }

        $priority = $priorities ? max($priorities) : 0;

        if($priority <= 0) {

            /** @var IntegerNet_Varnish_Model_Validate $validate */
            $validate = Mage::getSingleton('integernet_varnish/validate');
            $cacheRoute = $validate->getCacheRoute();

            if($cacheRoute) {
                $priority = $cacheRoute['priority'];
            }
        }

        return $priority;
    }

    
    /**
     * @return bool
     */
    public function _isCount()
    {
        /** @var IntegerNet_Varnish_Model_Config $config */
        $config = Mage::getSingleton('integernet_varnish/config');

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && $_SERVER['HTTP_USER_AGENT'] == $config->getBuildUserAgent()) {

            return true;
        }

        return false;
    }
}

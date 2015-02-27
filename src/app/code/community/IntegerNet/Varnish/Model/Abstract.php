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
 * Class IntegerNet_Varnish_Model_Abstract
 */
class IntegerNet_Varnish_Model_Abstract
{


    /**
     * @var IntegerNet_Varnish_Model_Config
     */
    protected $_config;

    /**
     * @var IntegerNet_Varnish_Model_Resource_Index
     */
    protected $_indexResource;

    /**
     * @var Mage_Core_Controller_Request_Http
     */
    protected $_request;

    /**
     * @var Mage_Core_Controller_Response_Http
     */
    protected $_response;


    /**
     *
     */
    public function __construct()
    {
        $this->_config = Mage::getSingleton('integernet_varnish/config');
        $this->_indexResource = Mage::getResourceModel('integernet_varnish/index');
        $this->_request = Mage::app()->getRequest();
        $this->_response = Mage::app()->getResponse();
    }


    /**
     * @return IntegerNet_Varnish_Model_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }


    /**
     * get http cache control header lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        if ($this->isNoRoute()) {
            return 0;
        }

        $route = $this->_request->getRequestedRouteName();
        $controller = $this->_request->getRequestedControllerName();
        $action = $this->_request->getRequestedActionName();

        foreach ($this->_config->getCacheRoutes() as $cacheRoute) {

            if ($cacheRoute['route'] == $route) {

                if ($cacheRoute['controller'] == $controller || $cacheRoute['controller'] == '*') {

                    if ($cacheRoute['action'] == $action || $cacheRoute['action'] == '*') {

                        return $cacheRoute['lifetime'];
                    }
                }
            }
        }

        return 0;
    }


    /**
     * @return bool
     */
    public function isNoRoute()
    {
        $route = $this->_request->getRequestedRouteName();
        $controller = $this->_request->getRequestedControllerName();
        $action = $this->_request->getRequestedActionName();

        if ($route . $controller . $action == 'cmsindexnoRoute') {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isHttpsAllowed()
    {
        return $this->_config->isHttpsAllowed();
    }
}

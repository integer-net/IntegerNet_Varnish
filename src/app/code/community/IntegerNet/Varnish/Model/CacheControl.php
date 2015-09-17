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
 * Set and remove response http headers to control varnish
 *
 * Class IntegerNet_Varnish_Model_CacheControl
 */
class IntegerNet_Varnish_Model_CacheControl extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * /* @var $cookie Mage_Core_Model_Cookie
     */
    protected $_cookie;


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_cookie = Mage::getSingleton('core/cookie');
    }


    /**
     * @return $this
     */
    public function updateResponseHeaders()
    {
        if ($this->_config->isEnabled()) {

            if (!$this->_response->canSendHeaders()) {

                $this->debugHeader('Header Error');

            } else {

                if ($this->isNoRoute()) {

                    $this->_removeIndex();

                } elseif ($this->_request->isSecure() && !$this->isHttpsAllowed()) {

                    $this->debugHeader('https');

                } elseif ($bypass = $this->getBypassStates()) {

                    $this->debugHeader('bypass', $bypass);
                    $this->setNoCacheCookie();

                } elseif ($this->hasNoCacheCookie()) {

                    $this->debugHeader('bypass unset');
                    $this->unsetNoCacheCookie();

                } elseif (!$this->_request->isGet()) {

                    $this->debugHeader('method', $this->_request->getMethod());

                } elseif ($disqualified = $this->getDisqualifiedStates()) {

                    $this->debugHeader('disqualified', $disqualified);

                } elseif ($lifetime = $this->getLifetime()) {

                    $this->debugHeader('lifetime', $lifetime);
                    $this->_headersRemove();
                    $this->_headersAdd($lifetime);
                    $this->_addIndex($lifetime);

                } else {

                    $this->debugHeader('Lifetime', 0);
                }

            }

        }

        return $this;
    }


    /**
     * Remove HTTP response headers
     */
    protected function _headersRemove()
    {
        foreach ($this->_config->getHeadersRemove() as $header) {
            header_remove($header);
        }
    }


    /**
     * Add HTTP response headers
     */
    protected function _headersAdd($lifetime)
    {
        $search = array(
            '{{lifetime}}',
            '{{date}}',
        );

        $replace = array(
            $lifetime,
            gmdate('D, d M Y H:i:s \G\M\T', time() + $lifetime)
        );

        foreach ($this->_config->getHeadersAdd() as $header => $value) {

            $value = str_replace($search, $replace, $value);
            $this->_response->setHeader($header, $value, true);
        }
    }


    /**
     * @param $lifetime
     */
    protected function _addIndex($lifetime)
    {
        $url = Mage::helper('core/url')->getCurrentUrl();

        $route = $this->_request->getRequestedRouteName()
            . '/' . $this->_request->getRequestedControllerName()
            . '/' . $this->_request->getRequestedActionName();

        $this->_indexResource->indexUrl($url, $route, $lifetime);

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && $_SERVER['HTTP_USER_AGENT'] != $this->_config->getBuildUserAgent()) {
            $this->_indexResource->countUrl($url);
        }
    }


    /**
     *
     */
    protected function _removeIndex()
    {
        $url = Mage::helper('core/url')->getCurrentUrl();
        $this->_indexResource->removeByUrl($url);
    }


    /**
     * @return null|array
     */
    public function getBypassStates()
    {
        $states = array();

        $invalidateModels = $this->_config->getInvalidateResponseModels();
        $bypassStates = $this->_config->getBypassStates();

        foreach ($invalidateModels as $invalidate) {

            if (in_array($invalidate->getCode(), $bypassStates) && $invalidate->hasData()) {

                $states[] = $invalidate->getCode();
            }
        }

        return count($states) ? $states : null;
    }


    /**
     * @return null|array
     */
    public function getDisqualifiedStates()
    {
        $states = array();

        $allowedParams = $this->_config->getAllowedParams();

        foreach ($_GET as $param => $value) {

            if (
                !array_key_exists($param, $allowedParams)
                || ($allowedParams[$param] && !in_array($value, $allowedParams[$param]))
            ) {

                $states[] = $param;

                if (!$this->_config->isDebugMode() && count($states)) {
                    return $states;
                }
            }
        }


        $disqualifiedPaths = $this->_config->getDisqualifiedPaths();

        foreach ($disqualifiedPaths as $disqualifiedPath) {

            if (strpos($this->_request->getOriginalPathInfo(), $disqualifiedPath) !== false) {

                $states[] = $disqualifiedPath;

                if (!$this->_config->isDebugMode() && count($states)) {
                    return $states;
                }
            }
        }


        $invalidateModels = $this->_config->getInvalidateResponseModels();
        $disqualifiedStates = $this->_config->getDisqualifiedStates();

        foreach ($invalidateModels as $invalidate) {

            if (in_array($invalidate->getCode(), $disqualifiedStates) && $invalidate->hasData()) {

                $states[] = $invalidate->getCode();

                if (!$this->_config->isDebugMode() && count($states)) {
                    return $states;
                }
            }
        }

        return count($states) ? $states : null;
    }


    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return boolean
     */
    public function hasNoCacheCookie()
    {
        return (boolean)$this->_cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
    }


    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return void
     */
    public function setNoCacheCookie()
    {
        Mage::helper('pagecache')->setNoCacheCookie();
        Mage::helper('integernet_varnish/enterprise')->setNoCacheCookie();
    }


    /**
     * Disable caching on external storage side by setting special cookie
     *
     * @return void
     */
    public function unsetNoCacheCookie()
    {
        if ($this->_cookie->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE)) {
            $this->_cookie->delete(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
        }
        Mage::helper('integernet_varnish/enterprise')->unsetNoCacheCookie();
    }


    /**
     * @param string $message
     * @param null|string|array $additional
     *
     * @return void
     */
    public function debugHeader($message, $additional = null)
    {
        if ($this->_config->isEnabled() && $this->_config->isDebugMode() && $this->_response->canSendHeaders()) {

            if ($additional !== null) {
                $additional = is_array($additional) ? implode(',', $additional) : (string)$additional;
                $message = sprintf('%s (%s)', $message, $additional);
            }

            $this->_response->setHeader('X-IntegerNet-Varnish', $message);
        }
    }
}

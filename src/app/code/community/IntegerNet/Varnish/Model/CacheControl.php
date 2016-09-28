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
 * Set and remove response http headers to control varnish
 *
 * Class IntegerNet_Varnish_Model_CacheControl
 */
class IntegerNet_Varnish_Model_CacheControl extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @throws Zend_Controller_Response_Exception
     */
    public function postdispatch()
    {
        if ($this->getConfig()->isEnabled() && $this->getResponse()->canSendHeaders()) {

            if ($this->getValidate()->isNoRoute()) {

                $this->getIndex()->remove();
                $this->_debugHeader('No Route');
                $this->_debugHeader('Remove URL From Index');

            } elseif ($bypassStates = $this->getValidate()->getBypassStates()) {

                $this->setNoCacheCookie();
                $this->_debugHeader('Bypass States', $bypassStates);
                $this->_debugHeader('Set No Cache Cookie');

            } elseif ($this->getValidate()->hasNoCacheCookie()) {

                $this->_unsetNoCacheCookie();
                $this->_debugHeader('Unset No Cache Cookie');

            } elseif ($this->getValidate()->isDynamicBlockRequest()) {

                $this->_debugHeader('Dynamic Block Request');

            } elseif ($this->getRequest()->isNoGet()) {

                $this->_debugHeader('No Get Request');

            } elseif (!$this->getValidate()->isHttpsAllowed()) {

                $this->_debugHeader('Https Not Allowed');

            } elseif ($disqualifiedParams = $this->getValidate()->getDisqualifiedParams()) {

                $this->_debugHeader('Disqualified Params', $disqualifiedParams);

            } elseif ($disqualifiedPaths = $this->getValidate()->getDisqualifiedPaths()) {

                $this->_debugHeader('Disqualified Paths', $disqualifiedPaths);

            } elseif ($disqualifiedStates = $this->getValidate()->getDisqualifiedStates()) {

                $this->_debugHeader('Disqualified States', $disqualifiedStates);

            } elseif ($lifetime = $this->getValidate()->getLifetime()) {

                $this->_headersRemove();
                $this->_headersAdd($lifetime);

                $this->getIndex()->add($lifetime);

                $this->_debugHeader('Lifetime', $lifetime);

            } else {
                $this->_debugHeader('Lifetime', 0);
            }
        }
    }


    /**
     * Remove HTTP response headers
     */
    protected function _headersRemove()
    {
        foreach ($this->getConfig()->getHeadersRemove() as $header) {

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

        foreach ($this->getConfig()->getHeadersAdd() as $header => $value) {

            $value = str_replace($search, $replace, $value);
            $this->getResponse()->setHeader($header, $value, true);
        }
    }


    /**
     * Disable caching on external storage side by setting special cookie
     */
    public function setNoCacheCookie()
    {
        /** @var Mage_PageCache_Helper_Data $pageCacheHelper */
        $pageCacheHelper = Mage::helper('pagecache');
        $pageCacheHelper->setNoCacheCookie();

        /** @var IntegerNet_Varnish_Model_Enterprise $enterprise */
        $enterprise = Mage::getModel('integernet_varnish/enterprise');
        $enterprise->setNoCacheCookie();
    }


    /**
     * Disable caching on external storage side by setting special cookie
     */
    protected function _unsetNoCacheCookie()
    {
        if ($this->getCookies()->get(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE)) {
            $this->getCookies()->delete(Mage_PageCache_Helper_Data::NO_CACHE_COOKIE);
        }

        /** @var IntegerNet_Varnish_Model_Enterprise $enterprise */
        $enterprise = Mage::getModel('integernet_varnish/enterprise');
        $enterprise->unsetNoCacheCookie();
    }


    /**
     * @param string $message
     * @param null|string|array $additional
     */
    protected function _debugHeader($message, $additional = null)
    {
        if ($this->getConfig()->isDebugMode() && $this->getResponse()->canSendHeaders()) {

            if ($additional !== null) {

                $additional = implode(',', (array)$additional);
                $message = sprintf('%s: %s', $message, $additional);
            }

            $this->getResponse()->setHeader('X-IntegerNet-Varnish', $message);
        }
    }
}

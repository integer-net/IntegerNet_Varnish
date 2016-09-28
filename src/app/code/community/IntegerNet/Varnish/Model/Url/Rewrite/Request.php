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
 * Class IntegerNet_Varnish_Model_Url_Rewrite_Request
 */
class IntegerNet_Varnish_Model_Url_Rewrite_Request extends Mage_Core_Model_Url_Rewrite_Request
{
    

    /**
     * Add location header and disable browser page caching
     *
     * @param string $url
     * @param bool $isPermanent
     */
    protected function _sendRedirectHeaders($url, $isPermanent = false)
    {
        Mage::dispatchEvent('send_redirect_headers', array(
           'url' => $url,
        ));
        
        parent::_sendRedirectHeaders($url, $isPermanent);
    }
}

<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Varnish_Model_Urlrewrite_Matcher_Product extends Enterprise_Catalog_Model_Urlrewrite_Matcher_Product
{
    /**
     * Redirect to product from another store if custom url key defined
     *
     * Rewritten to also set NO_CACHE cookie for Varnish
     *
     * @param int $productId
     * @param string $categoryPath
     */
    protected function _checkStoreRedirect($productId, $categoryPath)
    {
        parent::_checkStoreRedirect($productId, $categoryPath);
        if ($this->_request->isDispatched()) {
            Mage::getSingleton('integernet_varnish/cacheControl')->setNoCacheCookie();
        }
    }

}
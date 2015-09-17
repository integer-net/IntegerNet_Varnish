<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Varnish_Model_Urlrewrite_Matcher_Category extends Enterprise_Catalog_Model_Urlrewrite_Matcher_Category
{
    /**
     * Redirect to category from another store if custom url key defined
     *
     * Rewritten to also set NO_CACHE cookie for Varnish
     *
     * @param int $rewriteId
     */
    protected function _checkStoreRedirect($rewriteId)
    {
        parent::_checkStoreRedirect($rewriteId);
        if ($this->_request->isDispatched()) {
            Mage::getSingleton('integernet_varnish/cacheControl')->setNoCacheCookie();
        }
    }

}
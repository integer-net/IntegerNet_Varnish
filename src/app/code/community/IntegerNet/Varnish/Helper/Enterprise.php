<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */#
class IntegerNet_Varnish_Helper_Enterprise extends Mage_Core_Helper_Abstract
{
    public function isEnterprise113()
    {
        return method_exists('Mage', 'getEdition')
        && Mage::getEdition() === Mage::EDITION_ENTERPRISE
        && version_compare(Mage::getVersion(), '1.13', '>=');
    }

    /**
     * Set NO_CACHE cookie
     */
    public function setNoCacheCookie()
    {
        if ($this->isEnterprise113()) {
            Mage::getSingleton('enterprise_pagecache/cookie')->renew(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);
        }
    }
    /**
     * Unset NO_CACHE cookie
     */
    public function unsetNoCacheCookie()
    {
        if ($this->isEnterprise113()) {
            Mage::getSingleton('enterprise_pagecache/cookie')->delete(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);
        }
    }
}
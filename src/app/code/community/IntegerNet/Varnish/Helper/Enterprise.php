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
            $cookie   = Mage::getSingleton('core/cookie');
            $lifetime = Mage::getStoreConfig(Mage_PageCache_Helper_Data::XML_PATH_EXTERNAL_CACHE_LIFETIME);
            $noCache  = $cookie->get(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);

            if ($noCache) {
                $cookie->renew(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE, $lifetime);
            } else {
                $cookie->set(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE, 1, $lifetime);
            }
        }
    }
    /**
     * Unset NO_CACHE cookie
     */
    public function unsetNoCacheCookie()
    {
        if ($this->isEnterprise113()) {
            Mage::getSingleton('core/cookie')->delete(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);
        }
    }
}
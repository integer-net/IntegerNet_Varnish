<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

/**
 * Class IntegerNet_Varnish_Model_Enterprise
 */
class IntegerNet_Varnish_Model_Enterprise extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @return bool
     */
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
            
            $lifetime = Mage::getStoreConfig(Mage_PageCache_Helper_Data::XML_PATH_EXTERNAL_CACHE_LIFETIME);
            
            $noCache = $this->getCookies()->get(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);

            if ($noCache) {
                $this->getCookies()->renew(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE, $lifetime);
            } else {
                $this->getCookies()->set(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE, 1, $lifetime);
            }
        }
    }
    

    /**
     * Unset NO_CACHE cookie
     */
    public function unsetNoCacheCookie()
    {
        if ($this->isEnterprise113()) {
            
            $this->getCookies()->delete(Enterprise_PageCache_Model_Processor::NO_CACHE_COOKIE);
        }
    }
}
<?php
/**
 * integer_net Magento Module
 *
 * @category IntegerNet
 * @package IntegerNet_<Module>
 * @copyright  Copyright (c) 2012-2013 integer_net GmbH (http://www.integer-net.de/)
 * @author Viktor Franz <vf@integer-net.de>
 */

/**
 * Enter description here ...
 */
class IntegerNet_Varnish_Helper_Config extends Mage_PageCache_Helper_Data
{
    /**
     *
     */
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_DEBUG = 'system/external_page_cache/integernet_varnish_debug';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_ACTION = 'system/external_page_cache/integernet_varnish_action';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_DISQUALIFIED = 'system/external_page_cache/integernet_varnish_invalidate_disqualified';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_INVALIDATE_BYPASS = 'system/external_page_cache/integernet_varnish_invalidate_bypass';
    const XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_PURGE_URI = 'system/external_page_cache/integernet_varnish_purge_uri';

    /**
     *
     */
    const XML_PATH_GLOBAL_INTEGERNET_VARNISH_INVALIDATE = 'global/integernet_varnish/invalidate';

    /**
     * @var null
     */
    protected $_wrapBlockInfo = null;

    /**
     * @param null $store
     * @return string
     */
    public function getPurgeUri($store = null)
    {
        return (string)Mage::getStoreConfig(self::XML_PATH_EXTERNAL_CACHE_INTEGERNET_VARNISH_PURGE_URI, $store);
    }

    /**
     * @return array|null
     */
    public function getBlockWrapInfo()
    {
        if ($this->_wrapBlockInfo === null) {

            $this->_wrapBlockInfo = array();
            $varnishWrap = Mage::app()->getLayout()->getXpath('//varnishwrap');

            if (is_array($varnishWrap)) {
                foreach ($varnishWrap as $node) {
                    $this->_wrapBlockInfo[$node->getAttribute('name')] = array(
                        'name' => $node->getAttribute('name'),
                        'nocache' => ($node->getAttribute('nocache') == 1) ? 1 : 0,
                    );
                }
            }
        }

        return $this->_wrapBlockInfo;
    }
}



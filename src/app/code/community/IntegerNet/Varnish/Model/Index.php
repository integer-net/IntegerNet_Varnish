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
class IntegerNet_Varnish_Model_Index extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    const PRIORITY_HIGH = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_LOW = 3;

    /**
     * prevent duplicate execution on events
     * - catalog_product_save_after
     * - cataloginventory_stock_item_save_after
     *
     * @var array
     */
    protected static $_saveProduct = array();

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('integernet_varnish/index');
    }

    /**
     * add/update product urls for purge control
     *
     * @param $product
     * @return $this
     */
    public function observerProductUrls($product)
    {
        $productId = null;
        if (is_numeric($product)) {
            $productId = $product;
        } elseif ($product instanceof Mage_Catalog_Model_Product && $product->getId()) {
            $productId = $product->getId();
        }

        if ($productId && !array_key_exists($productId, self::$_saveProduct)) {

            $indexUrls = array();

            /** @var $urlCollection Mage_Core_Model_Resource_Url_Rewrite_Collection */
            $urlCollection = Mage::getResourceModel('core/url_rewrite_collection');
            $urlCollection->addFieldToSelect('store_id');
            $urlCollection->addFieldToSelect('request_path');
            $urlCollection->addFieldToFilter('product_id', $productId);
            $urlCollection->load();

            foreach ($urlCollection as $urlItem) {

                $url = Mage::app()->getStore($urlItem->getData('store_id'))->getBaseUrl() . $urlItem->getData('request_path');
                $key = md5($url);

                $indexUrls[$key] = array(
                    'url_key' => $key,
                    'url' => $url,
                    'expire' => date('Y-m-d H:i:s'),
                    'priority' => self::PRIORITY_HIGH,
                );
            }

            $this->getResource()->updateUrls($indexUrls);
            self::$_saveProduct[$productId] = $productId;
        }

        return $this;
    }

    /**
     * add urls by request for purge control
     *
     * @param $url
     * @param $lifetime
     * @return $this
     */
    public function addUrl($url, $lifetime)
    {
        $key = md5($url);

        $indexUrls = array();
        $indexUrls[$key] = array(
            'url_key' => $key,
            'url' => $url,
            'expire' => date('Y-m-d H:i:s', time() + $lifetime),
            'priority' => self::PRIORITY_NORMAL,
        );

        $this->getResource()->addUrls($indexUrls);

        return $this;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getExpiredUrls($limit = 1000)
    {
        return $this->getResource()->getExpiredUrls($limit);
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function removeByIds(array $ids)
    {
        $this->getResource()->removeByIds($ids);

        return $this;
    }

    public function setAllExpire()
    {
        $this->getResource()->setAllExpire();
    }
}
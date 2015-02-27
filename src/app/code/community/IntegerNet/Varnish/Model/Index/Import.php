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
 * Class IntegerNet_Varnish_Model_Index_Import
 */
class IntegerNet_Varnish_Model_Index_Import
{


    /**
     * @param int $storeId Store ID
     *
     * @return int Number of added category URLs
     */
    public function importCategoryUrl($storeId)
    {
        $importCount = 0;

        if (is_numeric($storeId)
            && $storeId > Mage_Core_Model_App::ADMIN_STORE_ID
            && Mage::app()->getStore($storeId)
        ) {

            /** @var IntegerNet_Varnish_Model_Resource_Index $index */
            $index = Mage::getResourceModel('integernet_varnish/index');

            /** @var $categoryCollection Mage_Catalog_Model_Resource_Category_Collection */
            $categoryCollection = Mage::getResourceModel('catalog/category_collection');
            $categoryCollection->setStoreId((int)$storeId);
            $categoryCollection->addAttributeToFilter('is_active', 1);

            /** @var Mage_Catalog_Model_Resource_Url $catalogUrl */
            $catalogUrl = Mage::getResourceModel('catalog/url');

            foreach ($categoryCollection->getAllIds() as $categoryId) {

                $select = $catalogUrl->getReadConnection()->select();
                $select->from($catalogUrl->getMainTable(), array('request_path'));
                $select->where('store_id = ?', (int)$storeId);
                $select->where('category_id = ?', $categoryId);
                $select->where('product_id IS NULL');
                $select->where('options IS NULL');

                $requestPaths = $catalogUrl->getReadConnection()->fetchCol($select);

                foreach ($requestPaths as $requestPath) {

                    $importCount += $index->indexUrl(Mage::getBaseUrl('web') . $requestPath, 'catalog/category/view', -1);
                }
            }
        }

        return $importCount;
    }


    /**
     * @param int $storeId Store ID
     *
     * @return int Number of added product URLs
     */
    public function importProductUrl($storeId)
    {
        $importCount = 0;

        if (is_numeric($storeId)
            && $storeId > Mage_Core_Model_App::ADMIN_STORE_ID
            && Mage::app()->getStore($storeId)
        ) {

            /** @var IntegerNet_Varnish_Model_Resource_Index $index */
            $index = Mage::getResourceModel('integernet_varnish/index');

            /** @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
            $productCollection = Mage::getResourceModel('catalog/product_collection');
            $productCollection->setStoreId((int)$storeId);
            $productCollection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
            $productCollection->addAttributeToFilter('visibility', Mage::getModel('catalog/product_visibility')->getVisibleInSiteIds());

            /** @var Mage_Catalog_Model_Resource_Url $catalogUrl */
            $catalogUrl = Mage::getResourceModel('catalog/url');

            foreach ($productCollection->getAllIds() as $productId) {

                $select = $catalogUrl->getReadConnection()->select();
                $select->from($catalogUrl->getMainTable(), array('request_path'));
                $select->where('store_id = ?', (int)$storeId);
                $select->where('product_id = ?', $productId);
                $select->where('options IS NULL');

                if (!Mage::getStoreConfig('catalog/seo/product_use_categories', $storeId)) {
                    $select->where('category_id IS NULL');
                }

                foreach ($catalogUrl->getReadConnection()->fetchCol($select) as $requestPath) {
                    $importCount += $index->indexUrl(Mage::getBaseUrl('web') . $requestPath, 'catalog/product/view', -1);
                }
            }
        }

        return $importCount;
    }
}

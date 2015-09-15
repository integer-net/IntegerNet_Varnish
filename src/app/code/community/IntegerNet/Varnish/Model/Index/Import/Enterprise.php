<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Varnish_Model_Index_Import_Enterprise implements IntegerNet_Varnish_Model_Index_Import_Interface
{
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

            $select = $categoryCollection->getSelect();

            // main_table must be first array element
            $from = array('main_table' => null) + $select->getPart(Zend_Db_Select::FROM);
            $from['main_table'] = $from['e'];
            unset($from['e']);
            $from = array_map(function($from) {
                $from['joinCondition'] = is_null($from['joinCondition']) ? null : str_replace('`e`', '`main_table`', $from['joinCondition']);
                return $from;
            }, $from);
            $select->setPart(Zend_Db_Select::FROM, $from);
            $where = $select->getPart(Zend_Db_Select::WHERE);
            $where = array_map(function($expr) {
                return str_replace('`e`', '`main_table`', $expr);
            }, $where);
            $select->setPart(Zend_Db_Select::WHERE, $where);

            $select->reset(Zend_Db_Select::COLUMNS);
            $select->columns('main_table.' . $categoryCollection->getEntity()->getIdFieldName());

            Mage::getSingleton('catalog/factory')->getCategoryUrlRewriteHelper()
                ->joinTableToSelect($select, $storeId);

            foreach ($index->getReadConnection()->fetchPairs($select) as $categoryId => $requestPath) {
                $importCount += $index->indexUrl(Mage::app()->getStore($storeId)->getBaseUrl('web') . $requestPath, 'catalog/category/view', -1);
            }
        }

        return $importCount;
    }

    /**
     * This imports only product URLs without added category path. It works in EE and CE but since
     * we can fetch the complete list of product URLs with category paths from the CE rewrite index,
     * the CE specific implementation stays in place.
     *
     * @param $storeId
     * @return int
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

            $select = Mage::getSingleton('catalog/factory')->getProductUrlRewriteHelper()
                ->getTableSelect($productCollection->getAllIds(), 0, 1);

            foreach ($index->getReadConnection()->fetchPairs($select) as $productId => $requestPath) {
                $importCount += $index->indexUrl(Mage::app()->getStore($storeId)->getBaseUrl('web') . $requestPath, 'catalog/product/view', -1);
            }

            return $importCount;
        }
    }

}
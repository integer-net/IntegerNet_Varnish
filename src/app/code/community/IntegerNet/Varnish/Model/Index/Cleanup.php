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
 * Class IntegerNet_Varnish_Model_Index_Cleanup
 */
class IntegerNet_Varnish_Model_Index_Cleanup
{


    /**
     * @param int $storeId
     *
     * @return int The number of deleted urls
     */
    public function deleteRedirectUrls($storeId)
    {
        if (is_numeric($storeId)
            && $storeId > Mage_Core_Model_App::ADMIN_STORE_ID
            && Mage::app()->getStore($storeId)
        ) {

            /** @var IntegerNet_Varnish_Model_Resource_Index $indexResource */
            $indexResource = Mage::getResourceModel('integernet_varnish/index');

            /** @var Varien_Db_Select $rewriteSelect */
            $indexSelect = $indexResource->getReadConnection()->select();
            $indexSelect->from($indexResource->getMainTable(), array('entity_id', 'url'));
            $indexSelect->where('route IN (?)', array('catalog/category/view', 'catalog/product/view'));

            $urls = $indexResource->getReadConnection()->fetchPairs($indexSelect);

            $removeIndexIds = array();

            foreach ($urls as $id => $url) {

                $requestPath = parse_url($url, PHP_URL_PATH);
                $requestPath = substr($requestPath, 1);

                if ($this->_isRedirectPath($storeId, $requestPath)) {

                    $removeIndexIds[] = $id;
                }
            }

            return Mage::getResourceModel('integernet_varnish/index')->removeById($removeIndexIds);
        }

        return 0;
    }


    /**
     * @param int $storeId
     * @param string $requestPath
     *
     * @return bool
     */
    protected function _isRedirectPath($storeId, $requestPath)
    {
        /** @var Mage_Core_Model_Resource_Url_Rewrite $rewrite */
        $rewriteResource = Mage::getResourceModel('core/url_rewrite');

        /** @var Varien_Db_Select $rewriteSelect */
        $rewriteSelect = $rewriteResource->getReadConnection()->select();
        $rewriteSelect->from($rewriteResource->getMainTable(), $rewriteResource->getIdFieldName());
        $rewriteSelect->where('options IS NOT NULL');
        $rewriteSelect->where('store_id = ?', (int)$storeId);
        $rewriteSelect->where('request_path = ?', $requestPath);

        return (bool)$rewriteResource->getReadConnection()->fetchOne($rewriteSelect);
    }
}

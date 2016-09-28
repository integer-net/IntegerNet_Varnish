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
 * Interface IntegerNet_Varnish_Model_Index_Import_Interface
 */
interface IntegerNet_Varnish_Model_Index_Import_Interface
{


    /**
     * @param $storeId
     * @return int
     */
    public function importCategoryUrl($storeId);


    /**
     * @param $storeId
     * @return int
     */
    public function importProductUrl($storeId);
}

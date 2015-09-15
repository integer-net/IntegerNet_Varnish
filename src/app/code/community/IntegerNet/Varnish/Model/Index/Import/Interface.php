<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
interface IntegerNet_Varnish_Model_Index_Import_Interface
{
    public function importCategoryUrl($storeId);

    public function importProductUrl($storeId);
}
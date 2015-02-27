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
 * Class IntegerNet_Varnish_Model_Invalidate_Resource_Product
 */
class IntegerNet_Varnish_Model_Invalidate_Resource_Product implements IntegerNet_Varnish_Model_Invalidate_Resource_Interface
{


    /**
     * @param Varien_Object $resource
     */
    public function invalidate(Varien_Object $resource)
    {
        if ($resource instanceof Mage_Catalog_Model_Product) {

        }
    }
}

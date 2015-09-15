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
 * Class IntegerNet_Varnish_Helper_Data
 */
class IntegerNet_Varnish_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @return IntegerNet_Varnish_Model_Index_Import_Interface
     */
    public function getIndexImportSingleton()
    {
        if ($this->isEnterprise113()) {
            return Mage::getSingleton('integernet_varnish/index_import_enterprise');
        }
        return Mage::getSingleton('integernet_varnish/index_import');
    }

    public function isEnterprise113()
    {
        return method_exists('Mage', 'getEdition')
            && Mage::getEdition() === Mage::EDITION_ENTERPRISE
            && version_compare(Mage::getVersion(), '1.13', '>=');
    }

}

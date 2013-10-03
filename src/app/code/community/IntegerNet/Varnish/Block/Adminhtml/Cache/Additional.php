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
class IntegerNet_Varnish_Block_Adminhtml_Cache_Additional extends Mage_Adminhtml_Block_Template
{
    /**
     * Get clean cache url
     *
     * @return string
     */
    public function getFullPurgeUrl()
    {
        return $this->getUrl('*/integernetvarnish_purge/fullpurge');
    }

    /**
     * Check if block can be displayed
     *
     * @return bool
     */
    public function canShowButton()
    {
        return Mage::helper('integernet_varnish/config')->isEnabled() && Mage::getSingleton('admin/session')->isAllowed('integernet_varnish');
    }
}

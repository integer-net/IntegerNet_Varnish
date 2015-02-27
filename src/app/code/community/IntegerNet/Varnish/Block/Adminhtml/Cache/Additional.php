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
 * Class IntegerNet_Varnish_Block_Adminhtml_Cache_Additional
 */
class IntegerNet_Varnish_Block_Adminhtml_Cache_Additional extends Mage_Adminhtml_Block_Template
{


    /**
     * Get clean cache url
     *
     * @return string
     */
    public function getIndexUrl()
    {
        return $this->getUrl('*/integernetvarnish_index/index');
    }


    /**
     * Check if block can be displayed
     *
     * @return bool
     */
    public function canShowIndexButton()
    {
        return Mage::getSingleton('integernet_varnish/config')->isEnabled() && Mage::getSingleton('admin/session')->isAllowed('system/cache/integernet_varnish_index');
    }


    /**
     * @return null|string
     */
    protected function _toHtml()
    {
        return $this->canShowIndexButton() ? parent::_toHtml() : null;
    }
}

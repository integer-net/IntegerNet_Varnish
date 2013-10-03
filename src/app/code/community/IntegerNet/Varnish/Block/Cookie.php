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
class IntegerNet_Varnish_Block_Cookie extends Mage_Core_Block_Template
{
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if(Mage::helper('integernet_varnish/config')->isEnabled()) {
            return null;
        }

        return parent::_toHtml();
    }
}
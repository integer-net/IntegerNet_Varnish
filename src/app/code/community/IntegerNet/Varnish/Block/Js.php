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
class IntegerNet_Varnish_Block_Js extends Mage_Core_Block_Template
{

    /**
     * @return string
     */
    public function getFetchUrl()
    {
        return $this->getUrl('integernetvarnish/fetch/index');
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (Mage::helper('integernet_varnish')->isEnabled() && Mage::helper('integernet_varnish')->getLifetime()) {
            return parent::_toHtml();
        }

        return null;
    }
}
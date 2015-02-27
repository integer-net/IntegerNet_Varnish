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
 * Class IntegerNet_Varnish_Block_Cookie
 */
class IntegerNet_Varnish_Block_Cookie extends Mage_Core_Block_Template
{


    /**
     * @return string
     */
    public function getTemplate()
    {
        return parent::getTemplate() ? parent::getTemplate() : 'pagecache/cookie.phtml';
    }


    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (Mage::getSingleton('integernet_varnish/config')->isEnabled()) {
            return null;
        }

        return parent::_toHtml();
    }
}

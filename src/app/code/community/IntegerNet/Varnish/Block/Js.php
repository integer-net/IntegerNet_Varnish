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
 * Class IntegerNet_Varnish_Block_Js
 */
class IntegerNet_Varnish_Block_Js extends Mage_Core_Block_Template
{

    /**
     * @return string
     */
    public function getTemplate()
    {
        return parent::getTemplate() ? parent::getTemplate() : 'integernet_varnish/js.phtml';
    }


    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var IntegerNet_Varnish_Model_CacheControl $cacheControl */
        $cacheControl = Mage::getSingleton('integernet_varnish/cacheControl');

        if ($cacheControl->getConfig()->isEnabled()
            && $cacheControl->getConfig()->isDynamicBlock()
            && $cacheControl->getLifetime()
        ) {
            return parent::_toHtml();
        }

        return null;
    }
}

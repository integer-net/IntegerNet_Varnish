<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Block_Cookie
 */
class IntegerNet_Varnish_Block_Cookie extends Mage_Core_Block_Template
{


    /**
     * @var string
     */
    protected $_template = 'pagecache/cookie.phtml';


    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var IntegerNet_Varnish_Model_Config $config */
        $config = Mage::getSingleton('integernet_varnish/config');

        if ($config->isEnabled()) {
            return null;
        }

        return parent::_toHtml();
    }
}

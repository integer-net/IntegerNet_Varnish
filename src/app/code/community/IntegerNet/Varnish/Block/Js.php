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
     * @var string
     */
    protected $_template = 'integernet_varnish/js.phtml';
    

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var IntegerNet_Varnish_Model_Validate $validate */
        $validate = Mage::getSingleton('integernet_varnish/validate');
        
        if ($validate->getConfig()->isEnabled()
            && $validate->getConfig()->isDynamicBlock()
            && !$validate->getDisqualifiedPaths()
            && !$validate->getDisqualifiedParams()
            && !$validate->getDisqualifiedStates()
            && !$validate->getBypassStates()
        ) {
            return parent::_toHtml();
        }

        return null;
    }
}

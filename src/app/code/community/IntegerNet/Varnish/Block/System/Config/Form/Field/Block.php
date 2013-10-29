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
class IntegerNet_Varnish_Block_System_Config_Form_Field_Block extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     *
     */
    public function __construct()
    {
        $this->addColumn('name', array(
            'label' => Mage::helper('integernet_varnish')->__('Block Name'),
            'style' => 'width:336px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('integernet_varnish')->__('Add Block');
        parent::__construct();
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $id = $this->getElement()->getHtmlId();
        return sprintf('<input id="%s" style="display: none;"/>', $id) . parent::_toHtml();
    }
}

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
 * Class IntegerNet_Varnish_Block_System_Config_Form_Field_HeaderRemove
 */
class IntegerNet_Varnish_Block_System_Config_Form_Field_HeaderRemove extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{


    /**
     *
     */
    public function __construct()
    {
        $this->addColumn('header', array(
            'label' => Mage::helper('integernet_varnish')->__('Header'),
            'style' => 'width:630px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('integernet_varnish')->__('Add');
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

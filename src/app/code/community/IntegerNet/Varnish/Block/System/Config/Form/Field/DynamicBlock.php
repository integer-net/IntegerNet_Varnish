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
 * Class IntegerNet_Varnish_Block_System_Config_Form_Field_DynamicBlock
 */
class IntegerNet_Varnish_Block_System_Config_Form_Field_DynamicBlock extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{


    /**
     *
     */
    public function __construct()
    {
        $this->addColumn('name', array(
            'label' => Mage::helper('integernet_varnish')->__('Name'),
            'style' => 'width:200px',
        ));

        $this->addColumn('type', array(
            'label' => Mage::helper('integernet_varnish')->__('Type'),
            'style' => 'width:200px',
        ));

        $this->addColumn('template', array(
            'label' => Mage::helper('integernet_varnish')->__('Template'),
            'style' => 'width:200px',
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

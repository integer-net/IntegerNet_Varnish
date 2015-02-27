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
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Main
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Main extends Mage_Adminhtml_Block_Widget_Grid_Container
{


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_removeButton('add');

        $this->_addButton('back', array(
            'label' => Mage::helper('integernet_varnish')->__('Back'),
            'onclick' => 'setLocation(\'' . $this->getUrl('*/cache/index') . '\')',
            'class' => 'back',
        ));

        $this->_addButton('config', array(
            'label' => Mage::helper('integernet_varnish')->__('Config'),
            'onclick' => sprintf('setLocation(\'%s\')', $this->getUrl('*/system_config/edit', array('section' => 'system'))),
        ));


        $this->_blockGroup = 'integernet_varnish';
        $this->_controller = 'adminhtml_index';

        $this->_headerText = Mage::helper('integernet_varnish')->__('Varnish Index');
    }


    /**
     * @return string
     */
    protected function _toHtml()
    {
        $control = $this->getLayout()->createBlock('integernet_varnish/adminhtml_index_control');

        return parent::_toHtml() . $control->toHtml();
    }
}

<?php

class IntegerNet_Varnish_Block_Adminhtml_Index_Main extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_addButton('back', array(
            'label' => Mage::helper('integernet_varnish')->__('Back'),
            'onclick' => 'setLocation(\'' . $this->getUrl('*/cache/index') . '\')',
            'class' => 'back',
        ));

        $this->_removeButton('add');

        $this->_blockGroup = 'integernet_varnish';
        $this->_controller = 'adminhtml_index_main';

        $this->_headerText = Mage::helper('integernet_varnish')->__('Full Page Cache Index');
    }
}

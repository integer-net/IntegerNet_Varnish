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
class IntegerNet_Varnish_Block_System_Config_Form_Field_Action extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     *
     */
    public function __construct()
    {
        $this->addColumn('action', array(
            'label' => Mage::helper('integernet_varnish')->__('Full Action Name'),
            'style' => 'width:200px',
        ));
        $this->addColumn('lifetime', array(
            'label' => Mage::helper('integernet_varnish')->__('Lifetime'),
            'style' => 'width:120px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('integernet_varnish')->__('Add Action');
        parent::__construct();
    }
}

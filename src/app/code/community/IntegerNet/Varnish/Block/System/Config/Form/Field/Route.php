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
 * Class IntegerNet_Varnish_Block_System_Config_Form_Field_Route
 */
class IntegerNet_Varnish_Block_System_Config_Form_Field_Route extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{


    /**
     *
     */
    public function __construct()
    {
        $this->addColumn('route', array(
            'label' => Mage::helper('integernet_varnish')->__('Route'),
            'style' => 'width:515px',
        ));

        $this->addColumn('lifetime', array(
            'label' => Mage::helper('integernet_varnish')->__('Lifetime (s)'),
            'style' => 'width:80px',
        ));

        $this->addColumn('priority', array(
            'label' => Mage::helper('integernet_varnish')->__('Priority'),
            'style' => 'width:80px',
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

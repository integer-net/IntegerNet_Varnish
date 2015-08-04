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
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Control
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Control extends Mage_Adminhtml_Block_Template
{


    /**
     * @var string
     */
    protected $_template = 'integernet_varnish/index/control.phtml';

    /**
     * @var null|int
     */
    protected $_store;


    /**
     * @return null|string
     */
    protected function _toHtml()
    {
        if (Mage::getSingleton('integernet_varnish/config')->getBuildType()) {

            if (Mage::app()->isSingleStoreMode()) {
                $this->_store = Mage::app()->getDefaultStoreView()->getId();
            } else {
                $this->_store = $this->getRequest()->getParam('store');
            }

            return parent::_toHtml();
        }

        return null;
    }


    /**
     * @return string
     */
    public function getStoreSwitcher()
    {
        /** @var Mage_Adminhtml_Block_Store_Switcher $storeSwitcher */
        $storeSwitcher = $this->getLayout()->createBlock('adminhtml/store_switcher');
        $storeSwitcher->setData('default_store_name', $this->__('Please select Store'));
        $storeSwitcher->setData('use_confirm', false);

        $storeSwitcher->setTemplate('store/switcher.phtml');

        return $storeSwitcher->toHtml();
    }


    /**
     * @return string
     */
    public function getImportProductUrlsButton()
    {
        if ($this->_store) {

            /** @var Mage_Adminhtml_Block_Widget_Button $importProductUrlsButton */
            $importProductUrlsButton = $this->getLayout()->createBlock('adminhtml/widget_button');

            $importProductUrlsButton->setData(array(
                'label' => Mage::helper('integernet_varnish')->__('Import Product Urls'),
                'onclick' => sprintf('confirmSetLocation(\'%s\', \'%s\')',
                    $this->__('Are you sure you want to do this?'),
                    $this->getUrl('*/*/importproducturls', array('store' => $this->_store))),
                'class' => 'add'
            ));

            return $importProductUrlsButton->toHtml();
        }

        return '';
    }


    /**
     * @return string
     */
    public function getImportCategoryUrlsButton()
    {
        if ($this->_store) {

            /** @var Mage_Adminhtml_Block_Widget_Button $importCategoryUrlsButton */
            $importCategoryUrlsButton = $this->getLayout()->createBlock('adminhtml/widget_button');

            $importCategoryUrlsButton->setData(array(
                'label' => Mage::helper('integernet_varnish')->__('Import Category Urls'),
                'onclick' => sprintf('confirmSetLocation(\'%s\', \'%s\')',
                    $this->__('Are you sure you want to do this?'),
                    $this->getUrl('*/*/importcategoryurls', array('store' => $this->_store))),
                'class' => 'add'
            ));

            return $importCategoryUrlsButton->toHtml();
        }

        return '';
    }


    /**
     * @return string
     */
    public function getRemoveRedirectUrlsButton()
    {
        if ($this->_store) {

            /** @var Mage_Adminhtml_Block_Widget_Button $removeRedirectUrlsButton */
            $removeRedirectUrlsButton = $this->getLayout()->createBlock('adminhtml/widget_button');

            $removeRedirectUrlsButton->setData(array(
                'label' => Mage::helper('integernet_varnish')->__('Remove Redirect Urls'),
                'onclick' => sprintf('confirmSetLocation(\'%s\', \'%s\')',
                    $this->__('Are you sure you want to do this?'),
                    $this->getUrl('*/*/cleanupredirect', array('store' => $this->_store))),
                'class' => 'delete'
            ));

            return $removeRedirectUrlsButton->toHtml();
        }

        return '';
    }
}

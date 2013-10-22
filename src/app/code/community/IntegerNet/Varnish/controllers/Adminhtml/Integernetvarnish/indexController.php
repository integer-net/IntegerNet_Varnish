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
class IntegerNet_Varnish_Adminhtml_Integernetvarnish_IndexController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/cache');
        $this->_title($this->__('System'))->_title($this->__('Cache'))->_title($this->__('Varnish Index'));
        $this->_addContent($this->getLayout()->createBlock('integernet_varnish/adminhtml_index_main'));
        $this->renderLayout();
    }

    /**
     * Check current user permission on resource and privilege
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/cache/integernet_varnish_purge');
    }
}

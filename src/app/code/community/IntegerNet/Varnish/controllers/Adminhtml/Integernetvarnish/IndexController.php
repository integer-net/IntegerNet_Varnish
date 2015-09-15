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
 * Class IntegerNet_Varnish_Adminhtml_Integernetvarnish_IndexController
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
     *
     */
    public function masspriorityAction()
    {
        try {
            $indexIds = $this->getRequest()->getParam('index');
            $priority = $this->getRequest()->getParam('priority');

            $affected = Mage::getSingleton('integernet_varnish/index_priority')->update($indexIds, $priority);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('Selected index entries have been deleted.'));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }


    /**
     *
     */
    public function masspurgeflagAction()
    {
        try {
            $ids = $this->getRequest()->getParam('index');

            $affected = Mage::getResourceModel('integernet_varnish/index')->setPurgeFlagById($ids);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('Selected index entries have been set to purge.'));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }


    /**
     *
     */
    public function massdeleteAction()
    {
        try {
            $ids = $this->getRequest()->getParam('index');

            $affected = Mage::getResourceModel('integernet_varnish/index')->removeById($ids);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('%s index entries have been deleted.', $affected));

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }


    /**
     *
     */
    public function importcategoryurlsAction()
    {
        try {
            $storeId = $this->getRequest()->getParam('store');

            $affected = Mage::helper('integernet_varnish')->getIndexImportSingleton()->importCategoryUrl($storeId);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('%s Category URls has been imported.', $affected));

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }


    /**
     *
     */
    public function importproducturlsAction()
    {
        try {
            $storeId = $this->getRequest()->getParam('store');

            $affected = Mage::helper('integernet_varnish')->getIndexImportSingleton()->importProductUrl($storeId);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('%s Product URls has been imported.', $affected));

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }


    /**
     *
     */
    public function cleanupredirectAction()
    {
        try {
            $storeId = $this->getRequest()->getParam('store');

            $affected = Mage::getSingleton('integernet_varnish/index_cleanup')->deleteRedirectUrls($storeId);

            $this->_getSession()->addSuccess(Mage::helper('integernet_varnish')->__('Selected index entries have been deleted.'));

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
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

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
class IntegerNet_Varnish_Adminhtml_Integernetvarnish_PurgeController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Retrieve session model
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * Clean external cache action
     *
     * @return void
     */
    public function fullpurgeAction()
    {
        try {
            if (Mage::helper('integernet_varnish/config')->isEnabled()) {

                Mage::getModel('integernet_varnish/purge')->fullPurge();
            }
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $e->getMessage());
        }

        $this->_redirect('*/cache/index');
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

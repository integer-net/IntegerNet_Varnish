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
 * Class IntegerNet_Varnish_Model_Observer
 */
class IntegerNet_Varnish_Model_Observer
{


    /**
     * @param Varien_Event_Observer $observer
     */
    public function sendRedirectHeaders(Varien_Event_Observer $observer)
    {
        /** @var IntegerNet_Varnish_Model_Index_Helper $index */
        $index = Mage::getSingleton('integernet_varnish/index_helper');
        $index->remove();
    }


    /**
     * any save or delete event
     *
     * @scope global
     * @event model_save_after
     * @event model_delete_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function resourceChange(Varien_Event_Observer $observer)
    {
        $object = $observer->getData('object');

        /** @var IntegerNet_Varnish_Model_Invalidate_Resource $invalidateResource */
        $invalidateResource = Mage::getSingleton('integernet_varnish/invalidate_resource');
        $invalidateResource->invalidate($object);
    }


    /**
     * @scope frontend
     * @event controller_action_predispatch
     * @see Mage_Core_Controller_Varien_Action::preDispatch()
     *
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        /** @var IntegerNet_Varnish_Model_FormKey $formKey */
        $formKey = Mage::getSingleton('integernet_varnish/formKey');
        $formKey->updateFormKey();

        /** @var IntegerNet_Varnish_Model_DynamicBlock $dynamicBlock */
        $dynamicBlock = Mage::getSingleton('integernet_varnish/dynamicBlock');
        $dynamicBlock->predispatchDynamicBlockRequest();

        /** @var IntegerNet_Varnish_Model_Config $config */
        $config = Mage::getSingleton('integernet_varnish/config');

        /**
         * event controller_action_predispatch/observers/pagecache
         * is disables by IntegerNet_Varnish module
         */
        if (!$config->isEnabled()) {

            /** @var Mage_PageCache_Model_Observer $pageCacheObserver */
            $pageCacheObserver = Mage::getSingleton('pagecache/observer');
            $pageCacheObserver->processPreDispatch($observer);
        }
    }


    /**
     * @param Varien_Event_Observer $observer
     *
     * @scope frontend
     * @event controller_action_postdispatch
     * @see Mage_Core_Controller_Varien_Action::postDispatch()
     */
    public function controllerActionPostdispatch(Varien_Event_Observer $observer)
    {
        /** @var IntegerNet_Varnish_Model_CacheControl $cacheControl */
        $cacheControl = Mage::getSingleton('integernet_varnish/cacheControl');
        $cacheControl->postdispatch();

        /** @var IntegerNet_Varnish_Model_DynamicBlock $dynamicBlock */
        $dynamicBlock = Mage::getSingleton('integernet_varnish/dynamicBlock');
        $dynamicBlock->postdispatchDynamicBlockRequest();
    }


    /**
     * @param Varien_Event_Observer $observer
     *
     * @scope frontend
     * @event core_block_abstract_to_html_after
     * @see Mage_Core_Block_Abstract::toHtml()
     */
    public function coreBlockAbstractToHtmlAfter(Varien_Event_Observer $observer)
    {
        $block = $observer->getData('block');
        $transport = $observer->getData('transport');

        /** @var IntegerNet_Varnish_Model_DynamicBlock $dynamicBlock */
        $dynamicBlock = Mage::getSingleton('integernet_varnish/dynamicBlock');
        $dynamicBlock->wrapDynamicBlock($block, $transport);
    }
}

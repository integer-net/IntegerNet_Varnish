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
 * Class IntegerNet_Varnish_Model_Observer
 */
class IntegerNet_Varnish_Model_Observer
{


    /**
     * any save or delete event
     *
     * @param Varien_Event_Observer $observer
     */
    public function resourceChange(Varien_Event_Observer $observer)
    {
        $object = $observer->getData('object');

        Mage::getSingleton('integernet_varnish/invalidate_resource')->invalidate($object);
    }


    /**
     * @param Varien_Event_Observer $observer
     *
     * @see Mage_Core_Controller_Varien_Action::preDispatch()
     */
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        Mage::getSingleton('integernet_varnish/formKey')->updateFormKey();

        if (!Mage::getSingleton('integernet_varnish/dynamicBlock')->predispatchDynamicBlockRequest()) {

            Mage::getSingleton('integernet_varnish/cacheControl')->updateResponseHeaders();
        }

        /**
         * event controller_action_predispatch/observers/pagecache
         * is disables by IntegerNet_Varnish module
         */
        if (!Mage::getSingleton('integernet_varnish/config')->isEnabled()) {

            Mage::getSingleton('pagecache/observer')->processPreDispatch($observer);
        }
    }


    /**
     * @param Varien_Event_Observer $observer
     *
     * @see Mage_Core_Controller_Varien_Action::postDispatch()
     */
    public function controllerActionPostdispatch(Varien_Event_Observer $observer)
    {
        Mage::getSingleton('integernet_varnish/dynamicBlock')->postdispatchDynamicBlockRequest();
    }


    /**
     * @param Varien_Event_Observer $observer
     *
     * @see Mage_Core_Block_Abstract::toHtml()
     */
    public function coreBlockAbstractToHtmlAfter(Varien_Event_Observer $observer)
    {
        $block = $observer->getData('block');
        $transport = $observer->getData('transport');

        Mage::getSingleton('integernet_varnish/dynamicBlock')->wrapDynamicBlock($block, $transport);
    }
}

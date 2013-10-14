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
class IntegerNet_Varnish_FetchController extends Mage_Core_Controller_Front_Action
{
    /**
     *
     */
    public function indexAction()
    {
        /**
         * @see Mage_Core_Helper_Url::getCurrentUrl
         */
        if($from = $this->getRequest()->getParam('from')) {
            $_SERVER['REQUEST_URI'] = base64_decode($from);
        }

        $response = array();

        $blocks = $this->_blocks();
        $response = array_merge($response, $blocks);

        $response = Mage::helper('core')->jsonEncode($response);

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody($response);
    }

    /**
     * @return array
     */
    protected function _blocks()
    {
        $blocks = array();

        /**
         * avoid ___SID param
         */
        Mage::app()->setUseSessionInUrl(false);

        $this->loadLayout();

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('review/session');
        $this->_initLayoutMessages('tag/session');
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('wishlist/session');
        $this->_initLayoutMessages('paypal/session');

        foreach (Mage::helper('integernet_varnish/config')->getBlockWrapInfo() as $name => $info) {

            /** @var $block Mage_Core_Block_Abstract */
            $block = $this->getLayout()->getBlock($name);
            if ($block) {
                $blockWrapId = Mage::helper('integernet_varnish')->getWrapId($name);

                if($block instanceof Mage_Core_Block_Messages && $block->getMessageCollection()->count()) {
                    $blocks['_bb'][$blockWrapId] = $block->toHtml();
                } else {
                    $blocks['_ba'][$blockWrapId] = $block->toHtml();
                }
            }
        }

        return $blocks;
    }
}
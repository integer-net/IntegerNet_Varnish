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
        $response = array(
            'blocks' => $this->_blocks(),
        );

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * @return array
     */
    protected function _blocks()
    {
        $this->loadLayout();

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('review/session');
        $this->_initLayoutMessages('tag/session');
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('wishlist/session');
        $this->_initLayoutMessages('paypal/session');

        $blocks = array();

        foreach (Mage::helper('integernet_varnish/config')->getBlockWrapInfo() as $name => $info) {
            $block = $this->getLayout()->getBlock($name);
            if ($block) {
                $blocks[Mage::helper('integernet_varnish')->getWrapId($name)] = array(
                    'html' => $block->toHtml(),
                    'nocache' => $info['nocache'],
                );
            }
        }

        return $blocks;
    }
}
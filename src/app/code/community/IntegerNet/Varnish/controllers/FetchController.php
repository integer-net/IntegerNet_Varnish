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
        $blocks = array();
        $this->loadLayout();

        foreach (Mage::helper('integernet_varnish')->getBlockWrapInfo() as $name => $id) {
            $block = $this->getLayout()->getBlock($name);
            if ($block) {
                $blocks[$id] = $block->toHtml();
            }
        }

        return $blocks;
    }
}
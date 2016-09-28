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
 * Class IntegerNet_Varnish_Model_DynamicBlock
 */
class IntegerNet_Varnish_Model_DynamicBlock extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @return bool
     */
    public function predispatchDynamicBlockRequest()
    {
        if ($this->getConfig()->isEnabled() && $this->getValidate()->isDynamicBlockRequest()) {

            Mage::app()->setUseSessionInUrl(false); // prevent add ___SID argument to urls
            Mage::app()->getFrontController()->setData('no_render', true);
        }
    }


    /**
     * @throws Zend_Controller_Response_Exception
     */
    public function postdispatchDynamicBlockRequest()
    {
        if ($this->getConfig()->isEnabled()
            && $this->getResponse()->canSendHeaders()
            && $this->getValidate()->isDynamicBlockRequest()
        ) {

            /** @var $layout Mage_Core_Model_Layout */
            $layout = Mage::app()->getLayout();

            $response = array(
                'blocks' => array(),
                'script' => $this->getConfig()->getDynamicBlockJs(),
            );

            foreach ($this->getConfig()->getDynamicBlocks() as $name => $info) {

                /** @var $block Mage_Core_Block_Abstract */
                $block = $layout->getBlock($name);

                if ($block) {

                    $html = trim($block->toHtml());

                    if ($html) {
                        $blockWrapId = $this->_getWrapId($name);
                        $response['blocks'][$blockWrapId] = $html;
                    }
                }
            }

            /** @var Mage_Core_Helper_Data $coreHelper */
            $coreHelper = Mage::helper('core');
            $response = $coreHelper->jsonEncode($response);

            $this->getResponse()->setHeader('Content-Type', 'application/json');
            $this->getResponse()->setBody($response);
        }
    }


    /**
     * @param Mage_Core_Block_Abstract $block
     * @param Varien_Object $transport
     */
    public function wrapDynamicBlock(Mage_Core_Block_Abstract $block, Varien_Object $transport)
    {
        if ($this->getConfig()->isEnabled() && $this->getConfig()->isDynamicBlock()) 
        {
            /** @var $layout Mage_Core_Model_Layout */
            $layout = Mage::app()->getLayout();

            $blockName = $block->getNameInLayout();
            $dynamicBlocks = $this->getConfig()->getDynamicBlocks();

            if (array_key_exists($blockName, $dynamicBlocks)) {

                $dynamicBlock = $dynamicBlocks[$blockName];

                $html = $transport->getData('html');


                /**
                 * If regular request, replace block by placeholder
                 */
                if ($dynamicBlock['type'] && !$this->getValidate()->isDynamicBlockRequest()) {

                    /** @var Mage_Core_Block_Abstract $mockBlock */
                    $mockBlock = $layout->createBlock($dynamicBlock['type'], null, array('template' => $dynamicBlock['template']));

                    if ($mockBlock instanceof Mage_Core_Block_Abstract) {
                        $html = $mockBlock->toHtml();
                    }
                }

                /**
                 * Message block should be have content.
                 */
                if (!in_array($blockName, array('global_messages', 'messages')) || trim($html)) {

                    $info = $this->getConfig()->isDebugMode() ? sprintf('<!-- IntegerNet_Varnish Dynamic Block: %s -->', $blockName) : null;

                    $id = $this->_getWrapId($block->getNameInLayout());
                    $html = sprintf('<div id="%s">%s%s</div>', $id, $info, $html);

                    $transport->setData('html', $html);
                }
            }
        }
    }


    /**
     * @param $blockNameInLayout
     *
     * @return string
     */
    protected function _getWrapId($blockNameInLayout)
    {
        return sprintf('%s_%s', self::DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM, md5($blockNameInLayout));
    }
}

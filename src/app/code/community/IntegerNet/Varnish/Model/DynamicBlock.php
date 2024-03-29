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
 * Set and remove response http headers to control varnish
 *
 * Class IntegerNet_Varnish_Model_DynamicBlock
 */
class IntegerNet_Varnish_Model_DynamicBlock extends IntegerNet_Varnish_Model_Abstract
{


    /**
     *
     */
    const DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM = 'dynamicblock';


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return bool
     */
    public function predispatchDynamicBlockRequest()
    {
        if ($this->_config->isEnabled() && $this->isDynamicBlockRequest()) {

            $this->setNoRender();
        }
    }


    /**
     * @return bool
     */
    public function isDynamicBlockRequest()
    {
        return $this->_request->isAjax() && $this->_request->getParam(self::DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM);
    }


    /**
     *
     */
    public function setNoRender()
    {
        Mage::app()->getFrontController()->setData('no_render', true);
    }


    /**
     *
     */
    public function postdispatchDynamicBlockRequest()
    {
        if ($this->_config->isEnabled()
            && $this->_response->canSendHeaders()
            && $this->isDynamicBlockRequest()
        ) {

            /**
             * privent add ___SID argument to urls
             */
            Mage::app()->setUseSessionInUrl(false);

            /** @var $layout Mage_Core_Model_Layout */
            $layout = Mage::app()->getLayout();

            $response = array(
                'blocks' => array(),
                'script' => $this->_config->getDynamicBlockJs(),
            );

            foreach ($this->_config->getDynamicBlocks() as $name => $info) {

                /** @var $block Mage_Core_Block_Abstract */
                $block = $layout->getBlock($name);

                if ($block) {

                    $html = $block->toHtml();

                    if(trim($html)) {
                        $blockWrapId = $this->getWrapId($name);
                        $response['blocks'][$blockWrapId] = $html;
                    }
                }
            }

            $response = Mage::helper('core')->jsonEncode($response);

            $this->_response->setHeader('Content-Type', 'application/json');
            $this->_response->setBody($response);

            return true;
        }

        return false;
    }


    /**
     * @param Mage_Core_Block_Abstract $block
     * @param Varien_Object $transport
     */
    public function wrapDynamicBlock(Mage_Core_Block_Abstract $block, Varien_Object $transport)
    {
        /** @var IntegerNet_Varnish_Model_CacheControl $cacheControl */
        $cacheControl = Mage::getSingleton('integernet_varnish/cacheControl');

        if ($this->_config->isEnabled()
            && $this->_config->isDynamicBlock()
            && !$cacheControl->getDisqualifiedStates()
            && !$cacheControl->getBypassStates()
        ) {

            /** @var $layout Mage_Core_Model_Layout */
            $layout = Mage::app()->getLayout();

            $blockName = $block->getNameInLayout();
            $dynamicBlocks = $this->_config->getDynamicBlocks();

            if (array_key_exists($blockName, $dynamicBlocks)) {

                $dynamicBlock = $dynamicBlocks[$blockName];

                $html = $transport->getData('html');

                if (!$this->isDynamicBlockRequest() && $dynamicBlock['type']) {

                    /** @var Mage_Core_Block_Abstract $mockBlock */
                    $mockBlock = $layout->createBlock($dynamicBlock['type'], null, array('template' => $dynamicBlock['template']));

                    if ($mockBlock instanceof Mage_Core_Block_Abstract) {
                        $html = $mockBlock->toHtml();
                    }
                }

                /**
                 * Message block should be have content.
                 */
                if(!in_array($blockName, array('global_messages', 'messages')) || trim($html)) {

                    $info = $this->_config->isDebugMode() ? sprintf('<!-- %s -->', $blockName) : null;

                    $id = $this->getWrapId($block->getNameInLayout());
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
    public function getWrapId($blockNameInLayout)
    {
        return sprintf('%s_%s', self::DYNAMIC_BLOCK_REQUEST_IDENTIFICATION_PARAM, md5($blockNameInLayout));
    }

}

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
class IntegerNet_Varnish_Block_Rewrite_Core_Messages extends Mage_Core_Block_Messages
{
    /**
     * Retrieve messages in HTML format grouped by type
     *
     * @return  string
     */
    public function getGroupedHtml()
    {
        $html = parent::getGroupedHtml();

        $enable = Mage::helper('integernet_varnish/config')->isEnabled();
        $holePunching = Mage::helper('integernet_varnish/config')->isHolePunching();

        if ($enable && $holePunching) {
            $blockWrapInfo = Mage::helper('integernet_varnish/config')->getBlockWrapInfo();
            $nameInLayout = $this->getNameInLayout();

            if (array_key_exists($nameInLayout, $blockWrapInfo)) {
                $html = sprintf('<div id="%s">%s</div>', Mage::helper('integernet_varnish')->getWrapId($nameInLayout), $html);
            }
        }

        return $html;
    }

    /**
     *
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = parent::_toHtml();

        $enable = Mage::helper('integernet_varnish/config')->isEnabled();
        $holePunching = Mage::helper('integernet_varnish/config')->isHolePunching();

        if ($enable && $holePunching) {
            $blockWrapInfo = Mage::helper('integernet_varnish/config')->getBlockWrapInfo();
            $nameInLayout = $this->getNameInLayout();

            if (array_key_exists($nameInLayout, $blockWrapInfo)) {
                if (strpos($html, Mage::helper('integernet_varnish')->getWrapId($nameInLayout)) !== false) {
                    $this->setFrameTags(null);
                }
            }
        }

        return $html;
    }
}

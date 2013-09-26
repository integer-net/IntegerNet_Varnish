<?php

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

        $blockWrapInfo = Mage::helper('integernet_varnish')->getBlockWrapInfo();
        $nameInLayout = $this->getNameInLayout();

        if (array_key_exists($nameInLayout, $blockWrapInfo)) {
            $html = sprintf('<div id="%s">%s</div>', Mage::helper('integernet_varnish')->getWrapId($nameInLayout), $html);
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

        $blockWrapInfo = Mage::helper('integernet_varnish')->getBlockWrapInfo();
        $nameInLayout = $this->getNameInLayout();

        if (array_key_exists($nameInLayout, $blockWrapInfo)) {
            if (strpos($html, Mage::helper('integernet_varnish')->getWrapId($nameInLayout)) !== false) {
                $this->setFrameTags(null);
            }
        }

        return $html;
    }
}

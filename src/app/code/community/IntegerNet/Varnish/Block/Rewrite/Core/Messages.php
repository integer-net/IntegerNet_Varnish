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
 * Class IntegerNet_Varnish_Block_Rewrite_Core_Messages
 *
 * Die Ausgabe Session Mesages erfolgt über die Methode getGroupedHtml
 * da beim Aufruf der Methode kein core_block_abstract_to_html_after Event
 * ...
 */
class IntegerNet_Varnish_Block_Rewrite_Core_Messages extends Mage_Core_Block_Messages
{


    /**
     * @var bool
     */
    protected static $_dispatchEvent = true;


    /**
     * Retrieve messages in HTML format grouped by type
     *
     * Dispatch core_block_abstract_to_html_after event
     * to wrap HTML output for IntegerNet_Varnish dynamic blocks
     *
     * @see IntegerNet_Varnish_Block_Rewrite_Core_Messages::toHtml()
     *
     * @return  string
     */
    public function getGroupedHtml()
    {
        $html = parent::getGroupedHtml();

        if (self::$_dispatchEvent && Mage::app()->getStore()->getId() !== Mage_Core_Model_App::ADMIN_STORE_ID) {

            $transportObject = new Varien_Object();
            $transportObject->setData('html', $html);

            Mage::dispatchEvent('core_block_abstract_to_html_after', array(
                'block' => $this,
                'transport' => $transportObject
            ));

            $html = $transportObject->getData('html');
        }

        self::$_dispatchEvent = true;

        return $html;
    }


    /**
     * @return string
     */
    protected function _toHtml()
    {
        self::$_dispatchEvent = false;

        return parent::_toHtml();
    }
}

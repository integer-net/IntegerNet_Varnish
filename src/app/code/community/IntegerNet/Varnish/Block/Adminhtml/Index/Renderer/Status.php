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

class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $date = $row->getData($this->getColumn()->getIndex());
        $dataFormat = Mage::app()->getLocale()->date($date, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);

        if (strtotime(now()) > strtotime($date)) {
            return '<span class="grid-severity-critical"><span>'.$dataFormat.'</span></span>';
        }

        return '<span class="grid-severity-notice"><span>'.$dataFormat.'</span></span>';
    }
}
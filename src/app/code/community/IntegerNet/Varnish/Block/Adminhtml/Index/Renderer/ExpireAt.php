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
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_ExpireAt
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_ExpireAt extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{


    /**
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $date = $row->getData($this->getColumn()->getIndex());
        $dataFormat = Mage::app()->getLocale()->date($date, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);

        if (strtotime(now()) > strtotime($date)) {
            return '<span style="display: block; border-radius: 10px; background-color: #E41101; color: #FFFFFF;">' . $dataFormat . '</span>';
        }

        return '<span style="display: block; border-radius: 10px; background-color: #3CB861; color: #FFFFFF;">' . $dataFormat . '</span>';
    }
}

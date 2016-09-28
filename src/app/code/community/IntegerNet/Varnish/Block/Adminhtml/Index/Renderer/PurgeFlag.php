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
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_PurgeFlag
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_PurgeFlag extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{


    /**
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $purgeFlag = $row->getData($this->getColumn()->getIndex());

        if ($purgeFlag == IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_YES) {
            return '<span style="display: block; border-radius: 10px; background-color: #FF9C00; color: #FFFFFF;">' . $this->__('Yes') . '</span>';
        }

        return '<span style="display: block; border-radius: 10px; background-color: #3CB861; color: #FFFFFF;">' . $this->__('No') . '</span>';
    }
}

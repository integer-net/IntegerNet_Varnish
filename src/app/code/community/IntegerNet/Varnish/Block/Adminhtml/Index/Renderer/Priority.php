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
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_Priority
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Renderer_Priority extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Number
{


    /**
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $value = parent::render($row);

        $priority = Mage::getSingleton('integernet_varnish/config')->getBuildPriority();

        if ($priority && $value > 0 && $value <= $priority) {
            return '<span style="display: block; border-radius: 10px; background-color: #3CB861; color: #FFFFFF; text-align: center;">' . $value . '</span>';
        }

        return '<span style="display: block; border-radius: 10px; background-color: #E41101; color: #FFFFFF; text-align: center;">' . $value . '</span>';
    }
}

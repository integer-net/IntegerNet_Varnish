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
class IntegerNet_Varnish_Model_System_Config_Source_Invalidate
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();

        /** @var $invalidateModel IntegerNet_Varnish_Model_Invalidate_Interface */
        foreach (Mage::helper('integernet_varnish')->getInvalidateModels() as $invalidateModel) {
            $options[] = array('value' => $invalidateModel->getCode(), 'label' => $invalidateModel->getName());
        }

        return $options;
    }
}

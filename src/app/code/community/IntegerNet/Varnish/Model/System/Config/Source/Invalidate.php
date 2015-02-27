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
 * Class IntegerNet_Varnish_Model_System_Config_Source_Invalidate
 */
class IntegerNet_Varnish_Model_System_Config_Source_Invalidate
{


    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();

        /** @var IntegerNet_Varnish_Model_Config $config */
        $config = Mage::getSingleton('integernet_varnish/config');

        /** @var $invalidateModel IntegerNet_Varnish_Model_Invalidate_Response_Interface */
        foreach ($config->getInvalidateResponseModels() as $invalidateModel) {

            $options[] = array('value' => $invalidateModel->getCode(), 'label' => $invalidateModel->getName());
        }

        return $options;
    }
}

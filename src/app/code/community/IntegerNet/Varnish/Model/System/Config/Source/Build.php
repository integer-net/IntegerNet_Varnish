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
 * Class IntegerNet_Varnish_Model_System_Config_Source_Build
 */
class IntegerNet_Varnish_Model_System_Config_Source_Build
{


    const BUILD_NO = 0;
    const BUILD_SHELL = 1;
    const BUILD_PHP = 2;


    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::BUILD_NO, 'label' => Mage::helper('integernet_varnish')->__('No')),
            array('value' => self::BUILD_SHELL, 'label' => Mage::helper('integernet_varnish')->__('Shell (recommended)')),
            array('value' => self::BUILD_PHP, 'label' => Mage::helper('integernet_varnish')->__('PHP')),
        );
    }


    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::BUILD_NO => Mage::helper('integernet_varnish')->__('No'),
            self::BUILD_SHELL => Mage::helper('integernet_varnish')->__('Yes (Shell)'),
            self::BUILD_PHP => Mage::helper('integernet_varnish')->__('Yes (PHP)'),
        );
    }

}

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
 * Class IntegerNet_Varnish_Model_Index_Priority
 */
class IntegerNet_Varnish_Model_Index_Priority
{


    /**
     *
     */
    const PRIORITY_HEIGHT = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_LOW = 3;


    /**
     * @return array
     */
    public function getOptions()
    {
        return array(
            self::PRIORITY_HEIGHT => Mage::helper('integernet_varnish')->__('Height'),
            self::PRIORITY_NORMAL => Mage::helper('integernet_varnish')->__('Normal'),
            self::PRIORITY_LOW => Mage::helper('integernet_varnish')->__('Low'),
        );
    }


    /**
     * @param array $entityIds
     * @param int $priority
     *
     * @return int The number of affected rows.
     */
    public function update($entityIds, $priority)
    {
        if ($entityIds && array_key_exists($priority, $this->getOptions())) {

            return Mage::getResourceModel('integernet_varnish/index')->setPriority($entityIds, $priority);
        }

        return 0;
    }
}

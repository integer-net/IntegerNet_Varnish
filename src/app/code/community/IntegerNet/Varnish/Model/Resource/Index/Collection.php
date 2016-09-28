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
 * Class IntegerNet_Varnish_Model_Resource_Index_Collection
 */
class IntegerNet_Varnish_Model_Resource_Index_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{


    /**
     *
     */
    protected function _construct()
    {
        $this->_init('integernet_varnish/index');
    }
}

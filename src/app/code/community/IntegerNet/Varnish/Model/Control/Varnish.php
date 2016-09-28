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
 * Class IntegerNet_Varnish_Model_Control_Varnish
 */
class IntegerNet_Varnish_Model_Control_Varnish implements Mage_PageCache_Model_Control_Interface
{


    /**
     *
     */
    public function clean()
    {
        /** @var IntegerNet_Varnish_Model_Index_Purge $indexPurge */
        $indexPurge = Mage::getModel('integernet_varnish/index_purge');
        $indexPurge->purgeAll();
    }
}

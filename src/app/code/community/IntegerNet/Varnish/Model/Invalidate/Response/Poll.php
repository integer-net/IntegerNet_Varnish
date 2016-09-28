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
 * Class IntegerNet_Varnish_Model_Invalidate_Response_Poll
 */
class IntegerNet_Varnish_Model_Invalidate_Response_Poll implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{


    /**
     * @return string
     */
    public function getCode()
    {
        return 'poll';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'Poll';
    }


    /**
     * @return bool
     */
    public function hasData()
    {
        return (bool)Mage::getSingleton('poll/poll')->getVotedPollsIds();
    }
}

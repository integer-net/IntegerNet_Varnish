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
 * Interface IntegerNet_Varnish_Model_Invalidate_Response_Interface
 */
interface IntegerNet_Varnish_Model_Invalidate_Response_Interface
{


    /**
     * @return mixed
     */
    public function getCode();


    /**
     * @return mixed
     */
    public function getName();


    /**
     * @return mixed
     */
    public function hasData();
}

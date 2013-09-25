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
interface IntegerNet_Varnish_Model_Invalidate_Interface
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
    public function getDescription();

    /**
     * @return mixed
     */
    public function hasData();

    /**
     * @return mixed
     */
    public function hasChange();
}
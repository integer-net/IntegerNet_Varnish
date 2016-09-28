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
 * Class IntegerNet_Varnish_Model_Index
 * @method IntegerNet_Varnish_Model_Resource_Index getResource()
 */
class IntegerNet_Varnish_Model_Index extends Mage_Core_Model_Abstract
{


    /**
     *
     */
    protected function _construct()
    {
        $this->_init('integernet_varnish/index');
    }


    /**
     * @return array
     */
    public function getRouteOptions()
    {
        $routeOptions = $this->getResource()->getRouteOptions();

        if ($routeOptions) {
            return array_combine($routeOptions, $routeOptions);
        }

        return array();
    }
}

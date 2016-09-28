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
 * Class IntegerNet_Varnish_Model_Invalidate_Resource
 */
class IntegerNet_Varnish_Model_Invalidate_Resource
{


    /**
     * @var IntegerNet_Varnish_Model_Config
     */
    protected $_config;


    /**
     *
     */
    public function __construct()
    {
        $this->_config = Mage::getSingleton('integernet_varnish/config');
    }


    /**
     *
     *
     * @param mixed $resource
     *
     * @return $this
     */
    public function invalidate($resource)
    {
        if ($resource && $this->_config->isEnabled()) {

            foreach ($this->_config->getInvalidateResourceModels() as $invalidateResourceModel) {

                $invalidateResourceModel->invalidate($resource);
            }
        }

        return $this;
    }
}

<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/*
 * Class IntegerNet_Varnish_Model_Invalidate_Response_Category
 */
class IntegerNet_Varnish_Model_Invalidate_Response_Category implements IntegerNet_Varnish_Model_Invalidate_Response_Interface
{


    /**
     * @return string
     */
    public function getCode()
    {
        return 'category';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'Category (Page Size, View, Sorting)';
    }


    /**
     * @return bool
     */
    public function hasData()
    {
        $toolbarParams = array(
            'limit_page' => 'limit',
            'sort_order' => 'order',
            'sort_direction' => 'dir',
            'display_mode' => 'mode',
        );

        $catalogSession = Mage::getSingleton('catalog/session');
        $request = Mage::app()->getRequest();

        foreach ($toolbarParams as $sessionParam => $requestParam) {
            if ($catalogSession->getData($sessionParam) && !$request->getParam($requestParam)) {
                return true;
            }
        }

        return false;
    }
}

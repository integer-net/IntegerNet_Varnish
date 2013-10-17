<?php

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

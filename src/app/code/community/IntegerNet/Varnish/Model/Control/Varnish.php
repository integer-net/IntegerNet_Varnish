<?php
/**
 * Created by PhpStorm.
 * User: vfranz
 * Date: 29.10.13
 * Time: 22:02
 */

class IntegerNet_Varnish_Model_Control_Varnish implements Mage_PageCache_Model_Control_Interface
{
    public function clean()
    {
        return Mage::getModel('integernet_varnish/purge')->fullPurge();
    }
} 
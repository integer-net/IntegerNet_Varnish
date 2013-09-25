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
class IntegerNet_Varnish_Model_Purge
{
    /**
     *
     */
    public function warm()
    {
        $removerFromIndex = array();

        /** @var $purge IntegerNet_Varnish_Model_Index */
        $index = Mage::getModel('integernet_varnish/index');
        $expiredUrls = $index->getExpiredUrls();

        $clint = new Zend_Http_Client();
        $clint->setHeaders('Accept-Encoding', 'gzip, deflate');
        $clint->setHeaders('Cache-Control', 'no-cache');

        foreach($expiredUrls as $id => $url) {
            $clint->setUri($url);
            $response = $clint->request();

            if($response->getStatus() != 200) {
                $removerFromIndex[] = $id;
            }
        }

        $index->removeByIds($removerFromIndex);
    }
}
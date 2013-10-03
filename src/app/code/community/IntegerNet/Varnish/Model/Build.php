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
class IntegerNet_Varnish_Model_Build
{
    const MAX_RUN_TIME = 300;

    /**
     *
     */
    public function build()
    {
        if(Mage::helper('integernet_varnish/config')->isDebugMode()) {
            Mage::log('Start', null, 'integernet_varnish_build.log', true);
        }

        $runTime = 0;
        $removerFromIndex = array();

        /** @var $purge IntegerNet_Varnish_Model_Index */
        $index = Mage::getModel('integernet_varnish/index');
        $expiredUrls = $index->getExpiredUrls(self::MAX_RUN_TIME * 2);

        $expiredUrlTotal = count($expiredUrls);
        $expiredUrlCount = 0;

        $clint = new Zend_Http_Client();
        $clint->setHeaders('Accept-Encoding', 'gzip, deflate');
        $clint->setHeaders('Cache-Control', 'no-cache');

        foreach($expiredUrls as $id => $url) {

            $timeStart = microtime(true);
            $expiredUrlCount++;

            $clint->setUri($url);
            $response = $clint->request();

            if($response->getStatus() != 200) {
                $removerFromIndex[] = $id;
            }

            usleep(300000); // 0.3s

            $timeStop = microtime(true);
            $runTimeCurrent = $timeStop - $timeStart;
            $runTime += $runTimeCurrent;

            if(Mage::helper('integernet_varnish/config')->isDebugMode()) {
                $label = $this->_getLogLabel($expiredUrlCount, $expiredUrlTotal, $runTimeCurrent, $response->getStatus(), $url);
                Mage::log($label, null, 'integernet_varnish_build.log', true);
            }

            if($runTime > self::MAX_RUN_TIME) {
                break;
            }
        }

        $index->removeByIds($removerFromIndex);

        if(Mage::helper('integernet_varnish/config')->isDebugMode()) {
            Mage::log('Finish', null, 'integernet_varnish_build.log', true);
        }
    }

    /**
     * @param $i
     * @param $from
     * @param $time
     * @param $responseCode
     * @param $url
     * @return string
     */
    protected function _getLogLabel($i, $from, $time, $responseCode, $url)
    {
        $i = str_pad($i, 4, '_', STR_PAD_LEFT);
        $from = str_pad($from, 4, '_', STR_PAD_LEFT);
        $time = number_format($time, 2);
        $time = str_pad($time, 5, '0', STR_PAD_LEFT);

        return sprintf('[%s/%s] - %ss - %s : %s', $i, $from, $time, $responseCode, $url);
    }
}
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
 * Class IntegerNet_Varnish_Model_Index_Purge
 */
class IntegerNet_Varnish_Model_Index_Purge extends IntegerNet_Varnish_Model_Abstract
{


    /**
     *
     */
    const PURGE_FLAG_NO = 0;
    const PURGE_FLAG_YES = 1;


    /**
     * call by crontab to purge URL asynchronous
     *
     * @param Varien_Object|Mage_Cron_Model_Schedule $schedule
     */
    public function purgeSchedule(Varien_Object $schedule = null)
    {
        $message = array();
        $purgedUrlIds = array();

        foreach ($this->_indexResource->getPurgeFlaggedUrls($this->_config->getPurgeSize()) as $indexEntityId => $url) {

            $httpResponse = $this->purgeUrl($url);

            if ($httpResponse->isSuccessful()) {

                $purgedUrlIds[] = $indexEntityId;

            } else {
                $message[] = sprintf('%s - %s', $httpResponse->getStatus(), $url);
            }
        }

        if ($purgedUrlIds) {
            $this->_indexResource->setExpireById($purgedUrlIds);
            $this->_indexResource->unsetPurgeFlagById($purgedUrlIds);
        }

        if ($schedule && count($message)) {
            $schedule->setData('messages', implode(PHP_EOL, $message));
        }
    }


    /**
     * @throws Exception
     * @return void
     */
    public function purgeAll()
    {
        $purged = array();

        /** @var $store Mage_Core_Model_Store */
        foreach (Mage::app()->getStores() as $store) {

            /** @var Zend_Uri_Http $url */
            $url = $store->getBaseUrl();
            $url = Zend_Uri::factory($url);
            $url->setPath($this->_config->getPurgeUrl());

            if (!in_array($url->getHost(), $purged)) {

                $purged[] = $url->getHost();

                $httpResponse = $this->purgeUrl($url->getUri());

                $message = Mage::helper('integernet_varnish')->__('Varnish PURGE Response for %s <i>%s (%s)</i>', $url->getUri(), $httpResponse->getMessage(), $httpResponse->getStatus());

                if ($httpResponse->isSuccessful()) {

                    $this->_indexResource->setExpireAll();
                    $this->_indexResource->unsetPurgeFlagAll();

                    Mage::getSingleton('adminhtml/session')->addSuccess($message);
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($message);

                    throw new Exception($message);
                }
            }
        }
    }


    /**
     * @param string $url
     *
     * @return Zend_Http_Response
     */
    public function purgeUrl($url)
    {
        /** @var Zend_Uri_Http $requestUrl */
        $requestUrl = Zend_Uri::factory($url);
        $headerHost = $requestUrl->getHost();
        $requestUrl->setHost($this->_config->getPurgeServer());
        $requestUrl->setPort($this->_config->getPurgePort());

        /** @var Zend_Http_Client $httpClint */
        $httpClint = new Zend_Http_Client($requestUrl);
        $httpClint->setHeaders('Host', $headerHost);
        $httpClint->setMethod('PURGE');

        /** @var $response Zend_Http_Response */
        $httpResponse = $httpClint->request();

        return $httpResponse;
    }


    /**
     * @return array
     */
    public function getFlagOptions()
    {
        return array(
            self::PURGE_FLAG_NO => Mage::helper('integernet_varnish')->__('No'),
            self::PURGE_FLAG_YES => Mage::helper('integernet_varnish')->__('Yes'),
        );
    }
}

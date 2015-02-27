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
 * Class IntegerNet_Varnish_Model_Index_Build_Php
 */
class IntegerNet_Varnish_Model_Index_Build_Php extends IntegerNet_Varnish_Model_Index_Build
{


    /**
     *
     */
    public function build()
    {
        $timeout = 0;

        $logFilePathName = $this->_getVarDir() . $this->_outputFileName . '.log';

        $urls = $this->_indexResource->getExpiredUrls($this->_config->getBuildPhpLimit());

        foreach ($urls as $id => $url) {

            $totalTime = microtime(true);
            $timeoutStart = microtime(true);

            try {
                $httpClient = new Zend_Http_Client($url, array(
                    'useragent' => $this->_config->getBuildUserAgent(),
                    'timeout' => $this->_config->getBuildTimeout(),
                ));

                /** @var Zend_Http_Response $httpResponse */
                $httpResponse = $httpClient->request();

                $totalTime = microtime(true) - $totalTime;
                $totalTime = number_format($totalTime, 3, ',', '');

                $logMessage = sprintf('%s - %s - %ss - %s%s', now(), $httpResponse->getStatus(), $totalTime, $url, PHP_EOL);

                if ($httpResponse->getStatus() == 301) {

                    $this->_indexResource->removeById($id);
                }

            } catch (Exception $e) {

                $logMessage = sprintf('%s - %s - %s%s', now(), $e->getMessage(), $url, PHP_EOL);
            }

            file_put_contents($logFilePathName, $logMessage, FILE_APPEND);

            $timeout += microtime(true) - $timeoutStart;

            if ($timeout >= $this->_config->getBuildPhpTimeout()) {
                break;
            }
        }
    }
}

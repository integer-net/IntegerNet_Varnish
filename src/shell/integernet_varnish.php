<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


if (strpos(__FILE__, '.modman')) {
    require_once __DIR__ . '/../../../../src/shell/abstract.php';
} else {
    require_once 'abstract.php';
}


/**
 * Class IntegerNet_Varnish
 */
class IntegerNet_Varnish extends Mage_Shell_Abstract
{


    /**
     *
     */
    public function run()
    {
        if ($this->getArg('purge-url')) {

            /** @var IntegerNet_Varnish_Model_Index_Purge $purge */
            $purge = Mage::getSingleton('integernet_varnish/index_purge');
            $purge->purgeUrl($this->getArg('purge-url'));

        } elseif ($this->getArg('purge-all')) {

            /** @var IntegerNet_Varnish_Model_Index_Purge $purge */
            $purge = Mage::getSingleton('integernet_varnish/index_purge');
            $purge->purgeAll();

        } elseif ($this->getArg('build')) {

            /** @var IntegerNet_Varnish_Model_Index_Build $build */
            $build = Mage::getSingleton('integernet_varnish/index_build');
            $build->build();

        } elseif ($this->getArg('build-shell-export')) {

            /** @var IntegerNet_Varnish_Model_Index_Build_Shell $build */
            $build = Mage::getModel('integernet_varnish/index_build_shell');
            $build->writeShellBuildUrls();

        } elseif ($this->getArg('build-shell-export-all')) {

            /** @var IntegerNet_Varnish_Model_Index_Build_Shell $build */
            $build = Mage::getModel('integernet_varnish/index_build_shell');
            $build->writeAllShellBuildUrls();

        } else {

            echo $this->usageHelp();
        }
    }


    /**
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f integernet_varnish.php -- [options]

  --purge-url <url>           Purge single URL
  --purge-all                 Purge all URLs
  --build-shell-export        Export expired URLs for shell warmer
  --build-shell-export-all    Export all URLs for shell warmer
    help                      This help

USAGE;
    }
}


$shell = new IntegerNet_Varnish();
$shell->run();

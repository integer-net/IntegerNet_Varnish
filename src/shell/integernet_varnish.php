<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


require_once 'abstract.php';


/**
 * Class IntegerNet_Varnish
 */
class IntegerNet_Varnish extends Mage_Shell_Abstract
{


    /**
     * Run script
     *
     */
    public function run()
    {
        if ($purge = $this->getArg('purge')) {

            if ($purge == 'all') {

                Mage::getSingleton('integernet_varnish/index_purge')->purgeAll();

            } else {

                Mage::getSingleton('integernet_varnish/index_purge')->purgeUrl($purge);
            }
        } elseif ($this->getArg('reboot')) {

            Mage::getSingleton('integernet_varnish/index_build_shell')->deleteJobFiles();
            Mage::getSingleton('integernet_varnish/index_purge')->purgeAll();

        } else {
            echo $this->usageHelp();
        }
    }


    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f integernet_varnish.php -- [options]

  --purge all|<url>             Show Indexer(s) Status
  help                          This help

USAGE;
    }
}


$shell = new IntegerNet_Varnish();
$shell->run();

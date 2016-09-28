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
 * Class IntegerNet_Varnish_Model_Index_Build
 */
class IntegerNet_Varnish_Model_Index_Build extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @var string
     */
    protected $_outputFileName;


    /**
     *
     */
    public function __construct()
    {
        $this->_outputFileName = sprintf('build_%s', date('YmdHis'));
    }


    /**
     *
     */
    public function build()
    {
        if ($this->getConfig()->isBuildPhp()) {

            /** @var IntegerNet_Varnish_Model_Index_Build_Php $build */
            $build = Mage::getModel('integernet_varnish/index_build_php');
            $build->build();

        } elseif ($this->getConfig()->isBuildShell()) {

            /** @var IntegerNet_Varnish_Model_Index_Build_Shell $build */
            $build = Mage::getModel('integernet_varnish/index_build_shell');
            $build->build();
        }
    }


    /**
     * @return string
     */
    protected function _getVarDir()
    {
        $varDir = Mage::getBaseDir('var') . DS . 'integernet_varnish' . DS;
        Mage::getConfig()->getOptions()->createDirIfNotExists($varDir);
        
        return $varDir;
    }


    /**
     * @return IntegerNet_Varnish_Model_Resource_Index
     */
    protected function _indexResource()
    {
        return Mage::getResourceSingleton('integernet_varnish/index');
    }
}

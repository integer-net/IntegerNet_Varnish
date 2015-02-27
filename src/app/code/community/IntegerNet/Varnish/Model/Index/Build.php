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
 * Class IntegerNet_Varnish_Model_Index_Build
 */
class IntegerNet_Varnish_Model_Index_Build extends IntegerNet_Varnish_Model_Abstract
{


    /**
     * @var string
     */
    protected $_varDir;

    /**
     * @var string
     */
    protected $_outputFileName;


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_outputFileName = sprintf('build_%s', date('YmdHis'));
    }


    /**
     *
     */
    public function build()
    {
        if ($this->_config->isBuildPhp()) {

            Mage::getModel('integernet_varnish/index_build_php')->build();

        } elseif ($this->_config->isBuildShell()) {

            Mage::getModel('integernet_varnish/index_build_shell')->build();
        }
    }


    /**
     * @return string
     */
    protected function _getVarDir()
    {
        if ($this->_varDir === null) {
            $this->_varDir = Mage::getBaseDir('var') . DS . 'integernet_varnish' . DS;
            Mage::getConfig()->getOptions()->createDirIfNotExists($this->_varDir);
        }

        return $this->_varDir;
    }
}

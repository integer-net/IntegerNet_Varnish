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
 * Class IntegerNet_Varnish_BuildController
 */
class IntegerNet_Varnish_BuildController extends Mage_Core_Controller_Front_Action
{


    /**
     * @var IntegerNet_Varnish_Model_Index_Build_Shell
     */
    protected $_buildShell;


    /**
     * @return Mage_Core_Controller_Front_Action|void
     */
    public function preDispatch()
    {
        $this->_buildShell = Mage::getModel('integernet_varnish/index_build_shell');

        if ($this->_buildShell->getSecret() != $this->getRequest()->getParam('secret')) {

            exit();
        }
    }


    /**
     * Write URL list to shell build directory.
     */
    public function listAction()
    {
        $this->_buildShell->writeShellBuildUrls();
    }
}

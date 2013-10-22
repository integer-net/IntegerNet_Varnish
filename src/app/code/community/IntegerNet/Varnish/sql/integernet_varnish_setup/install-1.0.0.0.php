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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Balloon / Catalog Product Associated
 */
if (Mage::getIsDeveloperMode()) $installer->getConnection()->dropTable($installer->getTable('integernet_varnish/index'));

$table = $installer->getConnection()
    ->newTable($installer->getTable('integernet_varnish/index'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        'unsigned' => true,
    ), 'Entity Id')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Updated At')
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false,
    ), 'Url Key')
    ->addColumn('route', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable' => false,
    ), 'Rout')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 1024, array(
        'nullable' => false,
    ), 'Url')
    ->addColumn('expire', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Expire')
    ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Priority')
    ->addIndex(
        $installer->getIdxName($installer->getTable('integernet_varnish/index'), array('url_key'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('url_key'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('IntegerNet_Varnish / Index');
$installer->getConnection()->createTable($table);

$installer->endSetup();
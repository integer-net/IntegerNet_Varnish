<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * IntegerNet_Varnish / Index
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
    ->addColumn('added_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Added At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Updated At')
    ->addColumn('expire_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Expire At')
    ->addColumn('purge_flag', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'unsigned' => true,
        'default' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO,
    ), 'Purge Flag')
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false,
    ), 'Url Key')
    ->addColumn('route', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Rout')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
        'nullable' => false,
    ), 'Url')
    ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'unsigned' => true,
        'default' => 0,
    ), 'Priority')
    ->addColumn('count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
        'default' => 0,
    ), 'Count')
    ->addIndex(
        $installer->getIdxName($installer->getTable('integernet_varnish/index'), array('url_key'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('url_key'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->setComment('IntegerNet_Varnish / Index');
$installer->getConnection()->createTable($table);

$installer->endSetup();

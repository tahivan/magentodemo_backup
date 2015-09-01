<?php
/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 10:41 AM
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();
$productOptionTypeImageTable = $installer->getTable('qsoft_pricematrix/product_option_type_image');
if ($installer->getConnection()->isTableExists($productOptionTypeImageTable)) {
    $installer->getConnection()->dropTable($productOptionTypeImageTable);
}
$table = $installer->getConnection()
    ->newTable($productOptionTypeImageTable)
    ->addColumn('option_type_image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity ID')
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => false,
    ), 'option type id')
    ->addColumn('gallery_value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => false,
    ), 'gallery value id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(), 'Store id');
$installer->getConnection()->createTable($table);
$installer->endSetup();
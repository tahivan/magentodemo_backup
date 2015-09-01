<?php
/**
 * Created by PhpStorm.
 * User: thainv
 * Date: 8/24/2015
 * Time: 10:41 AM
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer = $this;

$targetTable = $installer->getTable('catalog/product_attribute_media_gallery_value');
$targetColumn = 'show_in';
$connection = $installer->getConnection();

try {

    if ($connection->tableColumnExists($targetTable, $targetColumn)) {
        $connection->dropColumn($targetTable, $targetColumn);
    }

    $connection->addColumn($targetTable, $targetColumn, 'TINYINT(1) UNSIGNED DEFAULT 0 AFTER label');

} catch (Exception $e) {
    throw new Exception($e->getMessage());
}

$installer->endSetup();
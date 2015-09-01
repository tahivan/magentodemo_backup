<?php
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

try {
    $installer->addAttribute('catalog_product', 'gallery_type', array(
        'group' => 'General',
        'label' => 'Gallery Type',
        'type' => 'varchar',
        'input' => 'multiselect',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'visible_in_advanced_search' => false,
        'unique' => false
    ));

} catch (Exception $e) {
    throw new Exception($e->getMessage());
}

$installer->endSetup();
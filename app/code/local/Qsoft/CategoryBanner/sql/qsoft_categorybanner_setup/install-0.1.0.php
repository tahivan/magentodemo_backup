<?php
/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 8/18/2015
 * Time: 3:09 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$setup->addAttribute('catalog_category', 'banner_text', array(
    'group'         => 'Category Banner',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Banner Text',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'     => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_category', 'banner_image', array(
    'group'         => 'Category Banner',
    'input'         => 'image',
    'type'          => 'varchar',
    'label'         => 'Banner Image',
    'backend'       => 'catalog/category_attribute_backend_image',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' 	=> 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_category', 'banner_children', array(
    'type'              => 'int',
    'group'         	=> 'Category Banner',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Apply to Sub-Categories',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
));

$installer->endSetup();